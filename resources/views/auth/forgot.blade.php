@extends('auth.master')
@section('content')
<div class="auth-form-light text-left py-5 px-4 px-sm-5">
    <h4>Lupa Password?</h4>
    <h6 class="fw-light">Masukkan email Anda untuk reset password.</h6>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" required autofocus>
            @error('email')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">Kirim Link Reset</button>
        </div>
        <div class="text-center mt-4 fw-light">
            <a href="{{ route('login') }}" class="text-primary">Kembali ke Login</a>
        </div>
    </form>
</div>
@endsection