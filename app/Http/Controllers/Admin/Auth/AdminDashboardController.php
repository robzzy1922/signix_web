<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Ormawas;
use App\Models\Dosen;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Mengambil statistik dokumen
        $diajukanDocuments = Dokumen::where('status_dokumen', 'diajukan')->count();
        $disahkanDocuments = Dokumen::where('status_dokumen', 'disahkan')->count();
        $direvisiDocuments = Dokumen::where('status_dokumen', 'direvisi')->count();

        // Mengambil data user terbaru (Ormawa dan Dosen)
        $recentUsers = collect()
            ->merge(Ormawas::latest()->take(3)->get()->map(function ($item) {
                return [
                    'name' => $item->namaMahasiswa,
                    'email' => $item->email,
                    'role' => 'Ormawa',
                    'created_at' => $item->created_at
                ];
            }))
            ->merge(Dosen::latest()->take(3)->get()->map(function ($item) {
                return [
                    'name' => $item->nama_dosen,
                    'email' => $item->email,
                    'role' => 'Dosen',
                    'created_at' => $item->created_at
                ];
            }))
            ->sortByDesc('created_at')
            ->take(5);

        // Mengambil aktivitas dokumen terbaru
        $recentActivities = Dokumen::with(['ormawa', 'dosen'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.adminDashboard', compact(
            'diajukanDocuments',
            'disahkanDocuments',
            'direvisiDocuments',
            'recentUsers',
            'recentActivities'
        ));
    }
}