
<?php $__env->startSection('title', 'Tambah OPD'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:900px;margin:0 auto;padding:18px 18px 30px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;font-weight:800;color:var(--gray-800)">+ Tambah OPD</h2>
      <div style="color:var(--gray-500);margin-top:4px;">Tambahkan OPD baru.</div>
    </div>
    <a href="<?php echo e(route('admin.opd.index')); ?>" class="filter-btn">← Kembali</a>
  </div>

  <div class="table-container" style="margin-top:16px;padding:18px;">
    <form method="POST" action="<?php echo e(route('admin.opd.store')); ?>">
      <?php echo csrf_field(); ?>

      <div style="display:grid;grid-template-columns:1fr;gap:12px;">
        <div>
          <label style="font-weight:700;">Nama OPD <span style="color:#ef4444">*</span></label>
          <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" required
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#ef4444;margin-top:6px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label style="font-weight:700;">Lantai (opsional)</label>
          <input type="text" name="lantai" value="<?php echo e(old('lantai')); ?>"
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          <?php $__errorArgs = ['lantai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#ef4444;margin-top:6px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label style="font-weight:700;">Gedung (opsional)</label>
          <input type="text" name="gedung" value="<?php echo e(old('gedung')); ?>"
                 style="width:100%;margin-top:6px;padding:12px 12px;border:1px solid var(--gray-200);border-radius:12px;">
          <?php $__errorArgs = ['gedung'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#ef4444;margin-top:6px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display:flex;gap:10px;align-items:center;">
          <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', 1) ? 'checked' : ''); ?>>
          <label style="margin:0;">Aktif</label>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;">
          <button type="submit" class="filter-btn" style="background:var(--blue-600);color:#fff;border-color:transparent;">
            Simpan
          </button>
          <a href="<?php echo e(route('admin.opd.index')); ?>" class="filter-btn">Batal</a>
        </div>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Ruangan_Menara_Wijaya\resources\views/admin/opd/create.blade.php ENDPATH**/ ?>