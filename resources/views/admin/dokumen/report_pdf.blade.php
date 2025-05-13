<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Mingguan Dokumen</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #1a1a1a;
        }

        .header p {
            margin: 8px 0 0;
            color: #666;
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 18px;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .chart-container {
            margin: 20px 0;
            text-align: center;
        }

        .chart-container img {
            max-width: 100%;
            height: auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }

        .stat-box h3 {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .stat-box p {
            margin: 10px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .page-break {
            page-break-after: always;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-diajukan { background-color: #FEF3C7; color: #92400E; }
        .status-disahkan { background-color: #D1FAE5; color: #065F46; }
        .status-direvisi { background-color: #DBEAFE; color: #1E40AF; }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Mingguan Dokumen</h1>
        <p>Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <!-- Grafik Statistik -->
    <div class="section">
        <h2>Grafik Statistik Dokumen</h2>

        <!-- Grafik Bulanan Status Dokumen -->
        <div class="chart-container">
            <h3>Statistik Bulanan Status Dokumen</h3>
            <img src="data:image/png;base64,{{ $monthlyChartImage }}" alt="Statistik Bulanan">
        </div>

        <!-- Grafik Keaktifan User -->
        <div class="chart-container">
            <h3>Distribusi Keaktifan Pengguna</h3>
            <img src="data:image/png;base64,{{ $userActivityImage }}" alt="Keaktifan User">
        </div>

        <!-- Grafik Aktivitas Bulanan Per Pengguna -->
        <div class="chart-container">
            <h3>Aktivitas Bulanan Per Pengguna</h3>
            <img src="data:image/png;base64,{{ $monthlyActivityImage }}" alt="Aktivitas Bulanan">
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Statistik Ringkasan -->
    <div class="section">
        <h2>Statistik Ringkasan</h2>
        <table>
            <tr>
                <th>Total Dokumen</th>
                <th>Dokumen Disahkan</th>
                <th>Dokumen Diajukan</th>
                <th>Dokumen Direvisi</th>
            </tr>
            <tr>
                <td>{{ $totalDocuments }}</td>
                <td>{{ $signedDocuments }}</td>
                <td>{{ $pendingDocuments }}</td>
                <td>{{ $revisedDocuments }}</td>
            </tr>
        </table>
    </div>

    <!-- Statistik User -->
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
                <td>{{ $ormawasCount }}</td>
                <td>{{ $dosenCount }}</td>
                <td>{{ $kemahasiswaanCount }}</td>
                <td>{{ $ormawasCount + $dosenCount + $kemahasiswaanCount }}</td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Ormawa Paling Aktif -->
    <div class="section">
        <h2>Ormawa Paling Aktif</h2>
        @if($mostActiveOrmawas->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Mahasiswa</th>
                    <th>Nama Ormawa</th>
                    <th>Jumlah Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mostActiveOrmawas as $index => $ormawa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ormawa->namaMahasiswa }}</td>
                    <td>{{ $ormawa->namaOrmawa }}</td>
                    <td>{{ $ormawa->document_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada data statistik Ormawa pada periode ini.</p>
        @endif
    </div>

    <!-- Dosen Paling Aktif -->
    <div class="section">
        <h2>Dosen Paling Aktif</h2>
        @if($mostActiveDosens->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Jumlah Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mostActiveDosens as $index => $dosen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dosen->nama_dosen }}</td>
                    <td>{{ $dosen->document_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada data statistik Dosen pada periode ini.</p>
        @endif
    </div>

    <div class="page-break"></div>

    <!-- Kemahasiswaan Paling Aktif -->
    <div class="section">
        <h2>Staff Kemahasiswaan Paling Aktif</h2>
        @if(isset($mostActiveKemahasiswaan) && $mostActiveKemahasiswaan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Staff</th>
                    <th>Jumlah Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mostActiveKemahasiswaan as $index => $staff)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $staff->nama_kemahasiswaan }}</td>
                    <td>{{ $staff->document_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada data statistik Staff Kemahasiswaan pada periode ini.</p>
        @endif
    </div>

    <!-- Daftar Dokumen -->
    <div class="section">
        <h2>Daftar Dokumen</h2>

        <!-- Dokumen Ormawa -->
        <h3>Dokumen Ormawa</h3>
        @if($dokumenOrmawa->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Nama Ormawa</th>
                    <th>Pengaju</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumenOrmawa as $index => $doc)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td>{{ $doc->nomor_surat }}</td>
                    <td>{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                    <td>{{ $doc->ormawa?->namaMahasiswa ?? 'N/A' }}</td>
                    <td>{{ $doc->perihal }}</td>
                    <td>
                        <span class="status-badge status-{{ $doc->status_dokumen }}">
                            {{ ucfirst($doc->status_dokumen) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada dokumen Ormawa pada periode ini.</p>
        @endif

        <div class="page-break"></div>

        <!-- Dokumen Dosen -->
        <h3>Dokumen Dosen</h3>
        @if($dokumenDosen->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dosen</th>
                    <th>Dari Ormawa</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumenDosen as $index => $doc)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td>{{ $doc->nomor_surat }}</td>
                    <td>{{ $doc->dosen?->nama_dosen ?? 'N/A' }}</td>
                    <td>{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                    <td>{{ $doc->perihal }}</td>
                    <td>
                        <span class="status-badge status-{{ $doc->status_dokumen }}">
                            {{ ucfirst($doc->status_dokumen) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada dokumen Dosen pada periode ini.</p>
        @endif

        <div class="page-break"></div>

        <!-- Dokumen Kemahasiswaan -->
        <h3>Dokumen Kemahasiswaan</h3>
        @if($dokumenKemahasiswaan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Nama Staff</th>
                    <th>Dari Ormawa</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumenKemahasiswaan as $index => $doc)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td>{{ $doc->nomor_surat }}</td>
                    <td>{{ $doc->kemahasiswaan?->nama_kemahasiswaan ?? 'N/A' }}</td>
                    <td>{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                    <td>{{ $doc->perihal }}</td>
                    <td>
                        <span class="status-badge status-{{ $doc->status_dokumen }}">
                            {{ ucfirst($doc->status_dokumen) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada dokumen Kemahasiswaan pada periode ini.</p>
        @endif
    </div>
</body>

</html>
