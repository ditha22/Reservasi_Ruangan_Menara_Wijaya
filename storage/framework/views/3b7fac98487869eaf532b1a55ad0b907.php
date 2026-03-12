
<?php $__env->startSection('title', 'Kelola Ruangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:1100px;margin:0 auto;padding:18px 18px 30px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;font-weight:800;color:var(--gray-800)">🏢 Kelola Ruangan</h2>
      <div style="color:var(--gray-500);margin-top:4px;">Tambah / ubah ruangan yang bisa dipinjam.</div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a href="<?php echo e(route('admin.dashboard')); ?>" class="filter-btn">← Kembali</a>
      <a href="<?php echo e(route('admin.ruang.create')); ?>" class="filter-btn" style="background:var(--blue-600);color:#fff;border-color:transparent;">+ Tambah Ruangan</a>
    </div>
  </div>

  <?php if(session('success')): ?>
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(34,197,94,.35);background:rgba(34,197,94,.08);border-radius:12px;color:rgb(22,101,52);">
      <?php echo e(session('success')); ?>

    </div>
  <?php endif; ?>

  <div class="table-container" style="margin-top:16px;">
    <div class="table-header" style="gap:10px;flex-wrap:wrap;">
      <span class="table-title">Daftar Ruangan</span>

      <form method="GET" action="<?php echo e(route('admin.ruang.index')); ?>" style="display:flex;gap:10px;align-items:center;">
        <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" placeholder="Cari ruangan..."
               style="padding:10px 12px;border:1px solid var(--gray-200);border-radius:12px;min-width:240px;">
        <button class="filter-btn" type="submit">Cari</button>
      </form>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th style="width:70px;">#</th>
            <th>Nama</th>
            <th style="width:180px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($rooms->firstItem() + $i); ?></td>
              <td style="font-weight:700;"><?php echo e($r->nama); ?></td>
              <td>
                <div class="td-actions" style="display:flex;gap:8px;flex-wrap:wrap;">
                  <a href="<?php echo e(route('admin.ruang.edit', $r->id)); ?>" class="btn-action btn-view">Edit</a>

                  <form method="POST" action="<?php echo e(route('admin.ruang.destroy', $r->id)); ?>" onsubmit="return confirm('Yakin ingin menghapus/nonaktifkan ruangan ini?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-action" style="background:rgba(239,68,68,.12);color:rgb(185,28,28);border:1px solid rgba(239,68,68,.22);">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="3" style="text-align:center;padding:36px;color:var(--gray-400);">
                Belum ada data ruangan.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div style="padding:14px 16px;">
      <?php echo e($rooms->links()); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/ruang/index.blade.php ENDPATH**/ ?>