@extends('layouts.app')
@section('title', 'Agenda Hari Ini')
@section('content')

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-700));padding:40px 24px;color:white">
  <div style="max-width:1280px;margin:0 auto">
    <div style="font-size:12px;opacity:0.6;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em">📅 Agenda Hari Ini</div>
    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);font-weight:700;margin-bottom:8px">Jadwal Kegiatan</h1>
    <p style="font-size:15px;opacity:0.7">{{ $today }}</p>
  </div>
</div>

<div style="max-width:1280px;margin:0 auto;padding:32px 24px">
  @if(count($agendaItems) > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px" id="agendaGrid">
      @foreach($agendaItems as $item)
        @php
          $statusLabel = ['berlangsung'=>'Sedang Berlangsung','akan'=>'Akan Datang','selesai'=>'Selesai'];
          $statusClass = ['berlangsung'=>'badge-disetujui','akan'=>'badge-menunggu','selesai'=>'badge-dibatalkan'];
          $colors = ['berlangsung'=>'#10b981','akan'=>'#3b87e0','selesai'=>'#94a3b8'];
        @endphp
        <div style="background:white;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border:1px solid var(--blue-100);position:relative;overflow:hidden;transition:var(--transition)" class="agenda-card-item" data-status="{{ $item['agenda_status'] }}">
          <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,{{ $colors[$item['agenda_status']] }},{{ $colors[$item['agenda_status']] }}80)"></div>
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div style="font-size:12px;font-weight:600;color:var(--blue-500);text-transform:uppercase;letter-spacing:0.08em">
              {{ $item['ruangan']['nama'] ?? '—' }}
            </div>
            <span class="badge {{ $statusClass[$item['agenda_status']] }}">
              {{ $statusLabel[$item['agenda_status']] }}
            </span>
          </div>
          <div style="font-size:16px;font-weight:600;color:var(--gray-800);margin-bottom:8px">{{ $item['kegiatan'] }}</div>
          <div style="font-size:13px;color:var(--gray-500);margin-bottom:14px">{{ $item['opd'] }}</div>
          <div style="display:flex;gap:16px;flex-wrap:wrap">
           <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-600)">
            <span>🕐</span> 
            {{ \Carbon\Carbon::parse($item['jam_mulai'])->format('H.i') }}
            –
            {{ \Carbon\Carbon::parse($item['jam_selesai'])->format('H.i') }}
          </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-600)">
              <span>👥</span> {{ $item['peserta'] }} peserta
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-600)">
              <span>🏢</span> {{ $item['ruangan']['lantai'] ?? '' }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <div class="empty-state-title">Tidak Ada Agenda Hari Ini</div>
      <div class="empty-state-desc">Belum ada kegiatan yang dijadwalkan hari ini</div>
    </div>
  @endif

  <div style="margin-top:32px;text-align:center">
    <a href="{{ route('kalender') }}" style="padding:14px 32px;border-radius:var(--radius);background:linear-gradient(135deg,var(--blue-600),var(--blue-500));color:white;border:none;font-size:15px;font-weight:600;cursor:pointer;box-shadow:0 4px 20px rgba(30,107,196,0.3);text-decoration:none;display:inline-block">🗓️ Lihat Kalender & Booking Sekarang</a>
  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
@endsection
