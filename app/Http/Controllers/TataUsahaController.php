<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TataUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function surat()
    {
        $title = 'Tata Usaha';
        return view('TataUsaha.Surat.main')->with('title', $title);
    }
    public function keuangan()
    {
        $title = 'Tata Usaha';
        return view('TataUsaha.Keuangan.main')->with('title', $title);
    }
    public function belanja()
    {
        $title = 'Tata Usaha';
        return view('TataUsaha.Belanja.main')->with('title', $title);
    }
    public function report()
    {
        $title = 'Tata Usaha';
        return view('TataUsaha.Report.main')->with('title', $title);
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
