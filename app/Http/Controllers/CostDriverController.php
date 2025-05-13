<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrukturASB;

class CostDriverController extends Controller
{
    public function index(StrukturASB $struktur_asb)
    {
        return view('cost_driver.index', [
            'struktur_asb' => $struktur_asb,
            'drivers' => $struktur_asb->costDrivers
        ]);
    }

    public function create(StrukturASB $struktur_asb)
    {
        return view('cost_driver.form', [
            'struktur_asb' => $struktur_asb,
            'driver' => null,
            'action' => route('asb.cost-driver.store', $struktur_asb->id),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request, StrukturASB $struktur_asb)
    {
        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $struktur_asb->costDrivers()->create([
            'label' => $request->label,
        ]);

        return redirect()->route('asb.edit', $struktur_asb->id)
            ->with('success', 'Cost Driver berhasil ditambahkan.');
    }

    public function edit(StrukturASB $struktur_asb, $id)
    {
        $driver = $struktur_asb->costDrivers()->findOrFail($id);

        return view('cost_driver.form', [
            'struktur_asb' => $struktur_asb,
            'driver' => $driver,
            'action' => route('asb.cost-driver.update', [$struktur_asb->id, $driver->id]),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, StrukturASB $struktur_asb, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $driver = $struktur_asb->costDrivers()->findOrFail($id);
        $driver->update([
            'label' => $request->label,
        ]);

        return redirect()->route('asb.edit', $struktur_asb->id)
            ->with('success', 'Cost Driver berhasil diperbarui.');
    }

    public function destroy(StrukturASB $struktur_asb, $id)
    {
        $driver = $struktur_asb->costDrivers()->findOrFail($id);
        $driver->delete();

        return redirect()->route('asb.edit', $struktur_asb->id)
            ->with('success', 'Cost Driver berhasil dihapus.');
    }
}
