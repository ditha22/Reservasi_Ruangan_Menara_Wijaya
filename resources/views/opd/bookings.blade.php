@extends('layouts.app')
@section('title', 'Booking Saya')
@section('content')

@php
  use Carbon\Carbon;
@endphp

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-600));padding:40px 24px;color:white">
  <div style="max-width:1280px;margin:0 auto;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px">
    <div>
      <div style="font-size:12px;opacity:0.6;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em">📋 Riwayat</div>
      <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);font-weight:700;margin-bottom:8px">Booking Saya</h1>
      <p style="font-size:15px;opacity:0.7">Pantau status pengajuan peminjaman ruangan Anda</p>
    </div>

    <a href="{{ route('kalender') }}"
       style="padding:12px 24px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:var(--radius);font-size:14px;font-weight:600;text-decoration:none;align-self:flex-end">
      + Booking Baru
    </a>
  </div>
</div>

<div style="max-width:1280px;margin:0 auto;padding:32px 24px">

  {{-- Flash message --}}
  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px">{{ session('error') }}</div>
  @endif

  <!-- Filters -->
  <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap">
    @foreach(['semua'=>'Semua','MENUNGGU'=>'⏳ Menunggu','DISETUJUI'=>'✅ Disetujui','DITOLAK'=>'❌ Ditolak','DIBATALKAN'=>'🚫 Dibatalkan'] as $key => $label)
      <a href="?filter={{ $key }}" class="filter-btn {{ ($filter ?? 'semua') === $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
  </div>

  @if($bookings->count() > 0)
    @foreach($bookings as $b)
      @php
        // pastikan ini model Eloquent + relasi room
        $kode   = (string) ($b->kode ?? $b->id);
        $status = strtoupper((string) ($b->status ?? ''));

        $ruangNama = optional($b->room)->nama ?? '—';
        $ruangIcon = optional($b->room)->icon ?? '📋';

        $tanggalFormatted = \App\Services\DataService::formatDate($b->tanggal ?? '');

        $jamMulai   = (string) ($b->jam_mulai ?? '00:00:00');
        $jamSelesai = (string) ($b->jam_selesai ?? '00:00:00');

        $peserta = (int) ($b->peserta ?? 0);

        $rejectionReason = (string) ($b->rejection_reason ?? '');

        // ===== aturan tombol batal =====
        // boleh batal jika status MENUNGGU / DISETUJUI
        $canCancelByStatus = in_array($status, ['MENUNGGU','DISETUJUI'], true);

        // hanya boleh batal jika waktunya masih di masa depan
        // pakai jam_mulai sebagai patokan
        $startAt = null;
        try {
          $startAt = Carbon::parse(($b->tanggal ?? '') . ' ' . $jamMulai);
        } catch (\Throwable $e) {
          $startAt = null;
        }

        $canCancelByTime = $startAt ? $startAt->isFuture() : false;

        $canCancel = $canCancelByStatus && $canCancelByTime;
      @endphp

      <div style="background:var(--white);border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border:1.5px solid var(--blue-100);margin-bottom:16px;display:flex;gap:20px;align-items:flex-start;transition:var(--transition)">
        <div style="width:52px;height:52px;border-radius:14px;flex-shrink:0;background:linear-gradient(135deg,var(--blue-600),var(--blue-400));display:flex;align-items:center;justify-content:center;font-size:24px">
          {{ $ruangIcon }}
        </div>

        <div style="flex:1">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;margin-bottom:10px">
            <div style="font-size:16px;font-weight:700;color:var(--gray-800)">{{ $b->kegiatan }}</div>
            <span class="badge badge-{{ strtolower($status) }}">{{ $status }}</span>
          </div>

          <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:13px;color:var(--gray-600)">
            <span>🏢 {{ $ruangNama }}</span>
            <span>📅 {{ $tanggalFormatted }}</span>
            <span>🕐 {{ $jamMulai }}–{{ $jamSelesai }}</span>
            <span>👥 {{ $peserta }} peserta</span>
          </div>

          @if($status === 'DITOLAK' && $rejectionReason)
            <div class="rejection-reason" style="margin-top:12px">
              <span style="font-size:18px">❌</span>
              <span class="rejection-reason-text"><strong>Alasan:</strong> {{ $rejectionReason }}</span>
            </div>
          @endif

          <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap">
            {{-- PAKAI KODE --}}
            <a href="{{ route('opd.booking.show', $kode) }}" class="btn-sm btn-sm-outline">🔍 Detail</a>

            @if($canCancel)
              <form method="POST"
                    action="{{ route('opd.booking.cancel', $kode) }}"
                    style="display:inline"
                    onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm btn-sm-danger">🚫 Batalkan</button>
              </form>
            @else
              @if($canCancelByStatus && !$canCancelByTime)
                <span class="btn-sm" style="opacity:.65;border:1px solid var(--gray-200);border-radius:10px;padding:8px 12px">
                  ⛔ Tidak bisa dibatalkan (sudah lewat)
                </span>
              @endif
            @endif
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <div class="empty-state-title">Belum Ada Booking</div>
      <div class="empty-state-desc">Anda belum pernah mengajukan peminjaman ruangan</div>
      <div style="margin-top:24px">
        <a href="{{ route('kalender') }}"
           style="padding:12px 28px;background:var(--blue-600);color:white;border-radius:var(--radius);text-decoration:none;font-size:14px;font-weight:600">
          Booking Sekarang →
        </a>
      </div>
    </div>
  @endif
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
@endsection