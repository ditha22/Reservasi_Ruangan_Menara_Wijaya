@extends('layouts.app')
@section('title', 'Beranda')

@push('styles')
<style>
/* =========================================
   HERO
========================================= */
.hero{
  background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-700) 50%, var(--blue-600) 100%);
  padding: 80px 24px 110px;
  position: relative;
  overflow: hidden;
}
.hero::before{
  content:'';
  position:absolute;
  inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events:none;
}
.hero-orb1{
  position:absolute;
  width:420px; height:420px;
  background: radial-gradient(circle, rgba(59,135,224,0.25) 0%, transparent 70%);
  top:-120px; right:-120px;
  border-radius:50%;
  animation: float 8s ease-in-out infinite;
}
.hero-orb2{
  position:absolute;
  width:320px; height:320px;
  background: radial-gradient(circle, rgba(106,174,255,0.15) 0%, transparent 70%);
  bottom:-70px; left:10%;
  border-radius:50%;
  animation: float 10s ease-in-out infinite reverse;
}

.hero-inner{
  max-width:1600px;
  margin:0 auto;
  position:relative;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:60px;
}

.hero-content{ max-width: 560px; }

.hero-badge{
  display:inline-flex;
  align-items:center;
  gap:10px;
  background: rgba(255,255,255,0.12);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  padding: 7px 16px;
  border-radius: 100px;
  margin-bottom: 24px;
  font-size: 13px;
  color: var(--blue-200);
  font-weight: 600;
}

.pulse-dot{
  width:8px; height:8px;
  border-radius:50%;
  background:#4ade80;
  display:inline-block;
  box-shadow: 0 0 0 rgba(74,222,128,0.5);
  animation: pulseDot 1.5s infinite;
}

.hero-title{
  font-family:'Playfair Display',serif;
  font-size: clamp(32px, 5vw, 54px);
  font-weight: 800;
  color: var(--white);
  line-height: 1.12;
  margin-bottom: 18px;
}
.hero-title span{ color: var(--blue-300); }

.hero-subtitle{
  font-size: 17px;
  color: rgba(255,255,255,0.72);
  line-height: 1.7;
  margin-bottom: 34px;
}

.hero-cta{
  display:flex;
  gap:14px;
  flex-wrap:wrap;
}

.btn-hero-primary{
  padding: 14px 28px;
  border-radius: var(--radius);
  font-size: 15px;
  font-weight: 700;
  background: var(--white);
  color: var(--blue-700);
  border:none;
  cursor:pointer;
  transition: var(--transition);
  box-shadow: 0 10px 30px rgba(0,0,0,0.22);
  text-decoration:none;
}
.btn-hero-primary:hover{
  transform: translateY(-2px);
  box-shadow: 0 16px 40px rgba(0,0,0,0.28);
}

.btn-hero-secondary{
  padding: 14px 28px;
  border-radius: var(--radius);
  font-size: 15px;
  font-weight: 700;
  background: rgba(255,255,255,0.14);
  color: var(--white);
  border: 1.5px solid rgba(255,255,255,0.30);
  cursor:pointer;
  transition: var(--transition);
  text-decoration:none;
}
.btn-hero-secondary:hover{
  background: rgba(255,255,255,0.22);
}

.hero-stats{
  display:flex;
  gap:34px;
  margin-top: 44px;
  flex-wrap:wrap;
}
.hero-stat-num{
  font-family:'Playfair Display',serif;
  font-size: 28px;
  font-weight: 800;
  color: var(--white);
  display:block;
}
.hero-stat-label{
  font-size: 12px;
  color: rgba(255,255,255,0.55);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.signage-card{
  background:#0d1f3c;
  border-radius: 22px;
  overflow:hidden;

  width:100%;
  min-width:950px;   
  max-width:1100px;   

  transform:scale(1.05);

  box-shadow:0 24px 64px rgba(0,0,0,0.45),
             0 0 0 1px rgba(255,255,255,0.06);

  flex-shrink:0;
}

.signage-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:18px 24px;
  background:#0f2549;
  border-bottom:1px solid rgba(255,255,255,0.07);
}

.signage-left{
  display:flex;
  align-items:center;
  gap:14px;
}

.signage-logo{
  width:36px;
  height:36px;
  border-radius:10px;
  background:rgba(59,135,224,0.18);
  border:1px solid rgba(59,135,224,0.35);

  display:flex;
  align-items:center;
  justify-content:center;

  font-size:18px;
}

.signage-building{
  font-size: 11px;
  font-weight: 900;
  letter-spacing: 0.12em;
  color:#7ec8ff;
  text-transform: uppercase;
}

.signage-sub{
  font-size: 11px;
  color: rgba(255,255,255,0.45);
  margin-top: 3px;
}

.signage-date-bar{
  display:flex;
  align-items:center;
  gap: 12px;
  padding: 14px 22px; /* ✅ lebih lega */
  background:#0d1f3c;
  border-bottom:1px solid rgba(255,255,255,0.06);
}

.signage-date-label{
  font-size: 12px;
  color: rgba(255,255,255,0.45);
  font-weight: 600;
  min-width: 56px;
}

.signage-date-value{
  flex:1;
  font-size: 17px;  /* ✅ lebih besar */
  font-weight: 800;
  color:#fff;
  font-family:'Playfair Display',serif;
}

/* =========================
   HEADER TABEL
========================= */

.signage-table-head{
  display:grid;
  grid-template-columns: 190px 100px 1fr 170px;
  padding:12px 26px;
  background:rgba(59,135,224,0.12);
  border-bottom:1px solid rgba(59,135,224,0.20);
  align-items:center;
}

.signage-table-head > div{
  font-size:12px;
  font-weight:700;
  letter-spacing:0.08em;
  color:#6aaeff;
}

/* alignment header */

.signage-table-head div:nth-child(1){ text-align:left; }
.signage-table-head div:nth-child(2){ text-align:center; }
.signage-table-head div:nth-child(3){ text-align:left; }
.signage-table-head div:nth-child(4){ text-align:right; }



/* =========================
   HEADER TABEL
========================= */

.signage-table-head{
  display:grid;
  grid-template-columns: 190px 100px 1fr 170px;
  padding:12px 26px;
  background:rgba(59,135,224,0.12);
  border-bottom:1px solid rgba(59,135,224,0.20);
  align-items:center;
}

.signage-table-head > div{
  font-size:12px;
  font-weight:700;
  letter-spacing:0.08em;
  color:#6aaeff;
}

/* alignment header */

.signage-table-head div:nth-child(1){
  text-align:left;
}

.signage-table-head div:nth-child(2){
  text-align:center;
}

.signage-table-head div:nth-child(3){
  text-align:center;
}

.signage-table-head div:nth-child(4){
  text-align:center;
}



/* =========================
   ROW TABEL
========================= */

.signage-row{
  display:grid;
  grid-template-columns: 190px 100px 1fr 170px;
  padding:18px 26px;
  border-bottom:1px solid rgba(255,255,255,0.05);
  align-items:center;
  gap:0;
  transition:0.18s;
}

.signage-row:hover{
  background:rgba(59,135,224,0.08);
}



/* =========================
   KOLOM RUANG
========================= */

.signage-col-ruang{
  font-size:13px;
  font-weight:800;
  color:#7ec8ff;

  text-align:left;

  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}



/* =========================
   KOLOM JAM
========================= */

.signage-col-jam{
  font-size:14px;
  font-weight:900;
  color:rgba(255,255,255,0.9);

  text-align:center;
}



/* =========================
   KOLOM ACARA
========================= */

.signage-col-acara{
  font-size:13px;
  color:rgba(255,255,255,0.92);

  text-align:center;

  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}



/* =========================
   KOLOM PELAKSANA
========================= */

.signage-col-pelaksana{
  font-size:12px;
  font-weight:700;
  color:rgba(255,255,255,0.65);

  text-transform:uppercase;
  letter-spacing:0.05em;

  text-align:center;

  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}



/* =========================
   EMPTY STATE
========================= */

.signage-empty{
  padding:34px 22px;
  text-align:center;
  color:rgba(255,255,255,0.35);
  font-size:13px;
}



/* =========================
   FOOTER BUTTON
========================= */

.signage-footer-btn{
  display:block;
  width:100%;
  padding:14px 22px;
  background:rgba(30,107,196,0.25);
  border:none;
  border-top:1px solid rgba(59,135,224,0.20);
  color:#7ec8ff;
  font-size:13px;
  font-weight:900;
  cursor:pointer;
  text-align:center;
  text-decoration:none;
  transition:0.25s;
}

.signage-footer-btn:hover{
  background:rgba(30,107,196,0.40);
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width:1024px){

  .signage-table-head,
  .signage-row{
    grid-template-columns:130px 80px 1fr 120px;
  }

}


@media (max-width:520px){

  .signage-table-head,
  .signage-row{
    grid-template-columns:100px 70px 1fr;
  }

  .signage-col-pelaksana,
  .signage-table-head div:last-child{
    display:none;
  }

}

/* =========================================
   ANIMATIONS (fallback kalau belum ada)
========================================= */
@keyframes float{
  0%,100%{ transform: translateY(0px); }
  50%{ transform: translateY(14px); }
}
@keyframes slideUp{
  from{ transform: translateY(16px); opacity: 0; }
  to{ transform: translateY(0); opacity: 1; }
}
@keyframes pulseDot{
  0%{ box-shadow: 0 0 0 0 rgba(74,222,128,0.55); }
  70%{ box-shadow: 0 0 0 10px rgba(74,222,128,0); }
  100%{ box-shadow: 0 0 0 0 rgba(74,222,128,0); }
}
</style>
@endpush

@section('content')

<!-- HERO -->
<div class="hero">
  <div class="hero-orb1"></div>
  <div class="hero-orb2"></div>

  <div class="hero-inner">
    <div class="hero-content" style="animation:slideUp 0.6s ease both">
      <div class="hero-badge">
        <span class="pulse-dot"></span>
        Sistem Aktif — Reservasi Online 24/7
      </div>

      <h1 class="hero-title">
        Reservasi Ruangan<br>
        <span>Menara Wijaya</span><br>
        Mudah & Cepat
      </h1>

      <p class="hero-subtitle">
        Platform digital terintegrasi untuk peminjaman ruangan rapat, seminar, dan kegiatan dinas di Menara Wijaya.
        Ajukan, pantau, dan kelola semua dalam satu sistem.
      </p>

      <div class="hero-cta">
        <a href="{{ route('kalender') }}" class="btn-hero-primary">🗓️ Lihat Kalender Ruangan</a>
        <a href="{{ route('agenda') }}" class="btn-hero-secondary">📋 Agenda Hari Ini</a>
      </div>

      <div class="hero-stats">
        <div class="hero-stat">
          <span class="hero-stat-num">{{ $totalRuangan }}</span>
          <span class="hero-stat-label">Ruangan</span>
        </div>
        <div class="hero-stat">
          <span class="hero-stat-num">{{ $totalToday }}</span>
          <span class="hero-stat-label">Booking Hari Ini</span>
        </div>
        <div class="hero-stat">
          <span class="hero-stat-num">3</span>
          <span class="hero-stat-label">Sesi Per Hari</span>
        </div>
      </div>
    </div>

    <!-- SIGNAGE CARD -->
    <div class="signage-card">
      <div class="signage-header">
        <div class="signage-left">
          <div class="signage-logo">🏛️</div>
          <div>
            <div class="signage-building">JADWAL RUANG RAPAT</div>
            <div class="signage-sub">Gedung Menara Wijaya Perkantoran Terpadu</div>
          </div>
        </div>
        <div>🔔</div>
      </div>

      <div class="signage-date-bar">
        <div class="signage-date-label">Hari Ini</div>
        <div class="signage-date-value">{{ \App\Services\DataService::formatDate(date('Y-m-d')) }}</div>
        <div>📅</div>
      </div>

      <div class="signage-table-head">
        <div>RUANG</div>
        <div>JAM</div>
        <div>ACARA</div>
        <div>PELAKSANA</div>
      </div>

      @if(count($todayBookings) > 0)
        @foreach($todayBookings as $b)
          @php $r = \App\Services\DataService::getRuanganById($b['ruang_id']); @endphp
          <div class="signage-row">
            <div class="signage-col-ruang">{{ $r ? $r['nama'] : '—' }}</div>
            <div class="signage-col-jam">{{ \Carbon\Carbon::parse($b['jam_mulai'])->format('H.i') }}</div>
            <div class="signage-col-acara">{{ $b['kegiatan'] }}</div>
            <div class="signage-col-pelaksana">{{ $b['opd'] }}</div>
          </div>
        @endforeach
      @else
        <div class="signage-empty">Tidak ada agenda hari ini</div>
      @endif

      <a href="{{ route('agenda') }}" class="signage-footer-btn">Lihat Semua Agenda Hari Ini →</a>
    </div>
  </div>
</div>

<!-- FITUR -->
<div class="section" style="background:white">
  <div class="section-inner">
    <div style="text-align:center;margin-bottom:48px">
      <span class="section-eyebrow">Cara Kerja</span>
      <h2 class="section-title">Proses Reservasi yang Mudah</h2>
      <p class="section-desc">Hanya 3 langkah sederhana untuk memesan ruangan yang Anda butuhkan</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px">
      @foreach([
        ['icon'=>'🔍','num'=>'1','title'=>'Cek Ketersediaan','desc'=>'Lihat kalender ruangan dan pilih slot yang tersedia sesuai kebutuhan Anda','color'=>'var(--blue-600),var(--blue-400)'],
        ['icon'=>'📝','num'=>'2','title'=>'Ajukan Booking','desc'=>'Isi form pengajuan peminjaman dengan lengkap dan kirimkan ke Admin Publik','color'=>'#059669,#10b981'],
        ['icon'=>'✅','num'=>'3','title'=>'Tunggu Persetujuan','desc'=>'Pantau status pengajuan Anda. Admin akan memverifikasi dan memberi keputusan','color'=>'#f59e0b,#fbbf24'],
      ] as $step)
        <div style="text-align:center;padding:32px 24px;border-radius:16px;background:var(--blue-50);border:1px solid var(--blue-100)">
          <div style="width:64px;height:64px;background:linear-gradient(135deg,{{ $step['color'] }});border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 20px">{{ $step['icon'] }}</div>
          <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--blue-900);margin-bottom:10px">{{ $step['num'] }}. {{ $step['title'] }}</h3>
          <p style="font-size:14px;color:var(--gray-500);line-height:1.6">{{ $step['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- CTA -->
<div class="section">
  <div class="section-inner" style="text-align:center">
    <div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-600));border-radius:24px;padding:60px 40px;position:relative;overflow:hidden">
      <div style="position:absolute;top:-60px;right:-60px;width:240px;height:240px;background:rgba(255,255,255,0.05);border-radius:50%"></div>
      <div style="position:absolute;bottom:-40px;left:-40px;width:180px;height:180px;background:rgba(255,255,255,0.05);border-radius:50%"></div>
      <h2 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);color:white;margin-bottom:14px;position:relative">Siap Membuat Reservasi?</h2>
      <p style="font-size:16px;color:rgba(255,255,255,0.7);margin-bottom:32px;position:relative">Login sebagai Admin OPD untuk mengajukan peminjaman ruangan</p>
      <a href="{{ route('login') }}" class="btn-hero-primary" style="font-size:16px;padding:16px 36px;position:relative">Booking Sekarang →</a>
    </div>
  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan. Dikembangkan untuk pelayanan publik yang lebih baik.</p>
</div>

@endsection