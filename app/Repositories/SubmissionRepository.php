<?php

namespace App\Repositories;

use App\Models\Submission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubmissionRepository
{
    public function findOrFail(int $id, array $relations = []): Submission
    {
        return Submission::with($relations)->findOrFail($id);
    }

    public function findByRegistrationNumber(string $registrationNumber, array $relations = []): ?Submission
    {
        return Submission::with($relations)
            ->where('registration_number', $registrationNumber)
            ->first();
    }

    public function findByRegistrationNumberWithSortedLogs(string $registrationNumber): ?Submission
    {
        return Submission::where('registration_number', $registrationNumber)
            ->with(['statusLogs' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'documents'])
            ->first();
    }

    public function countAll(): int
    {
        return Submission::count();
    }

    public function countByStatus(string $status): int
    {
        return Submission::where('current_status', $status)->count();
    }

    public function countInStatuses(array $statuses): int
    {
        return Submission::whereIn('current_status', $statuses)->count();
    }

    public function recent(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Submission::orderBy('created_at', 'desc')->take($limit)->get();
    }

    public function getSubmissionsPaginated(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Submission::query();

        if (!empty($filters['status'])) {
            $query->where('current_status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('university', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getMonthlyStats(int $year): \Illuminate\Database\Eloquent\Collection
    {
        return Submission::selectRaw('MONTH(created_at) as month, count(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getStatusStats(int $year): \Illuminate\Database\Eloquent\Collection
    {
        return Submission::selectRaw('current_status, count(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('current_status')
            ->get();
    }

    public function getAvailableYears(): Collection
    {
        return Submission::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');
    }
    
    public function getSubmissionsByMonthAndYear(int $month, int $year): \Illuminate\Database\Eloquent\Collection
    {
        return Submission::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
