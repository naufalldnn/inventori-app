<!doctype html>
<html lang="id"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f1f5f9}</style></head>
<body>
<h2>Laporan Stok Barang</h2>
<table><thead><tr><th>Kode</th><th>Barang</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Status</th></tr></thead><tbody>
@foreach ($items as $item)
<tr><td>{{ $item->code }}</td><td>{{ $item->name }}</td><td>{{ $item->category->name }}</td><td>{{ $item->stock }} {{ $item->unit }}</td><td>{{ $item->minimum_stock }}</td><td>{{ $item->stock_status }}</td></tr>
@endforeach
</tbody></table>
</body></html>
