<?php $__env->startSection('title', 'Detail Booking - Admin'); ?>
<?php $__env->startSection('content'); ?>

<?php
  use Carbon\Carbon;

  $kode = (string) $booking->kode;
  $status = (string) $booking->status;

  $tanggalFormatted = $booking->tanggal
    ? Carbon::parse($booking->tanggal)->translatedFormat('d F Y')
    : '—';

  $createdFormatted = $booking->created_at
    ? Carbon::parse($booking->created_at)->translatedFormat('d M Y H:i')
    : '—';

  $ruang = $booking->room;
?>

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-700));padding:20px 24px;color:white">
  <div style="max-width:760px;margin:0 auto;display:flex;align-items:center;gap:10px">
    <a href="<?php echo e(route('admin.bookings')); ?>" style="padding:7px 14px;border-radius:8px;border:1.5px solid rgba(255,255,255,0.25);color:rgba(255,255,255,0.85);font-size:13px;font-weight:600;text-decoration:none">‹ Kembali</a>
    <h1 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700">Verifikasi Booking</h1>
  </div>
</div>

<div style="max-width:760px;margin:0 auto;padding:24px 20px 48px">
  <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-lg);padding:32px;border:1px solid var(--blue-100)">

    <?php if(session('success')): ?>
      <div class="alert alert-success" style="margin-bottom:16px"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
      <div class="alert alert-error" style="margin-bottom:16px"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-size:12px;color:var(--gray-400);font-family:monospace;margin-bottom:6px"><?php echo e($kode); ?></div>
        <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--blue-900)"><?php echo e($booking->kegiatan); ?></h2>
      </div>
      <span class="badge badge-<?php echo e(strtolower($status)); ?>" style="font-size:13px;padding:6px 14px"><?php echo e($status); ?></span>
    </div>

    <div style="height:1px;background:var(--blue-100);margin-bottom:24px"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
      <?php $__currentLoopData = [
        ['label'=>'Ruangan','value'=> optional($ruang)->nama ?? '—'],
        ['label'=>'Kapasitas','value'=> (optional($ruang)->kapasitas ?? '—').' orang'],
        ['label'=>'Gedung','value'=> optional($ruang)->gedung ?? '—'],
        ['label'=>'Lantai','value'=> optional($ruang)->lantai ?? '—'],
        ['label'=>'Tanggal','value'=> $tanggalFormatted],
        ['label'=>'Sesi','value'=> strtoupper((string)$booking->sesi).' ('.$booking->jam_mulai.'–'.$booking->jam_selesai.')'],
        ['label'=>'OPD / Instansi','value'=> $booking->opd ?? '—'],
        ['label'=>'PIC','value'=> $booking->pj ?? '—'],
        ['label'=>'No. HP','value'=> $booking->telp ?? '—'],
        ['label'=>'Peserta','value'=> ($booking->peserta ?? '—').' orang'],
        ['label'=>'Diajukan','value'=> $createdFormatted],
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div>
          <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:4px"><?php echo e($item['label']); ?></div>
          <div style="font-size:15px;font-weight:600;color:var(--blue-900)"><?php echo e($item['value']); ?></div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($booking->catatan): ?>
      <div style="background:var(--blue-50);border-radius:var(--radius-sm);padding:14px;margin-bottom:24px">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--blue-500);margin-bottom:6px">CATATAN PEMOHON</div>
        <div style="font-size:14px;color:var(--gray-700)"><?php echo e($booking->catatan); ?></div>
      </div>
    <?php endif; ?>

    <?php if($status === 'DITOLAK' && $booking->rejection_reason): ?>
      <div class="rejection-reason" style="margin-bottom:24px">
        <span style="font-size:22px">❌</span>
        <div>
          <div style="font-size:11px;font-weight:700;color:#b91c1c;text-transform:uppercase;margin-bottom:4px">ALASAN PENOLAKAN</div>
          <span class="rejection-reason-text"><?php echo e($booking->rejection_reason); ?></span>
        </div>
      </div>
    <?php endif; ?>

    
    <?php if($status === 'MENUNGGU'): ?>
      <div style="height:1px;background:var(--blue-100);margin:24px 0"></div>
      <h3 style="font-family:'Playfair Display',serif;font-size:18px;color:var(--blue-900);margin-bottom:20px">⚖️ Verifikasi Pengajuan</h3>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <form method="POST" action="<?php echo e(route('admin.booking.approve', $kode)); ?>" onsubmit="return confirm('Setujui booking ini?')">
          <?php echo csrf_field(); ?>
          <button type="submit" style="width:100%;padding:14px;background:linear-gradient(135deg,var(--success),#059669);color:white;border:none;border-radius:var(--radius);font-size:15px;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(16,185,129,0.35)">✅ SETUJUI</button>
        </form>

        <button onclick="document.getElementById('rejectPanel').style.display='block';this.style.display='none'"
          style="width:100%;padding:14px;background:none;color:var(--danger);border:2px solid var(--danger);border-radius:var(--radius);font-size:15px;font-weight:700;cursor:pointer">❌ TOLAK</button>
      </div>

      <div id="rejectPanel" style="display:none;margin-top:16px">
        <form method="POST" action="<?php echo e(route('admin.booking.reject', $kode)); ?>">
          <?php echo csrf_field(); ?>
          <div class="form-group">
            <label class="form-label">Alasan Penolakan</label>
            <textarea class="form-textarea" name="alasan" placeholder="Tuliskan alasan penolakan secara jelas..." style="min-height:80px"></textarea>
            <div class="form-hint">Alasan ini akan ditampilkan kepada pemohon.</div>
          </div>
          <div style="display:flex;gap:10px">
            <button type="button" onclick="document.getElementById('rejectPanel').style.display='none'" class="btn-sm btn-sm-outline">Batal</button>
            <button type="submit" style="padding:10px 20px;background:var(--danger);color:white;border:none;border-radius:var(--radius-sm);font-size:14px;font-weight:700;cursor:pointer">Kirim Penolakan</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <div style="margin-top:24px">
      <a href="<?php echo e(route('admin.bookings')); ?>" class="btn-sm btn-sm-outline">← Kembali ke Daftar</a>
    </div>

  </div>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/booking-detail.blade.php ENDPATH**/ ?>