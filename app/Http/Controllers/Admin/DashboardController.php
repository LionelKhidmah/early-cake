<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan pesanan
        $totalOrdersToday = Order::whereDate('order_date', today())
            ->count();

        $pendingOrders = Order::where('status', 'pending')
            ->count();

        // Total pendapatan dari pembayaran yang berhasil
        $totalRevenue = Payment::where('payment_status', 'paid')
            ->sum('amount');
        
        // Grafik untuk  jumlah pesanan selama 7 hari terakhir
        $startDate = today()->subDays(6);

        $ordersQuery = Order::selectRaw(
            'DATE(order_date) as date, COUNT(*) as total'
        )
            ->whereDate('order_date', '>=', $startDate)
            ->whereDate('order_date', '<=', today())
            ->groupByRaw('DATE(order_date)')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

            $dailyOrders = collect();

            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dailyOrders->push([
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    'total' => (int) ($ordersQuery->get($date->format('Y-m-d'))->total ?? 0),
                ]);
            }

            // Grafik pendapatan selama 6 bulan terakhir
            $startMonth = today()
                ->startOfMonth()
                ->subMonths(5);

            $revenueQuery = Payment::selectRaw(
                'DATE_FORMAT (paid_at, "%Y-%m") as month, SUM(amount) as total'
            )
                ->where('payment_status', 'paid')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $startMonth)
                ->where('paid_at', '<', today()->addDay())
                ->groupByRaw('DATE_FORMAT(paid_at, "%Y-%m")')
                ->get()
                ->keyBy('month');

            $monthlyRevenue = collect();

            for ($i = 0; $i < 6; $i++) {
                $month = $startMonth->copy()->addMonths($i);
                $key = $month->format('Y-m');

                $monthlyRevenue->push([
                    'month' => $key,
                    'label' => $month->format('M Y'),
                    'total' => (float) ($revenueQuery->get($key)->total ?? 0),
                ]);
            }

            return view('admin.dashboard', compact (
                'totalOrdersToday',
                'pendingOrders',
                'totalRevenue',
                'dailyOrders',
                'monthlyRevenue'
            ));
    } 
}
