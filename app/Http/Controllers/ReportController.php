<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));
        $export = $request->query('export', 'json'); // json, csv

        $query = Submission::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month);

        $total = $query->count();
        $approved = (clone $query)->where('current_status', 'Disetujui')->count();
        $rejected = (clone $query)->where('current_status', 'Ditolak')->count();
        $processing = (clone $query)->whereIn('current_status', ['Sedang Diproses', 'Menunggu Verifikasi'])->count();

        $data = [
            'period' => "{$month}/{$year}",
            'total' => $total,
            'approved' => $approved,
            'rejected' => $rejected,
            'processing' => $processing,
        ];

        if ($export === 'csv') {
            return $this->exportCsv("Laporan_Bulanan_{$month}_{$year}.csv", [$data]);
        }

        return response()->json($data);
    }

    public function annual(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $export = $request->query('export', 'json'); // json, csv

        $query = Submission::whereYear('created_at', $year);

        $total = $query->count();
        $approved = (clone $query)->where('current_status', 'Disetujui')->count();
        $rejected = (clone $query)->where('current_status', 'Ditolak')->count();
        $processing = (clone $query)->whereIn('current_status', ['Sedang Diproses', 'Menunggu Verifikasi'])->count();

        // Also get monthly breakdown for graph
        $breakdown = [];
        for ($i = 1; $i <= 12; $i++) {
            $mQuery = Submission::whereYear('created_at', $year)->whereMonth('created_at', $i);
            $breakdown[] = [
                'month' => $i,
                'total' => $mQuery->count(),
                'approved' => (clone $mQuery)->where('current_status', 'Disetujui')->count(),
                'rejected' => (clone $mQuery)->where('current_status', 'Ditolak')->count(),
                'processing' => (clone $mQuery)->whereIn('current_status', ['Sedang Diproses', 'Menunggu Verifikasi'])->count(),
            ];
        }

        $data = [
            'period' => $year,
            'total' => $total,
            'approved' => $approved,
            'rejected' => $rejected,
            'processing' => $processing,
            'breakdown' => $breakdown
        ];

        if ($export === 'csv') {
            $csvData = array_merge([['Bulan', 'Total', 'Disetujui', 'Ditolak', 'Diproses']], array_map(function($item) {
                return [$item['month'], $item['total'], $item['approved'], $item['rejected'], $item['processing']];
            }, $breakdown));
            // Add summary row
            $csvData[] = ['Total', $total, $approved, $rejected, $processing];
            return $this->exportCsv("Laporan_Tahunan_{$year}.csv", $csvData, true);
        }

        return response()->json($data);
    }

    private function exportCsv($filename, $data, $isArray = false)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($data, $isArray) {
            $file = fopen('php://output', 'w');
            
            if (!$isArray && !empty($data)) {
                fputcsv($file, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($file, array_values($row));
                }
            } else if ($isArray) {
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
