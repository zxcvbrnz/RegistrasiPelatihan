<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function create(): View
    {
        return view('peserta.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'website' => ['prohibited'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'website.prohibited' => 'Permintaan tidak dapat diproses.',
        ]);

        Peserta::create($validated);

        return redirect()
            ->route('peserta.create')
            ->with('success', 'Data kamu telah dikirim.');
    }

    public function index(): View
    {
        return view('peserta.index', [
            'pesertas' => Peserta::query()->latest()->paginate(10),
        ]);
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $peserta->delete();

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Data peserta berhasil dihapus.');
    }
}
