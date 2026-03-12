@extends('layouts.app')
@section('title', 'Kelola Ruangan')

@section('content')
<div class="container" style="max-width:1100px;margin:0 auto;padding:18px 18px 30px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;font-weight:800;color:var(--gray-800)">🏢 Kelola Ruangan</h2>
      <div style="color:var(--gray-500);margin-top:4px;">Tambah / ubah ruangan yang bisa dipinjam.</div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a href="{{ route('admin.dashboard') }}" class="filter-btn">← Kembali</a>
      <a href="{{ route('admin.ruang.create') }}" class="filter-btn" style="background:var(--blue-600);color:#fff;border-color:transparent;">+ Tambah Ruangan</a>
    </div>
  </div>

  @if(session('success'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(34,197,94,.35);background:rgba(34,197,94,.08);border-radius:12px;color:rgb(22,101,52);">
      {{ session('success') }}
    </div>
  @endif

  <div class="table-container" style="margin-top:16px;">
    <div class="table-header" style="gap:10px;flex-wrap:wrap;">
      <span class="table-title">Daftar Ruangan</span>

      <form method="GET" action="{{ route('admin.ruang.index') }}" style="display:flex;gap:10px;align-items:center;">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari ruangan..."
               style="padding:10px 12px;border:1px solid var(--gray-200);border-radius:12px;min-width:240px;">
        <button class="filter-btn" type="submit">Cari</button>
      </form>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th style="width:70px;">#</th>
            <th>Nama</th>
            <th style="width:180px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rooms as $i => $r)
            <tr>
              <td>{{ $rooms->firstItem() + $i }}</td>
              <td style="font-weight:700;">{{ $r->nama }}</td>
              <td>
                <div class="td-actions" style="display:flex;gap:8px;flex-wrap:wrap;">
                  <a href="{{ route('admin.ruang.edit', $r->id) }}" class="btn-action btn-view">Edit</a>

                  <form method="POST" action="{{ route('admin.ruang.destroy', $r->id) }}" onsubmit="return confirm('Yakin ingin menghapus/nonaktifkan ruangan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action" style="background:rgba(239,68,68,.12);color:rgb(185,28,28);border:1px solid rgba(239,68,68,.22);">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" style="text-align:center;padding:36px;color:var(--gray-400);">
                Belum ada data ruangan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="padding:14px 16px;">
      {{ $rooms->links() }}
    </div>
  </div>
</div>
@endsection