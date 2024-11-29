<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah dokumen berdasarkan status
        $diajukanDocuments = Dokumen::where('status_dokumen', 'diajukan')->count();
        $disahkanDocuments = Dokumen::where('status_dokumen', 'disahkan')->count();
        $direvisiDocuments = Dokumen::where('status_dokumen', 'direvisi')->count();

        return view('admin.adminDashboard', compact(
            'diajukanDocuments',
            'disahkanDocuments',
            'direvisiDocuments'
        ));
    }
}