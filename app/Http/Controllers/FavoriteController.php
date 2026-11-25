<?php

namespace App\Http\Controllers;

use App\Models\Composition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favorites()
            ->with('composer')
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        $composerFavorites = $request->user()
            ->favoriteComposers()
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('favorites.index', compact('favorites', 'composerFavorites'));
    }

    public function store(
        Request $request,
        Composition $composition
    ): RedirectResponse {
        $request->user()
            ->favorites()
            ->syncWithoutDetaching([$composition->id]);

        return back()->with(
            'success',
            'Obra adicionada aos favoritos!'
        );
    }

    public function destroy(
        Request $request,
        Composition $composition
    ): RedirectResponse {
        $request->user()
            ->favorites()
            ->detach($composition->id);

        return back()->with(
            'success',
            'Obra removida dos favoritos!'
        );
    }
}
