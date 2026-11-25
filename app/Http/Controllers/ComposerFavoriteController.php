<?php

namespace App\Http\Controllers;

use App\Models\Composer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComposerFavoriteController extends Controller
{
    public function store(
        Request $request,
        Composer $composer
    ): RedirectResponse {
        $request->user()
            ->favoriteComposers()
            ->syncWithoutDetaching([$composer->id]);

        return back()->with(
            'success',
            'Compositor adicionado aos favoritos!'
        );
    }

    public function destroy(
        Request $request,
        Composer $composer
    ): RedirectResponse {
        $request->user()
            ->favoriteComposers()
            ->detach($composer->id);

        return back()->with(
            'success',
            'Compositor removido dos favoritos!'
        );
    }
}
