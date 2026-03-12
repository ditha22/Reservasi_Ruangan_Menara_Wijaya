@extends('layouts.app')
@section('title', 'Edit Ruangan')

@section('content')
<div class="container" style="max-width:900px;margin:0 auto;padding:18px 18px 30px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;font-weight:800;color:var(--gray-800)">✏️ Edit Ruangan</h2>
      <div style="color:var(--gray-500);margin-top:4px;">Perbarui informasi ruangan.</div>
    </div>
    <a href="{{ route('admin.ruang.index') }}" class="filter-btn">← Kembali</a>
  </div>

  <div class="table-container" style="margin-top:16px;padding:18px;">
    <form method="POST" action="{{ route('admin.ruang.update', $room->id) }}">
      @csrf
      @method('PUT')

      <div style="display:grid;grid-template-columns:1fr;gap:12px;">
        <div>
          <label style="font-weight:700;">Nama Ruangan <span style="color:#ef4444">*</span></label>
          <input type="text" name="nama" value="{{ old('nama', $room->nama) }}" required
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          @error('nama') <div style="color:#ef4444;margin-top:6px;">{{ $message }}</div> @enderror
        </div>

        <div>
          <label style="font-weight:700;">Kapasitas (opsional)</label>
          <input type="number" name="kapasitas" value="{{ old('kapasitas', $room->kapasitas ?? null) }}"
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          @error('kapasitas') <div style="color:#ef4444;margin-top:6px;">{{ $message }}</div> @enderror
        </div>

        <div>
          <label style="font-weight:700;">Lokasi (opsional)</label>
          <input type="text" name="lokasi" value="{{ old('lokasi', $room->lokasi ?? null) }}"
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          @error('lokasi') <div style="color:#ef4444;margin-top:6px;">{{ $message }}</div> @enderror
        </div>

        <div>
          <label style="font-weight:700;">Fasilitas (opsional)</label>
          <textarea name="fasilitas" rows="3"
                    style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">{{ old('fasilitas', $room->fasilitas ?? '') }}</textarea>
          @error('fasilitas') <div style="color:#ef4444;margin-top:6px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex;gap:10px;align-items:center;">
          <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $room->is_active ?? 1) ? 'checked' : '') }}>
          <label style="margin:0;">Aktif</label>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;">
          <button type="submit" class="filter-btn" style="background:var(--blue-600);color:#fff;border-color:transparent;">
            Update
          </button>
          <a href="{{ route('admin.ruang.index') }}" class="filter-btn">Batal</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection