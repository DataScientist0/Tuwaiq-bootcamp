@props(['categories'])

@auth
    {{-- Floating "+" button --}}
    <button type="button" popovertarget="quote-popup" aria-label="Share a quote"
        class="fixed right-6 bottom-6 z-40 flex h-16 w-16 cursor-pointer items-center justify-center rounded-full bg-gradient-to-br from-tuwaiq-purple-light to-tuwaiq-purple text-white shadow-xl shadow-tuwaiq-purple/40 ring-1 ring-white/20 transition hover:scale-110 focus:outline-none focus-visible:ring-4 focus-visible:ring-tuwaiq-teal/40">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Pop-up form --}}
    <div id="quote-popup" popover
        class="glass m-auto w-[92%] max-w-lg bg-tuwaiq-night/95 p-0 text-white backdrop:bg-black/60 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('quotes.store') }}" class="space-y-5 p-6">
            @csrf
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold">Share a <span class="gradient-text">quote</span></h2>
                <button type="button" popovertarget="quote-popup" popovertargetaction="hide" aria-label="Close"
                    class="icon-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div>
                <label for="quote-body" class="sr-only">Quote</label>
                <textarea id="quote-body" name="content" rows="4" required minlength="3" maxlength="155" autofocus
                    placeholder="Write your quote…"
                    class="input font-quote peer resize-none text-lg user-invalid:border-red-400">{{ old('quote_id') === null ? old('content') : '' }}</textarea>
                <p class="mt-1 text-xs text-white/50 peer-user-invalid:text-red-300">3–155 characters.</p>
                @if ($errors->has('content') && old('quote_id') === null)
                    <p class="mt-1 text-xs text-red-300">{{ $errors->first('content') }}</p>
                @endif
            </div>

            <div>
                <x-category-picker :categories="$categories"
                    :selected="old('quote_id') === null ? old('category_id') : null" />
                @if ($errors->has('category_id') && old('quote_id') === null)
                    <p class="mt-1 text-xs text-red-300">{{ $errors->first('category_id') }}</p>
                @endif
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-lg">Send</button>
        </form>
    </div>

    @if (($errors->has('content') || $errors->has('category_id')) && old('quote_id') === null)
        <script>
            document.getElementById('quote-popup').showPopover();
        </script>
    @endif
@else
    {{-- Guests: log in to share --}}
    <a href="{{ route('login') }}" aria-label="Log in to share a quote" title="Log in to share a quote"
        class="fixed right-6 bottom-6 z-40 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-tuwaiq-purple-light to-tuwaiq-purple text-white shadow-xl shadow-tuwaiq-purple/40 ring-1 ring-white/20 transition hover:scale-110 focus:outline-none focus-visible:ring-4 focus-visible:ring-tuwaiq-teal/40">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </a>
@endauth
