@extends('Layout.AuthImg')
@extends('Layout.app')

@section('title', 'Sign Up')
@section('content')

@section('form')

        <div>
            <form action="{{ route('signup') }}" method="POST" class="glass mx-auto w-full max-w-md p-8">
                @csrf
                <h1 class="text-center text-3xl font-bold text-white">Sign Up</h1>
                <p class="mt-2 mb-6 text-center text-sm text-white/60">Create an account to share your favourite quotes.</p>
                <div class="mb-4">
                    <label for="name" class="label">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="input peer user-invalid:border-red-400"
    required minlength="2" maxlength="50" pattern="[A-Za-z\u0600-\u06FF ]+">

                    <p class="mt-1 text-xs text-white/50 peer-user-invalid:text-red-300">
                        2–50 characters, letters only.
                    </p>
                    @error('name')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
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
                   Already have an account? <a href="{{ route('login') }}" class="font-semibold text-tuwaiq-teal hover:underline">Log In</a>
                </p>

                <button type="submit" class="btn-primary w-full py-3">Sign Up</button>
            </form>
        </div>
        
@endsection
@endsection

