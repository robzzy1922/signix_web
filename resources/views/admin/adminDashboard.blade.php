@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-[#fd7e14] text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold">30</h2>
                    <p class="text-white/80">Surat yang diajukan</p>
                </div>
            </div>
        </div>

        <div class="bg-[#28a745] text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold">10</h2>
                    <p class="text-white/80">Surat sudah tertanda</p>
                </div>
            </div>
        </div>

        <div class="bg-[#4e73df] text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold">1</h2>
                    <p class="text-white/80">Surat perlu direvisi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Data -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 flex justify-between items-center border-b">
                <h2 class="text-xl font-semibold">Data User</h2>
                <a href="#" class="text-blue-500 hover:text-blue-600">Lihat Semua</a>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-4">Nama</th>
                            <th class="pb-4">Email</th>
                            <th class="pb-4">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2">John Doe</td>
                            <td class="py-2">john@example.com</td>
                            <td class="py-2">Admin</td>
                        </tr>
                        <tr>
                            <td class="py-2">Jane Smith</td>
                            <td class="py-2">jane@example.com</td>
                            <td class="py-2">User</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 flex justify-between items-center border-b">
                <h2 class="text-xl font-semibold">Aktivitas Terbaru</h2>
                <a href="#" class="text-blue-500 hover:text-blue-600">Lihat Semua</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-800">Surat baru ditambahkan</p>
                            <p class="text-sm text-gray-500">2 menit yang lalu</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-800">Surat telah ditandatangani</p>
                            <p class="text-sm text-gray-500">5 menit yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
