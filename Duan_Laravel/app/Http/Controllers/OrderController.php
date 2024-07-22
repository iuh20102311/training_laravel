<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use App\Models\UserAddress;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Hash;
use DB;

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

    // -- ADMIN -- \\

    public function show(Order $order)
    {
        $order->load(['orderDetails.product', 'shippingAddresses', 'user']);
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

    public function create()
    {
        $users = User::with('addresses')->get();
        $products = Product::all();
        $discountCodes = DiscountCode::where('is_active', true)->get();
        return view('orders.create', compact('users', 'products', 'discountCodes'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        // Kiểm tra xem đây là khách hàng mới hay đã đăng ký
        $user = null;
        if ($request->user_type == 'existing') {
            $user = User::findOrFail($request->user_id);
        } else {
            // Tạo user mới
            $user = User::create([
                'name' => $request->new_user_name,
                'email' => $request->new_user_email,
                'password' => Hash::make('password'), 
            ]);
        }

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => $request->user_id,
            'order_number' => 'ORD-' . uniqid(),
            'user_name' => User::find($request->user_id)->name,
            'user_email' => User::find($request->user_id)->email,
            'phone_number' => $request->phone_number,
            'order_date' => now(),
            'order_status' => 0, // Đang xử lý
            'payment_method' => $request->payment_method,
            'sub_total' => 0,
            'total' => 0,
            'tax' => 0,
            'discount_code_id' => $request->discount_code_id,
        ]);

        $subTotal = 0;

        foreach ($request->products as $key => $product) {
            // Xử lý địa chỉ giao hàng
            if ($request->user_type == 'existing' && $request->shipping_addresses[$key]['type'] == 'existing') {
                $addressId = $request->shipping_addresses[$key]['address_id'];
                $userAddress = UserAddress::findOrFail($addressId);

                $shippingAddress = ShippingAddress::create([
                    'order_id' => $order->order_id,
                    'phone_number' => $userAddress->phone_number,
                    'city' => $userAddress->city,
                    'district' => $userAddress->district,
                    'ward' => $userAddress->ward,
                    'address' => $userAddress->address,
                    'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                ]);
            } else {
                // Xử lý cho cả địa chỉ mới của khách hàng hiện tại và khách hàng mới
                $shippingAddress = ShippingAddress::create([
                    'order_id' => $order->order_id,
                    'phone_number' => $request->shipping_addresses[$key]['phone_number'],
                    'city' => $request->shipping_addresses[$key]['city'],
                    'district' => $request->shipping_addresses[$key]['district'],
                    'ward' => $request->shipping_addresses[$key]['ward'],
                    'address' => $request->shipping_addresses[$key]['address'],
                    'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                ]);

                // Thêm địa chỉ mới vào bảng user_address
                UserAddress::create([
                    'user_id' => $user->id,
                    'phone_number' => $request->shipping_addresses[$key]['phone_number'],
                    'city' => $request->shipping_addresses[$key]['city'],
                    'district' => $request->shipping_addresses[$key]['district'],
                    'ward' => $request->shipping_addresses[$key]['ward'],
                    'address' => $request->shipping_addresses[$key]['address'],
                ]);
            }

            // Tạo chi tiết đơn hàng
            $orderDetail = OrderDetail::create([
                'order_id' => $order->order_id,
                'product_id' => $product['id'],
                'shipping_address_id' => $shippingAddress->id,
                'product_name' => Product::find($product['id'])->name,
                'price_buy' => Product::find($product['id'])->price,
                'quantity' => $product['quantity'],
            ]);

            $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
        }

        // Tính thuế và tổng tiền
        $tax = $subTotal * 0.1; // Giả sử thuế 10%
        $discountAmount = 0;
        if ($request->discount_code_id) {
            $discountCode = DiscountCode::find($request->discount_code_id);
            $discountAmount = $discountCode->amount ?? ($subTotal * $discountCode->percentage / 100);
        }

        $total = $subTotal + $tax - $discountAmount;

        // Cập nhật đơn hàng
        $order->update([
            'sub_total' => $subTotal,
            'total' => $total,
            'tax' => $tax,
            'discount_amount' => $discountAmount,
        ]);

        DB::commit();
        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }



    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);
            $order->update([
                'user_id' => $request->user_id,
                'user_name' => User::find($request->user_id)->name,
                'user_email' => User::find($request->user_id)->email,
                'phone_number' => $request->phone_number,
                'order_status' => $request->order_status,
                'payment_method' => $request->payment_method,
                'discount_code_id' => $request->discount_code_id,
            ]);

            // Delete existing order details and shipping addresses
            $order->orderDetails()->delete();
            $order->shippingAddresses()->delete();

            $subTotal = 0;
            foreach ($request->products as $key => $product) {
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product['product_id'],
                    'product_name' => Product::find($product['product_id'])->name,
                    'price_buy' => Product::find($product['product_id'])->price,
                    'quantity' => $product['quantity'],
                ]);

                if ($request->shipping_addresses[$key]['type'] == 'existing') {
                    $address = UserAddress::find($request->shipping_addresses[$key]['address_id']);
                    $shippingAddress = ShippingAddress::create([
                        'order_id' => $order->order_id,
                        'phone_number' => $address->phone_number,
                        'city' => $address->city,
                        'district' => $address->district,
                        'ward' => $address->ward,
                        'address' => $address->address,
                        'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                    ]);
                } else {
                    $shippingAddress = ShippingAddress::create([
                        'order_id' => $order->order_id,
                        'phone_number' => $request->shipping_addresses[$key]['phone_number'],
                        'city' => $request->shipping_addresses[$key]['city'],
                        'district' => $request->shipping_addresses[$key]['district'],
                        'ward' => $request->shipping_addresses[$key]['ward'],
                        'address' => $request->shipping_addresses[$key]['address'],
                        'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                    ]);

                    // Thêm địa chỉ mới vào bảng user_address
                    UserAddress::create([
                        'user_id' => $request->user_id,
                        'phone_number' => $request->shipping_addresses[$key]['phone_number'],
                        'city' => $request->shipping_addresses[$key]['city'],
                        'district' => $request->shipping_addresses[$key]['district'],
                        'ward' => $request->shipping_addresses[$key]['ward'],
                        'address' => $request->shipping_addresses[$key]['address'],
                    ]);
                }

                $orderDetail->shipping_address_id = $shippingAddress->id;
                $orderDetail->save();

                $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
            }

            $tax = $subTotal * 0.1; // Assuming 10% tax
            $discountAmount = 0;
            if ($request->discount_code_id) {
                $discountCode = DiscountCode::find($request->discount_code_id);
                $discountAmount = $discountCode->amount ?? ($subTotal * $discountCode->percentage / 100);
            }

            $total = $subTotal + $tax - $discountAmount;

            $order->update([
                'sub_total' => $subTotal,
                'total' => $total,
                'tax' => $tax,
                'discount_amount' => $discountAmount,
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    // -- Customer -- \\
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->product_id])) {
            $cart[$product->product_id]['quantity']++;
        } else {
            $cart[$product->product_id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "added_at" => now() // Lấy sản phẩm từ lúc thêm giỏ hàng không bị đổi giá
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng']);
    }


    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('orders.cart', compact('cart'));
    }

    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        $user = auth()->user();

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống, vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $tax = $sub_total * 0.1; // 10% tax
        $ship_charge = 15000; // Fixed shipping fee
        $total = $sub_total + $tax + $ship_charge;

        return view('orders.checkout', compact('cart', 'user', 'sub_total', 'tax', 'ship_charge', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'addresses' => 'required|array',
            'addresses.*' => 'string',
            'payment_method' => 'required',
            'discount_code' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng trống');
        }

        $user = auth()->user();
        $order = new Order();
        $order->user_id = $user->id;
        $order->user_name = $user->name;
        $order->user_email = $user->email;
        $order->phone_number = $request->phone_number;
        $order->order_number = 'ORD-' . strtoupper(uniqid());
        $order->order_date = now();
        $order->order_status = 0; // Processing
        $order->payment_method = $request->payment_method;
        $order->sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $order->tax = $order->sub_total * 0.1; // 10% tax
        $order->ship_charge = 15000; // Fixed shipping fee
        $order->discount_amount = 0;

        if ($request->discount_code) {
            $discount = DiscountCode::where('code', $request->discount_code)->first();
            if ($discount && $discount->isValid()) {
                if ($discount->amount) {
                    $order->discount_amount = $discount->amount;
                } elseif ($discount->percentage) {
                    $order->discount_amount = $order->sub_total * ($discount->percentage / 100);
                }

                // Increase usage count
                $discount->used_time += 1;
                $discount->save();
            }
        }

        $order->total = $order->sub_total + $order->tax + $order->ship_charge - $order->discount_amount;
        $order->save();

        foreach ($cart as $product_id => $details) {
            $shippingAddress = new ShippingAddress();
            $shippingAddress->order_id = $order->order_id;
            $shippingAddress->phone_number = $request->phone_number;
            $shippingAddress->address = $request->addresses[$product_id];
            $shippingAddress->save();

            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->order_id;
            $orderDetail->product_id = $product_id;
            $orderDetail->shipping_address_id = $shippingAddress->id;
            $orderDetail->product_name = $details['name'];
            $orderDetail->price_buy = $details['price'];
            $orderDetail->quantity = $details['quantity'];
            $orderDetail->save();
        }

        session()->forget('cart');

        return redirect()->route('orders.preview', $order->id)->with('success', 'Đơn hàng đã được đặt thành công');
    }

    public function checkDiscount(Request $request)
    {
        $discountCode = $request->input('discount_code');
        $discount = DiscountCode::where('code', $discountCode)->first();

        if ($discount && $discount->isValid()) {
            return response()->json([
                'success' => true,
                'discount' => [
                    'amount' => $discount->amount,
                    'percentage' => $discount->percentage
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ'
        ], 400);
    }


    public function preview(Request $request)
    {
        $cart = session()->get('cart', []);


        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống, vui lòng thêm sản phẩm trước khi xem đơn hàng.');
        }


        $user = auth()->user();

        $sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $tax = $sub_total * 0.1; // 10% tax
        $ship_charge = 15000; // Phí ship cố định

        $discount_code = $request->discount_code;
        $discount_amount = 0;
        $discount_percentage = 0;

        if ($discount_code) {
            $discount = DiscountCode::where('code', $discount_code)->first();
            if ($discount && $discount->isValid()) {
                if ($discount->amount) {
                    $discount_amount = $discount->amount;
                } elseif ($discount->percentage) {
                    $discount_percentage = $discount->percentage;
                    $discount_amount = $sub_total * ($discount_percentage / 100);
                }
            }
        }

        $total = $sub_total + $tax + $ship_charge - $discount_amount;

        return view(
            'orders.preview',
            compact(
                'cart',
                'user',
                'sub_total',
                'tax',
                'ship_charge',
                'discount_code',
                'discount_amount',
                'discount_percentage',
                'total',
                'phone_number',
                'payment_method',
                'addresses'
            )
        );
    }
}
