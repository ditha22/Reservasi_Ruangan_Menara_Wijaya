@extends('layouts.app')
@section('title', 'Login')

@push('styles')
<style>

.login-wrapper {
  min-height: calc(100vh - 68px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 24px;
  background: linear-gradient(135deg, var(--blue-50) 0%, var(--blue-100) 100%);
}

.login-container {
  width: 100%;
  max-width: 440px;
  background: var(--white);
  border-radius: 20px;
  padding: 48px 40px;
  box-shadow: var(--shadow-xl);
  border: 1px solid var(--blue-100);
}

.login-logo-icon {
  width: 64px;
  height: 64px;
  background: linear-gradient(135deg, var(--blue-700), var(--blue-400));
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  margin: 0 auto 16px;
  box-shadow: 0 8px 30px rgba(30,107,196,0.35);
}

.login-title {
  font-family: 'Playfair Display', serif;
  font-size: 24px;
  font-weight: 700;
  color: var(--blue-900);
}

.login-subtitle {
  font-size: 14px;
  color: var(--gray-500);
  margin-top: 6px;
  margin-bottom: 28px;
}

.login-role-toggle {
  display: flex;
  background: var(--gray-100);
  border-radius: 10px;
  padding: 4px;
  margin-bottom: 28px;
}

.role-tab {
  flex: 1;
  padding: 9px 14px;
  border-radius: 8px;
  text-align: center;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  color: var(--gray-500);
  border: none;
  background: none;
}

.role-tab.active {
  background: var(--white);
  color: var(--blue-700);
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.login-demo-hint {
  margin-top: 20px;
  padding: 14px 16px;
  background: var(--blue-50);
  border-radius: var(--radius-sm);
  border: 1px solid var(--blue-100);
}

.login-demo-hint p {
  font-size: 12px;
  color: var(--blue-600);
  font-weight: 500;
}

.login-demo-hint span {
  color: var(--blue-800);
  font-weight: 700;
}

@media(max-width:768px){
  .login-container {
    padding: 36px 24px;
  }
}

/* DEMO BOX */

.demo-mini {
  margin-top: 20px;
  padding: 12px 14px;
  background: var(--blue-50);
  border-radius: var(--radius-sm);
  border: 1px solid var(--blue-100);
}

.demo-mini .row {
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}

.demo-mini p {
  font-size: 12px;
  color: var(--blue-600);
  font-weight: 500;
}

.demo-mini span {
  color: var(--blue-800);
  font-weight: 700;
}

.demo-toggle {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 12px;
  font-weight: 700;
  color: var(--blue-600);
  padding: 6px 8px;
  border-radius: 8px;
  transition: var(--transition);
}

.demo-toggle:hover {
  background: rgba(30,107,196,0.10);
}

.demo-box {
  margin-top: 10px;
  display:none;
  background: #fff;
  border: 1px solid var(--blue-100);
  border-radius: 12px;
  padding: 10px 12px;
}

.demo-box.open {
  display:block;
}

.demo-list {
  margin-top: 6px;
  display:flex;
  flex-direction:column;
  gap:6px;
  max-height:120px;
  overflow:auto;
  padding-right:6px;
}

.demo-item {
  display:flex;
  justify-content:space-between;
  gap:10px;
  align-items:flex-start;
  flex-wrap:wrap;
}

.demo-mono {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
  font-size: 12px;
}

.demo-name {
  color: var(--gray-500);
  font-size: 11px;
}

.demo-note {
  margin-top: 8px;
  font-size: 11px;
  color: var(--gray-500);
  line-height: 1.4;
}

</style>
@endpush

@section('content')

@php
  $demoPublik = \App\Models\User::where('role','publik')->orderBy('id')->first(['username','name']);
  $demoOpdUsers = \App\Models\User::where('role','opd')->orderBy('username')->limit(12)->get(['username','name']);
  $demoOpdFirst = \App\Models\User::where('role','opd')->orderBy('username')->first(['username','name']);

  $demoPublikUsername = $demoPublik?->username ?? 'adminpublik';
  $demoOpdUsername = $demoOpdFirst?->username ?? 'ppid';
@endphp

<div class="login-wrapper">

  <div class="login-container fade-in">

    <div style="text-align:center;margin-bottom:32px">
      <div class="login-logo-icon">🏢</div>
      <h2 class="login-title">Masuk ke Sistem</h2>
      <p class="login-subtitle">Reservasi Ruangan Menara Wijaya</p>
    </div>

    @if($errors->has('login'))
      <div class="alert alert-error">
        {{ $errors->first('login') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
      @csrf

      <input type="hidden" name="role" id="roleInput" value="opd">

      <div class="login-role-toggle">
        <button type="button" class="role-tab active" id="tabOpd" onclick="setRole('opd')">
          👤 Admin OPD
        </button>

        <button type="button" class="role-tab" id="tabPublik" onclick="setRole('publik')">
          🔑 Admin Publik
        </button>
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input
          class="form-input"
          name="username"
          type="text"
          placeholder="Masukkan username"
          value="{{ old('username') }}"
          autocomplete="off"
        >
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>

        <div style="position:relative">
          <input
            class="form-input"
            name="password"
            id="pwInput"
            type="password"
            placeholder="••••••••"
            style="padding-right:44px"
          >

          <button
            type="button"
            onclick="togglePw()"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;color:var(--gray-400)"
          >
            👁
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        Masuk →
      </button>

    </form>

    <!-- DEMO OPD -->

    <div class="demo-mini" id="hintOpd">

      <div class="row">
        <p style="margin:0">
          Demo Admin OPD:
          username <span>{{ $demoOpdUsername }}</span>
          / password <span>Opd12345</span>
        </p>

        <button class="demo-toggle" type="button" onclick="toggleDemo('demoOpdBox')">
          Lihat
        </button>
      </div>

      <div class="demo-box" id="demoOpdBox">

        <div style="font-size:12px;font-weight:800;color:var(--blue-900);margin-bottom:6px">
          Akun Demo OPD (pilih salah satu)
        </div>

        <div class="demo-list">

          @forelse($demoOpdUsers as $u)

            <div class="demo-item">
              <span class="demo-mono">
                {{ $u->username }} / Opd12345
              </span>

              <span class="demo-name">
                {{ $u->name }}
              </span>
            </div>

          @empty

            <div style="font-size:12px;color:var(--gray-500)">
              Belum ada akun OPD.
              Jalankan
              <span class="demo-mono">
                php artisan migrate:fresh --seed
              </span>.
            </div>

          @endforelse

        </div>

        <div class="demo-note">
          Username OPD dibuat otomatis dari nama OPD.
          Password default:
          <span class="demo-mono">Opd12345</span>
        </div>

      </div>

    </div>

    <!-- DEMO PUBLIK -->

    <div class="demo-mini" id="hintPublik" style="display:none">

      <div class="row">
        <p style="margin:0">
          Demo Admin Publik:
          username <span>{{ $demoPublikUsername }}</span>
          / password <span>AdminPublik123</span>
        </p>

        <button class="demo-toggle" type="button" onclick="toggleDemo('demoPublikBox')">
          Lihat
        </button>
      </div>

      <div class="demo-box" id="demoPublikBox">

        <div style="font-size:12px;font-weight:800;color:var(--blue-900);margin-bottom:6px">
          Akun Demo Admin Publik
        </div>

        <div class="demo-item">
          <span class="demo-mono">
            {{ $demoPublikUsername }} / AdminPublik123
          </span>

          <span class="demo-name">
            {{ $demoPublik?->name ?? 'Admin Publik' }}
          </span>
        </div>

        <div class="demo-note">
          Akun ini dibuat dari seeder
          <span class="demo-mono">UserSeeder</span>.
        </div>

      </div>

    </div>

    <p style="text-align:center;margin-top:16px;font-size:13px;color:var(--gray-500)">
      Belum punya akun?
      <span
        style="color:var(--blue-500);cursor:pointer;font-weight:600"
        onclick="showToast('Hubungi administrator sistem untuk pendaftaran akun.','info')"
      >
        Hubungi Admin
      </span>
    </p>

  </div>

</div>

@push('scripts')

<script>

function setRole(role)
{
  document.getElementById('roleInput').value = role;

  document.getElementById('tabOpd')
    .classList.toggle('active', role === 'opd');

  document.getElementById('tabPublik')
    .classList.toggle('active', role === 'publik');

  document.getElementById('hintOpd')
    .style.display = role === 'opd' ? 'block' : 'none';

  document.getElementById('hintPublik')
    .style.display = role === 'publik' ? 'block' : 'none';
}

function togglePw()
{
  const pw = document.getElementById('pwInput');

  pw.type = pw.type === 'password'
    ? 'text'
    : 'password';
}

document.getElementById('pwInput')
.addEventListener('keydown', function(e)
{
  if(e.key === 'Enter')
  {
    document.getElementById('loginForm').submit();
  }
});

function toggleDemo(id)
{
  const el = document.getElementById(id);
  el.classList.toggle('open');
}

</script>

@endpush

@endsection