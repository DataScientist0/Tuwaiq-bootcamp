@extends('Layout.app')

@section('title', $user->name)
@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-12 pb-28">

        <header class="glass relative overflow-hidden p-8 sm:p-10">
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-tuwaiq-purple/40 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-tuwaiq-teal/20 blur-3xl" aria-hidden="true"></div>

            <div class="relative flex flex-col items-center gap-6 text-center sm:flex-row sm:text-left">
                <x-avatar :user="$user" size="lg" />
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="mt-1 text-white/50">Joined {{ $user->created_at->format('F Y') }}</p>
                </div>

                <dl class="flex gap-3 sm:ml-auto">
                    <div class="rounded-2xl bg-white/5 px-6 py-4 text-center ring-1 ring-white/10">
                        <dt class="text-xs tracking-wide text-white/50 uppercase">Quotes</dt>
                        <dd class="mt-1 text-3xl font-bold text-white">{{ $quotes->count() }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/5 px-6 py-4 text-center ring-1 ring-white/10">
                        <dt class="text-xs tracking-wide text-white/50 uppercase">Likes received</dt>
                        <dd class="mt-1 text-3xl font-bold text-violet-300">{{ $likesReceived }}</dd>
                    </div>
                </dl>
            </div>
        </header>

        <section class="mt-10" aria-label="Quotes by {{ $user->name }}">
            @if ($quotes->isEmpty())
                <div class="glass mx-auto max-w-md p-10 text-center">
                    <p class="font-quote text-5xl text-tuwaiq-orange/70">“</p>
                    <p class="mt-2 text-white/70">
                        {{ auth()->user()?->is($user) ? "You haven't shared any quotes yet." : "{$user->name} hasn't shared any quotes yet." }}
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

    @if (auth()->user()?->is($user))
        <x-share-quote :categories="$categories" />
    @endif
@endsection
