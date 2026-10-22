<?php

namespace App\Models;

use Database\Factories\QuoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['content', 'category_id'])]
class Quote extends Model
{
    /** @use HasFactory<QuoteFactory> */
    use HasFactory;

    /**
     * Get the user who shared the quote.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the quote.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the users who liked the quote.
     *
     * @return BelongsToMany<User, $this>
     */
    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    /**
     * Load everything a quote card shows: author, category, like count,
     * and whether the viewer liked it. Newest quotes come first.
     *
     * @param  Builder<Quote>  $query
     */
    #[Scope]
    protected function forFeed(Builder $query, ?User $viewer): void
    {
        $query->with(['user', 'category'])
            ->withCount('likers')
            ->withExists(['likers as is_liked' => fn (Builder $likers) => $likers->whereKey($viewer?->id)])
            ->latest()
            ->latest('id');
    }

    /**
     * Keep only quotes whose text or author name contains the search term.
     *
     * @param  Builder<Quote>  $query
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $query) => $query->where(
            fn (Builder $query) => $query->whereLike('content', "%{$term}%")
                ->orWhereRelation('user', 'name', 'like', "%{$term}%")
        ));
    }
}
