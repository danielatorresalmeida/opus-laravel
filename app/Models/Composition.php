<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Composition extends Model
{
    protected $fillable = [
        'composer_id',
        'title',
        'openopus_id',
        'catalogue_number',
        'composition_year',
        'genre',
        'instrumentation',
        'description',
        'image',
        'score_path'
    ];

    public function composer(): BelongsTo
    {
        return $this->belongsTo(Composer::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }
}
