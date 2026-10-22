@props(['page' => ''])

<nav class="sticky top-0 z-50 border-b border-white/10 bg-tuwaiq-night/80 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4">
        <a href="{{ route('home') }}" class="site-name">QuoteIt</a>

        <div class="ml-auto flex items-center gap-2">
            @if ($page === 'Sign Up')
                <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
            @elseif ($page === 'Log In')
                <a href="{{ route('signup') }}" class="btn-ghost">Sign Up</a>
            @else
                @auth
                    <a href="{{ route('users.show', auth()->user()) }}"
                        class="flex items-center gap-2 rounded-full py-1 pr-3 pl-1 transition hover:bg-white/10">
                        <x-avatar :user="auth()->user()" size="sm" />
                        <span class="hidden font-medium text-white sm:inline">{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-ghost" aria-label="Log Out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span class="hidden sm:inline">Log Out</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
                    <a href="{{ route('signup') }}" class="btn-primary">Sign Up</a>
                @endauth
            @endif

            <img src="{{ asset('images/logo.png') }}" class="ml-2 hidden h-11 w-auto md:block" alt="Tuwaiq Club">
        </div>
    </div>
</nav>
