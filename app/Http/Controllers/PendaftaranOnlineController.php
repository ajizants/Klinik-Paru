<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendaftaranOnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function createBaru()
    {
        return view('Pendaftaran.Online.baru');
        // return view('register_patient');
    }
    public function createLama()
    {
        return view('Pendaftaran.Online.lama');
        // return view('register_patient');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
        ]);

        // Simpan data pasien ke database
        // Patient::create($validated);

        return redirect()->route('patient.create')->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
