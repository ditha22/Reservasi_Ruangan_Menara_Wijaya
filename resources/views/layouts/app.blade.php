<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Reservasi Ruangan') — Menara Wijaya</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --blue-900: #0a1628; --blue-800: #0d2045; --blue-700: #0f3460;
  --blue-600: #1a4f8a; --blue-500: #1e6bc4; --blue-400: #3b87e0;
  --blue-300: #6aaeff; --blue-200: #b8d8fa; --blue-100: #e8f3fd; --blue-50: #f4f9ff;
  --white: #ffffff; --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
  --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-500: #64748b; --gray-600: #475569;
  --gray-700: #334155; --gray-800: #1e293b;
  --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
  --radius: 12px; --radius-sm: 8px;
  --shadow: 0 4px 24px rgba(10,22,40,0.10); --shadow-lg: 0 8px 40px rgba(10,22,40,0.15);
  --shadow-xl: 0 20px 60px rgba(10,22,40,0.18); --transition: 0.22s cubic-bezier(0.4,0,0.2,1);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; background: var(--blue-50); color: var(--gray-800); min-height: 100vh; }
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--blue-50); }
::-webkit-scrollbar-thumb { background: var(--blue-300); border-radius: 3px; }

/* NAV */
.nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; background: rgba(255,255,255,0.92); backdrop-filter: blur(16px); border-bottom: 1px solid var(--blue-100); box-shadow: 0 2px 20px rgba(10,22,40,0.08); }
.nav-inner {
  width:100%;
  max-width:1600px;
  margin:0 auto;
  padding:0 16px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  height:68px;
}
.nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.nav-brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--blue-600), var(--blue-400)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.nav-brand-text strong { display: block; font-family: 'Playfair Display', serif; font-size: 16px; color: var(--blue-800); font-weight: 700; }
.nav-brand-text span { font-size: 11px; color: var(--gray-500); letter-spacing: 0.05em; text-transform: uppercase; }
.nav-links { display: flex; align-items: center; gap: 4px; }
.nav-link { padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; color: var(--gray-600); text-decoration: none; transition: var(--transition); }
.nav-link:hover, .nav-link.active { color: var(--blue-600); background: var(--blue-100); }
.nav-actions { display: flex; align-items: center; gap: 10px; }
.btn-nav-login { padding: 9px 20px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; background: linear-gradient(135deg, var(--blue-600), var(--blue-500)); color: var(--white); border: none; cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-nav-login:hover { transform: translateY(-1px); }
.nav-user { display: flex; align-items: center; gap: 10px; }
.nav-user-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--blue-500), var(--blue-300)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: white; }
.nav-user-name { font-size: 13px; font-weight: 600; color: var(--gray-800); }
.nav-user-role { font-size: 11px; color: var(--blue-500); font-weight: 500; }
.btn-logout { padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; border: 1.5px solid var(--gray-200); background: none; cursor: pointer; color: var(--gray-600); transition: var(--transition); }
.btn-logout:hover { border-color: var(--danger); color: var(--danger); }
.hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 8px; background: none; border: none; }
.hamburger span { display: block; width: 22px; height: 2px; background: var(--gray-700); border-radius: 2px; }
.mobile-menu { display: none; position: fixed; top: 68px; left: 0; right: 0; z-index: 99; background: var(--white); border-bottom: 1px solid var(--blue-100); padding: 12px 24px 20px; box-shadow: var(--shadow); }
.mobile-menu.open { display: block; }
.mobile-menu .nav-link { display: block; padding: 12px 16px; }

/* MAIN CONTENT */
.main-content { padding-top: 68px; }

/* BADGE */
.badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
.badge-menunggu { background: #fef3c7; color: #b45309; }
.badge-disetujui { background: #dcfce7; color: #15803d; }
.badge-ditolak { background: #fee2e2; color: #b91c1c; }
.badge-dibatalkan { background: var(--gray-100); color: var(--gray-500); }

/* FORMS */
.form-group { margin-bottom: 20px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 7px; }
.form-input, .form-select, .form-textarea { width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--gray-200); font-size: 14px; font-family: 'DM Sans', sans-serif; color: var(--gray-800); background: var(--white); transition: var(--transition); outline: none; }
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(30,107,196,0.10); }
.form-textarea { resize: vertical; min-height: 100px; }
.form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-hint { font-size: 12px; color: var(--gray-400); margin-top: 5px; }
.btn-submit { width: 100%; padding: 14px; border-radius: var(--radius); font-size: 15px; font-weight: 700; background: linear-gradient(135deg, var(--blue-600), var(--blue-500)); color: var(--white); border: none; cursor: pointer; transition: var(--transition); box-shadow: 0 4px 20px rgba(30,107,196,0.35); }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(30,107,196,0.45); }
.btn-sm { padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1.5px solid; cursor: pointer; transition: var(--transition); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
.btn-sm-outline { border-color: var(--blue-300); color: var(--blue-600); background: none; }
.btn-sm-outline:hover { background: var(--blue-50); }
.btn-sm-danger { border-color: var(--danger); color: var(--danger); background: none; }
.btn-sm-danger:hover { background: #fee2e2; }
.btn-sm-success { border-color: var(--success); color: var(--success); background: none; }
.btn-sm-success:hover { background: #dcfce7; }

/* TABLES */
.table-container { background: var(--white); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); border: 1px solid var(--blue-100); }
.table-header { padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--blue-100); flex-wrap: wrap; gap: 12px; }
.table-title { font-size: 16px; font-weight: 700; color: var(--blue-900); }
.table-filters { display: flex; gap: 10px; flex-wrap: wrap; }
.filter-btn { padding: 7px 14px; border-radius: 100px; font-size: 12px; font-weight: 600; border: 1.5px solid var(--gray-200); background: none; cursor: pointer; transition: var(--transition); color: var(--gray-600); text-decoration: none; }
.filter-btn:hover { border-color: var(--blue-400); color: var(--blue-600); }
.filter-btn.active { background: var(--blue-600); border-color: var(--blue-600); color: white; }
.table-responsive { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead tr { background: var(--blue-50); }
th { padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: var(--blue-700); text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
td { padding: 16px 20px; font-size: 14px; color: var(--gray-700); border-bottom: 1px solid var(--blue-50); }
tr:hover td { background: var(--blue-50); }
tr:last-child td { border-bottom: none; }
.td-actions { display: flex; gap: 8px; }
.btn-action { padding: 6px 12px; border-radius: 7px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-approve { background: #dcfce7; color: #15803d; }
.btn-approve:hover { background: var(--success); color: white; }
.btn-reject { background: #fee2e2; color: #b91c1c; }
.btn-reject:hover { background: var(--danger); color: white; }
.btn-view { background: var(--blue-100); color: var(--blue-700); }
.btn-view:hover { background: var(--blue-600); color: white; }

/* ALERTS */
.alert { padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px; font-weight: 500; }
.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; }
.alert-error { background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; }
.alert-info { background: var(--blue-100); border: 1px solid var(--blue-200); color: var(--blue-800); }

/* EMPTY STATE */
.empty-state { text-align: center; padding: 60px 20px; }
.empty-state-icon { font-size: 56px; margin-bottom: 16px; opacity: 0.5; }
.empty-state-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: var(--blue-900); margin-bottom: 10px; }
.empty-state-desc { font-size: 15px; color: var(--gray-500); }

/* TOAST */
.toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 999; display: flex; flex-direction: column; gap: 10px; }
.toast { display: flex; align-items: center; gap: 12px; padding: 14px 20px; border-radius: var(--radius); font-size: 14px; font-weight: 500; box-shadow: var(--shadow-lg); min-width: 280px; transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1); }
.toast.show { transform: translateX(0); }
.toast-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.toast-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.toast-info { background: var(--blue-100); color: var(--blue-800); border: 1px solid var(--blue-200); }

/* SECTION */
.section { padding: 64px 24px; }
.section-inner { max-width: 1280px; margin: 0 auto; }
.section-eyebrow { display: inline-block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: var(--blue-500); margin-bottom: 12px; }
.section-title { font-family: 'Playfair Display', serif; font-size: clamp(26px, 4vw, 38px); font-weight: 700; color: var(--blue-900); margin-bottom: 14px; }
.section-desc { font-size: 16px; color: var(--gray-500); max-width: 540px; margin: 0 auto; line-height: 1.7; }

/* FOOTER */
.footer { background: var(--blue-900); color: rgba(255,255,255,0.6); padding: 32px 24px; text-align: center; font-size: 13px; margin-top: 60px; }
.footer strong { color: var(--blue-200); }

/* BLACKOUT CARD */
.blackout-card { background: linear-gradient(135deg, #fff7ed, #fef3c7); border: 1.5px solid #fde68a; border-radius: var(--radius); padding: 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
.btn-blackout-del { padding: 7px 14px; background: none; border: 1.5px solid var(--danger); color: var(--danger); border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: var(--transition); }
.btn-blackout-del:hover { background: var(--danger); color: white; }

/* REJECTION BOX */
.rejection-reason { background: #fff5f5; border: 1.5px solid #fecaca; border-radius: var(--radius-sm); padding: 14px 16px; margin-top: 12px; display: flex; align-items: flex-start; gap: 10px; }
.rejection-reason-text { font-size: 13px; color: #b91c1c; line-height: 1.5; }

/* SEARCH */
.search-input-wrap { position: relative; }
.search-input-wrap input { padding: 8px 14px 8px 38px; border-radius: var(--radius-sm); border: 1.5px solid var(--gray-200); font-size: 13px; width: 220px; outline: none; transition: var(--transition); font-family: 'DM Sans', sans-serif; }
.search-input-wrap input:focus { border-color: var(--blue-400); }
.search-input-wrap::before { content: '🔍'; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px; }

/* RESPONSIVE */
@media (max-width: 768px) {
  .nav-links, .nav-actions { display: none; }
  .hamburger { display: flex; }
  .form-row { grid-template-columns: 1fr; }
}
@keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.fade-in { animation: fadeIn 0.5s ease both; }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
@keyframes pulseDot { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
.pulse-dot { animation: pulseDot 2s infinite; }
</style>
@stack('styles')
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-brand">
      <div class="nav-brand-icon">🏢</div>
      <div class="nav-brand-text">
        <strong>Menara Wijaya</strong>
        <span>Sistem Reservasi Ruangan</span>
      </div>
    </a>

    <div class="nav-links">
      <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
      <a href="{{ route('agenda') }}" class="nav-link {{ request()->routeIs('agenda') ? 'active' : '' }}">Agenda Hari Ini</a>
      <a href="{{ route('kalender') }}" class="nav-link {{ request()->routeIs('kalender') ? 'active' : '' }}">Kalender & Ruangan</a>
      @if(session('role') === 'opd')
        <a href="{{ route('opd.bookings') }}" class="nav-link {{ request()->routeIs('opd.*') ? 'active' : '' }}">Booking Saya</a>
      @endif
      @if(session('role') === 'publik')
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard Admin</a>
      @endif
    </div>

    <div class="nav-actions">
      @if(!session('logged_in'))
        <a href="{{ route('login') }}" class="btn-nav-login">Login →</a>
      @else
        <div class="nav-user">
          <div class="nav-user-avatar" style="{{ session('role')==='publik' ? 'background:linear-gradient(135deg,#7c3aed,#a78bfa)' : '' }}">
            {{ strtoupper(substr(session('user_name', 'A'), 0, 1)) }}
          </div>
          <div>
            <div class="nav-user-name">{{ session('user_name') }}</div>
            <div class="nav-user-role" style="{{ session('role')==='publik' ? 'color:#7c3aed' : '' }}">
              {{ session('role') === 'publik' ? 'Admin Publik' : 'Admin OPD' }}
            </div>
          </div>
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
          </form>
        </div>
      @endif
    </div>

    <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
  <a href="{{ route('home') }}" class="nav-link">🏠 Beranda</a>
  <a href="{{ route('agenda') }}" class="nav-link">📅 Agenda Hari Ini</a>
  <a href="{{ route('kalender') }}" class="nav-link">🗓️ Kalender & Ruangan</a>

  {{-- ✅ TAMBAH MENU ROLE-BASED UNTUK MOBILE --}}
  @if(session('role') === 'opd')
    <a href="{{ route('opd.bookings') }}" class="nav-link">📄 Booking Saya</a>
  @endif

  @if(session('role') === 'publik')
    <a href="{{ route('admin.dashboard') }}" class="nav-link">🛡️ Dashboard Admin</a>
  @endif

  @if(!session('logged_in'))
    <a href="{{ route('login') }}" class="nav-link">🔐 Login</a>
  @else
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-link" style="width:100%;text-align:left;border:none;background:none;cursor:pointer">🚪 Keluar</button>
    </form>
  @endif
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<!-- MAIN CONTENT -->
<div class="main-content">
  @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded',()=>showToast('{{ addslashes(session('success')) }}','success'));</script>
  @endif
  @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded',()=>showToast('{{ addslashes(session('error')) }}','error'));</script>
  @endif

  @yield('content')
</div>

<script>
function toggleMobileMenu(){
  document.getElementById('mobileMenu').classList.toggle('open');
}

function showToast(msg, type='info'){
  const tc = document.getElementById('toastContainer');
  const t = document.createElement('div');
  const icons = {success:'✅', error:'❌', info:'ℹ️'};
  t.className = `toast toast-${type}`;
  t.innerHTML = `<span>${icons[type]||'ℹ️'}</span><span>${msg}</span>`;
  tc.appendChild(t);
  setTimeout(()=>t.classList.add('show'),10);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),400); }, 3500);
}
</script>
@stack('scripts')
</body>
</html>