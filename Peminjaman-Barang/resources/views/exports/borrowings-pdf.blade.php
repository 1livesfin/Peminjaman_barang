<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman - BorrowEase</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { text-align: center; color: #4F46E5; font-size: 18px; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #6b7280; font-size: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background: #4F46E5; color: white; }
        th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 9px; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .footer { text-align: center; margin-top: 30px; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>BorrowEase — Laporan Peminjaman Barang</h1>
    <p class="subtitle">Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Peminjaman</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $i => $borrowing)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-size: 10px;">{{ $borrowing->borrowing_number }}</td>
                <td>{{ $borrowing->borrower_name }}</td>
                <td>
                    @foreach($borrowing->details as $d)
                        {{ $d->item->name }} ({{ $d->quantity }})@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->return_date->format('d/m/Y') }}</td>
                <td>
                    @php
                        $badgeClass = match($borrowing->status) {
                            'dikembalikan' => 'badge-green',
                            'dipinjam', 'disetujui' => 'badge-blue',
                            'menunggu' => 'badge-yellow',
                            default => 'badge-red'
                        };
                        $label = $borrowing->status_badge['label'];
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                </td>
                <td>{{ $borrowing->late_fine > 0 ? 'Rp '.number_format($borrowing->late_fine,0,',','.') : '–' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        BorrowEase — Sistem Peminjaman Barang Inventaris · Total: {{ $borrowings->count() }} data
    </div>
</body>
</html>
