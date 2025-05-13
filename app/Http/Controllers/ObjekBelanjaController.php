<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekBelanja;

class ObjekBelanjaController extends Controller
{
    public function index()
    {
        $data = ObjekBelanja::orderBy('nama_objek')->get();
        return view('objek.index', compact('data'));
    }

    public function create()
    {
        return view('objek.form', [
            'title'  => 'Tambah Objek Belanja',
            'action' => route('objek.store'),
            'method' => 'POST',
            'objek'  => null,
        ]);
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_objek' => 'required|unique:objek_belanja,nama_objek',
        ]);

        ObjekBelanja::create($r->only('nama_objek'));

        return redirect()->route('objek.index')->with('success', 'Data tersimpan');
    }

    public function edit($id)
    {
        $objek = ObjekBelanja::findOrFail($id);

        return view('objek.form', [
            'title'  => 'Edit Objek Belanja',
            'action' => route('objek.update', $id),
            'method' => 'PUT',
            'objek'  => $objek,
        ]);
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'nama_objek' => 'required|unique:objek_belanja,nama_objek,' . $id,
        ]);

        ObjekBelanja::where('id', $id)->update($r->only('nama_objek'));

        return redirect()->route('objek.index')->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        ObjekBelanja::destroy($id);
        return back()->with('success', 'Data dihapus');
    }
}
