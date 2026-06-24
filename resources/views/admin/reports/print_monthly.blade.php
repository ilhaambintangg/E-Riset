<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan E-Riset - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            color: #1f2937;
            background-color: #ffffff;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1f2937;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .header .address {
            font-size: 11px;
            margin-top: 5px;
            font-style: italic;
        }
        .title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 25px;
            text-decoration: underline;
        }
        .summary-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-item {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }
        .summary-label {
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #1f2937;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-container {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 70px;
        }
        @media print {
            body {
                margin: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #1e3a8a; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px;">Cetak Halaman Ini</button>
        <button onclick="window.close()" style="padding: 8px 16px; background-color: #4b5563; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px; margin-left: 8px;">Tutup</button>
    </div>

    <!-- Kop Surat Pengadilan Tinggi -->
    <div class="header">
        <h1>PENGADILAN TINGGI TANJUNGKARANG</h1>
        <h2>SISTEM INFORMASI E-RISET (IZIN PENELITIAN ONLINE)</h2>
        <div class="address">Jl. Cut Nyak Dien No.125, Durian Payung, Kec. Tanjung Karang Pusat, Kota Bandar Lampung, Lampung 35116</div>
    </div>

    <div class="title">
        LAPORAN BULANAN PERMOHONAN IZIN PENELITIAN<br>
        PERIODE: {{ $monthName }} {{ $year }}
    </div>

    <!-- Ringkasan Statistik -->
    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total Permohonan</div>
            <div class="summary-value">{{ $stats['total'] }}</div>
        </div>
        <div class="summary-item" style="border-left: 4px solid #16a34a;">
            <div class="summary-label">Disetujui</div>
            <div class="summary-value" style="color: #16a34a;">{{ $stats['approved'] }}</div>
        </div>
        <div class="summary-item" style="border-left: 4px solid #dc2626;">
            <div class="summary-label">Ditolak</div>
            <div class="summary-value" style="color: #dc2626;">{{ $stats['rejected'] }}</div>
        </div>
        <div class="summary-item" style="border-left: 4px solid #0284c7;">
            <div class="summary-label">Diproses/Menunggu</div>
            <div class="summary-value" style="color: #0284c7;">{{ $stats['processing'] + $stats['pending'] }}</div>
        </div>
    </div>

    <!-- Tabel Daftar Permohonan -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 15%;">No. Registrasi</th>
                <th style="width: 20%;">Nama Pemohon</th>
                <th style="width: 20%;">Universitas / Institusi</th>
                <th style="width: 25%;">Judul Penelitian</th>
                <th style="width: 15%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $index => $sub)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $sub->registration_number }}</strong></td>
                    <td>{{ $sub->name }}</td>
                    <td>{{ $sub->university }}</td>
                    <td>{{ $sub->title }}</td>
                    <td class="text-center">
                        {{ $sub->current_status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #6b7280;">Tidak ada permohonan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Panitera Pengadilan Tinggi Tanjungkarang,</strong></p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">(............................................)</p>
            <p>NIP. ............................................</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto trigger print when loaded directly
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
