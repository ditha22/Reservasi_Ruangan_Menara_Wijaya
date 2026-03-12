@extends('layouts.app')
@section('title', 'Pengajuan Booking')
@section('content')

@php
  // ✅ Sesi konsisten dengan DB enum: pagi, siang, sore, full
  $sesiList = [
    ['id' => 'pagi',  'label' => 'Pagi',         'waktu' => '07:00–12:00', 'start' => '07:00', 'end' => '12:00', 'wrap' => false],
    ['id' => 'siang', 'label' => 'Siang',        'waktu' => '13:00–17:00', 'start' => '13:00', 'end' => '17:00', 'wrap' => false],
    ['id' => 'sore',  'label' => 'Sore',         'waktu' => '18:00–00:00', 'start' => '18:00', 'end' => '00:00', 'wrap' => true],
    ['id' => 'full',  'label' => '1 Hari Penuh', 'waktu' => '07:00–00:00', 'start' => '07:00', 'end' => '00:00', 'wrap' => true],
  ];

  $selectedSesiId = old('sesi', $selectedSesi ?? '');

  // default fallback
  $jamDefaultMulai = '07:00';
  $jamDefaultSelesai = '12:00';

  foreach ($sesiList as $s) {
    if ($s['id'] === $selectedSesiId) {
      $jamDefaultMulai = $s['start'];
      // kalau sesi end 00:00, defaultkan 23:59 agar masuk di hari yang sama (lebih aman untuk input time)
      $jamDefaultSelesai = $s['wrap'] ? '23:59' : $s['end'];
      break;
    }
  }
@endphp

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-700));padding:20px 24px 0;color:white">
  <div style="max-width:720px;margin:0 auto">
    <div style="display:flex;align-items:center;gap:10px;padding-bottom:16px">
      <a href="{{ route('kalender') }}"
         style="display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;border:1.5px solid rgba(255,255,255,0.25);color:rgba(255,255,255,0.85);font-size:13px;font-weight:600;text-decoration:none">
        ‹ Kembali
      </a>
      <h1 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700">Pengajuan Pinjam Ruangan</h1>
    </div>
  </div>
</div>

<div style="max-width:720px;margin:0 auto;padding:24px 20px 48px">

  @if($errors->any())
    <div class="alert alert-error">
      @foreach($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('opd.booking.store') }}">
    @csrf

    {{-- ✅ Tambahan: field kompatibel untuk backend (tanpa menghapus ruang_id) --}}
    <input type="hidden" name="room_id" id="room_id_hidden" value="{{ old('room_id', old('ruang_id', $selectedRoom?->id)) }}">

    {{-- RUANGAN TERPILIH (jika datang dari kalender) --}}
    @if($selectedRoom)
      <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--blue-100);padding:18px;margin-bottom:20px;display:flex;align-items:center;gap:16px">
        <div style="width:64px;height:64px;background:linear-gradient(135deg,var(--blue-700),var(--blue-500));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:30px;flex-shrink:0">
          🏛️
        </div>
        <div>
          <div style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--blue-500);margin-bottom:4px">RUANGAN TERPILIH</div>
          <div style="font-size:17px;font-weight:800;color:var(--blue-900)">{{ $selectedRoom->nama }}</div>
          <div style="font-size:12px;color:var(--gray-500)">{{ $selectedRoom->gedung }} · Kapasitas {{ $selectedRoom->kapasitas }} orang</div>
          <div style="font-size:12px;color:var(--gray-400)">{{ $selectedRoom->lantai }}</div>
        </div>
      </div>

      <input type="hidden" name="ruang_id" id="ruang_id_hidden" value="{{ $selectedRoom->id }}">
    @endif

    {{-- DETAIL KEGIATAN --}}
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--blue-100);overflow:hidden;margin-bottom:16px">
      <div style="padding:14px 18px;background:var(--blue-50);border-bottom:1px solid var(--blue-100)">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--blue-600)">DETAIL KEGIATAN</div>
      </div>

      <div style="padding:18px">
        {{-- Jika belum ada selectedRoom, pilih manual dari dropdown --}}
        @if(!$selectedRoom)
          <div class="form-group">
            <label class="form-label">Ruangan <span style="color:var(--danger)">*</span></label>
            <select class="form-select" name="ruang_id" id="ruang_id_select" required>
              <option value="">-- Pilih Ruangan --</option>
              @foreach($ruangan as $r)
                <option value="{{ $r->id }}" {{ old('ruang_id') == $r->id ? 'selected' : '' }}>
                  {{ $r->nama }} (Kap. {{ $r->kapasitas }})
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <div class="form-group">
          <label class="form-label">Nama Kegiatan <span style="color:var(--danger)">*</span></label>
          <input class="form-input" name="kegiatan" type="text" placeholder="Contoh: Rapat Koordinasi OPD" value="{{ old('kegiatan') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Peminjaman <span style="color:var(--danger)">*</span></label>
          <input class="form-input" name="tanggal" type="date"
                 value="{{ old('tanggal', $selectedDate ?? date('Y-m-d')) }}"
                 min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Sesi <span style="color:var(--danger)">*</span></label>
          <select class="form-select" name="sesi" id="sesi" required>
            <option value="">-- Pilih Sesi --</option>
            @foreach($sesiList as $s)
              <option
                value="{{ $s['id'] }}"
                data-start="{{ $s['start'] }}"
                data-end="{{ $s['end'] }}"
                data-wrap="{{ $s['wrap'] ? '1' : '0' }}"
                {{ old('sesi', $selectedSesi ?? '') === $s['id'] ? 'selected' : '' }}
              >
                {{ $s['label'] }} ({{ $s['waktu'] }})
              </option>
            @endforeach
          </select>

          <div id="sesiHint" class="form-hint" style="margin-top:6px;color:var(--gray-500)">
            Pilih sesi untuk mengunci batas jam.
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Jam Mulai <span style="color:var(--danger)">*</span></label>
            <input
              class="form-input"
              id="jam_mulai"
              name="jam_mulai"
              type="time"
              value="{{ old('jam_mulai', $jamDefaultMulai) }}"
              required
            >
            <div id="mulaiWarn" class="form-hint" style="display:none;margin-top:6px;color:var(--danger)">
              Jam mulai harus berada di dalam sesi.
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Jam Selesai <span style="color:var(--danger)">*</span></label>
            <input
              class="form-input"
              id="jam_selesai"
              name="jam_selesai"
              type="time"
              value="{{ old('jam_selesai', $jamDefaultSelesai) }}"
              required
            >
            <div id="selesaiWarn" class="form-hint" style="display:none;margin-top:6px;color:var(--danger)">
              Jam selesai harus berada di dalam sesi dan tidak boleh lebih kecil dari jam mulai.
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- KONTAK & LOGISTIK --}}
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--blue-100);overflow:hidden;margin-bottom:16px">
      <div style="padding:14px 18px;background:var(--blue-50);border-bottom:1px solid var(--blue-100)">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--blue-600)">KONTAK & LOGISTIK</div>
      </div>

      <div style="padding:18px">
        {{-- ✅ OPD Dropdown (HANYA NAMA, TANPA LANTAI) --}}
        <div class="form-group">
          <label class="form-label">Nama OPD / Instansi <span style="color:var(--danger)">*</span></label>
          <select class="form-select" name="opd_id" required>
            <option value="">-- Pilih OPD / Instansi --</option>

            @foreach($opds as $o)
              <option value="{{ $o->id }}" {{ old('opd_id') == $o->id ? 'selected' : '' }}>
                {{ $o->nama }}
              </option>
            @endforeach
          </select>

          <div class="form-hint" style="margin-top:6px;color:var(--gray-500)">
            Pilih OPD/Instansi dari daftar.
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama PIC <span style="color:var(--danger)">*</span></label>
            <input class="form-input" name="pj" type="text" placeholder="Nama Lengkap" value="{{ old('pj') }}" required>
          </div>

          <div class="form-group">
            <label class="form-label">No HP / WA <span style="color:var(--danger)">*</span></label>
            <input class="form-input" name="telp" type="tel" placeholder="0812..." value="{{ old('telp') }}" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Peserta <span style="color:var(--danger)">*</span></label>
          <input class="form-input" name="peserta" type="number" placeholder="0" min="1" value="{{ old('peserta') }}" required>
        </div>
      </div>
    </div>

    {{-- CATATAN --}}
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--blue-100);overflow:hidden;margin-bottom:20px">
      <div style="padding:14px 18px;background:var(--blue-50);border-bottom:1px solid var(--blue-100)">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--blue-600)">INFORMASI TAMBAHAN</div>
      </div>
      <div style="padding:18px">
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">Catatan Khusus</label>
          <textarea class="form-textarea" name="catatan" placeholder="Contoh: Membutuhkan 2 unit proyektor...">{{ old('catatan') }}</textarea>
        </div>
      </div>
    </div>

    <div style="background:var(--blue-50);border:1px solid var(--blue-200);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:24px;display:flex;gap:10px;align-items:flex-start">
      <span style="font-size:18px;flex-shrink:0">ℹ️</span>
      <p style="font-size:13px;color:var(--blue-700);line-height:1.6">
        Pengajuan akan ditinjau oleh Admin maksimal dalam 1x24 jam. Mohon pastikan data yang diisi sudah sesuai.
      </p>
    </div>

    <button type="submit" class="btn-submit">▶ Kirim Pengajuan</button>
  </form>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>

{{-- ✅ SCRIPT: lock jam berdasarkan sesi (support sesi sampai 00:00) --}}
<script>
(function () {
  const sesi = document.getElementById('sesi');
  const mulai = document.getElementById('jam_mulai');
  const selesai = document.getElementById('jam_selesai');

  const sesiHint = document.getElementById('sesiHint');
  const mulaiWarn = document.getElementById('mulaiWarn');
  const selesaiWarn = document.getElementById('selesaiWarn');

  // ✅ Sinkron ruang_id -> room_id (tanpa menghapus ruang_id)
  const roomHidden = document.getElementById('room_id_hidden');
  const ruangHidden = document.getElementById('ruang_id_hidden');
  const ruangSelect = document.getElementById('ruang_id_select');

  function syncRoomId(val) {
    if (roomHidden) roomHidden.value = val || '';
  }

  if (ruangHidden && ruangHidden.value) {
    syncRoomId(ruangHidden.value);
  }

  if (ruangSelect) {
    syncRoomId(ruangSelect.value);
    ruangSelect.addEventListener('change', function () {
      syncRoomId(this.value);
    });
  }

  function toMinutes(t, wrap) {
    if (!t) return null;
    const parts = t.split(':');
    if (parts.length !== 2) return null;
    const h = parseInt(parts[0], 10);
    const m = parseInt(parts[1], 10);
    if (Number.isNaN(h) || Number.isNaN(m)) return null;

    // kalau sesi "wrap" (berakhir 00:00), maka 00:00 kita anggap 24:00 (1440 menit)
    if (wrap && h === 0 && m === 0) return 24 * 60;

    return (h * 60) + m;
  }

  function showTemp(el) {
    if (!el) return;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 2500);
  }

  function applySesiLimits() {
    const opt = sesi.options[sesi.selectedIndex];
    const start = opt && opt.dataset ? opt.dataset.start : null;
    const end   = opt && opt.dataset ? opt.dataset.end : null;
    const wrap  = opt && opt.dataset ? (opt.dataset.wrap === '1') : false;

    if (!start || !end) {
      // reset saja
      mulai.removeAttribute('min');
      mulai.removeAttribute('max');
      selesai.removeAttribute('min');
      selesai.removeAttribute('max');
      if (sesiHint) sesiHint.textContent = 'Pilih sesi untuk mengunci batas jam.';
      return;
    }

    // Untuk sesi normal, pakai min/max HTML
    if (!wrap) {
      mulai.min = start;
      mulai.max = end;
      selesai.min = start;
      selesai.max = end;
    } else {
      // Untuk sesi yang berakhir 00:00: min masih bisa, max dihapus supaya user masih bisa ketik 00:00
      mulai.min = start;
      mulai.removeAttribute('max');
      selesai.min = start;
      selesai.removeAttribute('max');
    }

    if (sesiHint) {
      const labelEnd = end; // tetap tampilkan 00:00 sesuai sesi
      sesiHint.textContent = `Batas sesi: ${start} – ${labelEnd}. Jam mulai & selesai harus berada dalam rentang ini.`;
    }
  }

  function validateTimes() {
    const opt = sesi.options[sesi.selectedIndex];
    const start = opt && opt.dataset ? opt.dataset.start : null;
    const end   = opt && opt.dataset ? opt.dataset.end : null;
    const wrap  = opt && opt.dataset ? (opt.dataset.wrap === '1') : false;

    if (mulaiWarn) mulaiWarn.style.display = 'none';
    if (selesaiWarn) selesaiWarn.style.display = 'none';
    if (!start || !end) return;

    const startMin = toMinutes(start, wrap);
    const endMin   = toMinutes(end, wrap); // kalau end=00:00 & wrap => 1440

    let mMulai = toMinutes(mulai.value, wrap);
    let mSelesai = toMinutes(selesai.value, wrap);

    let changed = false;

    // validasi jam mulai
    if (mMulai === null || mMulai < startMin || mMulai > endMin) {
      showTemp(mulaiWarn);
      mulai.value = start;
      mMulai = toMinutes(mulai.value, wrap);
      changed = true;
    }

    // validasi jam selesai
    if (mSelesai === null || mSelesai < startMin || mSelesai > endMin) {
      showTemp(selesaiWarn);
      // defaultkan ke end: kalau wrap dan end=00:00, isi 23:59 biar masuk hari yang sama
      selesai.value = (wrap && end === '00:00') ? '23:59' : end;
      mSelesai = toMinutes(selesai.value, wrap);
      changed = true;
    }

    // selesai >= mulai
    if (mSelesai !== null && mMulai !== null && mSelesai < mMulai) {
      showTemp(selesaiWarn);
      selesai.value = mulai.value;
      changed = true;
    }

    return !changed;
  }

  sesi.addEventListener('change', function () {
    applySesiLimits();

    // auto set default sesuai sesi
    const opt = sesi.options[sesi.selectedIndex];
    const start = opt && opt.dataset ? opt.dataset.start : null;
    const end   = opt && opt.dataset ? opt.dataset.end : null;
    const wrap  = opt && opt.dataset ? (opt.dataset.wrap === '1') : false;

    if (start && end) {
      mulai.value = start;
      selesai.value = (wrap && end === '00:00') ? '23:59' : end;
    }

    validateTimes();
  });

  mulai.addEventListener('change', validateTimes);
  selesai.addEventListener('change', validateTimes);
  mulai.addEventListener('input', validateTimes);
  selesai.addEventListener('input', validateTimes);

  // init
  applySesiLimits();
  validateTimes();
})();
</script>

@endsection