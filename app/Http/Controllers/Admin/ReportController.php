<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SubmissionRepository;

class ReportController extends Controller
{
    protected $submissionRepository;

    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Monthly stats for the selected year
        $monthlyData = $this->submissionRepository->getMonthlyStats($year)->keyBy('month');
            
        $monthlyStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStats[] = [
                'month' => date('F', mktime(0, 0, 0, $i, 1)),
                'count' => isset($monthlyData[$i]) ? $monthlyData[$i]->count : 0
            ];
        }

        // Status distribution for the selected year
        $statusData = $this->submissionRepository->getStatusStats($year)->pluck('count', 'current_status');
            
        $statusStats = [
            'Menunggu Verifikasi' => $statusData->get('Menunggu Verifikasi', 0),
            'Sedang Diproses' => $statusData->get('Sedang Diproses', 0),
            'Disetujui' => $statusData->get('Disetujui', 0),
            'Ditolak' => $statusData->get('Ditolak', 0),
        ];

        // List of available years
        $availableYears = $this->submissionRepository->getAvailableYears();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // Submissions filter query
        $query = \App\Models\Submission::query();

        if ($request->filled('university')) {
            $uni = $request->input('university');
            if ($uni === 'Lainnya') {
                $mainUnis = [
                    'Universitas Lampung',
                    'Universitas Bandar Lampung',
                    'Universitas Teknokrat Indonesia',
                    'Institut Informatika dan Bisnis Darmajaya',
                    'Universitas Mitra Indonesia'
                ];
                $query->whereNotIn('university', $mainUnis);
            } else {
                $query->where('university', $uni);
            }
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $submissions = $query->orderBy('created_at', 'desc')->get();

        // Calculate Ringkasan Laporan based on filtered submissions
        $summary = [
            'total' => $submissions->count(),
            'unila' => $submissions->where('university', 'Universitas Lampung')->count(),
            'ubl' => $submissions->where('university', 'Universitas Bandar Lampung')->count(),
            'teknokrat' => $submissions->where('university', 'Universitas Teknokrat Indonesia')->count(),
            'darmajaya' => $submissions->where('university', 'Institut Informatika dan Bisnis Darmajaya')->count(),
            'mitra' => $submissions->where('university', 'Universitas Mitra Indonesia')->count(),
            'lainnya' => $submissions->reject(function ($s) {
                return in_array($s->university, [
                    'Universitas Lampung',
                    'Universitas Bandar Lampung',
                    'Universitas Teknokrat Indonesia',
                    'Institut Informatika dan Bisnis Darmajaya',
                    'Universitas Mitra Indonesia'
                ]);
            })->count()
        ];

        $universities = \App\Models\University::orderBy('name')->get();

        return view('admin.reports.index', compact(
            'year', 'monthlyStats', 'statusStats', 'availableYears', 
            'submissions', 'summary', 'universities'
        ));
    }

    public function exportMonthly(Request $request)
    {
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));
        $format = $request->input('format', 'excel');
        $university = $request->input('university');

        $submissions = $this->submissionRepository->getSubmissionsByMonthAndYear($month, $year);

        if ($university) {
            if ($university === 'Lainnya') {
                $mainUnis = [
                    'Universitas Lampung',
                    'Universitas Bandar Lampung',
                    'Universitas Teknokrat Indonesia',
                    'Institut Informatika dan Bisnis Darmajaya',
                    'Universitas Mitra Indonesia'
                ];
                $submissions = $submissions->reject(function ($sub) use ($mainUnis) {
                    return in_array($sub->university, $mainUnis);
                });
            } else {
                $submissions = $submissions->where('university', $university);
            }
        }

        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthsIndo[$month] ?? date('F', mktime(0,0,0,$month,1));

        $stats = [
            'total' => $submissions->count(),
            'approved' => $submissions->where('current_status', 'Disetujui')->count(),
            'rejected' => $submissions->where('current_status', 'Ditolak')->count(),
            'processing' => $submissions->where('current_status', 'Sedang Diproses')->count(),
            'pending' => $submissions->where('current_status', 'Menunggu Verifikasi')->count(),
        ];

        if ($format === 'pdf') {
            return view('admin.reports.print_monthly', compact('submissions', 'month', 'year', 'monthName', 'stats'));
        }

        // Export Excel (CSV)
        $fileName = "Laporan_Bulanan_E-Riset_{$monthName}_{$year}.csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($submissions) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV headers
            fputcsv($file, ['No', 'Nomor Registrasi', 'Nama Pemohon', 'NIM', 'Universitas', 'Fakultas', 'Program Studi', 'Judul Penelitian', 'Status', 'Tanggal Pengajuan']);

            foreach ($submissions as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->registration_number,
                    $row->name,
                    $row->nim,
                    $row->university,
                    $row->faculty,
                    $row->study_program,
                    $row->title,
                    $row->current_status,
                    $row->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportYearly(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $format = $request->input('format', 'excel');
        $university = $request->input('university');

        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Gather statistics per month
        $monthlyStats = [];
        $totalStats = [
            'total' => 0,
            'approved' => 0,
            'rejected' => 0,
            'processing' => 0,
            'pending' => 0,
        ];

        for ($i = 1; $i <= 12; $i++) {
            $subs = $this->submissionRepository->getSubmissionsByMonthAndYear($i, $year);

            if ($university) {
                if ($university === 'Lainnya') {
                    $mainUnis = [
                        'Universitas Lampung',
                        'Universitas Bandar Lampung',
                        'Universitas Teknokrat Indonesia',
                        'Institut Informatika dan Bisnis Darmajaya',
                        'Universitas Mitra Indonesia'
                    ];
                    $subs = $subs->reject(function ($sub) use ($mainUnis) {
                        return in_array($sub->university, $mainUnis);
                    });
                } else {
                    $subs = $subs->where('university', $university);
                }
            }

            $count = $subs->count();
            $approved = $subs->where('current_status', 'Disetujui')->count();
            $rejected = $subs->where('current_status', 'Ditolak')->count();
            $processing = $subs->where('current_status', 'Sedang Diproses')->count();
            $pending = $subs->where('current_status', 'Menunggu Verifikasi')->count();

            $monthlyStats[$i] = [
                'month' => $monthsIndo[$i],
                'total' => $count,
                'approved' => $approved,
                'rejected' => $rejected,
                'processing' => $processing,
                'pending' => $pending
            ];

            $totalStats['total'] += $count;
            $totalStats['approved'] += $approved;
            $totalStats['rejected'] += $rejected;
            $totalStats['processing'] += $processing;
            $totalStats['pending'] += $pending;
        }

        if ($format === 'pdf') {
            return view('admin.reports.print_yearly', compact('monthlyStats', 'totalStats', 'year'));
        }

        // Export Excel (CSV)
        $fileName = "Laporan_Tahunan_E-Riset_{$year}.csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($monthlyStats, $totalStats) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV headers
            fputcsv($file, ['Bulan', 'Total Permohonan', 'Disetujui', 'Ditolak', 'Sedang Diproses', 'Menunggu Verifikasi']);

            foreach ($monthlyStats as $row) {
                fputcsv($file, [
                    $row['month'],
                    $row['total'],
                    $row['approved'],
                    $row['rejected'],
                    $row['processing'],
                    $row['pending']
                ]);
            }

            // Total row
            fputcsv($file, []);
            fputcsv($file, [
                'TOTAL',
                $totalStats['total'],
                $totalStats['approved'],
                $totalStats['rejected'],
                $totalStats['processing'],
                $totalStats['pending']
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
