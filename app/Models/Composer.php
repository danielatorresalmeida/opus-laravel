<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Composer extends Model
{
    protected $fillable = [
        'name',
        'openopus_id',
        'nationality',
        'period',
        'birth_year',
        'death_year',
        'biography',
        'image'
    ];

    public function compositions(): HasMany
    {
        return $this->hasMany(Composition::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
