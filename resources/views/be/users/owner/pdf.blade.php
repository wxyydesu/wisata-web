<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 25px;
            line-height: 1.4;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3a7bd5;
        }
        
        .header img {
            width: 80px;
            height: auto;
        }
        
        .header-text {
            text-align: right;
        }
        
        .header-text h1 {
            color: #3a7bd5;
            font-size: 20px;
            margin: 0 0 5px 0;
            font-weight: 600;
        }
        
        .header-text p {
            font-size: 12px;
            margin: 0;
            color: #666;
        }
        
        .info-box {
            background: linear-gradient(to right, #f5f7fa, #e4edf9);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .info-box h3 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #3a7bd5;
            font-weight: 600;
        }
        
        .info-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .info-item {
            flex: 1;
            min-width: 200px;
        }
        
        .info-item strong {
            display: block;
            color: #555;
            margin-bottom: 3px;
            font-size: 12px;
        }
        
        .info-item span {
            font-size: 14px;
            font-weight: 500;
            color: #222;
        }
        
        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin: 25px 0 10px;
            padding-bottom: 5px;
            color: #3a7bd5;
            border-bottom: 1px solid #e0e6ed;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            font-size: 11px;
        }
        
        thead th {
            background: linear-gradient(to bottom, #3a7bd5, #2a5db0);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        tbody tr:hover {
            background-color: #f0f7ff;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #e0e6ed;
            vertical-align: middle;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .status-pesan {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-dibayar {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-selesai {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .signature {
            margin-top: 50px;
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
            color: #666;
        }
        
        .signature-box .ttd {
            margin-top: 50px;
            font-weight: 600;
            color: #3a7bd5;
        }
        
        .signature-line {
            width: 150px;
            height: 1px;
            background-color: #3a7bd5;
            margin: 5px auto;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e0e6ed;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        
        .chart-container {
            height: 200px;
            margin: 15px 0;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            position: relative;
        }
        
        .chart-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo"> --}}
        <div class="header-text">
            <h1>Laporan Keuangan Wisata</h1>
            <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="info-box">
        <h3>Ringkasan Keuangan</h3>
        <div class="info-content">
            <div class="info-item">
                <strong>Total Pendapatan</strong>
                <span>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <div class="info-item">
                <strong>Paket Wisata Paling Laris</strong>
                <span>{{ $paketLaris->paket->nama_paket ?? '-' }} ({{ $paketLaris->jumlah ?? 0 }} Reservasi)</span>
            </div>
            <div class="info-item">
                <strong>Periode Laporan</strong>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <div class="section-title">Statistik Peserta Wisata</div>
    <table>
        <thead>
            <tr>
                <th style="border-top-left-radius: 6px;">Paket Wisata</th>
                <th style="border-top-right-radius: 6px;" class="text-center">Total Peserta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statistikPeserta as $row)
                <tr>
                    <td>{{ $row->paket->nama_paket ?? '-' }}</td>
                    <td class="text-center">{{ number_format($row->total_peserta, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Trend Pendapatan Bulanan</div>
    <div class="chart-container">
        <div class="chart-placeholder">
            [Grafik pendapatan bulanan akan ditampilkan di sini]
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="border-top-left-radius: 6px;">Bulan</th>
                <th style="border-top-right-radius: 6px;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grafikPendapatan as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->bulan)->translatedFormat('F Y') }}</td>
                    <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Detail Reservasi</div>
    <table>
        <thead>
            <tr>
                <th style="border-top-left-radius: 6px; width: 30px;">No</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Tgl Reservasi</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Peserta</th>
                <th class="text-center">Diskon</th>
                <th class="text-right">Total Bayar</th>
                <th class="text-center">Status</th>
                <th style="border-top-right-radius: 6px;">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi as $i => $r)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                    <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->tgl_reservasi_wisata)->format('d/m/Y') }}</td>
                    <td class="text-right">Rp{{ number_format($r->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $r->jumlah_peserta }}</td>
                    <td class="text-center">
                        @if($r->diskon)
                            {{ $r->diskon }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp{{ number_format($r->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @switch($r->status_reservasi_wisata)
                            @case('pesan') <span class="status status-pesan">Pesan</span> @break
                            @case('dibayar') <span class="status status-dibayar">Dibayar</span> @break
                            @case('selesai') <span class="status status-selesai">Selesai</span> @break
                        @endswitch
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sist
         Manajemen Wisata | &copy; {{ date('Y') }} All Rights Reserved
    </div>

</body>
</html>