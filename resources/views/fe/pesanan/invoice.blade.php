<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            max-height: 70px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #3f51b5;
        }
        .invoice-info {
            margin-top: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            background-color: #f5f5f5;
            text-align: left;
            padding: 10px;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .total-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <table width="100%">
            <tr>
                <td width="50%">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p>
                        <strong>No. Invoice:</strong> #{{ $reservasi->id }}<br>
                        <strong>Tanggal:</strong> {{ now()->format('d/m/Y') }}
                    </p>
                </td>
                <td width="50%" class="text-right">
                    <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Company Logo">
                </td>
            </tr>
        </table>
    </div>

    <div class="invoice-info">
        <table width="100%">
            <tr>
                <td width="50%">
                    <h3>Detail Pelanggan</h3>
                    <p>
                        <strong>Nama:</strong> {{ $reservasi->pelanggan->user->name }}<br>
                        <strong>Email:</strong> {{ $reservasi->pelanggan->user->email }}<br>
                        <strong>Telepon:</strong> {{ $reservasi->pelanggan->no_hp }}
                    </p>
                </td>
                <td width="50%">
                    <h3>Detail Reservasi</h3>
                    <p>
                        <strong>Paket:</strong> {{ $reservasi->paketWisata->nama_paket }}<br>
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d/m/Y') }}<br>
                        <strong>Status:</strong> {{ ucfirst($reservasi->status_reservasi) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <h3>Detail Pembayaran</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $reservasi->paketWisata->nama_paket }}</td>
                <td class="text-right">Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</td>
                <td class="text-right">{{ $reservasi->jumlah_peserta }}</td>
                <td class="text-right">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <table width="100%">
            <tr>
                <td width="70%"></td>
                <td width="30%">
                    <table width="100%">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-right">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-right">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih telah memesan di layanan kami.<br>
        Invoice ini sah dan dapat digunakan sebagai bukti pembayaran.</p>
    </div>
</body>
</html>