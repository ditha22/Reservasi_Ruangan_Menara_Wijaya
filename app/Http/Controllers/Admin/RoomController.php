<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RoomController extends Controller
{
    /**
     * List ruangan
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $rooms = Room::query()
            ->when($q !== '', function ($query) use ($q) {
                // kolom yang pasti kamu pakai di UI adalah "nama"
                $query->where('nama', 'like', "%{$q}%");

                // kalau ada kolom lain, boleh ikut cari (aman)
                if (Schema::hasColumn('rooms', 'lokasi')) {
                    $query->orWhere('lokasi', 'like', "%{$q}%");
                }
                if (Schema::hasColumn('rooms', 'fasilitas')) {
                    $query->orWhere('fasilitas', 'like', "%{$q}%");
                }
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ruang.index', compact('rooms', 'q'));
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('admin.ruang.create');
    }

    /**
     * Simpan ruangan baru
     */
    public function store(Request $request)
    {
        // minimal: nama wajib ada
        $rules = [
            'nama' => ['required', 'string', 'max:120'],
        ];

        // tambahan opsional jika kolomnya ada di DB
        if (Schema::hasColumn('rooms', 'kapasitas')) {
            $rules['kapasitas'] = ['nullable', 'integer', 'min:0'];
        }
        if (Schema::hasColumn('rooms', 'lokasi')) {
            $rules['lokasi'] = ['nullable', 'string', 'max:150'];
        }
        if (Schema::hasColumn('rooms', 'fasilitas')) {
            $rules['fasilitas'] = ['nullable', 'string', 'max:500'];
        }
        if (Schema::hasColumn('rooms', 'is_active')) {
            $rules['is_active'] = ['nullable', 'boolean'];
        }

        $data = $request->validate($rules);

        $room = new Room();
        $room->nama = $data['nama'];

        if (Schema::hasColumn('rooms', 'kapasitas') && array_key_exists('kapasitas', $data)) {
            $room->kapasitas = $data['kapasitas'];
        }
        if (Schema::hasColumn('rooms', 'lokasi') && array_key_exists('lokasi', $data)) {
            $room->lokasi = $data['lokasi'];
        }
        if (Schema::hasColumn('rooms', 'fasilitas') && array_key_exists('fasilitas', $data)) {
            $room->fasilitas = $data['fasilitas'];
        }
        if (Schema::hasColumn('rooms', 'is_active')) {
            // default aktif kalau checkbox tidak dikirim
            $room->is_active = (bool) ($request->input('is_active', 1));
        }

        $room->save();

        return redirect()
            ->route('admin.ruang.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.ruang.edit', compact('room'));
    }

    /**
     * Update ruangan
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $rules = [
            'nama' => ['required', 'string', 'max:120'],
        ];

        if (Schema::hasColumn('rooms', 'kapasitas')) {
            $rules['kapasitas'] = ['nullable', 'integer', 'min:0'];
        }
        if (Schema::hasColumn('rooms', 'lokasi')) {
            $rules['lokasi'] = ['nullable', 'string', 'max:150'];
        }
        if (Schema::hasColumn('rooms', 'fasilitas')) {
            $rules['fasilitas'] = ['nullable', 'string', 'max:500'];
        }
        if (Schema::hasColumn('rooms', 'is_active')) {
            $rules['is_active'] = ['nullable', 'boolean'];
        }

        $data = $request->validate($rules);

        $room->nama = $data['nama'];

        if (Schema::hasColumn('rooms', 'kapasitas') && array_key_exists('kapasitas', $data)) {
            $room->kapasitas = $data['kapasitas'];
        }
        if (Schema::hasColumn('rooms', 'lokasi') && array_key_exists('lokasi', $data)) {
            $room->lokasi = $data['lokasi'];
        }
        if (Schema::hasColumn('rooms', 'fasilitas') && array_key_exists('fasilitas', $data)) {
            $room->fasilitas = $data['fasilitas'];
        }
        if (Schema::hasColumn('rooms', 'is_active')) {
            $room->is_active = (bool) ($request->input('is_active', 1));
        }

        $room->save();

        return redirect()
            ->route('admin.ruang.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Hapus ruangan
     * Catatan: kalau ruangan sudah dipakai di bookings, delete bisa gagal karena foreign key.
     * Kalau terjadi, kamu bisa ganti ke "nonaktif" saja.
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);

        // Jika ada kolom is_active, lebih aman nonaktif daripada delete
        if (Schema::hasColumn('rooms', 'is_active')) {
            $room->is_active = 0;
            $room->save();

            return redirect()
                ->route('admin.ruang.index')
                ->with('success', 'Ruangan berhasil dinonaktifkan.');
        }

        $room->delete();

        return redirect()
            ->route('admin.ruang.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}