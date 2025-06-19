<?php

namespace App\Http\Controllers;

use App\Models\RanapPendaftaran;
use Illuminate\Http\Request;

class RanapPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        $title = 'Dashboard Pendaftaran';
        return view('Ranap.Pendaftaran.main', compact('title'));
    }
    public function index()
    {
        $title = 'Ranap Pendaftaran';
        return view('Ranap.Pendaftaran.main', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }
}
