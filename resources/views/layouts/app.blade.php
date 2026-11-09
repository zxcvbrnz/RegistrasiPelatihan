<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Data Peserta') - Formulir Peserta</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body>
    <div class="page-shell">
        <header class="topbar">
            <a class="brand" href="{{ route('peserta.create') }}">
                <span class="brand-mark" aria-hidden="true">P</span>
                <span>Pelatihan AI untuk ASN</span>
            </a>
            <nav class="navigation" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('peserta.create') ? 'active' : '' }}"
                    href="{{ route('peserta.create') }}">Input Data</a>
                @auth
                    <a class="{{ request()->routeIs('peserta.index') ? 'active' : '' }}"
                        href="{{ route('peserta.index') }}">Data Peserta</a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button class="navigation-logout" type="submit">Keluar</button>
                    </form>
                @endauth
            </nav>
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="footer">Formulir data peserta <span aria-hidden="true">·</span></footer>
    </div>
</body>

</html>
