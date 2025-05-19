@extends('auth.master')
@section('content')
<div class="auth-form-light text-left py-5 px-4 px-sm-5">
    <h4>Reset Password</h4>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Password Baru" required>
            @error('password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Konfirmasi Password" required>
        </div>
        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">Reset Password</button>
        </div>
    </form>
</div>
@endsection