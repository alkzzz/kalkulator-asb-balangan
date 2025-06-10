<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuManualController extends Controller
{
    // Admin - Tampilkan form upload
    public function create()
    {
        $path = 'manuals/buku-panduan-user-asb-sk.pdf';
        $existingFile = \Storage::disk('public')->exists($path) ? $path : null;

        return view('buku_manual.upload', compact('existingFile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'manual' => 'required|mimes:pdf|max:10240',
        ]);

        // Hapus file lama jika ada
        $files = \Storage::disk('public')->files('manuals');
        foreach ($files as $file) {
            \Storage::disk('public')->delete($file);
        }

        // Upload file baru dan simpan dengan nama tetap
        $uploaded = $request->file('manual');
        $filename = 'buku-panduan-user-asb-sk.pdf';
        $path = $uploaded->storeAs('manuals', $filename, 'public');

        return redirect()->back()->with('success', 'Buku panduan berhasil diunggah.');
    }

    // User - Tampilkan daftar buku manual
    public function index()
    {
        $files = Storage::disk('public')->files('manuals');

        return view('buku_manual.index', compact('files'));
    }
}
