@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="card-title text-center mb-4">Login</h1>
                <form method="POST" action="{{ route('login') }}"> 
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username</label>
                        <input type="text" id="email" name="email" class="form-control" required autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        {{-- Add error handling for password field if needed --}}
                         @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Optional: Add Remember Me checkbox --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>

                    {{-- Optional: Add links for password reset or registration --}}
                    {{-- <div class="text-center mt-3">
                        <a href="#">Forgot Password?</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection