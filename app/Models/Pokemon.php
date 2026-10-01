<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pokemon extends Model
{
    use SoftDeletes;
    protected $table = 'pokemons';

    protected $fillable = [
        'api_id',
        'name',
        'height',
        'weight',
        'sprite',
    ];

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class);
    }

    public function moves(): BelongsToMany
    {
        return $this->belongsToMany(Move::class);
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getFormattedNameAttribute(): string
    {
        return ucfirst($this->name);
    }

    public function getHeightInMetersAttribute(): string
    {
        return number_format($this->height / 10, 2, ',', '.') . ' m';
    }

    public function getWeightInKilogramsAttribute(): string
    {
        return number_format($this->weight / 10, 2, ',', '.') . ' kg';
    }
}
