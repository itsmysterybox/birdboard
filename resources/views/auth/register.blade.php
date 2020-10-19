@extends('layouts.app')

@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-card bg-white p-6 md:py-12 md:px-16 rounded shadow">
        <h1 class="text-2x1 font-normal mb-10 text-center">Register</h1>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="field mb-6">
                <label for="name" class="label text-sm mb-2 block">Name</label>
                <div class="control">
                    <input 
                        id="name" 
                        type="text" 
                        class="input bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full @error ('name') border-red-900 bg-gray-300 @enderror"
                        name="name" 
                        required
                        value="{{ old('name') }}"
                        autofocus>
                    @error('name')
                        <p class="text-sm text-red-900">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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
                <label for="password_confirmation" class="label text-sm mb-2 block">Confirm Password</label>
                <div class="control">
                    <input 
                        id="password-confirmation" 
                        type="password" 
                        class="input bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full @error ('password') border-red-900 bg-gray-300 @enderror"
                        name="password_confirmation"
                        required>
                    @error('password')
                        <p class="text-sm text-red-900">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button type="submit" class="button is-link mr-2">Register</button>
                </div>
            </div>
        </form>

    </div>
@endsection