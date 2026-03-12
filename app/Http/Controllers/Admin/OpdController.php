<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', 'aktif'); // aktif|nonaktif|semua

        $q = Opd::query()->orderBy('lantai')->orderBy('nama');

        if ($search !== '') {
            $q->where('nama', 'like', "%{$search}%");
        }

        if ($status === 'aktif') {
            $q->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $q->where('is_active', false);
        }

        $opds = $q->get();

        return view('admin.opd.index', compact('opds', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.opd.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:opds,nama',
            'lantai' => 'nullable|string|max:50',
            'gedung' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Opd::create($data);

        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $opd = Opd::findOrFail($id);
        return view('admin.opd.edit', compact('opd'));
    }

    public function update(Request $request, int $id)
    {
        $opd = Opd::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:opds,nama,' . $opd->id,
            'lantai' => 'nullable|string|max:50',
            'gedung' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', $opd->is_active);

        $opd->update($data);

        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil diperbarui.');
    }

    public function toggle(int $id)
    {
        $opd = Opd::findOrFail($id);
        $opd->update(['is_active' => ! $opd->is_active]);

        return redirect()->route('admin.opd.index')->with('success', 'Status OPD berhasil diubah.');
    }
}