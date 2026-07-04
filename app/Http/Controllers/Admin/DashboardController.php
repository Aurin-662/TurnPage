<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total revenue
        $totalRevenue = DB::selectOne('
            SELECT NVL(SUM(p.amount), 0) AS total
            FROM PAYMENT p
            JOIN ORDERS o ON p.order_id = o.order_id
            WHERE p.payment_status = :status
        ', ['status' => 'Completed'])->total;

        // Total orders
        $totalOrders = DB::selectOne('
            SELECT COUNT(*) AS total FROM ORDERS
        ')->total;

        // Total customers
        $totalCustomers = DB::selectOne('
            SELECT COUNT(*) AS total FROM APP_USER WHERE ROLE = :role
        ', ['role' => 'customer'])->total;

        // Total books
        $totalBooks = DB::selectOne('
            SELECT COUNT(*) AS total FROM BOOK
        ')->total;

        // Orders by status
        $ordersByStatus = DB::select('
            SELECT STATUS, COUNT(*) AS total
            FROM ORDERS
            GROUP BY STATUS
            ORDER BY total DESC
        ');

        // Top 5 bestselling books
        $topBooks = DB::select('
            SELECT * FROM (
                SELECT b.title, b.star_rating,
                       SUM(oi.quantity) AS total_sold,
                       SUM(oi.quantity * oi.price) AS total_revenue
                FROM ORDER_ITEM oi
                JOIN BOOK b ON oi.book_id = b.book_id
                GROUP BY b.title, b.star_rating
                ORDER BY total_sold DESC
            ) WHERE ROWNUM <= 5
        ');

        // Revenue by payment method
        $revenueByMethod = DB::select('
            SELECT payment_method, SUM(amount) AS total
            FROM PAYMENT
            WHERE payment_status = :status
            GROUP BY payment_method
        ', ['status' => 'Completed']);

        // Recent 5 orders
        $recentOrders = DB::select('
            SELECT * FROM (
                SELECT o.order_id, u.name AS customer_name,
                       o.total_amount, o.status, o.order_date
                FROM ORDERS o
                JOIN APP_USER u ON o.user_id = u.user_id
                ORDER BY o.order_date DESC
            ) WHERE ROWNUM <= 5
        ');

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalCustomers', 'totalBooks',
            'ordersByStatus', 'topBooks', 'revenueByMethod', 'recentOrders'
        ));
    }
}