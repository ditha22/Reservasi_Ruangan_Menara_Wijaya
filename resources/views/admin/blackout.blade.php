@extends('layouts.app')
@section('title', 'Kelola Blackout')
@section('content')

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-600));padding:40px 24px;color:white">
  <div style="max-width:1280px;margin:0 auto">
    <div style="display:flex;gap:8px;margin-bottom:16px;font-size:13px;color:rgba(255,255,255,0.5)">
      <a href="{{ route('admin.dashboard') }}" style="color:rgba(255,255,255,0.7);text-decoration:none">Dashboard</a>
      <span>›</span><span style="color:white">Blackout</span>
    </div>
    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);font-weight:700">Kelola Blackout / Maintenance</h1>
  </div>
</div>

<div style="max-width:900px;margin:0 auto;padding:32px 24px">

  <!-- ALERT SUCCESS -->
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- FORM TAMBAH -->
  <div style="background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);border:1px solid var(--blue-100);margin-bottom:28px">
    <h3 style="font-family:'Playfair Display',serif;font-size:18px;color:var(--blue-900);margin-bottom:20px">➕ Tambah Periode Blackout</h3>

    @if($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.blackout.store') }}">
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Ruangan</label>
          <select class="form-select" name="ruangan">
            <option value="Semua Ruangan">Semua Ruangan</option>
            @foreach($ruangan as $r)
              <option value="{{ $r->nama }}">{{ $r->nama }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal</label>
          <input class="form-input" type="date" name="tanggal" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alasan / Keterangan</label>
        <input class="form-input" type="text" name="alasan" placeholder="Contoh: Maintenance AC, Kegiatan Pimpinan..." required>
      </div>

      <button type="submit" class="btn-submit" style="width:auto;padding:12px 28px">
        Tambah Blackout
      </button>
    </form>
  </div>

  <!-- DAFTAR BLACKOUT -->
  <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--blue-900);margin-bottom:16px">
    📋 Daftar Blackout Aktif
  </h3>

  @if($blackouts->count() > 0)

    @foreach($blackouts as $b)

    <div class="blackout-card">

      <div style="display:flex;align-items:center;gap:14px">

        <div style="font-size:24px">
          🚧
        </div>

        <div>
          <div style="font-weight:700;color:var(--gray-800);font-size:15px">
            📅 {{ \App\Services\DataService::formatDateShort($b->tanggal) }} — {{ $b->ruangan }}
          </div>

          <div style="font-size:13px;color:var(--gray-500)">
            {{ $b->alasan }}
          </div>
        </div>

      </div>

      <form method="POST"
            action="{{ route('admin.blackout.delete', $b->id) }}"
            onsubmit="return confirm('Hapus blackout ini?')">

        @csrf
        @method('DELETE')

        <button type="submit" class="btn-blackout-del">
          Hapus
        </button>

      </form>

    </div>

    @endforeach

  @else

    <div class="empty-state">

      <div class="empty-state-icon">
        ✅
      </div>

      <div class="empty-state-title">
        Tidak Ada Blackout
      </div>

      <div class="empty-state-desc">
        Semua slot ruangan dalam kondisi normal.
      </div>

    </div>

  @endif

</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>

@endsection