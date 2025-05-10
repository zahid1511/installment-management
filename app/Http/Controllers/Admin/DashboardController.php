<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Installment;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    function report(){
        // Initialize data array with default values
        $data = [
            'customers_count' => 0,
            'new_customers_this_month' => 0,
            'active_purchases' => 0,
            'completed_purchases' => 0,
            'total_revenue' => 0,
            'collected_this_month' => 0,
            'defaulters_count' => 0,
            'defaulters_amount' => 0,
            'recent_payments' => collect([]),
            'due_today' => collect([]),
            'top_products' => collect([]),
            'active_customers' => 0,
            'completed_customers' => 0,
            'monthly_collections' => collect([])
        ];
        
        try {
            // Customer Statistics
            $data['customers_count'] = Customer::count();
            $data['new_customers_this_month'] = Customer::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // Purchase Statistics
            $data['active_purchases'] = Purchase::where('status', 'active')->count();
            $data['completed_purchases'] = Purchase::where('status', 'completed')->count();
            
            // Revenue Statistics
            $data['total_revenue'] = Purchase::sum('total_price') ?? 0;
            $data['collected_this_month'] = Installment::where('status', 'paid')
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('installment_amount') ?? 0;
            
            // Defaulter Statistics
            $data['defaulters_count'] = Customer::where('is_defaulter', true)->count();
            $data['defaulters_amount'] = 0; // Since status 'overdue' might not exist yet
            try {
                $data['defaulters_amount'] = Installment::where('status', 'overdue')
                    ->sum('installment_amount') ?? 0;
            } catch (\Exception $e) {
                // Keep default value of 0 if there's an error
            }
            
            // Recent Payments (last 5)
            $data['recent_payments'] = Installment::where('status', 'paid')
                ->with('customer')
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
            
            // Due Today
            $data['due_today'] = Installment::whereDate('due_date', today())
                ->with('customer')
                ->orderBy('due_date')
                ->limit(5)
                ->get();
            
            // Top Products
            $data['top_products'] = Product::select('products.*')
                ->selectRaw('COUNT(purchases.id) as sales_count')
                ->selectRaw('COALESCE(SUM(purchases.total_price), 0) as total_revenue')
                ->leftJoin('purchases', 'products.id', '=', 'purchases.product_id')
                ->groupBy('products.id', 'products.company', 'products.model', 'products.serial_no', 'products.price')
                ->orderBy('sales_count', 'desc')
                ->limit(5)
                ->get();
            
            // Customer Distribution
            $data['active_customers'] = Customer::whereHas('purchases', function($query) {
                $query->where('status', 'active');
            })->count();
            
            $data['completed_customers'] = Customer::whereDoesntHave('purchases', function($query) {
                $query->where('status', 'active');
            })->where('is_defaulter', false)->count();
            
            // Monthly Collections for chart (last 6 months)
            $data['monthly_collections'] = Installment::where('status', 'paid')
                ->where('date', '>=', now()->subMonths(6))
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month')
                ->selectRaw('COALESCE(SUM(installment_amount), 0) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
                
        } catch (\Exception $e) {
            // Log the error and return default values
            \Log::error('Dashboard Error: ' . $e->getMessage());
            // Data is already initialized with default values
        }
        
        return view('report', compact('data'));
    }

}