<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(): View|Factory|Application
    {
        $orders = Order::with('customer')->get();

        // Get all unique financial_status values
        $financialStatusOptions = Order::select('financial_status')->distinct()->pluck('financial_status');

        return view('dashboard', compact('orders', 'financialStatusOptions'));
    }

    // This better to move in API directory and routes, but for convenient check I added here
    public function apiOrders(Request $request): JsonResponse
    {
        $query = Order::query()->with('customer');

        // filters
        if ($request->filled('financial_status')) {
            $query->where('financial_status', $request->financial_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('total_price', 'like', "%{$search}%")
                ->orWhere('fulfillment_status', 'like', "%{$search}%");
        }

        // sorting
        if ($request->filled('sort') && $request->filled('order')) {
            $sortColumn = $request->sort;
            $sortDirection = $request->order;

            if ($sortColumn === 'customer_name') {
                $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->orderByRaw("(customers.first_name || ' ' || customers.last_name) $sortDirection");
            } elseif ($sortColumn === 'customer_email') {
                $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->orderBy('customers.email', $sortDirection);
            } else {
                $query->orderBy($sortColumn, $sortDirection);
            }
        }

        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);

        $total = $query->count();
        $orders = $query->skip($offset)->take($limit)->get();

        // Format data for the Bootstrap table
        $data = $orders->map(function ($order) {
            return [
                'customer_name' => "{$order->customer->first_name} {$order->customer->last_name}",
                'customer_email' => $order->customer->email,
                'total_price' => $order->total_price,
                'currency' => $order->currency ?? 'USD',
                'financial_status' => $order->financial_status,
                'fulfillment_status' => $order->fulfillment_status,
                'order_number' => $order->order_number,
                'processed_at' => $order->processed_at ? Carbon::parse($order->processed_at)->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json([
            'total' => $total,
            'rows' => $data,
        ]);
    }

}
