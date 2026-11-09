@extends('layouts.app')

@section('title', 'Semua Data')

@section('content')
    <section class="intro intro-row">
        <div>
            <span class="eyebrow">DATA PESERTA</span>
            <h1>Pelatihan AI untuk ASN.</h1>
            <p>Daftar peserta yang sudah tersimpan.</p>
        </div>
        <a class="button button-primary" href="{{ route('peserta.create') }}">＋ Tambah data</a>
    </section>

    @if (session('success'))
        <div hidden data-sweetalert-success>{{ session('success') }}</div>
    @endif

    <section class="table-card" aria-label="Daftar seluruh peserta">
        <div class="table-header">
            <div>
                <h2>Daftar peserta</h2>
                <p>{{ $pesertas->total() }} data tersimpan</p>
            </div>
            <span class="count-badge">{{ str_pad((string) $pesertas->total(), 2, '0', STR_PAD_LEFT) }} PESERTA</span>
        </div>

        @if ($pesertas->isEmpty())
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true">○</div>
                <h3>Belum ada data peserta</h3>
                <p>Data yang Anda tambahkan akan muncul di halaman ini.</p>
                <a href="{{ route('peserta.create') }}" class="text-link">Tambah data pertama <span
                        aria-hidden="true">→</span></a>
            </div>
        @else
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama lengkap</th>
                            <th scope="col">Tempat lahir</th>
                            <th scope="col">Tanggal lahir</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesertas as $index => $peserta)
                            <tr>
                                <td class="row-number">{{ $pesertas->firstItem() + $index }}</td>
                                <td class="name-cell">
                                    <span class="avatar"
                                        aria-hidden="true">{{ mb_strtoupper(mb_substr($peserta->nama, 0, 1)) }}</span>
                                    <span>{{ $peserta->nama }}</span>
                                </td>
                                <td>{{ $peserta->tempat_lahir }}</td>
                                <td>{{ $peserta->tanggal_lahir->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('peserta.destroy', $peserta) }}" method="POST" class="delete-form" data-confirm-delete data-name="{{ $peserta->nama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button button-delete" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($pesertas->hasPages())
                <div class="pagination-wrap">{{ $pesertas->links() }}</div>
            @endif
        @endif
    </section>
@endsection
