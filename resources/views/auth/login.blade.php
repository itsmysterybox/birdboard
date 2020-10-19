@extends('layouts.app')

@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-card bg-white p-6 md:py-12 md:px-16 rounded shadow">
        <h1 class="text-2x1 font-normal mb-10 text-center">Login</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field mb-6">
                <label for="email" class="label text-sm mb-2 block">Email Address</label>
                <div class="control">
                    <input 
                        type="email" 
                        class="input bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full @error ('email') border-red-900 bg-gray-300 @enderror"
                        name="email" 
                        required
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-sm text-red-900">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field mb-6">
                <label for="password" class="label text-sm mb-2 block">Password</label>
                <div class="control">
                    <input 
                        id="password" 
                        type="password" 
                        class="input bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full @error ('password') border-red-900 bg-gray-300 @enderror"
                        name="password"
                        required>
                    @error('password')
                        <p class="text-sm text-red-900">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field mb-6">
                <div class="control">
                    <input 
                        class="form-checkbox form-checkbox-dark mr-1" 
                        id="remember" 
                        type="checkbox"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="text-sm" for="remember">Remember Me</label>
                </div>
            </div>

            <div class="field mb-6">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="button mr-2">Login</button>
                    @if (Route::has('password.request'))
                        <a class="text-default text-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>

    </div>
@endsection
