<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Mingguan Dokumen</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header p {
            margin: 5px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section {
            margin-bottom: 20px;
        }

        h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            font-size: 16px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN MINGGUAN DOKUMEN</h1>
        <p>Sistem Pengesahan Dokumen Digital Dengan QR Code</p>
        <p>Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <div class="section">
        <h2>Statistik Dokumen</h2>
        <table>
            <tr>
                <th>Total Dokumen</th>
                <th>Dokumen Disahkan</th>
                <th>Dokumen Diajukan</th>
                <th>Dokumen Direvisi</th>
            </tr>
            <tr>
                <td align="center">{{ $totalDocuments }}</td>
                <td align="center">{{ $signedDocuments }}</td>
                <td align="center">{{ $pendingDocuments }}</td>
                <td align="center">{{ $revisedDocuments }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Statistik Pengguna</h2>
        <table>
            <tr>
                <th>Jumlah Ormawa</th>
                <th>Jumlah Dosen</th>
                <th>Jumlah Staff Kemahasiswaan</th>
                <th>Total Pengguna</th>
            </tr>
            <tr>
                <td align="center">{{ $ormawasCount }}</td>
                <td align="center">{{ $dosenCount }}</td>
                <td align="center">{{ $kemahasiswaanCount }}</td>
                <td align="center">{{ $ormawasCount + $dosenCount + $kemahasiswaanCount }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Ormawa Paling Aktif</h2>
        @if($mostActiveOrmawas->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Mahasiswa</th>
                <th>Ormawa</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($mostActiveOrmawas as $index => $ormawa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ormawa->namaMahasiswa }}</td>
                <td>{{ $ormawa->namaOrmawa }}</td>
                <td align="center">{{ $ormawa->document_count }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Ormawa pada periode ini.</p>
        @endif
    </div>

    <div class="section">
        <h2>Dosen Paling Aktif</h2>
        @if($mostActiveDosens->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Dosen</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($mostActiveDosens as $index => $dosen)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dosen->nama_dosen }}</td>
                <td align="center">{{ $dosen->document_count }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Dosen pada periode ini.</p>
        @endif
    </div>

    <div class="section">
        <h2>Kemahasiswaan Paling Aktif</h2>
        @if($mostActiveKemahasiswaan->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Kemahasiswaan</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($mostActiveKemahasiswaan as $index => $kemahasiswaan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kemahasiswaan->nama_kemahasiswaan }}</td>
                <td align="center">{{ $kemahasiswaan->document_count }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Kemahasiswaan pada periode ini.</p>
        @endif
    </div>

    <div class="page-break"></div>

    <div class="section">
        <h2>Statistik Berdasarkan Ormawa</h2>
        @if($ormawaStats->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Ormawa</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($ormawaStats as $index => $stat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $stat->namaOrmawa }}</td>
                <td align="center">{{ $stat->total }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Ormawa pada periode ini.</p>
        @endif
    </div>

    <div class="section">
        <h2>Statistik Berdasarkan Dosen</h2>
        @if($dosenStats->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Dosen</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($dosenStats as $index => $stat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $stat->nama_dosen }}</td>
                <td align="center">{{ $stat->total }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Dosen pada periode ini.</p>
        @endif
    </div>

    <div class="section">
        <h2>Statistik Berdasarkan Staff Kemahasiswaan</h2>
        @if(isset($kemahasiswaanStats) && $kemahasiswaanStats->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Staff</th>
                <th>Jumlah Dokumen</th>
            </tr>
            @foreach($kemahasiswaanStats as $index => $stat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $stat->nama_kemahasiswaan }}</td>
                <td align="center">{{ $stat->total }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada data statistik Staff Kemahasiswaan pada periode ini.</p>
        @endif
    </div>

    <div class="page-break"></div>

    <div class="section">
        <h2>Daftar Dokumen</h2>
        @if($dokumens->count() > 0)
        <table>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nomor Surat</th>
                <th>Pengaju</th>
                <th>Perihal</th>
                <th>Status</th>
            </tr>
            @foreach($dokumens as $index => $doc)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                <td>{{ $doc->nomor_surat }}</td>
                <td>{{ $doc->ormawa->namaMahasiswa ?? 'N/A' }}</td>
                <td>{{ $doc->perihal }}</td>
                <td>{{ ucfirst($doc->status_dokumen) }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>Tidak ada dokumen pada periode ini.</p>
        @endif
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 10px;">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>

</html>