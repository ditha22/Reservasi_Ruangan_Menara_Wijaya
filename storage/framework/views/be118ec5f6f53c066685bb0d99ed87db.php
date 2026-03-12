<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Menara Wijaya</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
    .title { font-size: 16px; font-weight: 800; margin-bottom: 6px; }
    .meta { margin-bottom: 12px; color: #374151; }
    .badge { display:inline-block; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 800; }
    .table { width:100%; border-collapse: collapse; margin-top: 10px; }
    .table th, .table td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
    .table th { background: #f3f4f6; font-size: 11px; }
    .muted { color:#6b7280; }
  </style>
</head>
<body>

  <?php
    $months = [
      1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
      7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];
    $roomLabel = $roomSelected?->nama ?? 'Semua Ruangan';
    $periodLabel = ($months[$month] ?? $month) . ' ' . $year;
  ?>

  <div class="title">Laporan Peminjaman Ruangan — Menara Wijaya</div>
  <div class="meta">
    Periode: <strong><?php echo e($periodLabel); ?></strong><br>
    Ruangan: <strong><?php echo e($roomLabel); ?></strong><br>
    Total Data: <strong><?php echo e($total); ?></strong>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th style="width:18%">Tanggal</th>
        <th style="width:28%">Ruang</th>
        <th style="width:34%">OPD</th>
        <th style="width:20%">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td><?php echo e($r['tanggal']); ?></td>
          <td><?php echo e($r['room']); ?></td>
          <td><?php echo e($r['opd']); ?></td>
          <td><?php echo e($r['status']); ?></td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="4" class="muted">Tidak ada data untuk periode ini.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <p class="muted" style="margin-top:14px">
    Dicetak pada: <?php echo e(\Illuminate\Support\Carbon::now()->format('d M Y H:i')); ?>

  </p>

</body>
</html><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/exports/laporan_pdf.blade.php ENDPATH**/ ?>