<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Invoice Semua Pesanan' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .invoice-box { width: 100%; margin-bottom: 40px; border: 1px solid #eee; padding: 20px; }
        .header { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #eee; padding: 6px 8px; }
        .table th { background: #f5f5f5; }
        .mb-2 { margin-bottom: 8px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Invoice Semua Pesanan</h2>
    @foreach($reservasis as $reservasi)
    <div class="invoice-box">
        <div class="header">Invoice #{{ $reservasi->id }}</div>
        <div class="mb-2"><strong>Paket Wisata:</strong> {{ $reservasi->paketWisata->nama_paket ?? '-' }}</div>
        <div class="mb-2"><strong>Tanggal:</strong>
            {{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }}
            -
            {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d M Y') }}
        </div>
        <div class="mb-2"><strong>Peserta:</strong> {{ $reservasi->jumlah_peserta }} orang</div>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($reservasi->status_reservasi) }}</div>
        <div class="mb-2"><strong>Total Bayar:</strong> Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</div>
        <table class="table">
            <tr>
                <th>Bank</th>
                <th>Kode Reservasi</th>
                <th>Tanggal Reservasi</th>
            </tr>
            <tr>
                <td>{{ $reservasi->bank->nama_bank ?? '-' }}</td>
                <td>{{ $reservasi->kode_reservasi }}</td>
                <td>
                    @php
                        $tgl = $reservasi->tgl_reservasi;
                        if (!($tgl instanceof \Carbon\Carbon)) {
                            $tgl = \Carbon\Carbon::parse($tgl);
                        }
                    @endphp
                    {{ $tgl->format('d M Y') }}
                </td>
            </tr>
        </table>
    </div>
    @endforeach
</body>
</html>
