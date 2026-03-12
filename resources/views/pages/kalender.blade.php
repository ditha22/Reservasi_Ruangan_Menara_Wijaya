@extends('layouts.app')
@section('title', 'Kalender Reservasi')
@push('styles')
<style>
.calendar-container { background: var(--white); border-radius: 16px; padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--blue-100); }
.calendar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.calendar-nav { display: flex; align-items: center; gap: 10px; }
.btn-cal-nav { width: 34px; height: 34px; border-radius: 50%; border: 1.5px solid var(--blue-200); background: none; cursor: pointer; font-size: 18px; color: var(--blue-600); display: flex; align-items: center; justify-content: center; transition: var(--transition); line-height: 1; }
.btn-cal-nav:hover { background: var(--blue-100); }
.calendar-month { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: var(--blue-900); }
.calendar-legend { display: flex; gap: 12px; flex-wrap: wrap; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--gray-600); }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }
.calendar-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); margin-bottom: 4px; border-bottom: 1px solid var(--blue-100); padding-bottom: 8px; }
.calendar-weekday { text-align: center; font-size: 11px; font-weight: 700; color: var(--blue-500); text-transform: uppercase; letter-spacing: 0.06em; padding: 4px 0; }
.calendar-weekday:first-child { color: var(--danger); }
.calendar-weekday:last-child { color: var(--blue-400); }
.calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); }
.calendar-day { display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding: 6px 2px 8px; min-height: 52px; cursor: pointer; transition: var(--transition); position: relative; font-size: 14px; font-weight: 500; color: var(--gray-700); border: 1px solid transparent; border-radius: 0; }
.calendar-day:hover { background: var(--blue-50); }
.calendar-day-num { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 500; transition: var(--transition); margin-bottom: 3px; }
.calendar-day:hover .calendar-day-num { background: var(--blue-100); color: var(--blue-700); }
.calendar-day.today .calendar-day-num { background: var(--blue-600); color: white; font-weight: 700; box-shadow: 0 2px 8px rgba(30,107,196,0.4); }
.calendar-day.selected .calendar-day-num { background: var(--blue-500); color: white; font-weight: 700; }
.calendar-day.selected { background: var(--blue-50); }
.calendar-day.empty { cursor: default; }
.calendar-day.empty:hover { background: none; }
.calendar-day.sunday .calendar-day-num { color: var(--danger); }
.calendar-day.saturday .calendar-day-num { color: var(--blue-400); }
.calendar-day.today.sunday .calendar-day-num,
.calendar-day.today.saturday .calendar-day-num,
.calendar-day.selected.sunday .calendar-day-num,
.calendar-day.selected.saturday .calendar-day-num { color: white; }
.calendar-day-dots { display: flex; gap: 3px; justify-content: center; align-items: center; min-height: 8px; }
.calendar-dot { width: 6px; height: 6px; border-radius: 50%; }
.calendar-dot.available { background: var(--success); }
.calendar-dot.full { background: var(--danger); }

.room-card { background: var(--white); border-radius: var(--radius); border: 1.5px solid var(--blue-100); overflow: hidden; transition: var(--transition); }
.room-card:hover { box-shadow: var(--shadow); border-color: var(--blue-300); }
.room-card-img { height: 80px; background: linear-gradient(135deg, var(--blue-700), var(--blue-500)); display: flex; align-items: center; justify-content: center; font-size: 32px; }
.room-card-body { padding: 16px; }
.room-card-name { font-size: 14px; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; }
.room-card-cap { font-size: 12px; color: var(--gray-500); margin-bottom: 12px; }
.session-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.session-btn { padding: 8px 10px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; border: 1.5px solid; cursor: pointer; transition: var(--transition); text-align: center; text-decoration: none; display: block; }
.session-btn.available { border-color: var(--success); color: var(--success); background: #f0fdf4; }
.session-btn.available:hover { background: var(--success); color: white; }
.session-btn.booked { border-color: var(--gray-300); color: var(--gray-400); background: var(--gray-50); cursor: not-allowed; }

#loadingSlots { display: none; text-align: center; padding: 40px; }
.spinner { width: 40px; height: 40px; border: 3px solid var(--blue-200); border-top-color: var(--blue-600); border-radius: 50%; animation: spin 0.7s linear infinite; margin: 0 auto; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush
@section('content')

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-700));padding:40px 24px;color:white">
  <div style="max-width:1280px;margin:0 auto">
    <div style="font-size:12px;opacity:0.6;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em">🗓️ Kalender</div>
    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);font-weight:700;margin-bottom:8px">Kalender Reservasi</h1>
    <p style="font-size:15px;opacity:0.7">Pilih tanggal untuk melihat ketersediaan ruangan dan sesi</p>
  </div>
</div>

<div style="max-width:1280px;margin:0 auto;padding:32px 24px">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start" id="kalenderGrid">

    <!-- CALENDAR -->
    <div class="calendar-container">
      <div class="calendar-header">
        <div class="calendar-nav">
          <button class="btn-cal-nav" id="prevMonth">‹</button>
          <span class="calendar-month" id="calendarMonth"></span>
          <button class="btn-cal-nav" id="nextMonth">›</button>
        </div>
        <div class="calendar-legend">
          <div class="legend-item"><div class="legend-dot" style="background:var(--success)"></div>Tersedia</div>
          <div class="legend-item"><div class="legend-dot" style="background:var(--danger)"></div>Penuh</div>
        </div>
      </div>
      <div>
        <div class="calendar-weekdays">
          @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $day)
            <div class="calendar-weekday">{{ $day }}</div>
          @endforeach
        </div>
        <div class="calendar-days" id="calendarDays"></div>
      </div>
    </div>

    <!-- ROOM SLOTS PANEL -->
    <div id="roomSlotsPanel">
      <div class="empty-state" style="background:var(--blue-50);border-radius:var(--radius);padding:48px 20px;border:1px dashed var(--blue-200)">
        <div class="empty-state-icon" style="opacity:1;font-size:48px">👈</div>
        <div class="empty-state-title" style="font-size:18px">Pilih Tanggal</div>
        <div class="empty-state-desc">Klik tanggal pada kalender untuk melihat ketersediaan ruangan</div>
      </div>
    </div>

  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const isLoggedIn = {{ session('logged_in') ? 'true' : 'false' }};
const userRole = '{{ session('role', '') }}';

let calYear = new Date().getFullYear();
let calMonth = new Date().getMonth();
let selectedDate = null;

const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function renderCalendar(){
  const monthEl = document.getElementById('calendarMonth');
  const daysEl = document.getElementById('calendarDays');
  monthEl.textContent = monthNames[calMonth] + ' ' + calYear;
  daysEl.innerHTML = '';

  const firstDay = new Date(calYear, calMonth, 1).getDay();
  const daysInMonth = new Date(calYear, calMonth+1, 0).getDate();
  const today = new Date();
  const todayStr = today.getFullYear()+'-'+(String(today.getMonth()+1).padStart(2,'0'))+'-'+(String(today.getDate()).padStart(2,'0'));

  // Empty cells
  for(let i=0;i<firstDay;i++){
    daysEl.innerHTML += '<div class="calendar-day empty"></div>';
  }

  for(let d=1;d<=daysInMonth;d++){
    const dateStr = calYear+'-'+String(calMonth+1).padStart(2,'0')+'-'+String(d).padStart(2,'0');
    const dow = new Date(calYear, calMonth, d).getDay();
    let cls = 'calendar-day';
    if(dateStr === todayStr) cls += ' today';
    if(dateStr === selectedDate) cls += ' selected';
    if(dow === 0) cls += ' sunday';
    if(dow === 6) cls += ' saturday';
    daysEl.innerHTML += `<div class="${cls}" onclick="selectDate('${dateStr}')">
      <div class="calendar-day-num">${d}</div>
      <div class="calendar-day-dots"><div class="calendar-dot available"></div></div>
    </div>`;
  }
}

function selectDate(date){
  selectedDate = date;
  renderCalendar();
  loadRoomSlots(date);
}

async function loadRoomSlots(date){
  const panel = document.getElementById('roomSlotsPanel');
  panel.innerHTML = '<div style="text-align:center;padding:40px"><div class="spinner"></div><p style="margin-top:16px;color:var(--gray-500)">Memuat ketersediaan ruangan...</p></div>';

  try {
    const resp = await fetch(`/kalender/ruangan/${date}`);
    const data = await resp.json();

    let html = `<div style="font-family:'Playfair Display',serif;font-size:18px;font-weight:600;color:var(--blue-900);margin-bottom:20px">📅 ${data.dateFormatted}</div>`;
    html += '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px">';

    data.slots.forEach(room => {
      let sesiBtns = '';
      room.sesi_status.forEach(s => {
        if(s.booked){
          sesiBtns += `<div class="session-btn booked">🔒 ${s.label}<br><small>${s.waktu}</small></div>`;
        } else {
          if(isLoggedIn && userRole === 'opd'){
            sesiBtns += `<a href="/opd/booking/buat?ruang_id=${room.id}&sesi=${s.id}&tanggal=${date}" class="session-btn available">✅ ${s.label}<br><small>${s.waktu}</small></a>`;
          } else {
            sesiBtns += `<div class="session-btn available" onclick="handleBookBtn()">✅ ${s.label}<br><small>${s.waktu}</small></div>`;
          }
        }
      });

      html += `<div class="room-card">
        <div class="room-card-img">${room.icon}</div>
        <div class="room-card-body">
          <div class="room-card-name">${room.nama}</div>
          <div class="room-card-cap">Kapasitas ${room.kapasitas} orang · ${room.lantai}</div>
          <div class="session-grid">${sesiBtns}</div>
        </div>
      </div>`;
    });

    html += '</div>';

    if(!isLoggedIn){
      html += `<div style="margin-top:20px;background:var(--blue-50);border:1px solid var(--blue-200);border-radius:var(--radius-sm);padding:16px;text-align:center">
        <p style="font-size:14px;color:var(--blue-700);margin-bottom:10px">Login sebagai Admin OPD untuk melakukan booking</p>
        <a href="/login" style="padding:10px 24px;background:var(--blue-600);color:white;border-radius:var(--radius-sm);text-decoration:none;font-size:14px;font-weight:600">Login Sekarang →</a>
      </div>`;
    }

    panel.innerHTML = html;
  } catch(e) {
    panel.innerHTML = '<div class="alert alert-error">Gagal memuat data. Silakan coba lagi.</div>';
  }
}

function handleBookBtn(){
  showToast('Silakan login sebagai Admin OPD untuk melakukan booking.','info');
}

document.getElementById('prevMonth').addEventListener('click', ()=>{ calMonth--; if(calMonth<0){calMonth=11;calYear--;} renderCalendar(); });
document.getElementById('nextMonth').addEventListener('click', ()=>{ calMonth++; if(calMonth>11){calMonth=0;calYear++;} renderCalendar(); });

renderCalendar();

// Auto select today
const today = new Date();
const todayStr = today.getFullYear()+'-'+String(today.getMonth()+1).padStart(2,'0')+'-'+String(today.getDate()).padStart(2,'0');
selectDate(todayStr);

// Responsive
const style = document.createElement('style');
style.textContent = '@media(max-width:900px){#kalenderGrid{grid-template-columns:1fr!important;}}';
document.head.appendChild(style);
</script>
@endpush
@endsection
