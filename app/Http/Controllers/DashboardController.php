<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\ValueObjects\Money;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getTotalRevenue()
    {
        return $this->safe(function () {

            $totalRevenue = Order::whereIn('status', [Order::STATUS['completed']])->pluck('total_amount');
            $total = Money::sum($totalRevenue);
            $total = $total->format();
            return ApiResponse::success('ok', $total);

        }, __FILE__);
    }
    public function getOrderCount()
    {
        return $this->safe(function () {

            $orders = Order::count();

            return ApiResponse::success('ok', $orders);

        }, __FILE__);
    }

    public function getPizzaCount()
    {
        return $this->safe(function () {

            $pizzas = \App\Models\Pizza::count();

            return ApiResponse::success('ok', $pizzas);

        }, __FILE__);
    }

    public function getAvgRating()
    {
        return $this->safe(function () {

            $avgRating = Review::avg('rating');
            $avgRating = round($avgRating, 2);

            return ApiResponse::success('ok', $avgRating);

        }, __FILE__);
    }

    public function getOrderChartData()
    {
        return $this->safe(function () {

            $data = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $successData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->where('status', Order::STATUS['completed'])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->keyBy('date');

            $failData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->whereIn('status', [Order::STATUS['cancelled'], /* Order::STATUS['failed'], Order::STATUS['refunded'] */])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->keyBy('date');

            $labels = $data->pluck('date')->toArray();

            $successCounts = [];
            $failCounts = [];

            foreach ($labels as $date) {
                $successCounts[] = $successData[$date]->count ?? 0;
                $failCounts[] = $failData[$date]->count ?? 0;
            }

            $chartData['data']['datasets'] = [
                [
                    'label' => 'Success Orders',
                    'data' => $successCounts,
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Fail Orders',
                    'data' => $failCounts,
                    'fill' => false,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'tension' => 0.1,
                ],
            ];

            $chartData = [
                'type' => 'line',
                'data' => [
                    'labels' => $data->pluck('date'),
                    'datasets' => [
                        [
                            'label' => 'Orders',
                            'data' => $data->pluck('count'),
                            'fill' => false,
                            'borderColor' => 'rgb(75, 192, 192)',
                            'tension' => 0.1,
                        ],
                    ],
                ],
                'options' => [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                        ]
                    ],
                ],
            ];

            return ApiResponse::success('ok', $chartData);

        }, __FILE__);
    }

    public function getRevenueChartData()
    {
        return $this->safe(function () {

            $data = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
                ->where('created_at', '>=', now()->subDays(30))
                ->where('status', Order::STATUS['completed'])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $chartData = [
                'type' => 'line',
                'data' => [
                    'labels' => $data->pluck('date'),
                    'datasets' => [
                        [
                            'label' => 'Revenue',
                            'data' => $data->pluck('revenue'),
                            'fill' => false,
                            'borderColor' => 'rgb(54, 162, 235)',
                            'tension' => 0.1,
                        ],
                    ],
                ],
                'options' => [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                        ]
                    ],
                ],
            ];

            return ApiResponse::success('ok', $chartData);

        }, __FILE__);
    }
}
