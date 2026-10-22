<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\Category;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Uri;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * Show every shared quote, filtered by the search term and category.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $selectedCategory = $categories->firstWhere('slug', $request->string('category')->toString());
        $search = trim($request->string('search')->toString());

        $quotes = Quote::forFeed($request->user())
            ->search($search)
            ->when($selectedCategory, fn ($query) => $query->whereBelongsTo($selectedCategory))
            ->get();

        return view('welcome', [
            'page' => 'Home',
            'quotes' => $quotes,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'editingQuoteId' => $request->integer('edit'),
        ]);
    }

    /**
     * Share a new quote.
     */
    public function store(QuoteRequest $request): RedirectResponse
    {
        $request->user()->quotes()->create($request->validated());

        return redirect()->route('home');
    }

    /**
     * Update one of the user's own quotes.
     */
    public function update(QuoteRequest $request, Quote $quote): RedirectResponse
    {
        Gate::authorize('update', $quote);

        $quote->update($request->validated());

        return redirect()->to(Uri::of(url()->previous())->withoutQuery(['edit']));
    }

    /**
     * Delete one of the user's own quotes.
     */
    public function destroy(Quote $quote): RedirectResponse
    {
        Gate::authorize('delete', $quote);

        $quote->delete();

        return back();
    }
}
