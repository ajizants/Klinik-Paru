<?php

namespace App\Http\Controllers;

use App\Models\ROJenisFilm;
use App\Models\ROJenisFoto;
use App\Models\ROJenisKondisi;
use App\Models\ROJenisMesin;
use Illuminate\Http\Request;

class MasterRoController extends Controller
{
    public function index()
    {
        // Ambil data dari masing-masing model
        $jenisFoto = ROJenisFoto::all();
        $jenisFilm = ROJenisFilm::all();
        $jenisKondisi = ROJenisKondisi::all();
        $jenisMesin = ROJenisMesin::all();

        // Kirim data ke view
        return view('RO.Master.index', compact('jenisFoto', 'jenisFilm', 'jenisKondisi', 'jenisMesin'));
    }

    public function create()
    {
        // Tampilkan formulir untuk membuat data baru
        return view('RO.Master.create');
    }

    public function store(Request $request)
    {
        // Validasi input dari formulir
        $request->validate([
            // Sesuaikan dengan aturan validasi Anda
        ]);

        // Simpan data baru ke dalam database
        // Misalnya untuk jenis foto
        ROJenisFoto::create([
            'nama' => $request->nama,
            // Tambahkan kolom lainnya sesuai kebutuhan
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('masterRo.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        // Ambil data yang akan diedit
        $jenisFoto = ROJenisFoto::findOrFail($id);

        // Tampilkan formulir untuk mengedit data
        return view('RO.Master.edit', compact('jenisFoto'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari formulir
        $request->validate([
            // Sesuaikan dengan aturan validasi Anda
        ]);

        // Update data yang telah diedit
        $jenisFoto = ROJenisFoto::findOrFail($id);
        $jenisFoto->update([
            'nama' => $request->nama,
            // Update kolom lainnya sesuai kebutuhan
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('masterRo.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Hapus data
        $jenisFoto = ROJenisFoto::findOrFail($id);
        $jenisFoto->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('masterRo.index')->with('success', 'Data berhasil dihapus.');
    }
}
