<!doctype html>
<html lang="id"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f1f5f9}</style></head>
<body>
<h2>Laporan Stok Barang</h2>
<table><thead><tr><th>Kode</th><th>Barang</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Status</th></tr></thead><tbody>
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr><td><?php echo e($item->code); ?></td><td><?php echo e($item->name); ?></td><td><?php echo e($item->category->name); ?></td><td><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></td><td><?php echo e($item->minimum_stock); ?></td><td><?php echo e($item->stock_status); ?></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table>
</body></html>
<?php /**PATH /var/www/html/resources/views/reports/pdf/stock.blade.php ENDPATH**/ ?>