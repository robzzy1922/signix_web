<?php

namespace App\Http\Controllers\Admin\Auth;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Dokumen;
use App\Models\Ormawas;
use Illuminate\Http\Request;
use App\Models\Kemahasiswaan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\ChartGenerator;

class AdminDokumenController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $dokumens = Dokumen::query()
            ->when($request->search, function($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%")
                ->orWhere('judul_dokumen', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status_dokumen', $status);
            })
            ->paginate($perPage);

        return view('admin.dokumen.index', compact('dokumens'));
    }

    // Form untuk menampilkan halaman generate report
    public function showReportForm()
    {
        return view('admin.dokumen.report_form');
    }

    public function weeklyReport(Request $request)
    {
        // Mendapatkan tanggal awal dan akhir
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(7);

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }

        // Mengambil data dokumen dalam rentang waktu
        $dokumens = Dokumen::whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Pisahkan dokumen berdasarkan sumbernya
        $dokumenOrmawa = $dokumens->filter(function($doc) {
            return !empty($doc->id_ormawa);
        });

        $dokumenDosen = $dokumens->filter(function($doc) {
            return !empty($doc->id_dosen);
        });

        $dokumenKemahasiswaan = $dokumens->filter(function($doc) {
            return !empty($doc->id_kemahasiswaan);
        });

        // Menghitung statistik dokumen
        $totalDocuments = $dokumens->count();
        $signedDocuments = $dokumens->where('status_dokumen', 'disahkan')->count();
        $pendingDocuments = $dokumens->where('status_dokumen', 'diajukan')->count();
        $revisedDocuments = $dokumens->where('status_dokumen', 'direvisi')->count();

        // Statistik berdasarkan ormawa
        $ormawaStats = DB::table('dokumens')
                        ->join('ormawas', 'dokumens.id_ormawa', '=', 'ormawas.id')
                        ->whereBetween('dokumens.created_at', [$startDate, $endDate])
                        ->select('ormawas.namaOrmawa', DB::raw('count(*) as total'))
                        ->groupBy('ormawas.namaOrmawa')
                        ->get();

        // Statistik berdasarkan dosen
        $dosenStats = DB::table('dokumens')
                        ->join('dosen', 'dokumens.id_dosen', '=', 'dosen.id')
                        ->whereBetween('dokumens.created_at', [$startDate, $endDate])
                        ->select('dosen.nama_dosen', DB::raw('count(*) as total'))
                        ->groupBy('dosen.nama_dosen')
                        ->get();

        // Statistik kemahasiswaan
        $kemahasiswaanStats = DB::table('dokumens')
                        ->join('kemahasiswaan', 'dokumens.id_kemahasiswaan', '=', 'kemahasiswaan.id')
                        ->whereBetween('dokumens.created_at', [$startDate, $endDate])
                        ->select('kemahasiswaan.nama_kemahasiswaan', DB::raw('count(*) as total'))
                        ->groupBy('kemahasiswaan.nama_kemahasiswaan')
                        ->get();

        // Statistik semua user
        $ormawasCount = Ormawas::count();
        $dosenCount = Dosen::count();
        $kemahasiswaanCount = Kemahasiswaan::count();

        // Get most active users
        $mostActiveOrmawas = DB::table('ormawas')
                        ->join('dokumens', 'ormawas.id', '=', 'dokumens.id_ormawa')
                        ->select('ormawas.namaMahasiswa', 'ormawas.namaOrmawa', DB::raw('count(*) as document_count'))
                        ->groupBy('ormawas.id', 'ormawas.namaMahasiswa', 'ormawas.namaOrmawa')
                        ->orderBy('document_count', 'desc')
                        ->limit(5)
                        ->get();

        $mostActiveDosens = DB::table('dosen')
                        ->join('dokumens', 'dosen.id', '=', 'dokumens.id_dosen')
                        ->select('dosen.nama_dosen', DB::raw('count(*) as document_count'))
                        ->groupBy('dosen.id', 'dosen.nama_dosen')
                        ->orderBy('document_count', 'desc')
                        ->limit(5)
                        ->get();

        $mostActiveKemahasiswaan = DB::table('kemahasiswaan')
                        ->join('dokumens', 'kemahasiswaan.id', '=', 'dokumens.id_kemahasiswaan')
                        ->select('kemahasiswaan.nama_kemahasiswaan', DB::raw('count(*) as document_count'))
                        ->groupBy('kemahasiswaan.id', 'kemahasiswaan.nama_kemahasiswaan')
                        ->orderBy('document_count', 'desc')
                        ->limit(5)
                        ->get();

        // Prepare monthly statistics data for charts
        $monthlyStats = DB::table('dokumens')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(CASE WHEN status_dokumen = "diajukan" THEN 1 END) as total_diajukan'),
                DB::raw('COUNT(CASE WHEN status_dokumen = "disahkan" THEN 1 END) as total_disahkan'),
                DB::raw('COUNT(CASE WHEN status_dokumen = "direvisi" OR status_dokumen = "butuh_revisi" THEN 1 END) as total_revisi')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Get user activity data from database
        $ormawaActivity = DB::table('dokumens')
            ->join('ormawas', 'dokumens.id_ormawa', '=', 'ormawas.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->count();

        $dosenActivity = DB::table('dokumens')
            ->join('dosen', 'dokumens.id_dosen', '=', 'dosen.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->count();

        $kemahasiswaanActivity = DB::table('dokumens')
            ->join('kemahasiswaan', 'dokumens.id_kemahasiswaan', '=', 'kemahasiswaan.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->count();

        // Get detailed monthly activity for each user type
        $ormawaMonthly = DB::table('dokumens')
            ->join('ormawas', 'dokumens.id_ormawa', '=', 'ormawas.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(dokumens.created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $dosenMonthly = DB::table('dokumens')
            ->join('dosen', 'dokumens.id_dosen', '=', 'dosen.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(dokumens.created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $kemahasiswaanMonthly = DB::table('dokumens')
            ->join('kemahasiswaan', 'dokumens.id_kemahasiswaan', '=', 'kemahasiswaan.id')
            ->whereBetween('dokumens.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(dokumens.created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare user activity data for chart
        $userActivityData = [
            'labels' => ['Ormawa', 'Dosen', 'Kemahasiswaan'],
            'datasets' => [
                [
                    'data' => [$ormawaActivity, $dosenActivity, $kemahasiswaanActivity]
                ]
            ]
        ];

        // Format monthly stats for charts
        $monthlyChartData = [
            'labels' => $monthlyStats->pluck('month')->map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }),
            'datasets' => [
                [
                    'label' => 'Dokumen Diajukan',
                    'data' => $monthlyStats->pluck('total_diajukan'),
                ],
                [
                    'label' => 'Dokumen Disahkan',
                    'data' => $monthlyStats->pluck('total_disahkan'),
                ],
                [
                    'label' => 'Dokumen Revisi',
                    'data' => $monthlyStats->pluck('total_revisi'),
                ]
            ]
        ];

        // Prepare monthly activity data for each user type
        $userMonthlyActivityData = [
            'labels' => $monthlyStats->pluck('month')->map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('F Y');
            }),
            'datasets' => [
                [
                    'label' => 'Aktivitas Ormawa',
                    'data' => $ormawaMonthly->pluck('total'),
                ],
                [
                    'label' => 'Aktivitas Dosen',
                    'data' => $dosenMonthly->pluck('total'),
                ],
                [
                    'label' => 'Aktivitas Kemahasiswaan',
                    'data' => $kemahasiswaanMonthly->pluck('total'),
                ]
            ]
        ];

        // Jika request adalah untuk preview, tampilkan halaman preview
        if ($request->has('preview')) {
            return view('admin.dokumen.report_preview', compact(
                'dokumens',
                'dokumenOrmawa',
                'dokumenDosen',
                'dokumenKemahasiswaan',
                'startDate',
                'endDate',
                'totalDocuments',
                'signedDocuments',
                'pendingDocuments',
                'revisedDocuments',
                'ormawaStats',
                'dosenStats',
                'kemahasiswaanStats',
                'ormawasCount',
                'dosenCount',
                'kemahasiswaanCount',
                'mostActiveOrmawas',
                'mostActiveDosens',
                'mostActiveKemahasiswaan',
                'monthlyChartData',
                'userActivityData',
                'userMonthlyActivityData'
            ));
        }

        try {
            // Gunakan PDF facade dengan benar
            $pdf = Pdf::loadView('admin.dokumen.report_pdf', compact(
                'dokumens',
                'dokumenOrmawa',
                'dokumenDosen',
                'dokumenKemahasiswaan',
                'startDate',
                'endDate',
                'totalDocuments',
                'signedDocuments',
                'pendingDocuments',
                'revisedDocuments',
                'ormawaStats',
                'dosenStats',
                'kemahasiswaanStats',
                'ormawasCount',
                'dosenCount',
                'kemahasiswaanCount',
                'mostActiveOrmawas',
                'mostActiveDosens',
                'mostActiveKemahasiswaan',
                'monthlyChartData',
                'userActivityData',
                'userMonthlyActivityData'
            ));

            // Set options untuk PDF
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'margin-top' => 10,
                'margin-right' => 10,
                'margin-bottom' => 10,
                'margin-left' => 10,
                'dpi' => 150,
                'enable-javascript' => false,
                'no-stop-slow-scripts' => false,
                'enable-smart-shrinking' => true
            ]);

            // Set paper size dan orientasi
            $pdf->setPaper('A4', 'portrait');

            // Download PDF
            return $pdf->download('laporan-dokumen-' . $startDate->format('d-m-Y') . '-sampai-' . $endDate->format('d-m-Y') . '.pdf');
        } catch (\Exception $e) {
            // Debug error
            return back()->with('error', 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            // Add other validation rules as needed
        ]);

        // Handle file upload and document creation
        $dokumen = new Dokumen();
        // Add document creation logic

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function edit(Dokumen $dokumen)
    {

    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // Add other validation rules as needed
        ]);

        // Handle file upload and document update
        // Add document update logic

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    public function destroy(Dokumen $dokumen)
    {
        // Add document deletion logic
        $dokumen->delete();

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['ormawa', 'dosen'])->findOrFail($id);

        // Return data as JSON for AJAX request
        return response()->json([
            'id' => $dokumen->id,
            'nomor_surat' => $dokumen->nomor_surat,
            'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
            'perihal' => $dokumen->perihal,
            'file' => $dokumen->file, // Path ke file dokumen
            'status_dokumen' => $dokumen->status_dokumen,
            'keterangan' => $dokumen->keterangan,
            'ormawa' => [
                'nama' => $dokumen->ormawa->namaMahasiswa ?? 'N/A',
                'namaOrmawa' => $dokumen->ormawa->namaOrmawa ?? 'N/A'
            ],
            'dosen' => [
                'nama' => $dokumen->dosen->nama_dosen ?? 'N/A'
            ]
        ]);
    }

    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $filePath = storage_path('app/public/' . $dokumen->file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        return response()->download($filePath);
    }

    public function view($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $filePath = storage_path('app/public/' . $dokumen->file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        // Return file for viewing in browser
        return response()->file($filePath);
    }
}
