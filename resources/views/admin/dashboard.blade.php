@extends('layouts.app')
@section('title', 'Dashboard Admin')

@push('styles')
<style>
  /* ===== HERO / BAR (dibuat lebih kecil) ===== */
  .dash-hero{
    background: linear-gradient(135deg,var(--blue-800),var(--blue-600));
    padding: 26px 24px 26px; /* lebih kecil dari sebelumnya (40px) */
    color: white;
  }
  .dash-hero-inner{max-width:1280px;margin:0 auto;}
  .dash-title{
    font-family:'Playfair Display',serif;
    font-size: 28px;
    font-weight: 800;
    margin: 0 0 6px 0;
    letter-spacing: .2px;
  }
  .dash-sub{font-size:14px;opacity:.72}

  /* ===== STAT CARDS (clickable) ===== */
  .stats-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
    margin-top: 18px; /* lebih rapat */
  }
  .stat-link{
    text-decoration:none;
    color: inherit;
    display:block;
  }
  .stat-card{
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius);
    padding: 18px;
    transition: var(--transition);
    cursor: pointer;
    min-height: 112px;
  }
  .stat-card:hover{
    background: rgba(255,255,255,0.18);
    transform: translateY(-2px);
  }
  .stat-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap: 10px;
    margin-bottom: 10px;
  }
  .stat-card-num{
    font-family:'Playfair Display',serif;
    font-size: 30px;
    font-weight: 800;
    color: white;
    margin: 0;
  }
  .stat-card-label{
    font-size: 13px;
    opacity: 0.75;
    margin-top: 4px;
  }
  .stat-pill{
    font-size:12px;
    padding:4px 10px;
    border-radius:999px;
    font-weight:700;
    line-height:1;
  }
  .pill-total{background:rgba(74,222,128,0.2);color:#4ade80;}
  .pill-pending{background:rgba(248,113,113,0.2);color:#f87171;}
  .pill-approved{background:rgba(74,222,128,0.2);color:#4ade80;}
  .pill-rejected{background:rgba(251,113,133,0.16);color:#fb7185;}

  /* ✅ NEW: pill dibatalkan */
  .pill-canceled{background:rgba(148,163,184,0.22);color:#e2e8f0;}

  .stat-hint{font-size:12px;opacity:.65;margin-top:6px}

  /* ===== ACTION BUTTONS (dibuat lebih besar & jelas) ===== */
  .dash-wrap{
    max-width:1280px;
    margin:0 auto;
    padding: 22px 24px 32px;
  }
  .dash-actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    align-items:center;
    margin-bottom: 18px;
  }
  .dash-btn{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding: 13px 20px; /* lebih besar */
    border-radius: 14px;
    font-size: 14px;
    font-weight: 750;
    text-decoration:none;
    border: 1.5px solid transparent;
    transition: var(--transition);
    box-shadow: 0 10px 24px rgba(15,23,42,0.06);
  }
  .dash-btn-primary{
    background: linear-gradient(135deg,var(--blue-600),var(--blue-500));
    color:white;
  }
  .dash-btn-primary:hover{transform: translateY(-1px); opacity:.97}
  .dash-btn-ghost{
    background: var(--white);
    color: var(--blue-700);
    border-color: var(--blue-200);
  }
  .dash-btn-ghost:hover{transform: translateY(-1px); border-color: var(--blue-300)}
  .dash-btn-gray{
    background: var(--white);
    color: var(--gray-700);
    border-color: var(--gray-200);
  }
  .dash-btn-gray:hover{transform: translateY(-1px); border-color: var(--gray-300)}
</style>
@endpush

@section('content')
@php
  use Illuminate\Support\Str;
@endphp

<div class="dash-hero">
  <div class="dash-hero-inner">
    <div class="dash-title">Selamat Datang, Admin Publik 👋</div>
    <div class="dash-sub">{{ \App\Services\DataService::formatDate(date('Y-m-d')) }}</div>

    <div class="stats-grid">
      {{-- TOTAL (klik -> semua) --}}
      <a class="stat-link" href="{{ route('admin.bookings', ['filter' => 'semua']) }}">
        <div class="stat-card">
          <div class="stat-top">
            <span style="font-size:22px">📨</span>
            <span class="stat-pill pill-total">Total</span>
          </div>
          <div class="stat-card-num">{{ $total }}</div>
          <div class="stat-card-label">Total Booking</div>
          <div class="stat-hint">Klik untuk lihat semua</div>
        </div>
      </a>

      {{-- MENUNGGU (klik -> filter MENUNGGU) --}}
      <a class="stat-link" href="{{ route('admin.bookings', ['filter' => 'MENUNGGU']) }}">
        <div class="stat-card">
          <div class="stat-top">
            <span style="font-size:22px">⏳</span>
            <span class="stat-pill pill-pending">{{ $pending }} pending</span>
          </div>
          <div class="stat-card-num">{{ $pending }}</div>
          <div class="stat-card-label">Menunggu Verifikasi</div>
          <div class="stat-hint">Klik untuk proses</div>
        </div>
      </a>

      {{-- DISETUJUI (klik -> filter DISETUJUI) --}}
      <a class="stat-link" href="{{ route('admin.bookings', ['filter' => 'DISETUJUI']) }}">
        <div class="stat-card">
          <div class="stat-top">
            <span style="font-size:22px">✅</span>
            <span class="stat-pill pill-approved">Disetujui</span>
          </div>
          <div class="stat-card-num">{{ $approved }}</div>
          <div class="stat-card-label">Disetujui</div>
          <div class="stat-hint">Klik untuk lihat data</div>
        </div>
      </a>

      {{-- DITOLAK (klik -> filter DITOLAK) --}}
      <a class="stat-link" href="{{ route('admin.bookings', ['filter' => 'DITOLAK']) }}">
        <div class="stat-card">
          <div class="stat-top">
            <span style="font-size:22px">❌</span>
            <span class="stat-pill pill-rejected">Ditolak</span>
          </div>
          <div class="stat-card-num">{{ $rejected }}</div>
          <div class="stat-card-label">Ditolak</div>
          <div class="stat-hint">Klik untuk lihat alasan</div>
        </div>
      </a>

      {{-- ✅ NEW: DIBATALKAN (klik -> filter DIBATALKAN) --}}
      <a class="stat-link" href="{{ route('admin.bookings', ['filter' => 'DIBATALKAN']) }}">
        <div class="stat-card">
          <div class="stat-top">
            <span style="font-size:22px">🚫</span>
            <span class="stat-pill pill-canceled">Dibatalkan</span>
          </div>
          <div class="stat-card-num">{{ $canceled ?? 0 }}</div>
          <div class="stat-card-label">Dibatalkan</div>
          <div class="stat-hint">Klik untuk lihat riwayat</div>
        </div>
      </a>
    </div>
  </div>
</div>

<div class="dash-wrap">
  <div class="dash-actions">
    <a href="{{ route('admin.bookings') }}" class="dash-btn dash-btn-primary">📋 Kelola Semua Booking</a>
    <a href="{{ route('admin.laporan') }}" class="dash-btn dash-btn-ghost">📊 Laporan & Rekap</a>
    <a href="{{ route('admin.blackout') }}" class="dash-btn dash-btn-gray">🚧 Kelola Blackout</a>

    {{-- ✅ NEW: Kelola Ruangan & OPD --}}
    <a href="{{ route('admin.ruang.index') }}" class="dash-btn dash-btn-ghost">🏢 Kelola Ruangan</a>
    <a href="{{ route('admin.opd.index') }}" class="dash-btn dash-btn-gray">🏛️ Kelola OPD</a>
  </div>

  <div class="table-container">
    <div class="table-header">
      <span class="table-title">🕐 Aktivitas Terbaru</span>
      <a href="{{ route('admin.bookings') }}" class="filter-btn" style="color:var(--blue-600);border-color:var(--blue-200)">Lihat Semua →</a>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Kegiatan</th>
            <th>OPD</th>
            <th>Ruangan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($recent as $b)
            <tr>
              <td style="font-weight:600">{{ Str::limit($b->kegiatan, 40) }}</td>
              <td>{{ $b->opd }}</td>
              <td>{{ $b->room->nama ?? '—' }}</td>
              <td>{{ $b->tanggal_formatted ?? '-' }}</td>
              <td>
                <span class="badge badge-{{ strtolower($b->status) }}">{{ $b->status }}</span>
              </td>
              <td>
                <div class="td-actions">
                  <a href="{{ route('admin.booking.show', $b->kode) }}" class="btn-action btn-view">Detail</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;padding:40px;color:var(--gray-400)">
                Belum ada aktivitas booking.
              </td>
            </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
@endsection