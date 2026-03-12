<?php $__env->startSection('title', 'Laporan & Rekap'); ?>
<?php $__env->startSection('content'); ?>

<?php
  $months = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
  ];

  $nowYear = now()->year;
  $yearOptions = range($nowYear - 3, $nowYear + 1);

  $statusStyle = function($status){
    $s = strtoupper($status);
    if ($s === 'DISETUJUI') return ['bg'=>'#dcfce7','fg'=>'#166534','bd'=>'#86efac','label'=>'DISETUJUI'];
    if ($s === 'DITOLAK') return ['bg'=>'#fee2e2','fg'=>'#991b1b','bd'=>'#fecaca','label'=>'DITOLAK'];
    if ($s === 'DIBATALKAN') return ['bg'=>'#e5e7eb','fg'=>'#374151','bd'=>'#d1d5db','label'=>'DIBATALKAN'];
    return ['bg'=>'#fef3c7','fg'=>'#92400e','bd'=>'#fde68a','label'=>'PENDING'];
  };

  // ✅ NEW: label ruangan terpilih
  $roomLabel = $roomSelected?->nama ?? 'Semua Ruangan';
?>

<div style="background:linear-gradient(135deg,var(--blue-800),var(--blue-600));padding:30px 18px;color:white">
  <div style="max-width:920px;margin:0 auto">
    <div style="display:flex;gap:8px;margin-bottom:10px;font-size:13px;color:rgba(255,255,255,0.55)">
      <a href="<?php echo e(route('admin.dashboard')); ?>" style="color:rgba(255,255,255,0.8);text-decoration:none">Dashboard</a>
      <span>›</span><span style="color:white">Laporan</span>
    </div>
    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(22px,3vw,32px);font-weight:800;line-height:1.1;margin:0">
      Rekapitulasi Sederhana
    </h1>
    <p style="margin:8px 0 0;color:rgba(255,255,255,0.75);font-size:14px">
      Kelola dan tinjau data peminjaman OPD.
    </p>
  </div>
</div>

<div style="max-width:920px;margin:0 auto;padding:18px">

  
  <form method="GET" action="<?php echo e(route('admin.laporan')); ?>"
        style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;margin-bottom:14px">

    <div style="flex:1;min-width:170px;background:white;border:1px solid var(--blue-100);border-radius:14px;padding:12px 12px">
      <div style="font-size:11px;color:var(--gray-500);font-weight:700;margin-bottom:6px">BULAN</div>
      <select name="month" style="width:100%;border:0;outline:none;font-weight:700">
        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($num); ?>" <?php if((int)$month === (int)$num): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div style="flex:1;min-width:140px;background:white;border:1px solid var(--blue-100);border-radius:14px;padding:12px 12px">
      <div style="font-size:11px;color:var(--gray-500);font-weight:700;margin-bottom:6px">TAHUN</div>
      <select name="year" style="width:100%;border:0;outline:none;font-weight:700">
        <?php $__currentLoopData = $yearOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($y); ?>" <?php if((int)$year === (int)$y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    
    <div style="flex:1;min-width:220px;background:white;border:1px solid var(--blue-100);border-radius:14px;padding:12px 12px">
      <div style="font-size:11px;color:var(--gray-500);font-weight:700;margin-bottom:6px">RUANGAN</div>
      <select name="room_id" style="width:100%;border:0;outline:none;font-weight:700">
        <option value="" <?php if(empty($room_id)): echo 'selected'; endif; ?>>Semua Ruangan</option>
        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($r->id); ?>" <?php if((int)$room_id === (int)$r->id): echo 'selected'; endif; ?>><?php echo e($r->nama); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <button type="submit"
      style="padding:12px 16px;border-radius:14px;border:0;background:var(--blue-700);color:white;font-weight:800;cursor:pointer">
      Terapkan
    </button>
  </form>

  
  <div style="background:#2563eb;border-radius:18px;padding:16px;color:white;box-shadow:var(--shadow);margin-bottom:14px">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
      <div>
        <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.85);font-size:12px;font-weight:700">
          <span style="display:inline-flex;width:28px;height:28px;border-radius:10px;background:rgba(255,255,255,0.15);align-items:center;justify-content:center">📅</span>
          Total Peminjaman (<?php echo e($roomLabel); ?>)
        </div>
        <div style="font-size:30px;font-weight:900;line-height:1;margin-top:10px"><?php echo e($total); ?></div>

        <?php if(!is_null($percent)): ?>
          <div style="margin-top:8px;font-size:12px;color:rgba(255,255,255,0.85);font-weight:700">
            <?php echo e($percent >= 0 ? '+' : ''); ?><?php echo e($percent); ?>% vs bulan lalu
          </div>
        <?php else: ?>
          <div style="margin-top:8px;font-size:12px;color:rgba(255,255,255,0.85);font-weight:700">
            Data bulan lalu belum ada
          </div>
        <?php endif; ?>
      </div>

      <div style="width:62px;height:62px;border-radius:20px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;font-size:28px">
        📊
      </div>
    </div>
  </div>

  
  <div style="background:white;border-radius:18px;padding:14px;border:1px solid var(--blue-100);box-shadow:var(--shadow);margin-bottom:14px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
      <div style="width:34px;height:34px;border-radius:12px;background:var(--blue-50);display:flex;align-items:center;justify-content:center">🏢</div>
      <div style="font-weight:900;color:var(--blue-900)">Detail Penggunaan Ruang</div>
    </div>

    <?php if($roomUsage->count() === 0): ?>
      <div style="padding:12px;border-radius:14px;background:var(--gray-50);color:var(--gray-500);font-weight:700">
        Belum ada data peminjaman pada periode ini.
      </div>
    <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:10px">
        <?php $__currentLoopData = $roomUsage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomName => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid var(--blue-100);border-radius:14px;padding:12px">
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:12px;background:var(--gray-50);display:flex;align-items:center;justify-content:center">🏛️</div>
              <div style="font-weight:800;color:var(--blue-900);line-height:1.2"><?php echo e($roomName); ?></div>
            </div>
            <div style="font-weight:900;color:var(--blue-700)"><?php echo e($count); ?><span style="font-weight:700;color:var(--gray-500);margin-left:6px;font-size:12px">sesi</span></div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>

  
  <div style="background:white;border-radius:18px;padding:14px;border:1px solid var(--blue-100);box-shadow:var(--shadow);margin-bottom:14px">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px">
      <div style="display:flex;align-items:center;gap:10px">
        <div style="width:34px;height:34px;border-radius:12px;background:var(--blue-50);display:flex;align-items:center;justify-content:center">📋</div>
        <div>
          <div style="font-weight:900;color:var(--blue-900)">Daftar Peminjaman</div>
          <div style="font-size:12px;color:var(--gray-500);font-weight:700">Menampilkan <?php echo e($rows->count()); ?> data</div>
        </div>
      </div>

      <a href="<?php echo e(route('admin.bookings')); ?>"
         style="text-decoration:none;font-weight:900;color:var(--blue-700);font-size:12px">
        Lihat semua →
      </a>
    </div>

    <div style="overflow:auto;border-radius:14px;border:1px solid var(--blue-100)">
      <table style="width:100%;border-collapse:collapse;min-width:520px">
        <thead>
          <tr style="background:var(--blue-50)">
            <th style="text-align:left;padding:10px 12px;font-size:11px;color:var(--gray-600);font-weight:900">TGL</th>
            <th style="text-align:left;padding:10px 12px;font-size:11px;color:var(--gray-600);font-weight:900">RUANG</th>
            <th style="text-align:left;padding:10px 12px;font-size:11px;color:var(--gray-600);font-weight:900">OPD</th>
            <th style="text-align:left;padding:10px 12px;font-size:11px;color:var(--gray-600);font-weight:900">STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $st = $statusStyle($r['status']); ?>
            <tr style="border-top:1px solid var(--blue-100)">
              <td style="padding:10px 12px;font-weight:800;color:var(--blue-900)"><?php echo e($r['tanggal']); ?></td>
              <td style="padding:10px 12px;font-weight:800;color:var(--blue-900)"><?php echo e($r['room']); ?></td>
              <td style="padding:10px 12px;color:var(--gray-700);font-weight:700"><?php echo e($r['opd']); ?></td>
              <td style="padding:10px 12px">
                <span style="display:inline-flex;align-items:center;justify-content:center;padding:6px 10px;border-radius:999px;border:1px solid <?php echo e($st['bd']); ?>;background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['fg']); ?>;font-weight:900;font-size:11px">
                  <?php echo e($st['label']); ?>

                </span>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="4" style="padding:14px;color:var(--gray-500);font-weight:800">
                Tidak ada data untuk periode ini.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  
  <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end;margin-bottom:20px">
    <a href="<?php echo e(route('admin.laporan.pdf', ['month'=>$month,'year'=>$year,'room_id'=>$room_id])); ?>"
       style="display:inline-flex;align-items:center;gap:8px;padding:12px 14px;border-radius:14px;border:1px solid #fecaca;background:#fff1f2;color:#991b1b;text-decoration:none;font-weight:900">
      🧾 Cetak PDF
    </a>

    <a href="<?php echo e(route('admin.laporan.excel', ['month'=>$month,'year'=>$year,'room_id'=>$room_id])); ?>"
       style="display:inline-flex;align-items:center;gap:8px;padding:12px 14px;border-radius:14px;border:0;background:var(--blue-700);color:white;text-decoration:none;font-weight:900">
      📊 Unduh Excel
    </a>
  </div>

</div>

<div class="footer">
  <p>© 2025 <strong>Menara Wijaya</strong> — Sistem Reservasi Ruangan.</p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/laporan.blade.php ENDPATH**/ ?>