<?php

namespace App\Http\Controllers;

use App\Models\Composer;
use App\Models\Composition;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'composers' => Composer::count(),
            'compositions' => Composition::count(),
            'periods' => Composer::whereNotNull('period')
                ->distinct()
                ->count('period'),
        ];

        return view('dashboard', compact('stats'));
    }
}
