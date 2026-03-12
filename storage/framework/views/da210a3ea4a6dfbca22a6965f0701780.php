<?php $__env->startSection('title', 'Booking Saya'); ?>
<?php $__env->startSection('content'); ?>

<?php
  use Carbon\Carbon;
?>


<div style="max-width:1280px;margin:0 auto;padding:18px 24px 0">
  <a href="<?php echo e(route('admin.dashboard')); ?>" class="filter-btn" style="display:inline-flex;gap:8px;align-items:center">
    ← Kembali ke Dashboard
  </a>
</div>

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-600));padding:40px 24px;color:white">
  <div style="max-width:1280px;margin:0 auto;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px">
    <div>
      <div style="font-size:12px;opacity:0.6;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em">📋 Riwayat</div>
      <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,36px);font-weight:700;margin-bottom:8px">Booking Saya</h1>
      <p style="font-size:15px;opacity:0.7">Pantau status pengajuan peminjaman ruangan Anda</p>
    </div>

    
    
  </div>
</div>

<div style="max-width:1280px;margin:0 auto;padding:32px 24px">

  
  <?php if(session('success')): ?>
    <div class="alert alert-success" style="margin-bottom:16px"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if(session('error')): ?>
    <div class="alert alert-error" style="margin-bottom:16px"><?php echo e(session('error')); ?></div>
  <?php endif; ?>

  <!-- Filters -->
  <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap">
    <?php $__currentLoopData = ['semua'=>'Semua','MENUNGGU'=>'⏳ Menunggu','DISETUJUI'=>'✅ Disetujui','DITOLAK'=>'❌ Ditolak','DIBATALKAN'=>'🚫 Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="?filter=<?php echo e($key); ?>" class="filter-btn <?php echo e(($filter ?? 'semua') === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <?php if(count($bookings) > 0): ?>
    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $isArray = is_array($b);

        $kode = $isArray ? ($b['kode'] ?? null) : ($b->kode ?? null);
        $kegiatan = $isArray ? ($b['kegiatan'] ?? '-') : ($b->kegiatan ?? '-');
        $status = strtoupper($isArray ? ($b['status'] ?? '') : ($b->status ?? ''));

        $tanggalRaw = $isArray ? ($b['tanggal'] ?? null) : ($b->tanggal ?? null);

        $tanggalFormatted = $isArray
          ? ($b['tanggal_formatted'] ?? \App\Services\DataService::formatDate($tanggalRaw ?? ''))
          : (\App\Services\DataService::formatDate($tanggalRaw ?? ''));

        $jamMulai = $isArray ? ($b['jam_mulai'] ?? '-') : ($b->jam_mulai ?? '-');
        $jamSelesai = $isArray ? ($b['jam_selesai'] ?? '-') : ($b->jam_selesai ?? '-');
        $peserta = $isArray ? ($b['peserta'] ?? '-') : ($b->peserta ?? '-');

        $rejectionReason = $isArray ? ($b['rejection_reason'] ?? '') : ($b->rejection_reason ?? '');

        $ruangNama = '—';
        $ruangIcon = '📋';
        if ($isArray) {
          $ruangNama = $b['ruangan']['nama'] ?? '—';
          $ruangIcon = $b['ruangan']['icon'] ?? '📋';
        } else {
          $ruangNama = optional($b->room)->nama ?? '—';
          $ruangIcon = optional($b->room)->icon ?? '📋';
        }

        // aturan tombol batal admin:
        // ✅ DIUBAH: hanya boleh batal jika DISETUJUI dan belum mulai
        $canCancel = false;
        if ($tanggalRaw && $jamMulai && $jamMulai !== '-') {
          $jm = substr((string)$jamMulai, 0, 5) . ':00';
          $startAt = Carbon::parse($tanggalRaw . ' ' . $jm);
          $canCancel = in_array($status, ['DISETUJUI'], true) && $startAt->isFuture();
        }
      ?>

      <div style="background:var(--white);border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border:1.5px solid var(--blue-100);margin-bottom:16px;display:flex;gap:20px;align-items:flex-start;transition:var(--transition)">
        <div style="width:52px;height:52px;border-radius:14px;flex-shrink:0;background:linear-gradient(135deg,var(--blue-600),var(--blue-400));display:flex;align-items:center;justify-content:center;font-size:24px">
          <?php echo e($ruangIcon); ?>

        </div>

        <div style="flex:1">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;margin-bottom:10px">
            <div style="font-size:16px;font-weight:700;color:var(--gray-800)"><?php echo e($kegiatan); ?></div>
            <span class="badge badge-<?php echo e(strtolower($status)); ?>"><?php echo e($status); ?></span>
          </div>

          <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:13px;color:var(--gray-600)">
            <span>🏢 <?php echo e($ruangNama); ?></span>
            <span>📅 <?php echo e($tanggalFormatted); ?></span>
            <span>🕐 <?php echo e($jamMulai); ?>–<?php echo e($jamSelesai); ?></span>
            <span>👥 <?php echo e($peserta); ?> peserta</span>
          </div>

          <?php if($status === 'DITOLAK' && $rejectionReason): ?>
            <div class="rejection-reason" style="margin-top:12px">
              <span style="font-size:18px">❌</span>
              <span class="rejection-reason-text"><strong>Alasan:</strong> <?php echo e($rejectionReason); ?></span>
            </div>
          <?php endif; ?>

          <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap">
            <a href="<?php echo e(route('admin.booking.show', $kode)); ?>" class="btn-sm btn-sm-outline">🔍 Detail</a>

            
            <?php if($status === 'MENUNGGU'): ?>
              <form method="POST" action="<?php echo e(route('admin.booking.approve', $kode)); ?>" onsubmit="return confirm('Setujui booking ini?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-sm" style="background:linear-gradient(135deg,var(--success),#059669);color:white;border:none;border-radius:10px;padding:10px 14px;font-weight:800;cursor:pointer">
                  ✅ Setujui
                </button>
              </form>

              <form method="POST" action="<?php echo e(route('admin.booking.reject', $kode)); ?>" onsubmit="return confirm('Tolak booking ini?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="alasan" value="Ditolak oleh admin.">
                <button type="submit" class="btn-sm" style="background:none;color:var(--danger);border:2px solid var(--danger);border-radius:10px;padding:10px 14px;font-weight:800;cursor:pointer">
                  ❌ Tolak
                </button>
              </form>
            <?php endif; ?>

            
            <?php if($canCancel): ?>
              <form method="POST"
                    action="<?php echo e(route('admin.booking.cancelApproved', $kode)); ?>"
                    style="display:inline"
                    onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-sm btn-sm-danger">🚫 Batalkan</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
    <div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <div class="empty-state-title">Belum Ada Booking</div>
      <div class="empty-state-desc">Belum ada data booking</div>
      <div style="margin-top:24px">
        <a href="<?php echo e(route('kalender')); ?>"
           style="padding:12px 28px;background:var(--blue-600);color:white;border-radius:var(--radius);text-decoration:none;font-size:14px;font-weight:600">
          Booking Sekarang →
        </a>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/bookings.blade.php ENDPATH**/ ?>