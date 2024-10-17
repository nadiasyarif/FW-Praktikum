<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function create()
    {
        return view('master-data.suppliers-master.create-suppliers');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input data
        $validasi_data = $request->validate([
            'supplier_name'=> 'required|string|max:255',
            'supplier_address'=> 'required|string|max:500',
            'phone'=> 'required|string|max:50',
            'comment'=> 'nullable|string',
        ]);

        // proses simpan data kedalam database
        Suppliers::create($validasi_data);

        return redirect()->back()->with('success', 'Supplier created successfully!');
    }

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
