<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 30px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .header img {
            width: 70px;
            height: auto;
        }
        .header-text {
            text-align: right;
        }
        .header-text h1 {
            color: #004aad;
            text-transform: uppercase;
            font-size: 18px;
            margin: 0;
        }
        .header-text p {
            font-size: 12px;
            margin: 3px 0;
        }
        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            margin: 0 0 10px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-top: 25px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #555;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-center { text-align: center; }

        .signature {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-box p {
            margin: 0;
            font-size: 12px;
        }
        .signature-box .ttd {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <div class="header-text">
            <h1>Laporan Keuangan Wisata</h1>
            <p>Periode: {{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    <div class="info-box">
        <h3>Ringkasan</h3>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p><strong>Paket Wisata Paling Laris:</strong> {{ $paketLaris->paket->nama_paket ?? '-' }} ({{ $paketLaris->jumlah ?? 0 }} Reservasi)</p>
    </div>

    <div class="section-title">Statistik Peserta Wisata</div>
    <table>
        <thead>
            <tr>
                <th>Paket Wisata</th>
                <th class="text-center">Total Peserta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statistikPeserta as $row)
                <tr>
                    <td>{{ $row->paket->nama_paket ?? '-' }}</td>
                    <td class="text-center">{{ $row->total_peserta }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Grafik Pendapatan per Bulan</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grafikPendapatan as $row)
                <tr>
                    <td>{{ $row->bulan }}</td>
                    <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Daftar Reservasi</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Tgl Reservasi</th>
                <th>Harga</th>
                <th>Peserta</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi as $i => $r)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                    <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $r->tgl_reservasi_wisata }}</td>
                    <td>Rp{{ number_format($r->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $r->jumlah_peserta }}</td>
                    <td>
                        @if($r->diskon)
                            {{ $r->diskon }}%<br>(Rp{{ number_format($r->nilai_diskon, 0, ',', '.') }})
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp{{ number_format($r->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        @switch($r->status_reservasi_wisata)
                            @case('pesan') Pesan @break
                            @case('dibayar') Dibayar @break
                            @case('selesai') Selesai @break
                        @endswitch
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p>Cibinong, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p class="ttd">Admin Wisata</p>
        </div>
    </div>

</body>
</html>
