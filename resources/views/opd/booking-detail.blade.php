@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-700));padding:20px 24px;color:white">
  <div style="max-width:720px;margin:0 auto;display:flex;align-items:center;gap:10px">
    <a href="{{ route('opd.bookings') }}" style="padding:7px 14px;border-radius:8px;border:1.5px solid rgba(255,255,255,0.25);color:rgba(255,255,255,0.85);font-size:13px;font-weight:600;text-decoration:none">‹ Kembali</a>
    <h1 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700">Detail Booking</h1>
  </div>
</div>

<div style="max-width:720px;margin:0 auto;padding:24px 20px 48px">
  <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-lg);padding:32px;border:1px solid var(--blue-100)">

    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-size:12px;color:var(--gray-400);font-family:monospace;margin-bottom:6px">{{ $booking['id'] }}</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--blue-900)">{{ $booking['kegiatan'] }}</h2>
      </div>
      <span class="badge badge-{{ strtolower($booking['status']) }}" style="font-size:13px;padding:6px 14px">{{ $booking['status'] }}</span>
    </div>

    <div style="height:1px;background:var(--blue-100);margin-bottom:24px"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
      @foreach([
        ['label'=>'Ruangan','value'=>$booking['ruangan']['nama'] ?? '—'],
        ['label'=>'Tanggal','value'=>$booking['tanggal_formatted']],
        ['label'=>'Sesi','value'=>($booking['sesi_data']['label'] ?? '').' ('.$booking['jam_mulai'].'–'.$booking['jam_selesai'].')'],
        ['label'=>'OPD / Instansi','value'=>$booking['opd']],
        ['label'=>'PIC','value'=>$booking['pj']],
        ['label'=>'No. HP','value'=>$booking['telp']],
        ['label'=>'Peserta','value'=>$booking['peserta'].' orang'],
        ['label'=>'Diajukan','value'=>\App\Services\DataService::formatDateShort($booking['created_at'])],
      ] as $item)
      <div>
        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:4px">{{ $item['label'] }}</div>
        <div style="font-size:15px;font-weight:600;color:var(--blue-900)">{{ $item['value'] }}</div>
      </div>
      @endforeach
    </div>

    @if($booking['catatan'])
    <div style="background:var(--blue-50);border-radius:var(--radius-sm);padding:14px;margin-bottom:24px">
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--blue-500);margin-bottom:6px">CATATAN</div>
      <div style="font-size:14px;color:var(--gray-700)">{{ $booking['catatan'] }}</div>
    </div>
    @endif

    @if($booking['status'] === 'DITOLAK' && $booking['rejection_reason'])
    <div class="rejection-reason" style="margin-bottom:24px">
      <span style="font-size:22px;flex-shrink:0">❌</span>
      <div>
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:#b91c1c;margin-bottom:4px">ALASAN PENOLAKAN</div>
        <span class="rejection-reason-text">{{ $booking['rejection_reason'] }}</span>
      </div>
    </div>
    @endif

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="{{ route('opd.bookings') }}" class="btn-sm btn-sm-outline">← Kembali ke Daftar</a>
      @if($booking['status'] === 'MENUNGGU')
        <form method="POST" action="{{ route('opd.booking.cancel', $booking['id']) }}" onsubmit="return confirm('Yakin ingin membatalkan?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn-sm btn-sm-danger">🚫 Batalkan Booking</button>
        </form>
      @endif
    </div>
  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
@endsection
