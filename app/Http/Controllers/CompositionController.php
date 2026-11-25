<?php

namespace App\Http\Controllers;

use App\Models\Composer;
use App\Models\Composition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompositionController extends Controller
{
    public function show(Composition $composition)
    {
        $composition->load('composer');

        return view('compositions.show', compact('composition'));
    }

    public function create(Composer $composer)
    {
        return view('compositions.form', compact('composer'));
    }

    public function store(Request $request, Composer $composer)
    {
        $data = $this->validateComposition($request);

        unset($data['score']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('compositions', 'public');

            abort_if($data['image'] === false, 500);
        }

        if ($request->hasFile('score')) {
            $data['score_path'] = $request->file('score')
                ->store('scores', 'public');

            abort_if($data['score_path'] === false, 500);
        }

        $composer->compositions()->create($data);

        return redirect()
            ->route('composers.show', $composer)
            ->with('success', 'Obra adicionada com sucesso.');
    }

    public function edit(Composition $composition)
    {
        $composer = $composition->composer;

        return view('compositions.form', compact(
            'composition',
            'composer'
        ));
    }

    public function update(Request $request, Composition $composition)
    {
        $data = $this->validateComposition($request);

        $oldImage = $composition->image;
        $oldScore = $composition->score_path;

        // Não apagar o ficheiro anterior quando não foi enviado um novo.
        unset($data['image'], $data['score']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('compositions', 'public');

            abort_if($data['image'] === false, 500);
        }

        if ($request->hasFile('score')) {
            $data['score_path'] = $request->file('score')
                ->store('scores', 'public');

            abort_if($data['score_path'] === false, 500);
        }

        $composition->update($data);

        if (isset($data['image']) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        if (isset($data['score_path']) && $oldScore) {
            Storage::disk('public')->delete($oldScore);
        }

        return redirect()
            ->route('composers.show', $composition->composer)
            ->with('success', 'Obra atualizada com sucesso.');
    }

    public function destroy(Composition $composition)
    {
        $composer = $composition->composer;
        $image = $composition->image;
        $score = $composition->score_path;

        $composition->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        if ($score) {
            Storage::disk('public')->delete($score);
        }

        return redirect()
            ->route('composers.show', $composer)
            ->with('success', 'Obra eliminada com sucesso.');
    }

    private function validateComposition(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'catalogue_number' => 'nullable|string|max:100',
            'composition_year' => 'nullable|integer|min:1|max:2100',
            'genre' => 'nullable|string|max:100',
            'instrumentation' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'score' => 'nullable|file|mimes:pdf|max:20480',
        ]);
    }
}
