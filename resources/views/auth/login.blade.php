@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
    <section class="intro">
        <span class="eyebrow">AREA ADMIN</span>
        <h1>Masuk ke akun admin.</h1>
        <p>Login untuk melihat data peserta yang tersimpan.</p>
    </section>

    <section class="form-card login-card" aria-labelledby="login-title">
        <div class="card-heading">
            <div class="heading-icon" aria-hidden="true">⌑</div>
            <div>
                <h2 id="login-title">Login admin</h2>
                <p>Gunakan email dan kata sandi akun admin Anda.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="participant-form">
            @csrf
            <div class="field">
                <label for="email">Email <span>*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="admin@contoh.com" autocomplete="username" required autofocus>
            </div>

            <div class="field">
                <label for="password">Kata sandi <span>*</span></label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>

            <label class="remember-option">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya</span>
            </label>

            <div class="form-actions">
                <a class="text-link" href="{{ route('peserta.create') }}">← Kembali ke formulir</a>
                <button class="button button-primary" type="submit">Masuk <span aria-hidden="true">→</span></button>
            </div>
        </form>
    </section>
@endsection
