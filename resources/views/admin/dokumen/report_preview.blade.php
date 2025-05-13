@extends('layouts.admin.app')

@section('title', 'Preview Laporan')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .chart-container.large {
        height: 400px;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }

        .chart-container.large {
            height: 300px;
        }
    }
</style>

<!-- Breadcrumb -->
<div class="mb-4">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.adminDashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('admin.dokumen.index') }}"
                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Dokumen</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('admin.dokumen.report') }}"
                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Laporan</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Preview</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block py-2 min-w-full">
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Preview Laporan Mingguan Dokumen</h2>
                    <a href="{{ route('admin.dokumen.generate-report', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                        class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Download PDF
                    </a>
                </div>

                <div class="p-6">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Laporan</h3>
                        <p class="mt-1 text-sm text-gray-600">Periode: {{ $startDate->format('d F Y') }} - {{
                            $endDate->format('d F Y') }}</p>
                    </div>

                    <!-- Grafik Statistik -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Grafik Statistik Dokumen</h3>
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <!-- Grafik Bulanan Status Dokumen -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="mb-4 font-medium text-gray-800 text-md">Statistik Bulanan Status Dokumen</h4>
                                <div class="chart-container">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>

                            <!-- Grafik Keaktifan User -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="mb-4 font-medium text-gray-800 text-md">Distribusi Keaktifan Pengguna</h4>
                                <div class="chart-container">
                                    <canvas id="userActivityChart"></canvas>
                                </div>
                            </div>

                            <!-- Grafik Aktivitas Bulanan Per Pengguna -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm lg:col-span-2">
                                <h4 class="mb-4 font-medium text-gray-800 text-md">Aktivitas Bulanan Per Pengguna</h4>
                                <div class="chart-container large">
                                    <canvas id="userMonthlyActivityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Ringkasan Dokumen -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Dokumen</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Total Dokumen</h4>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Disahkan</h4>
                                <p class="mt-2 text-3xl font-bold text-green-600">{{ $signedDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Diajukan</h4>
                                <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $pendingDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Direvisi</h4>
                                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $revisedDocuments }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik User -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Pengguna</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Ormawa</h4>
                                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $ormawasCount }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Dosen</h4>
                                <p class="mt-2 text-3xl font-bold text-red-600">{{ $dosenCount }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Staff Kemahasiswaan</h4>
                                <p class="mt-2 text-3xl font-bold text-purple-600">{{ $kemahasiswaanCount }}</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Total Pengguna</h4>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $ormawasCount + $dosenCount +
                                    $kemahasiswaanCount }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ormawa Paling Aktif -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Ormawa Paling Aktif</h3>
                        @if($mostActiveOrmawas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Mahasiswa</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Ormawa</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mostActiveOrmawas as $index => $ormawa)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $ormawa->namaMahasiswa }}</td>
                                        <td class="px-6 py-4">{{ $ormawa->namaOrmawa }}</td>
                                        <td class="px-6 py-4 text-center">{{ $ormawa->document_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Ormawa pada periode ini.</p>
                        @endif
                    </div>

                    <!-- Dosen Paling Aktif -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Dosen Paling Aktif</h3>
                        @if($mostActiveDosens->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Dosen</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mostActiveDosens as $index => $dosen)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $dosen->nama_dosen }}</td>
                                        <td class="px-6 py-4 text-center">{{ $dosen->document_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Dosen pada periode ini.</p>
                        @endif
                    </div>

                    <!-- Kemahasiswaan Paling Aktif -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Staff Kemahasiswaan Paling Aktif</h3>
                        @if(isset($mostActiveKemahasiswaan) && $mostActiveKemahasiswaan->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Staff</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mostActiveKemahasiswaan as $index => $staff)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $staff->nama_kemahasiswaan }}</td>
                                        <td class="px-6 py-4 text-center">{{ $staff->document_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Staff Kemahasiswaan pada periode ini.
                        </p>
                        @endif
                    </div>

                    <!-- Statistik Ormawa -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Berdasarkan Ormawa</h3>
                        @if($ormawaStats->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Ormawa</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($ormawaStats as $index => $stat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $stat->namaOrmawa }}</td>
                                        <td class="px-6 py-4 text-center">{{ $stat->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Ormawa pada periode ini.</p>
                        @endif
                    </div>

                    <!-- Statistik Dosen -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Berdasarkan Dosen</h3>
                        @if($dosenStats->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Dosen</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($dosenStats as $index => $stat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $stat->nama_dosen }}</td>
                                        <td class="px-6 py-4 text-center">{{ $stat->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Dosen pada periode ini.</p>
                        @endif
                    </div>

                    <!-- Statistik Kemahasiswaan -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Berdasarkan Staff Kemahasiswaan
                        </h3>
                        @if(isset($kemahasiswaanStats) && $kemahasiswaanStats->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-800">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Nama Staff</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Jumlah Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($kemahasiswaanStats as $index => $stat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $stat->nama_kemahasiswaan }}</td>
                                        <td class="px-6 py-4 text-center">{{ $stat->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Tidak ada data statistik Staff Kemahasiswaan pada periode ini.
                        </p>
                        @endif
                    </div>

                    <!-- Daftar Dokumen -->
                    <div>
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Daftar Dokumen</h3>

                        <!-- Dokumen Ormawa -->
                        <div class="mb-8">
                            <h4 class="mb-4 font-medium text-gray-800 text-md">Dokumen Ormawa</h4>
                            @if($dokumenOrmawa->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-800">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nomor Surat</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nama Ormawa</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Pengaju</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Diajukan Kepada</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Perihal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($dokumenOrmawa as $index => $doc)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">{{ $doc->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4">{{ $doc->nomor_surat }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaMahasiswa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">
                                                @if($doc->dosen)
                                                    {{ $doc->dosen->nama_dosen }} (Dosen)
                                                @elseif($doc->kemahasiswaan)
                                                    {{ $doc->kemahasiswaan->nama_kemahasiswaan }} (Kemahasiswaan)
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        {{ $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                                        ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                                        'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($doc->status_dokumen) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">Tidak ada dokumen Ormawa pada periode ini.</p>
                            @endif
                        </div>

                        <!-- Dokumen Dosen -->
                        <div class="mb-8">
                            <h4 class="mb-4 font-medium text-gray-800 text-md">Dokumen Dosen</h4>
                            @if($dokumenDosen->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-800">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nomor Surat</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nama Dosen</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Dari Ormawa</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nama Mahasiswa</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Perihal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($dokumenDosen as $index => $doc)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">{{ $doc->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4">{{ $doc->nomor_surat }}</td>
                                            <td class="px-6 py-4">{{ $doc->dosen?->nama_dosen ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaMahasiswa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        {{ $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                                        ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                                        'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($doc->status_dokumen) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">Tidak ada dokumen Dosen pada periode ini.</p>
                            @endif
                        </div>

                        <!-- Dokumen Kemahasiswaan -->
                        <div class="mb-8">
                            <h4 class="mb-4 font-medium text-gray-800 text-md">Dokumen Kemahasiswaan</h4>
                            @if($dokumenKemahasiswaan->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-800">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nomor Surat</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nama Staff</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Dari Ormawa</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nama Mahasiswa</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Perihal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($dokumenKemahasiswaan as $index => $doc)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">{{ $doc->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4">{{ $doc->nomor_surat }}</td>
                                            <td class="px-6 py-4">{{ $doc->kemahasiswaan?->nama_kemahasiswaan ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaMahasiswa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        {{ $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                                        ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                                        'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($doc->status_dokumen) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">Tidak ada dokumen Kemahasiswaan pada periode ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data untuk grafik
const monthlyChartData = @json($monthlyChartData);
const userActivityData = @json($userActivityData);
const userMonthlyActivityData = @json($userMonthlyActivityData);

// Konfigurasi warna untuk grafik bulanan status dokumen
const monthlyChartColors = {
    diajukan: {
        borderColor: '#FCD34D',
        backgroundColor: 'rgba(252, 211, 77, 0.2)'
    },
    disahkan: {
        borderColor: '#34D399',
        backgroundColor: 'rgba(52, 211, 153, 0.2)'
    },
    revisi: {
        borderColor: '#60A5FA',
        backgroundColor: 'rgba(96, 165, 250, 0.2)'
    }
};

// Konfigurasi warna untuk grafik aktivitas bulanan per pengguna
const userActivityColors = {
    ormawa: {
        borderColor: 'rgb(79, 70, 229)',
        backgroundColor: 'rgba(79, 70, 229, 0.2)'
    },
    dosen: {
        borderColor: 'rgb(239, 68, 68)',
        backgroundColor: 'rgba(239, 68, 68, 0.2)'
    },
    kemahasiswaan: {
        borderColor: 'rgb(147, 51, 234)',
        backgroundColor: 'rgba(147, 51, 234, 0.2)'
    }
};

// Inisialisasi grafik bulanan status dokumen
const monthlyChart = new Chart(
    document.getElementById('monthlyChart'),
    {
        type: 'line',
        data: {
            labels: monthlyChartData.labels,
            datasets: [
                {
                    label: 'Dokumen Diajukan',
                    data: monthlyChartData.datasets[0].data,
                    borderColor: monthlyChartColors.diajukan.borderColor,
                    backgroundColor: monthlyChartColors.diajukan.backgroundColor,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dokumen Disahkan',
                    data: monthlyChartData.datasets[1].data,
                    borderColor: monthlyChartColors.disahkan.borderColor,
                    backgroundColor: monthlyChartColors.disahkan.backgroundColor,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dokumen Revisi',
                    data: monthlyChartData.datasets[2].data,
                    borderColor: monthlyChartColors.revisi.borderColor,
                    backgroundColor: monthlyChartColors.revisi.backgroundColor,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 10,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' dokumen';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Dokumen'
                    }
                },
                x: {
                    grid: {
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: 'Periode'
                    }
                }
            }
        }
    }
);

// Inisialisasi grafik keaktifan user (doughnut)
const userActivityChart = new Chart(
    document.getElementById('userActivityChart'),
    {
        type: 'doughnut',
        data: {
            labels: userActivityData.labels,
            datasets: [{
                data: userActivityData.datasets[0].data,
                backgroundColor: [
                    'rgba(79, 70, 229, 0.8)',  // Indigo untuk Ormawa
                    'rgba(239, 68, 68, 0.8)',   // Red untuk Dosen
                    'rgba(147, 51, 234, 0.8)'   // Purple untuk Kemahasiswaan
                ],
                borderColor: [
                    'rgb(79, 70, 229)',
                    'rgb(239, 68, 68)',
                    'rgb(147, 51, 234)'
                ],
                borderWidth: 1,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    }
);

// Inisialisasi grafik aktivitas bulanan per pengguna
const userMonthlyActivityChart = new Chart(
    document.getElementById('userMonthlyActivityChart'),
    {
        type: 'line',
        data: {
            labels: userMonthlyActivityData.labels,
            datasets: [
                {
                    label: 'Aktivitas Ormawa',
                    data: userMonthlyActivityData.datasets[0].data,
                    borderColor: userActivityColors.ormawa.borderColor,
                    backgroundColor: userActivityColors.ormawa.backgroundColor,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Aktivitas Dosen',
                    data: userMonthlyActivityData.datasets[1].data,
                    borderColor: userActivityColors.dosen.borderColor,
                    backgroundColor: userActivityColors.dosen.backgroundColor,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Aktivitas Kemahasiswaan',
                    data: userMonthlyActivityData.datasets[2].data,
                    borderColor: userActivityColors.kemahasiswaan.borderColor,
                    backgroundColor: userActivityColors.kemahasiswaan.backgroundColor,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' aktivitas';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Aktivitas'
                    }
                },
                x: {
                    grid: {
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: 'Periode'
                    }
                }
            }
        }
    }
);
</script>

@endsection
