<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SKPDController extends Controller
{
    // Show list
    public function index()
    {
        $skpd = DB::table('skpd')->get();
        return view('skpd.index', compact('skpd'));
    }

    // Show create form
    public function create()
    {
        return view('skpd.form', [
            'title' => 'Tambah SKPD',
            'action' => route('data-skpd.store'),
            'method' => 'POST',
            'skpd' => null
        ]);
    }

    // Store data
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
        ]);

        DB::table('skpd')->insert([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('data-skpd.index')->with('success', 'Data SKPD berhasil ditambahkan.');
    }

    // Show edit form
    public function edit($id)
    {
        $skpd = DB::table('skpd')->where('id', $id)->first();

        return view('skpd.form', [
            'title' => 'Edit SKPD',
            'action' => route('data-skpd.update', $id),
            'method' => 'PUT',
            'skpd' => $skpd
        ]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
        ]);

        DB::table('skpd')->where('id', $id)->update([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'updated_at' => now()
        ]);

        return redirect()->route('data-skpd.index')->with('success', 'Data SKPD berhasil diperbarui.');
    }

    // Delete data
    public function destroy($id)
    {
        DB::table('skpd')->where('id', $id)->delete();
        return redirect()->route('data-skpd.index')->with('success', 'Data SKPD berhasil dihapus.');
    }
}
