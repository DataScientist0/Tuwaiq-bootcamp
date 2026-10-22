@extends('Layout.app')

@section('title', 'Home')
@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-12 pb-28">

        <header class="mx-auto max-w-2xl text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                Words worth <span class="gradient-text">sharing</span>
            </h1>
            <p class="mt-3 text-lg text-white/60">
                Read quotes from the community{{ auth()->check() ? ' and share your own' : '' }}.
            </p>

            <form method="GET" action="{{ route('home') }}" class="relative mt-8" role="search">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                @endif
                <label for="search" class="sr-only">Search quotes</label>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="pointer-events-none absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 text-white/40">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input id="search" name="search" type="search" value="{{ $search }}"
                    placeholder="Search by words or author…" class="input rounded-2xl py-3.5 pr-28 pl-12 text-base">
                <button type="submit" class="btn-primary absolute top-1/2 right-1.5 -translate-y-1/2 py-2">Search</button>
            </form>

            <nav class="mt-6 flex flex-wrap justify-center gap-2" aria-label="Categories">
                <a href="{{ route('home', array_filter(['search' => $search])) }}"
                    @class(['chip', 'chip-active' => ! $selectedCategory])>All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('home', array_filter(['search' => $search, 'category' => $category->slug])) }}"
                        @class(['chip', 'chip-active' => $selectedCategory?->is($category)])>{{ $category->name }}</a>
                @endforeach
            </nav>
        </header>

        <section class="mt-12" aria-label="Quotes">
            @if ($search || $selectedCategory)
                <div class="mb-5 flex items-center justify-between text-sm text-white/60">
                    <p>{{ $quotes->count() }} {{ Str::plural('quote', $quotes->count()) }} found</p>
                    <a href="{{ route('home') }}" class="hover:text-white hover:underline">Clear filters</a>
                </div>
            @endif

            @if ($quotes->isEmpty())
                <div class="glass mx-auto max-w-md p-10 text-center">
                    <p class="font-quote text-5xl text-tuwaiq-orange/70">“</p>
                    <p class="mt-2 text-white/70">
                        @if ($search || $selectedCategory)
                            No quotes match your search.
                        @else
                            No quotes yet. Be the first to share one!
                        @endif
                    </p>
                </div>
            @else
                <div class="columns-1 gap-5 md:columns-2 lg:columns-3">
                    @foreach ($quotes as $quote)
                        <x-quote-card :quote="$quote" :categories="$categories"
                            :editing="$quote->id === $editingQuoteId && auth()->id() === $quote->user_id" />
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <x-share-quote :categories="$categories" />
@endsection
