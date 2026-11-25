<?php

namespace Database\Seeders;

use App\Models\Composer;
use Illuminate\Database\Seeder;

class CompositionSeeder extends Seeder
{
    public function run(): void
    {
        $compositions = [
            // Renascimento
            [
                'composer' => 'Giovanni Pierluigi da Palestrina',
                'title' => 'Sicut cervus',
                'catalogue_number' => null,
                'composition_year' => null,
                'genre' => 'Motete',
                'instrumentation' => 'Coro SATB a cappella',
            ],
            [
                'composer' => 'Giovanni Pierluigi da Palestrina',
                'title' => 'Missa Papae Marcelli',
                'catalogue_number' => null,
                'composition_year' => 1562,
                'genre' => 'Missa',
                'instrumentation' => 'Coro a cappella',
            ],

            // Barroco
            [
                'composer' => 'Johann Sebastian Bach',
                'title' => 'Wachet auf, ruft uns die Stimme',
                'catalogue_number' => 'BWV 140',
                'composition_year' => 1731,
                'genre' => 'Cantata',
                'instrumentation' => 'Solistas, coro e orquestra',
            ],
            [
                'composer' => 'Johann Sebastian Bach',
                'title' => 'Herz und Mund und Tat und Leben',
                'catalogue_number' => 'BWV 147',
                'composition_year' => 1723,
                'genre' => 'Cantata',
                'instrumentation' => 'Solistas, coro e orquestra',
            ],
            [
                'composer' => 'Carlos Seixas',
                'title' => 'Concerto para cravo em Lá maior',
                'catalogue_number' => null,
                'composition_year' => null,
                'genre' => 'Concerto',
                'instrumentation' => 'Cravo e cordas',
            ],

            // Classicismo
            [
                'composer' => 'Wolfgang Amadeus Mozart',
                'title' => 'Ave verum corpus',
                'catalogue_number' => 'K. 618',
                'composition_year' => 1791,
                'genre' => 'Motete',
                'instrumentation' => 'Coro SATB, cordas e órgão',
            ],
            [
                'composer' => 'Wolfgang Amadeus Mozart',
                'title' => 'Requiem em Ré menor',
                'catalogue_number' => 'K. 626',
                'composition_year' => 1791,
                'genre' => 'Requiem',
                'instrumentation' => 'Solistas, coro e orquestra',
            ],

            // Romantismo
            [
                'composer' => 'Clara Schumann',
                'title' => 'Três Romances para violino e piano',
                'catalogue_number' => 'Op. 22',
                'composition_year' => 1853,
                'genre' => 'Romance',
                'instrumentation' => 'Violino e piano',
            ],
            [
                'composer' => 'Clara Schumann',
                'title' => 'Três Romances para piano',
                'catalogue_number' => 'Op. 21',
                'composition_year' => 1853,
                'genre' => 'Romance',
                'instrumentation' => 'Piano',
            ],

            // Impressionismo
            [
                'composer' => 'Claude Debussy',
                'title' => "Prélude à l'après-midi d'un faune",
                'catalogue_number' => null,
                'composition_year' => 1894,
                'genre' => 'Poema sinfónico',
                'instrumentation' => 'Orquestra',
            ],
            [
                'composer' => 'Claude Debussy',
                'title' => 'La Mer',
                'catalogue_number' => null,
                'composition_year' => 1905,
                'genre' => 'Obra sinfónica',
                'instrumentation' => 'Orquestra',
            ],

            // Século XX
            [
                'composer' => 'Béla Bartók',
                'title' => 'Allegro barbaro',
                'catalogue_number' => 'Sz. 49 / BB 63',
                'composition_year' => 1911,
                'genre' => 'Peça para piano',
                'instrumentation' => 'Piano solo',
            ],
            [
                'composer' => 'Béla Bartók',
                'title' => 'Danças Populares Romenas',
                'catalogue_number' => 'Sz. 56 / BB 68',
                'composition_year' => 1915,
                'genre' => 'Danças para piano',
                'instrumentation' => 'Piano solo',
            ],

        ];

        foreach ($compositions as $composition) {
            $composer = Composer::where(
                'name',
                $composition['composer']
            )->firstOrFail();

            $composer->compositions()->firstOrCreate(
                ['title' => $composition['title']],
                [
                    'catalogue_number' => $composition['catalogue_number'],
                    'composition_year' => $composition['composition_year'],
                    'genre' => $composition['genre'],
                    'instrumentation' => $composition['instrumentation'],
                ]
            );
        }
    }
}
