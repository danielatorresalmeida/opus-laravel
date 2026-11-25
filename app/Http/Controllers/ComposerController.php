<?php

namespace App\Http\Controllers;

use App\Models\Composer;
use App\Models\Composition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComposerController extends Controller
{
    public function index(Request $request)
    {
        $query = Composer::withCount('compositions');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->input('q').'%');
        }

        if ($request->filled('period')) {
            $query->where('period', $request->input('period'));
        }

        if ($request->filled('works')) {
            $works = $request->input('works');

            if ($works === '3plus') {
                $query->has('compositions', '>=', 3);
            } elseif (in_array($works, ['0', '1', '2'], true)) {
                $query->has('compositions', '=', (int) $works);
            }
        }

        // Só permitir ordenar pelas colunas do catálogo
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        if (! in_array($sort, ['name', 'period', 'works'], true)) {
            $sort = 'name';
        }
        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $column = $sort === 'works' ? 'compositions_count' : $sort;
        $query->orderBy($column, $direction);
        if ($sort !== 'name') {
            $query->orderBy('name');
        }

        $composers = $query->paginate(10)->withQueryString();
        $periods = Composer::whereNotNull('period')
            ->distinct()->orderBy('period')->pluck('period');

        $stats = [
            'composers' => Composer::count(),
            'compositions' => Composition::count(),
            'periods' => $periods->count(),
        ];

        return view('composers.index', compact('composers', 'periods', 'stats'));
    }

    public function show(Request $request, Composer $composer)
    {
        $query = $composer->compositions();

        if ($request->filled('q')) {
            $query->where(function ($search) use ($request) {
                $search->where(
                    'title', 'like', '%'.$request->q.'%'
                )->orWhere(
                    'catalogue_number', 'like', '%'.$request->q.'%'
                );
            });
        }

        $compositions = $query
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        return view('composers.show', compact(
            'composer',
            'compositions'
        ));
    }

    public function create()
    {
        return view('composers.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateComposer($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('composers', 'public');

            abort_if($data['image'] === false, 500);
        }

        Composer::create($data);

        return redirect()
            ->route('composers.index')
            ->with('success', 'Compositor adicionado com sucesso.');
    }

    public function edit(Composer $composer)
    {
        return view('composers.form', compact('composer'));
    }

    public function update(Request $request, Composer $composer)
    {
        $data = $this->validateComposer($request);

        $oldImage = $composer->image;

        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('composers', 'public');

            abort_if($data['image'] === false, 500);
        }

        $composer->update($data);

        if (isset($data['image']) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('composers.index')
            ->with('success', 'Compositor atualizado com sucesso.');
    }

    public function destroy(Composer $composer)
    {
        $files = [];

        if ($composer->image) {
            $files[] = $composer->image;
        }

        foreach ($composer->compositions as $composition) {
            if ($composition->image) {
                $files[] = $composition->image;
            }

            if ($composition->score_path) {
                $files[] = $composition->score_path;
            }
        }

        $composer->delete();

        if (! empty($files)) {
            Storage::disk('public')->delete($files);
        }

        return redirect()
            ->route('composers.index')
            ->with('success', 'Compositor eliminado com sucesso.');
    }

    private function validateComposer(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'period' => 'nullable|string|max:255',
            'birth_year' => 'nullable|integer|min:1|max:2100',
            'death_year' => [
                'nullable',
                'integer',
                'min:1',
                'max:2100',
                $request->filled('birth_year')
                    ? 'gte:birth_year'
                    : 'nullable',
            ],
            'biography' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }
}
