<?php

namespace App\Services;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Kitchen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Get complaint statistics for a given period.
     */
    public function getComplaintStats(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Complaint::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $total = (clone $query)->count();
        $pending = (clone $query)->where('status', ComplaintStatus::PENDING)->count();
        $received = (clone $query)->where('status', ComplaintStatus::RECEIVED)->count();
        $inProgress = (clone $query)->where('status', ComplaintStatus::IN_PROGRESS)->count();
        $resolved = (clone $query)->where('status', ComplaintStatus::RESOLVED)->count();
        $rejected = (clone $query)->where('status', ComplaintStatus::REJECTED)->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'received' => $received,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'rejected' => $rejected,
            'unresolved' => $pending + $received + $inProgress,
        ];
    }

    /**
     * Get monthly complaint data for charts.
     */
    public function getMonthlyData(int $year = null): Collection
    {
        $year = $year ?? now()->year;

        return Complaint::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->total];
            });
    }

    /**
     * Get complaints grouped by category.
     */
    public function getByCategory(): Collection
    {
        return ComplaintCategory::withCount('complaints')
            ->where('is_active', true)
            ->orderByDesc('complaints_count')
            ->get();
    }

    /**
     * Get complaints grouped by kitchen.
     */
    public function getByKitchen(): Collection
    {
        return Kitchen::withCount('complaints')
            ->orderByDesc('complaints_count')
            ->get();
    }

    /**
     * Get average resolution time in hours.
     */
    public function getAverageResolutionTime(): float
    {
        $avg = Complaint::where('status', ComplaintStatus::RESOLVED)
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->value('avg_hours');

        return round($avg ?? 0, 1);
    }
}
