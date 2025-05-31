@extends('layouts.admin.app')

@section('title', 'Preview Laporan')

@section('content')
<!-- Breadcrumb -->
<div class="mb-4">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.adminDashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
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
        <div class="inline-block min-w-full py-2">
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
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

                    <!-- Statistik Ringkasan Dokumen -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Dokumen</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Total Dokumen</h4>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Disahkan</h4>
                                <p class="mt-2 text-3xl font-bold text-green-600">{{ $signedDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Diajukan</h4>
                                <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $pendingDocuments }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Dokumen Direvisi</h4>
                                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $revisedDocuments }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik User -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistik Pengguna</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Ormawa</h4>
                                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $ormawasCount }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Dosen</h4>
                                <p class="mt-2 text-3xl font-bold text-red-600">{{ $dosenCount }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-medium text-gray-500">Jumlah Staff Kemahasiswaan</h4>
                                <p class="mt-2 text-3xl font-bold text-purple-600">{{ $kemahasiswaanCount }}</p>
                            </div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
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
                        <p class="mb-4 text-sm text-gray-600">Periode: {{ $startDate->format('d F Y') }} - {{
                            $endDate->format('d F Y') }}</p>

                        <!-- Dokumen yang Ditujukan ke Dosen -->
                        <div class="mb-8">
                            <h4 class="mb-3 font-medium text-gray-800 text-md">Dokumen yang Ditujukan ke Dosen</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-800">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nomor Surat</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Pengaju</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Dosen Penerima</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Perihal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($dosenDocs as $index => $doc)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">{{ $doc->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4">{{ $doc->nomor_surat }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->dosen?->nama_dosen ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{
                                                    $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                                    ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                                    ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                                    'bg-red-100 text-red-800'))
                                                }}">
                                                    {{ ucfirst($doc->status_dokumen) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                Tidak ada dokumen yang ditujukan ke dosen pada periode ini
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Dokumen yang Ditujukan ke Kemahasiswaan -->
                        <div class="mb-8">
                            <h4 class="mb-3 font-medium text-gray-800 text-md">Dokumen yang Ditujukan ke Kemahasiswaan
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-800">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Nomor Surat</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Pengaju</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Staff Penerima</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Perihal</th>
                                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($kemahasiswaanDocs as $index => $doc)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">{{ $doc->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4">{{ $doc->nomor_surat }}</td>
                                            <td class="px-6 py-4">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $doc->kemahasiswaan?->nama_kemahasiswaan ?? 'N/A'
                                                }}</td>
                                            <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{
                                                    $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                                    ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                                    ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                                    'bg-red-100 text-red-800'))
                                                }}">
                                                    {{ ucfirst($doc->status_dokumen) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                Tidak ada dokumen yang ditujukan ke kemahasiswaan pada periode ini
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection