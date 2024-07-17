<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        $filters = [
            'user_name' => $request->user_name,
            'order_status' => $request->order_status,
            'min_total' => $request->min_total,
            'max_total' => $request->max_total,
        ];

        if ($filters['user_name']) {
            $query->where('user_name', 'like', '%' . $filters['user_name'] . '%');
        }

        if (!empty($filters['min_total']) && is_numeric($filters['min_total']) && $filters['min_total'] >= 0) {
            $query->where('total', '>=', $filters['min_total']);
        }

        if (!empty($filters['max_total']) && is_numeric($filters['max_total']) && $filters['max_total'] >= 0) {
            $query->where('total', '<=', $filters['max_total']);
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $filters['order_status']);
        }

        $perPage = $request->input('perPage', 10);

        $orders = $query->orderByDesc('created_at')->paginate($perPage)->appends($filters);

        if ($request->ajax()) {
            return view('orders.index', compact('orders', 'filters'))
                ->fragment('orders-list');
        }

        return view('orders.index', compact('orders', 'filters'));
    }

    public function show(Order $order)
    {
        $order->load('orderDetails', 'shippingAddresses', 'user');
        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Đơn hàng đã được xóa thành công',
            'order_id' => $order->id 
        ], 200);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = $request->input('order_status');
        $order->update(['order_status' => $newStatus]);

        $statusMessages = [
            0 => 'Đơn hàng đã chuyển về trạng thái đang xử lý.',
            1 => 'Đơn hàng đã được xác nhận.',
            2 => 'Đơn hàng đã bị hủy.'
        ];

        return response()->json([
            'message' => $statusMessages[$newStatus],
            'order_status' => $newStatus
        ], 200);
    }
}
