<?php

namespace App\Http\Controllers;

use App\Models\GiziModel;
use Illuminate\Http\Request;

class GiziModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function subKelas()
    {
        $data = GiziModel::all();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
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
    public function show(GiziModel $giziModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GiziModel $giziModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GiziModel $giziModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiziModel $giziModel)
    {
        //
    }
}
