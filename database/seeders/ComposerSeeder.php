<?php

namespace Database\Seeders;

use App\Models\Composer;
use Illuminate\Database\Seeder;

class ComposerSeeder extends Seeder
{
    public function run(): void
    {
        $composers = [
            [
                'name' => 'Giovanni Pierluigi da Palestrina',
                'nationality' => 'Italiana',
                'period' => 'Renascimento',
                'birth_year' => 1525,
                'death_year' => 1594,
                'biography' => 'Compositor de música sacra e uma das principais figuras da polifonia renascentista.',
            ],
            [
                'name' => 'Johann Sebastian Bach',
                'nationality' => 'Alemã',
                'period' => 'Barroco',
                'birth_year' => 1685,
                'death_year' => 1750,
                'biography' => 'Compositor e organista conhecido pelo domínio do contraponto e pela sua produção instrumental e sacra.',
            ],
            [
                'name' => 'Carlos Seixas',
                'nationality' => 'Portuguesa',
                'period' => 'Barroco',
                'birth_year' => 1704,
                'death_year' => 1742,
                'biography' => 'Compositor, organista e cravista português, destacado pela sua produção de música para tecla.',
            ],
            [
                'name' => 'Wolfgang Amadeus Mozart',
                'nationality' => 'Austríaca',
                'period' => 'Classicismo',
                'birth_year' => 1756,
                'death_year' => 1791,
                'biography' => 'Compositor do período clássico com uma vasta produção operática, sinfónica, instrumental e sacra.',
            ],
            [
                'name' => 'Clara Schumann',
                'nationality' => 'Alemã',
                'period' => 'Romantismo',
                'birth_year' => 1819,
                'death_year' => 1896,
                'biography' => 'Pianista e compositora que desempenhou um importante papel na vida musical europeia do século XIX.',
            ],
            [
                'name' => 'Claude Debussy',
                'nationality' => 'Francesa',
                'period' => 'Impressionismo',
                'birth_year' => 1862,
                'death_year' => 1918,
                'biography' => 'Compositor francês associado ao Impressionismo musical, conhecido pela exploração da harmonia, do timbre e das cores orquestrais.',
            ],
            [
                'name' => 'Béla Bartók',
                'nationality' => 'Húngara',
                'period' => 'Século XX',
                'birth_year' => 1881,
                'death_year' => 1945,
                'biography' => 'Compositor, pianista e investigador de música tradicional húngara. Uma das figuras mais importantes da música do século XX, combinou influências folclóricas com uma linguagem musical inovadora, especialmente no ritmo e na harmonia.',
            ],
        ];

        foreach ($composers as $composer) {
            Composer::firstOrCreate(
                ['name' => $composer['name']],
                $composer
            );
        }
    }
}
