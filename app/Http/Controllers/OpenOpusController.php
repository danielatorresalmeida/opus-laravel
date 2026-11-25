<?php

namespace App\Http\Controllers;

use App\Models\Composer;
use App\Models\Composition;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class OpenOpusController extends Controller
{
    private const API = 'https://api.openopus.org';
    private const BATCH_SIZE = 50;

    // Lista remota, com paginação feita no Laravel (a API devolve os resultados de uma vez).
    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => 'nullable|string|max:80',
            'scope' => 'nullable|in:Popular,Recommended,All',
            'page' => 'nullable|integer|min:1|max:10000',
        ]);
        $search = trim($data['q'] ?? '');
        $scope = $data['scope'] ?? 'Popular';
        $path = '/composer/list/pop.json';
        if ($search !== '') {
            $path = '/composer/list/search/'.rawurlencode($search).'.json';
        } elseif ($scope === 'Recommended') {
            $path = '/composer/list/rec.json';
        } elseif ($scope === 'All') {
            $path = '/composer/list/name/all.json';
        }

        $response = $this->requestApi($path);
        $apiError = $response === null ? 'Não foi possível consultar a Open Opus. Tenta novamente mais tarde.' : null;
        $rows = collect($response['composers'] ?? [])
            ->filter(fn ($row) => is_array($row) && isset($row['id'], $row['complete_name']))
            ->values();
        $composers = $this->paginate($rows, $request);
        $noResults = $apiError === null && $rows->isEmpty();

        // Basta verificar os 50 compositores visíveis, em vez de consultar toda a base de dados.
        $names = $composers->getCollection()->pluck('complete_name')->all();
        $ids = $composers->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $existing = Composer::whereIn('openopus_id', $ids)
            ->orWhereIn('name', $names)->get(['id', 'name', 'openopus_id']);

        return view('openopus.index', compact('composers', 'existing', 'search', 'scope', 'apiError', 'noResults'));
    }

    // O formulário envia IDs, nunca os nomes nem os restantes dados a guardar.
    public function importComposers(Request $request)
    {
        $data = $request->validate([
            'composer_ids' => 'required|array|min:1|max:50',
            'composer_ids.*' => 'required|integer|min:1',
        ]);
        $ids = array_values(array_unique(array_map('intval', $data['composer_ids'])));
        $response = $this->requestApi('/composer/list/ids/'.implode(',', $ids).'.json');
        if ($response === null) {
            return back()->with('error', 'Não foi possível contactar a Open Opus. Tenta novamente.');
        }
        $rows = collect($response['composers'] ?? [])->keyBy('id');

        foreach ($ids as $id) {
            if (! isset($rows[$id]['complete_name']) || trim($rows[$id]['complete_name']) === '') {
                throw ValidationException::withMessages([
                    'composer_ids' => 'A API não devolveu todos os compositores selecionados.',
                ]);
            }
        }

        $created = 0;
        $already = 0;
        DB::transaction(function () use ($ids, $rows, &$created, &$already) {
            foreach ($ids as $id) {
                $row = $rows[$id];
                $name = trim($row['complete_name']);
                if (mb_strlen($name) > 255) {
                    continue;
                }

                $composer = Composer::where('openopus_id', $id)->first()
                    ?? Composer::whereNull('openopus_id')->where('name', $name)->first();

                if ($composer) {
                    if ($composer->openopus_id === null) {
                        // Ligamos a API sem substituir informação que já foi editada no OPUS.
                        $composer->update(['openopus_id' => $id]);
                    }
                    $already++;
                    continue;
                }

                Composer::create([
                    'openopus_id' => $id,
                    'name' => $name,
                    'period' => $this->period($row['epoch'] ?? null),
                    'birth_year' => $this->year($row['birth'] ?? null),
                    'death_year' => $this->year($row['death'] ?? null),
                ]);
                $created++;
            }
        });

        return redirect()->route('openopus.index')
            ->with('success', "Importados: {$created}. Já existentes ou associados: {$already}.");
    }

    public function works(Request $request, Composer $composer)
    {
        abort_unless($composer->openopus_id, 404);
        $data = $request->validate([
            'selection' => 'nullable|in:All,Recommended,Popular',
            'page' => 'nullable|integer|min:1|max:10000',
        ]);
        $selection = $data['selection'] ?? 'Recommended';
        $response = $this->requestApi($this->worksPath($composer, $selection));
        $apiError = $response === null ? 'Não foi possível consultar as obras na Open Opus. Tenta novamente mais tarde.' : null;
        $rows = collect($response['works'] ?? [])
            ->filter(fn ($row) => is_array($row) && isset($row['id'], $row['title']))
            ->values();
        $works = $this->paginate($rows, $request);
        $noResults = $apiError === null && $rows->isEmpty();

        $ids = $works->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $titles = $works->getCollection()->pluck('title')->all();
        $existing = $composer->compositions()
            ->where(function ($query) use ($ids, $titles) {
                $query->whereIn('openopus_id', $ids)->orWhereIn('title', $titles);
            })->get(['title', 'openopus_id']);

        return view('openopus.works', compact('composer', 'works', 'existing', 'selection', 'apiError', 'noResults'));
    }

    public function importWorks(Request $request, Composer $composer)
    {
        abort_unless($composer->openopus_id, 404);
        $data = $request->validate([
            'selection' => 'required|in:All,Recommended,Popular',
            'page' => 'nullable|integer|min:1|max:10000',
            'work_ids' => 'required|array|min:1|max:50',
            'work_ids.*' => 'required|integer|min:1',
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['work_ids'])));
        $response = $this->requestApi($this->worksPath($composer, $data['selection']));
        if ($response === null) {
            return back()->with('error', 'Não foi possível contactar a Open Opus. Tenta novamente.');
        }
        $rows = collect($response['works'] ?? [])->keyBy('id');
        foreach ($ids as $id) {
            if (! isset($rows[$id]['title']) || trim($rows[$id]['title']) === '') {
                throw ValidationException::withMessages([
                    'work_ids' => 'A API não devolveu todas as obras selecionadas.',
                ]);
            }
        }

        [$created, $already] = $this->saveWorks($composer, collect($ids)->map(fn ($id) => $rows[$id]));

        return redirect()->route('openopus.works', [
            'composer' => $composer,
            'selection' => $data['selection'],
            'page' => $data['page'] ?? 1,
        ])->with('success', "Obras importadas: {$created}. Já existentes: {$already}.");
    }

    private function saveWorks(Composer $composer, Collection $rows): array
    {
        $created = 0;
        $already = 0;

        DB::transaction(function () use ($composer, $rows, &$created, &$already) {
            foreach ($rows as $row) {
                $id = (int) ($row['id'] ?? 0);
                $title = trim((string) ($row['title'] ?? ''));
                if ($id < 1 || $title === '' || mb_strlen($title) > 255) {
                    continue;
                }

                // Um ID externo é único em todo o catálogo.
                $composition = Composition::where('openopus_id', $id)->first()
                    ?? $composer->compositions()->whereNull('openopus_id')
                        ->where('title', $title)->first();

                if ($composition) {
                    if ($composition->openopus_id === null) {
                        $composition->update(['openopus_id' => $id]);
                    }
                    $already++;
                    continue;
                }

                $composer->compositions()->create([
                    'openopus_id' => $id,
                    'title' => $title,
                    'genre' => mb_substr((string) ($row['genre'] ?? ''), 0, 100) ?: null,
                ]);
                $created++;
            }
        });

        return [$created, $already];
    }

    private function worksPath(Composer $composer, string $selection): string
    {
        return '/work/list/composer/'.$composer->openopus_id.'/genre/'
            .($selection === 'All' ? 'all' : $selection).'.json';
    }

    private function paginate(Collection $rows, Request $request): LengthAwarePaginator
    {
        $page = max(1, (int) $request->query('page', 1));
        return new LengthAwarePaginator(
            $rows->forPage($page, self::BATCH_SIZE)->values(),
            $rows->count(),
            self::BATCH_SIZE,
            $page,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );
    }

    // Endpoint fixo: o utilizador nunca pode indicar um URL arbitrário.
    private function requestApi(string $path): ?array
    {
        try {
            $response = Http::acceptJson()->connectTimeout(5)->timeout(20)
                ->get(self::API.$path);
        } catch (ConnectionException $exception) {
            return null;
        }

        if ($response->failed() || ! is_array($response->json())) {
            return null;
        }
        $data = $response->json();
        if (isset($data['status']['success']) &&
            ! in_array($data['status']['success'], ['true', true], true)) {
            // A Open Opus também usa success=false para pesquisas sem resultados.
            // Só os casos "No composers/works found" são listas vazias válidas.
            $reason = strtolower(trim((string) ($data['status']['error'] ?? ''), " .\t\n\r"));
            if (! in_array($reason, ['no composers found', 'no works found'], true)) {
                return null;
            }
        }
        return $data;
    }

    // A Open Opus indica o ano, não uma data completa historicamente confirmada.
    private function year(?string $date): ?int
    {
        if (! $date || ! preg_match('/^(\d{4})-/', $date, $matches)) {
            return null;
        }
        return (int) $matches[1];
    }

    private function period(?string $epoch): ?string
    {
        $periods = [
            'Medieval' => 'Medieval',
            'Renaissance' => 'Renascimento',
            'Baroque' => 'Barroco',
            'Classical' => 'Classicismo',
            'Early Romantic' => 'Romantismo',
            'Romantic' => 'Romantismo',
            'Late Romantic' => 'Romantismo',
            '20th Century' => 'Século XX',
            'Post-War' => 'Século XX',
            '21st Century' => 'Contemporâneo',
        ];

        return $periods[$epoch] ?? null;
    }
}
