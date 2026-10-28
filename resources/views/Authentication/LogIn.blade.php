@extends('Layout.AuthImg')

@section('title', 'Log In')
@section('form')

    <div>
        <form action="{{ route('login') }}" method="POST" class="glass mx-auto w-full max-w-md p-8">
            @csrf

            <h1 class="text-center text-3xl font-bold"><span class="gradient-text">Log In</span></h1>
            <p class="mt-2 mb-6 text-center text-sm text-white/60">Welcome back! Log in to share and like quotes.</p>

            <div class="mb-4">
                <label for="email" class="label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="input peer user-invalid:border-red-400" required
                    pattern="[^@\s]+@[^@\s]+\.[A-Za-z]{2,}">
                <p class="mt-1 text-xs text-white/50 peer-user-invalid:text-red-300">
                    A valid email, e.g. name@example.com
                </p>
                @error('email')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror

            </div>

            <div class="mb-4">
                <label for="password" class="label">Password</label>
                <input id="password" name="password" type="password" class="input peer user-invalid:border-red-400" required
                    minlength="8">
                <p class="mt-1 text-xs text-white/50 peer-user-invalid:text-red-300">
                    At least 8 characters.
                </p>
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <p class="mb-5 text-sm text-white/70">
                Don't have an account? <a href="{{ route('signup') }}" class="font-semibold text-tuwaiq-teal hover:underline">Sign Up</a>
            </p>

            <button type="submit" class="btn-primary w-full py-3">Log In</button>
        </form>
    </div>


@endsection
