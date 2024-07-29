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
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
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
        $discountCodes = DiscountCode::where('is_active', true)->get()->filter(function ($code) 
        {
            return $code->isValid();
        })->map(function ($code) {
            return [
                'id' => $code->id,
                'code' => $code->code,
                'type' => $code->amount ? 'amount' : 'percentage',
                'value' => $code->type == 'amount' ? $code->amount : $code->percentage
            ];
        });
        return view('orders.create', compact('users', 'products', 'discountCodes'));
    }

    public function store(CreateOrderRequest $request)
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

        $discountCode = null;
        if ($request->filled('discount_code')) {
            $discountCode = DiscountCode::where('code', $request->discount_code)->first();
            if (!$discountCode || !$discountCode->isValid()) {
                return back()->withErrors(['discount_code' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'])->withInput();
            }
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

    public function edit(Order $order)
    {
        $users = User::with('addresses')->get();
        $products = Product::all();
        $discountCodes = DiscountCode::where('is_active', true)->get();
        return view('orders.edit', compact('order', 'users', 'products', 'discountCodes'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::beginTransaction();

        if (count($request->products) < 1) {
            throw new \Exception('Đơn hàng phải có ít nhất một sản phẩm.');
        }

        // Cập nhật thông tin cơ bản của đơn hàng
        $order->update([
            'phone_number' => $request->phone_number,
            'payment_method' => $request->payment_method,
            'discount_code_id' => $request->discount_code_id,
        ]);
        // Xóa chi tiết đơn hàng cũ
        $order->orderDetails()->delete();
        
        $subTotal = 0;

        foreach ($request->products as $key => $product) {
            // Xử lý địa chỉ giao hàng
            if ($request->shipping_addresses[$key]['type'] == 'existing') {
                $addressId = $request->shipping_addresses[$key]['address_id'];
                $userAddress = UserAddress::findOrFail($addressId);

                $shippingAddress = ShippingAddress::updateOrCreate(
                    ['order_id' => $order->order_id, 'id' => $request->shipping_addresses[$key]['id'] ?? null],
                    [
                        'phone_number' => $userAddress->phone_number,
                        'city' => $userAddress->city,
                        'district' => $userAddress->district,
                        'ward' => $userAddress->ward,
                        'address' => $userAddress->address,
                        'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                    ]
                );
            } else {
                $shippingAddress = ShippingAddress::updateOrCreate(
                    ['order_id' => $order->order_id, 'id' => $request->shipping_addresses[$key]['id'] ?? null],
                    [
                        'phone_number' => $request->shipping_addresses[$key]['phone_number'],
                        'city' => $request->shipping_addresses[$key]['city'],
                        'district' => $request->shipping_addresses[$key]['district'],
                        'ward' => $request->shipping_addresses[$key]['ward'],
                        'address' => $request->shipping_addresses[$key]['address'],
                        'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
                    ]
                );
            }

            // Tạo chi tiết đơn hàng mới
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
        $tax = $subTotal * 0.1;
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
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
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
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if(isset($cart[$productId]))
        {
            unset($cart[$productId]);
            session()->put('cart',$cart);

            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
                'cart' => $cart,
                'total' => $total
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
    }


    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('orders.cart', compact('cart'));
    }

    public function updateQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => $total
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
    }


    public function showCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        $user = auth()->user()->load('addresses');

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống, vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $discount_amount = 0;
        $discount_code = $request->session()->get('discount_code');

        if ($discount_code) {
            $discount = DiscountCode::where('code', $discount_code)->first();
            if ($discount && $discount->isValid()) {
                if ($discount->amount) {
                    $discount_amount = $discount->amount;
                } elseif ($discount->percentage) {
                    $discount_amount = $sub_total * ($discount->percentage / 100);
                }
            }
        }

        $discounted_subtotal = $sub_total - $discount_amount;

        // Tính phí vận chuyển cho từng sản phẩm
        $initial_ship_charge = 10000;
        $total_ship_charge = 0;
        $index = 0;
        foreach ($cart as $productId => &$item) { // Thêm & để tham chiếu trực tiếp đến item
            $item['ship_charge'] = round($initial_ship_charge * (1 + 0.1 * $index));
            $total_ship_charge += $item['ship_charge'];
            $index++;
        }

        $tax = round($discounted_subtotal * 0.1); // 10% tax
        $total = $discounted_subtotal + $tax + $total_ship_charge;

        return view('orders.checkout', compact('cart', 'user', 'sub_total', 'tax', 'total_ship_charge', 'total', 'discount_amount', 'discount_code'));
    }

    public function placeOrder(Request $request)
    {
        $previewData = session('order_preview');
        if (!$previewData) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng xem lại đơn hàng trước khi đặt hàng');
        }

        $validatedData = $previewData['validatedData'];
        $cart = $previewData['cart'];
        $user = auth()->user();

        $order = new Order();
        $order->user_id = $user->id;
        $order->user_name = $user->name;
        $order->user_email = $user->email;
        $order->phone_number = $validatedData['phone_number'];
        $order->order_number = 'ORD-' . strtoupper(uniqid());
        $order->order_date = now();
        $order->order_status = 0; // Processing
        $order->payment_method = $validatedData['payment_method'];
        $order->sub_total = $previewData['sub_total'];
        $order->tax = $previewData['tax'];
        $order->discount_amount = $previewData['discount_amount'];
        $order->total = $previewData['total'];
        $order->save();

        foreach ($cart as $product_id => $details) {
            $addressData = $validatedData['shipping_addresses'][$product_id];

            if ($addressData['type'] === 'existing') {
                $userAddress = UserAddress::findOrFail($addressData['address_id']);
                $shippingAddress = new ShippingAddress([
                    'order_id' => $order->order_id,
                    'phone_number' => $userAddress->phone_number,
                    'address' => $userAddress->address,
                    'city' => $userAddress->city,
                    'district' => $userAddress->district,
                    'ward' => $userAddress->ward,
                    'ship_charge' => $addressData['ship_charge'],
                ]);
            } else {
                $shippingAddress = new ShippingAddress([
                    'order_id' => $order->order_id,
                    'phone_number' => $addressData['phone_number'],
                    'address' => $addressData['address'],
                    'city' => $addressData['city'],
                    'district' => $addressData['district'],
                    'ward' => $addressData['ward'],
                    'ship_charge' => $addressData['ship_charge'],
                ]);

                // Thêm địa chỉ mới vào bảng user_addresses
                UserAddress::create([
                    'user_id' => $user->id,
                    'phone_number' => $addressData['phone_number'],
                    'address' => $addressData['address'],
                    'city' => $addressData['city'],
                    'district' => $addressData['district'],
                    'ward' => $addressData['ward'],
                ]);
            }
           
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

        // Xóa session sau khi đã xử lý
        session()->forget(['cart', 'order_preview']);

        return redirect()->route('products.index')->with('success', 'Đơn hàng đã được đặt thành công');
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
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'shipping_addresses' => 'required|array',
            'shipping_addresses.*.type' => 'required|in:existing,new',
            'shipping_addresses.*.address_id' => 'required_if:shipping_addresses.*.type,existing',
            'shipping_addresses.*.address' => 'required_if:shipping_addresses.*.type,new',
            'shipping_addresses.*.city' => 'required_if:shipping_addresses.*.type,new',
            'shipping_addresses.*.district' => 'required_if:shipping_addresses.*.type,new',
            'shipping_addresses.*.ward' => 'required_if:shipping_addresses.*.type,new',
            'shipping_addresses.*.phone_number' => 'required_if:shipping_addresses.*.type,new',
            'payment_method' => 'required',
            'discount_code' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        $user = auth()->user();

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng trống');
        }
        
        $sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $tax = $sub_total * 0.1; // 10% tax
        $ship_charge = 0;
        $initial_ship_charge = 10000;
        $index = 0;
        foreach ($validatedData['shipping_addresses'] as $productId => &$address) {
            $address['ship_charge'] = $initial_ship_charge * (1 + 0.1 * $index);
            $ship_charge += $address['ship_charge'];
            $index++;
        }
        $discount_amount = 0;


        if ($validatedData['discount_code']) {
            $discount = DiscountCode::where('code', $validatedData['discount_code'])->first();
            if ($discount && $discount->isValid()) {
                if ($discount->amount) {
                    $discount_amount = $discount->amount;
                } elseif ($discount->percentage) {
                    $discount_amount = $sub_total * ($discount->percentage / 100);
                }
            }
        }

        $total = $sub_total + $tax + $ship_charge - $discount_amount;

        // Lưu dữ liệu vào session để sử dụng trong trang preview
        session([
            'order_preview' => [
                'validatedData' => $validatedData,
                'cart' => $cart,
                'sub_total' => $sub_total,
                'tax' => $tax,
                'ship_charge' => $ship_charge,
                'discount_amount' => $discount_amount,
                'total' => $total
            ]
        ]);

        return view('orders.preview', compact('validatedData', 'cart', 'user', 'sub_total', 'tax', 'ship_charge', 'discount_amount', 'total'));
    }
}
