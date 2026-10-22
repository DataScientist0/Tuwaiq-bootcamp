<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the quotes in this category.
     *
     * @return HasMany<Quote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Get the Tailwind classes that colour this category's badge.
     */
    public function badgeClasses(): string
    {
        return match ($this->slug) {
            'motivation' => 'bg-tuwaiq-orange/15 text-tuwaiq-orange ring-tuwaiq-orange/30',
            'wisdom' => 'bg-tuwaiq-teal/15 text-tuwaiq-teal ring-tuwaiq-teal/30',
            'life' => 'bg-sky-400/15 text-sky-300 ring-sky-400/30',
            'love' => 'bg-pink-400/15 text-pink-300 ring-pink-400/30',
            'success' => 'bg-emerald-400/15 text-emerald-300 ring-emerald-400/30',
            'humor' => 'bg-yellow-300/15 text-yellow-200 ring-yellow-300/30',
            default => 'bg-white/10 text-tuwaiq-text ring-white/20',
        };
    }
}
