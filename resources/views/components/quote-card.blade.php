@props(['quote', 'categories' => collect(), 'editing' => false])

@php
    $isMine = auth()->id() === $quote->user_id;
@endphp

<article @class([
    'glass mb-5 break-inside-avoid p-6 transition duration-300',
    'hover:-translate-y-0.5 hover:border-white/20' => ! $editing,
    'border-tuwaiq-purple-light/60 bg-gradient-to-br from-tuwaiq-purple/50 to-tuwaiq-purple/10' => $isMine,
])>
    @if ($editing)
        <form method="POST" action="{{ route('quotes.update', $quote) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="quote_id" value="{{ $quote->id }}">

            <div>
                <label for="edit-quote-{{ $quote->id }}" class="label">Edit your quote</label>
                <textarea id="edit-quote-{{ $quote->id }}" name="content" rows="3" required minlength="3" maxlength="155"
                    class="input resize-none" autofocus>{{ old('quote_id') == $quote->id ? old('content') : $quote->content }}</textarea>
                @if ($errors->has('content') && old('quote_id') == $quote->id)
                    <p class="mt-1 text-xs text-red-300">{{ $errors->first('content') }}</p>
                @endif
            </div>

            <x-category-picker :categories="$categories"
                :selected="old('quote_id') == $quote->id ? old('category_id') : $quote->category_id" />

            <div class="flex gap-2">
                <button type="submit" class="btn-secondary">Save</button>
                <a href="{{ request()->fullUrlWithoutQuery('edit') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    @else
        <div class="flex items-start justify-between gap-3">
            <span class="font-quote -mb-4 text-6xl leading-none text-tuwaiq-orange/70" aria-hidden="true">“</span>
            @if ($quote->category)
                <a href="{{ route('home', ['category' => $quote->category->slug]) }}"
                    class="badge {{ $quote->category->badgeClasses() }}">{{ $quote->category->name }}</a>
            @endif
        </div>

        <blockquote class="font-quote text-xl leading-relaxed text-tuwaiq-text">{{ $quote->content }}</blockquote>

        <footer class="mt-5 flex items-center gap-3 border-t border-white/10 pt-4">
            <a href="{{ route('users.show', $quote->user) }}" class="shrink-0">
                <x-avatar :user="$quote->user" size="sm" />
            </a>
            <div class="min-w-0">
                <a href="{{ route('users.show', $quote->user) }}"
                    class="block truncate text-sm font-semibold text-white hover:underline">
                    {{ $isMine ? 'You' : $quote->user->name }}
                </a>
                <time datetime="{{ $quote->created_at->toDateString() }}"
                    class="text-xs text-white/50">{{ $quote->created_at->format('Y-m-d') }}</time>
            </div>

            <div class="ml-auto flex items-center">
                @auth
                    <form method="POST" action="{{ route('quotes.like', $quote) }}">
                        @csrf
                        <button type="submit" @class(['icon-btn', 'text-violet-400 hover:text-violet-300' => $quote->is_liked])
                            aria-label="{{ $quote->is_liked ? 'Unlike' : 'Like' }} quote" aria-pressed="{{ $quote->is_liked ? 'true' : 'false' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                                fill="{{ $quote->is_liked ? 'currentColor' : 'none' }}" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span>{{ $quote->likers_count }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" aria-label="Log in to like this quote">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        <span>{{ $quote->likers_count }}</span>
                    </a>
                @endauth

                @if ($isMine)
                    <a href="{{ request()->fullUrlWithQuery(['edit' => $quote->id]) }}" class="icon-btn" aria-label="Edit quote">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('quotes.destroy', $quote) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn hover:text-red-300" aria-label="Delete quote">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </footer>
    @endif
</article>
