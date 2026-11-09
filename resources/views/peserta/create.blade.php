@extends('layouts.app')

@section('title', 'Input Data')

@section('content')
    <section class="intro">
        <span class="eyebrow">FORMULIR PESERTA</span>
        <h1>Pelatihan AI untuk ASN.</h1>
        <p>Lengkapi informasi berikut untuk registrasi.</p>
    </section>

    @if (session('success'))
        <div hidden data-sweetalert-success>{{ session('success') }}</div>
    @endif

    <section class="form-card" aria-labelledby="form-title">
        <div class="card-heading">
            <div class="heading-icon" aria-hidden="true">✦</div>
            <div>
                <h2 id="form-title">Informasi pribadi</h2>
                <p>Pastikan data yang dimasukkan sudah benar.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Periksa kembali data Anda.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peserta.store') }}" method="POST" class="participant-form" data-single-submit>
            @csrf
            <div class="form-honeypot" aria-hidden="true">
                <label for="website">Biarkan kolom ini kosong</label>
                <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>
            <div class="field">
                <label for="nama">Nama lengkap <span>*</span></label>
                <input id="nama" name="nama" type="text" value="{{ old('nama') }}"
                    placeholder="Contoh: Aditya Pratama" autocomplete="name" maxlength="255" required>
                @error('nama')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="field">
                <label for="tempat_lahir">Tempat lahir <span>*</span></label>
                <input id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}"
                    placeholder="Contoh: Bandung" maxlength="255" required>
                @error('tempat_lahir')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="field">
                <label for="tanggal_lahir">Tanggal lahir <span>*</span></label>
                <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}"
                    max="{{ now()->toDateString() }}" required>
                @error('tanggal_lahir')
                    <small class="field-error">{{ $message }}</small>
                @enderror
                <small class="field-hint">Pilih tanggal, bulan, dan tahun lahir.</small>
            </div>

            <div class="form-actions">
                <p><span>*</span> Wajib diisi</p>
                <button class="button button-primary" type="submit">
                    Kirim <span aria-hidden="true">→</span>
                </button>
            </div>
        </form>
    </section>
@endsection
