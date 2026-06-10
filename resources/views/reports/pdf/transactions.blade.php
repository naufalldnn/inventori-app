<!doctype html>
<html lang="id"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f1f5f9}</style></head>
<body>
<h2>{{ $type === 'in' ? 'Laporan Barang Masuk' : 'Laporan Barang Keluar' }}</h2>
<p>Periode: {{ $dateFrom ?: '-' }} sampai {{ $dateTo ?: '-' }}</p>
<table><thead><tr><th>Tanggal</th><th>Barang</th><th>Kategori</th><th>Jumlah</th><th>Petugas</th><th>Catatan</th></tr></thead><tbody>
@foreach ($transactions as $transaction)
<tr><td>{{ $transaction->transaction_date->format('d M Y') }}</td><td>{{ $transaction->item->name }}</td><td>{{ $transaction->item->category->name }}</td><td>{{ $transaction->quantity }}</td><td>{{ $transaction->user->name }}</td><td>{{ $transaction->notes }}</td></tr>
@endforeach
</tbody></table>
</body></html>
