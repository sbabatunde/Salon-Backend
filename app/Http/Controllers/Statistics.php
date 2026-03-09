<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Account;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Style;
use App\Models\Blog;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Statistics extends Controller
{
    // Main dashboard statistics with date range support
    public function dashboardStatistics(Request $request)
    {
        try {
            // Get date range from request
            $dateRange = $this->getDateRange($request);
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
            $previousPeriod = $this->getPreviousPeriod($startDate, $endDate, $request->period);

            $today = Carbon::today();

            // Appointment Statistics with date range
            $totalAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();
            $todayAppointments = Appointment::whereDate('date', $today)->count();
            $pendingAppointments = Appointment::where('status', 'Pending')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $confirmedAppointments = Appointment::where('status', 'Confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $completedAppointments = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Financial Statistics with date range
            $totalRevenue = Account::whereBetween('created_at', [$startDate, $endDate])->sum('amount_paid') ?? 0;
            $monthlyRevenue = Account::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount_paid') ?? 0;
            $totalProfit = Account::whereBetween('created_at', [$startDate, $endDate])->sum('profit') ?? 0;

            // Client Statistics with date range
            $totalClients = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $newClientsThisMonth = Appointment::where('status', 'Completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
            $returningClients = $this->calculateReturningClients($startDate, $endDate);

            // Service Statistics with date range
            $popularServices = $this->getPopularServices($startDate, $endDate);
            $serviceRevenue = $this->getServiceRevenue($startDate, $endDate);

            // Inventory Statistics (no date filter needed)
            $lowStockItems = Inventory::where('stock', '<', 5)->count();
            $totalInventoryValue = Inventory::sum(DB::raw('stock * price')) ?? 0;

            // Reviews Statistics with date range
            $averageRating = Testimonial::where('submitted', true)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('rating') ?? 0;
            $totalReviews = Testimonial::where('submitted', true)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Growth Statistics (compare with previous period)
            $revenueGrowth = $this->calculateRevenueGrowth($startDate, $endDate, $previousPeriod);
            $clientGrowth = $this->calculateClientGrowth($startDate, $endDate, $previousPeriod);
            $appointmentGrowth = $this->calculateAppointmentGrowth($startDate, $endDate, $previousPeriod);

            // Projected revenue based on trends
            $projectedRevenue = $this->calculateProjectedRevenue($startDate, $endDate, $totalRevenue);

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => [
                        'total_appointments' => $totalAppointments,
                        'today_appointments' => $todayAppointments,
                        'pending_appointments' => $pendingAppointments,
                        'confirmed_appointments' => $confirmedAppointments,
                        'completed_appointments' => $completedAppointments,
                        'total_revenue' => $totalRevenue,
                        'monthly_revenue' => $monthlyRevenue,
                        'total_profit' => $totalProfit,
                        'average_rating' => round($averageRating, 1),
                        'total_reviews' => $totalReviews,
                        'projected_revenue' => $projectedRevenue,
                    ],
                    'clients' => [
                        'total_clients' => $totalClients,
                        'new_clients_this_month' => $newClientsThisMonth,
                        'returning_clients' => $returningClients,
                        'client_growth_rate' => $clientGrowth,
                    ],
                    'services' => [
                        'popular_services' => $popularServices,
                        'service_revenue' => $serviceRevenue,
                        'most_profitable_service' => $this->getMostProfitableService($startDate, $endDate),
                    ],
                    'inventory' => [
                        'low_stock_items' => $lowStockItems,
                        'total_inventory_value' => $totalInventoryValue,
                        'fast_moving_items' => $this->getFastMovingItems(),
                    ],
                    'growth' => [
                        'revenue_growth_rate' => $revenueGrowth,
                        'appointment_growth_rate' => $appointmentGrowth,
                        'profit_margin' => $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 2) : 0,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard statistics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get revenue chart data with full date range support
    public function revenueChartData(Request $request)
    {
        try {
            $period = $request->input('period', 'monthly');
            $startDate = $request->input('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(6);
            $endDate = $request->input('end_date') ? Carbon::parse($request->end_date) : Carbon::now();

            $data = [];

            // Get all accounts in date range
            $accounts = Account::whereBetween('created_at', [$startDate, $endDate])
                ->get(['created_at', 'amount_paid', 'profit']);

            $daysDiff = $startDate->diffInDays($endDate);

            if ($daysDiff <= 7 || $period === 'daily') {
                // Daily grouping
                $grouped = $accounts->groupBy(function ($account) {
                    return Carbon::parse($account->created_at)->format('Y-m-d');
                });

                foreach ($grouped as $date => $items) {
                    $carbonDate = Carbon::parse($date);
                    $revenue = $items->sum('amount_paid');
                    $profit = $items->sum('profit');

                    $data[] = [
                        'date' => $carbonDate->format('M d'),
                        'revenue' => $revenue,
                        'profit' => $profit,
                        'day' => $carbonDate->format('D')
                    ];
                }

                // Sort by date
                usort($data, function ($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });
            } elseif ($daysDiff <= 31 || $period === 'weekly') {
                // Weekly grouping
                $grouped = $accounts->groupBy(function ($account) {
                    $date = Carbon::parse($account->created_at);
                    return $date->year . '-W' . $date->weekOfYear;
                });

                foreach ($grouped as $weekKey => $items) {
                    preg_match('/(\d+)-W(\d+)/', $weekKey, $matches);
                    $year = $matches[1];
                    $week = $matches[2];

                    $weekStart = Carbon::now()->setISODate($year, $week)->startOfWeek();
                    $weekEnd = Carbon::now()->setISODate($year, $week)->endOfWeek();

                    $revenue = $items->sum('amount_paid');

                    $data[] = [
                        'week' => 'Week ' . $week,
                        'revenue' => $revenue,
                        'start_date' => $weekStart->format('M d'),
                        'end_date' => $weekEnd->format('M d')
                    ];
                }

                // Sort by week
                usort($data, function ($a, $b) {
                    return $a['week'] <=> $b['week'];
                });
            } elseif ($daysDiff <= 365 || $period === 'monthly') {
                // Monthly grouping
                $grouped = $accounts->groupBy(function ($account) {
                    return Carbon::parse($account->created_at)->format('Y-m');
                });

                foreach ($grouped as $yearMonth => $items) {
                    $carbonDate = Carbon::parse($yearMonth . '-01');
                    $revenue = $items->sum('amount_paid');
                    $profit = $items->sum('profit');

                    $data[] = [
                        'month' => $carbonDate->format('M Y'),
                        'revenue' => $revenue,
                        'profit' => $profit
                    ];
                }

                // Sort by date
                usort($data, function ($a, $b) {
                    return strtotime($a['month']) - strtotime($b['month']);
                });
            } else {
                // Yearly grouping
                $grouped = $accounts->groupBy(function ($account) {
                    return Carbon::parse($account->created_at)->year;
                });

                foreach ($grouped as $year => $items) {
                    $revenue = $items->sum('amount_paid');
                    $profit = $items->sum('profit');

                    $data[] = [
                        'year' => $year,
                        'revenue' => $revenue,
                        'profit' => $profit
                    ];
                }

                // Sort by year
                ksort($data);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Revenue chart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch revenue data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get appointment trends with date range
    public function appointmentTrends(Request $request)
    {
        try {
            $startDate = $request->input('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(6);
            $endDate = $request->input('end_date') ? Carbon::parse($request->end_date) : Carbon::now();
            $period = $request->input('period', 'monthly');

            $trends = [];

            // Get all appointments in date range
            $appointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->get(['created_at', 'status']);

            // Group by appropriate interval based on date range length
            $daysDiff = $startDate->diffInDays($endDate);

            if ($daysDiff <= 7 || $period === 'daily') {
                // Daily grouping
                $grouped = $appointments->groupBy(function ($appointment) {
                    return Carbon::parse($appointment->created_at)->format('Y-m-d');
                });

                foreach ($grouped as $date => $items) {
                    $carbonDate = Carbon::parse($date);
                    $total = $items->count();
                    $completed = $items->where('status', 'Completed')->count();
                    $cancelled = $items->where('status', 'Cancelled')->count();

                    $trends[] = [
                        'date' => $carbonDate->format('M d'),
                        'full_date' => $date,
                        'total' => $total,
                        'completed' => $completed,
                        'cancelled' => $cancelled,
                        'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
                    ];
                }

                // Sort by date
                usort($trends, function ($a, $b) {
                    return strtotime($a['full_date']) - strtotime($b['full_date']);
                });
            } elseif ($daysDiff <= 93 || $period === 'weekly') {
                // Weekly grouping
                $grouped = $appointments->groupBy(function ($appointment) {
                    $date = Carbon::parse($appointment->created_at);
                    return $date->year . '-W' . $date->weekOfYear;
                });

                foreach ($grouped as $weekKey => $items) {
                    preg_match('/(\d+)-W(\d+)/', $weekKey, $matches);
                    $year = $matches[1];
                    $week = $matches[2];

                    $weekStart = Carbon::now()->setISODate($year, $week)->startOfWeek();
                    $weekEnd = Carbon::now()->setISODate($year, $week)->endOfWeek();

                    $total = $items->count();
                    $completed = $items->where('status', 'Completed')->count();
                    $cancelled = $items->where('status', 'Cancelled')->count();

                    $trends[] = [
                        'week' => 'Week ' . $week,
                        'start_date' => $weekStart->format('M d'),
                        'end_date' => $weekEnd->format('M d'),
                        'total' => $total,
                        'completed' => $completed,
                        'cancelled' => $cancelled,
                        'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
                    ];
                }

                // Sort by week
                usort($trends, function ($a, $b) {
                    return $a['week'] <=> $b['week'];
                });
            } else {
                // Monthly grouping
                $grouped = $appointments->groupBy(function ($appointment) {
                    return Carbon::parse($appointment->created_at)->format('Y-m');
                });

                foreach ($grouped as $yearMonth => $items) {
                    $carbonDate = Carbon::parse($yearMonth . '-01');

                    $total = $items->count();
                    $completed = $items->where('status', 'Completed')->count();
                    $cancelled = $items->where('status', 'Cancelled')->count();

                    $trends[] = [
                        'month' => $carbonDate->format('M Y'),
                        'total' => $total,
                        'completed' => $completed,
                        'cancelled' => $cancelled,
                        'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
                    ];
                }

                // Sort by date
                usort($trends, function ($a, $b) {
                    return strtotime($a['month']) - strtotime($b['month']);
                });
            }

            return response()->json([
                'success' => true,
                'data' => $trends
            ]);
        } catch (\Exception $e) {
            \Log::error('Appointment trends error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch appointment trends',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get service performance with date range
    public function servicePerformance(Request $request)
    {
        try {
            $startDate = $request->input('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(6);
            $endDate = $request->input('end_date') ? Carbon::parse($request->end_date) : Carbon::now();

            $services = Appointment::selectRaw('service, COUNT(*) as total_appointments, 
                SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "Cancelled" THEN 1 ELSE 0 END) as cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service')
                ->orderBy('total_appointments', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($service) {
                    return [
                        'service' => $service->service,
                        'total_appointments' => $service->total_appointments,
                        'completed' => $service->completed,
                        'pending' => $service->pending,
                        'cancelled' => $service->cancelled,
                        'completion_rate' => $service->total_appointments > 0
                            ? round(($service->completed / $service->total_appointments) * 100, 2)
                            : 0
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            \Log::error('Service performance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch service performance'
            ], 500);
        }
    }

    // Get client demographics with date range
    public function clientDemographics(Request $request)
    {
        try {
            $startDate = $request->input('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(6);
            $endDate = $request->input('end_date') ? Carbon::parse($request->end_date) : Carbon::now();

            // Get all appointments for processing
            $appointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->get(['time', 'date', 'service']);

            // Process peak hours manually
            $peakHours = $appointments->groupBy(function ($appointment) {
                // Extract hour from time string (assuming time format like "14:30" or "2:30 PM")
                $timeStr = $appointment->time;
                $hour = null;

                // Try to parse different time formats
                if (strpos($timeStr, ':') !== false) {
                    // Format like "14:30"
                    $hour = (int) explode(':', $timeStr)[0];
                } elseif (strtotime($timeStr)) {
                    // Try using strtotime
                    $hour = (int) date('H', strtotime($timeStr));
                }

                return $hour !== null ? $hour . ':00' : 'Unknown';
            })->map(function ($group, $hour) {
                return [
                    'hour' => $hour,
                    'appointments' => $group->count()
                ];
            })->sortByDesc('appointments')->take(5)->values();

            // Process popular days
            $popularDays = $appointments->groupBy(function ($appointment) {
                return Carbon::parse($appointment->date)->format('l'); // Full day name
            })->map(function ($group, $day) {
                return [
                    'day' => $day,
                    'appointments' => $group->count()
                ];
            })->sortByDesc('appointments')->values();

            // Process service preferences
            $servicePreferences = $appointments->groupBy('service')
                ->map(function ($group, $service) {
                    return [
                        'service' => $service,
                        'appointments' => $group->count()
                    ];
                })->sortByDesc('appointments')->take(5)->values();

            // Determine peak season (month with most appointments)
            $peakSeason = $appointments->groupBy(function ($appointment) {
                return Carbon::parse($appointment->created_at)->format('F'); // Full month name
            })->map(function ($group, $month) {
                return [
                    'month' => $month,
                    'count' => $group->count()
                ];
            })->sortByDesc('count')->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'peak_hours' => $peakHours,
                    'popular_days' => $popularDays,
                    'service_preferences' => $servicePreferences,
                    'peak_season' => $peakSeason['month'] ?? 'N/A',
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Client demographics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client demographics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper method to get date range from request
    private function getDateRange(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $startDate = null;
        $endDate = Carbon::now();

        if ($request->has('start_date') && $request->has('end_date')) {
            // Custom range
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } else {
            // Predefined period
            switch ($period) {
                case 'daily':
                    $startDate = Carbon::today()->startOfDay();
                    break;
                case 'weekly':
                    $startDate = Carbon::now()->subDays(7)->startOfDay();
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->subMonth()->startOfDay();
                    break;
                case 'quarterly':
                    $startDate = Carbon::now()->subMonths(3)->startOfDay();
                    break;
                case 'annual':
                case 'yearly':
                    $startDate = Carbon::now()->subYear()->startOfDay();
                    break;
                default:
                    $startDate = Carbon::now()->subMonth()->startOfDay();
            }
        }

        return ['start' => $startDate, 'end' => $endDate];
    }

    // Get previous period for growth comparison
    private function getPreviousPeriod($startDate, $endDate, $period)
    {
        $daysDiff = $startDate->diffInDays($endDate);

        return [
            'start' => (clone $startDate)->subDays($daysDiff + 1),
            'end' => (clone $startDate)->subDay()
        ];
    }

    // Calculate revenue growth compared to previous period
    private function calculateRevenueGrowth($startDate, $endDate, $previousPeriod)
    {
        $currentRevenue = Account::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_paid') ?? 0;

        $previousRevenue = Account::whereBetween('created_at', [$previousPeriod['start'], $previousPeriod['end']])
            ->sum('amount_paid') ?? 0;

        if ($previousRevenue == 0) return 0;

        return round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2);
    }

    // Calculate client growth compared to previous period
    private function calculateClientGrowth($startDate, $endDate, $previousPeriod)
    {
        $currentClients = Appointment::where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousClients = Appointment::where('status', 'Completed')
            ->whereBetween('created_at', [$previousPeriod['start'], $previousPeriod['end']])
            ->count();

        if ($previousClients == 0) return 0;

        return round((($currentClients - $previousClients) / $previousClients) * 100, 2);
    }

    // Calculate appointment growth compared to previous period
    private function calculateAppointmentGrowth($startDate, $endDate, $previousPeriod)
    {
        $currentAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousAppointments = Appointment::whereBetween('created_at', [$previousPeriod['start'], $previousPeriod['end']])->count();

        if ($previousAppointments == 0) return 0;

        return round((($currentAppointments - $previousAppointments) / $previousAppointments) * 100, 2);
    }

    // Calculate projected revenue based on current trends
    private function calculateProjectedRevenue($startDate, $endDate, $currentRevenue)
    {
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $dailyAverage = $currentRevenue / $daysInPeriod;

        // Project for next 30 days
        return round($dailyAverage * 30);
    }

    // Get monthly revenue data with date range
    private function getMonthlyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = clone $startDate->startOfMonth();

        while ($current <= $endDate) {
            $monthEnd = (clone $current)->endOfMonth();

            $revenue = Account::whereBetween('created_at', [$current, $monthEnd])
                ->sum('amount_paid') ?? 0;

            $profit = Account::whereBetween('created_at', [$current, $monthEnd])
                ->sum('profit') ?? 0;

            $data[] = [
                'month' => $current->format('M Y'),
                'revenue' => $revenue,
                'profit' => $profit
            ];

            $current->addMonth();
        }

        return $data;
    }

    // Get weekly revenue data with date range
    private function getWeeklyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = clone $startDate->startOfWeek();

        while ($current <= $endDate) {
            $weekEnd = (clone $current)->endOfWeek();

            $revenue = Account::whereBetween('created_at', [$current, $weekEnd])
                ->sum('amount_paid') ?? 0;

            $data[] = [
                'week' => 'Week ' . $current->weekOfYear,
                'revenue' => $revenue,
                'start_date' => $current->format('M d'),
                'end_date' => $weekEnd->format('M d')
            ];

            $current->addWeek();
        }

        return $data;
    }

    // Get daily revenue data with date range
    private function getDailyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = clone $startDate;

        while ($current <= $endDate) {
            $revenue = Account::whereDate('created_at', $current)
                ->sum('amount_paid') ?? 0;

            $data[] = [
                'date' => $current->format('M d'),
                'revenue' => $revenue,
                'day' => $current->format('D')
            ];

            $current->addDay();
        }

        return $data;
    }

    // Get quarterly revenue data with date range
    private function getQuarterlyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = clone $startDate->startOfQuarter();

        while ($current <= $endDate) {
            $quarterEnd = (clone $current)->endOfQuarter();

            $revenue = Account::whereBetween('created_at', [$current, $quarterEnd])
                ->sum('amount_paid') ?? 0;

            $profit = Account::whereBetween('created_at', [$current, $quarterEnd])
                ->sum('profit') ?? 0;

            $data[] = [
                'quarter' => 'Q' . $current->quarter . ' ' . $current->year,
                'revenue' => $revenue,
                'profit' => $profit
            ];

            $current->addQuarter();
        }

        return $data;
    }

    // Get yearly revenue data with date range
    private function getYearlyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = clone $startDate->startOfYear();

        while ($current <= $endDate) {
            $yearEnd = (clone $current)->endOfYear();

            $revenue = Account::whereBetween('created_at', [$current, $yearEnd])
                ->sum('amount_paid') ?? 0;

            $profit = Account::whereBetween('created_at', [$current, $yearEnd])
                ->sum('profit') ?? 0;

            $data[] = [
                'year' => $current->year,
                'revenue' => $revenue,
                'profit' => $profit
            ];

            $current->addYear();
        }

        return $data;
    }

    // Helper methods with date range support
    private function calculateReturningClients($startDate, $endDate)
    {
        return DB::table(function ($query) use ($startDate, $endDate) {
            $query->select('phone')
                ->from('appointments')
                ->where('status', 'Completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('phone')
                ->havingRaw('COUNT(*) > 1');
        }, 'returning_clients')->count();
    }

    private function getPopularServices($startDate, $endDate)
    {
        return Appointment::selectRaw('service, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('service')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'service' => $item->service,
                    'count' => $item->count
                ];
            });
    }

    private function getServiceRevenue($startDate, $endDate)
    {
        if (!Account::exists()) {
            return collect();
        }

        return Appointment::join('accounts', 'appointments.id', '=', 'accounts.client_id')
            ->selectRaw('appointments.service, SUM(accounts.amount_paid) as revenue')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->groupBy('appointments.service')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'service' => $item->service,
                    'revenue' => $item->revenue ?? 0
                ];
            });
    }

    private function getMostProfitableService($startDate, $endDate)
    {
        if (!Account::exists()) {
            return null;
        }

        $result = Appointment::join('accounts', 'appointments.id', '=', 'accounts.client_id')
            ->selectRaw('appointments.service, SUM(accounts.profit) as total_profit')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->groupBy('appointments.service')
            ->orderBy('total_profit', 'desc')
            ->first();

        return $result ? [
            'service' => $result->service,
            'total_profit' => $result->total_profit ?? 0
        ] : null;
    }

    private function getFastMovingItems()
    {
        return Inventory::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product,
                    'stock' => $item->stock,
                    'unit' => $item->unit
                ];
            });
    }
}
