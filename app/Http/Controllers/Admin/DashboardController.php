<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\School;
use App\Models\Suggestion;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    /**
     * Show admin dashboard.
     */
    public function index(): View
    {
        $stats = $this->reportService->getComplaintStats();
        $monthlyData = $this->reportService->getMonthlyData();
        $byCategory = $this->reportService->getByCategory();
        $byKitchen = $this->reportService->getByKitchen();
        $avgResolution = $this->reportService->getAverageResolutionTime();

        $totalSchools = School::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalSuggestions = Suggestion::count();
        $unreadSuggestions = Suggestion::unread()->count();

        $recentComplaints = Complaint::with(['user.school', 'kitchen', 'category'])
            ->latest()
            ->limit(3)
            ->get();

        // Prepare chart data
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData->get($i, 0);
        }

        return view('admin.dashboard', compact(
            'stats',
            'monthlyData',
            'byCategory',
            'byKitchen',
            'avgResolution',
            'totalSchools',
            'totalUsers',
            'totalSuggestions',
            'unreadSuggestions',
            'recentComplaints',
            'months',
            'chartData'
        ));
    }
}
