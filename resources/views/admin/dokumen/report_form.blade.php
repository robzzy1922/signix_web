@extends('layouts.admin.app')

@section('title', 'Generate Laporan')

@section('content')
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full py-2">
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Generate Laporan Mingguan Dokumen</h2>
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('admin.dokumen.generate-report') }}">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ Carbon\Carbon::now()->subDays(7)->format('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div>
                                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal
                                    Akhir</label>
                                <input type="date" id="end_date" name="end_date"
                                    value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-between mt-6 space-x-4">
                            <!-- Preview Button -->
                            <button type="submit" name="preview" value="1"
                                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Preview Laporan
                            </button>

                            <!-- Download Button -->
                            <button type="submit"
                                class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection