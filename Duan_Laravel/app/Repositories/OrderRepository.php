<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use App\Models\UserAddress;
use App\Models\DiscountCode;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(Request $request)
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

        return $orders;
    }

    public function showOrder(Order $order)
    {
        $order->load(['orderDetails.product', 'shippingAddresses', 'user']);
        return $order;
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => trans('orders.deleted_successfully'),
            'order_id' => $order->order_id
        ], 200);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = $request->input('order_status');
        $order->update(['order_status' => $newStatus]);

        $statusMessages = [
            0 => trans('orders.order_processing'),
            1 => trans('orders.order_confirmed'),
            2 => trans('orders.order_cancelled')
        ];

        return response()->json([
            'message' => $statusMessages[$newStatus],
            'order_status' => $newStatus
        ], 200);
    }

    public function createOrder()
    {
        $users = User::with('addresses')->get();
        $products = Product::all();
        $discountCodes = DiscountCode::where('is_active', true)->get()->filter(function ($code) {
            return $code->isValid();
        })->map(function ($code) {
            return [
                'id' => $code->id,
                'code' => $code->code,
                'type' => $code->amount ? 'amount' : 'percentage',
                'amount' => $code->amount,
                'percentage' => $code->percentage,
            ];
        });
        return compact('users', 'products', 'discountCodes');
    }

    // public function storeOrder(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Kiểm tra xem đây là khách hàng mới hay đã đăng ký
    //         $user = null;
    //         if ($request->user_type == 'existing') {
    //             $user = User::findOrFail($request->user_id);
    //         } else {
    //             // Tạo user mới
    //             $user = User::create([
    //                 'name' => $request->new_user_name,
    //                 'email' => $request->new_user_email,
    //                 'password' => Hash::make('password'),
    //             ]);
    //         }

    //         $discountCode = null;
    //         if ($request->filled('discount_code')) {
    //             $discountCode = DiscountCode::where('code', $request->discount_code)->first();
    //             if (!$discountCode || !$discountCode->isValid()) {
    //                 return back()->withErrors(['discount_code' => trans('orders.discount_error')])->withInput();
    //             }
    //         }

    //         // Tạo đơn hàng
    //         $order = Order::create([
    //             'user_id' => $request->user_id,
    //             'order_number' => 'ORD-' . Str::substr(Str::uuid(), 0, 10),
    //             'user_name' => User::find($request->user_id)->name,
    //             'user_email' => User::find($request->user_id)->email,
    //             'phone_number' => $request->phone_number,
    //             'order_date' => now(),
    //             'order_status' => 0, // Đang xử lý
    //             'payment_method' => $request->payment_method,
    //             'sub_total' => 0,
    //             'total' => 0,
    //             'tax' => 0,
    //             'discount_code_id' => $request->discount_code_id,
    //         ]);

    //         $subTotal = 0;

    //         foreach ($request->products as $key => $product) {
    //             // Xử lý địa chỉ giao hàng
    //             if ($request->user_type == 'existing' && $request->shipping_addresses[$key]['type'] == 'existing') {
    //                 $addressId = $request->shipping_addresses[$key]['address_id'];
    //                 $userAddress = UserAddress::findOrFail($addressId);

    //                 $shippingAddress = ShippingAddress::create([
    //                     'order_id' => $order->order_id,
    //                     'phone_number' => $userAddress->phone_number,
    //                     'city' => $userAddress->city,
    //                     'district' => $userAddress->district,
    //                     'ward' => $userAddress->ward,
    //                     'address' => $userAddress->address,
    //                     'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
    //                 ]);
    //             } else {
    //                 // Xử lý cho cả địa chỉ mới của khách hàng hiện tại và khách hàng mới
    //                 $shippingAddress = ShippingAddress::create([
    //                     'order_id' => $order->order_id,
    //                     'phone_number' => $request->shipping_addresses[$key]['phone_number'],
    //                     'city' => $request->shipping_addresses[$key]['city'],
    //                     'district' => $request->shipping_addresses[$key]['district'],
    //                     'ward' => $request->shipping_addresses[$key]['ward'],
    //                     'address' => $request->shipping_addresses[$key]['address'],
    //                     'ship_charge' => $request->shipping_addresses[$key]['ship_charge'],
    //                 ]);

    //                 // Thêm địa chỉ mới vào bảng user_address
    //                 UserAddress::create([
    //                     'user_id' => $user->id,
    //                     'phone_number' => $request->shipping_addresses[$key]['phone_number'],
    //                     'city' => $request->shipping_addresses[$key]['city'],
    //                     'district' => $request->shipping_addresses[$key]['district'],
    //                     'ward' => $request->shipping_addresses[$key]['ward'],
    //                     'address' => $request->shipping_addresses[$key]['address'],
    //                 ]);
    //             }

    //             // Tạo chi tiết đơn hàng
    //             $orderDetail = OrderDetail::create([
    //                 'order_id' => $order->order_id,
    //                 'product_id' => $product['id'],
    //                 'shipping_address_id' => $shippingAddress->id,
    //                 'product_name' => Product::find($product['id'])->name,
    //                 'price_buy' => Product::find($product['id'])->price,
    //                 'quantity' => $product['quantity'],
    //             ]);

    //             $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
    //         }

    //         // Tính thuế và tổng tiền
    //         $tax = $subTotal * 0.1; // Giả sử thuế 10%
    //         $discountAmount = 0;

    //         if ($request->discount_code_id) {
    //             $discountCode = DiscountCode::find($request->discount_code_id);
    //             $discountAmount = $discountCode->amount ?? ($subTotal * $discountCode->percentage / 100);
    //         }

    //         $total = $subTotal + $tax - $discountAmount;

    //         // Cập nhật đơn hàng
    //         $order->update([
    //             'sub_total' => $subTotal,
    //             'total' => $total,
    //             'tax' => $tax,
    //             'discount_amount' => $discountAmount,
    //         ]);

    //         DB::commit();
    //         return $order; // Trả về đối tượng Order đã được tạo
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', trans('orders.order_error') . $e->getMessage());
    //     }
    // }

    public function storeOrder(array $validatedData)
    {
        try {
            DB::beginTransaction();

            // Xử lý người dùng
            if (empty($validatedData['user_id'])) {
                $user = User::create([
                    'name' => $validatedData['user_name'],
                    'email' => $validatedData['user_email'],
                    'password' => Hash::make('password'),
                ]);
            } else {
                $user = User::findOrFail($validatedData['user_id']);
            }

            // Xử lý mã giảm giá
            $discountCode = null;
            $discountAmount = 0;
            if (!empty($validatedData['discount_code_id'])) {
                $discountCode = DiscountCode::findOrFail($validatedData['discount_code_id']);
                if (!$discountCode->isValid()) {
                    throw new \Exception(trans('orders.discount_error'));
                }
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . Str::substr(Str::uuid(), 0, 10),
                'user_name' => $user->name,
                'user_email' => $user->email,
                'order_date' => now(),
                'order_status' => 0, // Đang xử lý
                'payment_method' => $validatedData['payment_method'],
                'phone_number' => $validatedData['shipping_addresses'][0]['phone_number'] ?? '',
                'sub_total' => 0,
                'total' => 0,
                'tax' => 0,
                'discount_code_id' => $discountCode ? $discountCode->id : null,
            ]);

            $subTotal = 0;
            $totalShipping = 0;

            // Xử lý từng địa chỉ giao hàng
            foreach ($validatedData['shipping_addresses'] as $addressData) {
                // Xử lý địa chỉ giao hàng
                $shippingAddress = ShippingAddress::create([
                    'order_id' => $order->order_id,
                    'phone_number' => $addressData['phone_number'],
                    'city' => $addressData['city'],
                    'district' => $addressData['district'],
                    'ward' => $addressData['ward'],
                    'address' => $addressData['address'],
                    'ship_charge' => $addressData['ship_charge'],
                ]);

                $totalShipping += $addressData['ship_charge'];

                // Thêm địa chỉ mới vào bảng user_address nếu là user mới
                if (!empty($validatedData['new_user_checkbox'])) {
                    UserAddress::create([
                        'user_id' => $user->id,
                        'phone_number' => $addressData['phone_number'],
                        'city' => $addressData['city'],
                        'district' => $addressData['district'],
                        'ward' => $addressData['ward'],
                        'address' => $addressData['address'],
                    ]);
                }

                // Xử lý sản phẩm trong đơn hàng cho địa chỉ này
                foreach ($addressData['products'] as $productId => $productData) {
                    $product = Product::findOrFail($productId);
                    $orderDetail = OrderDetail::create([
                        'order_id' => $order->order_id,
                        'product_id' => $product->product_id,
                        'shipping_address_id' => $shippingAddress->id,
                        'product_name' => $product->name,
                        'price_buy' => $product->price,
                        'quantity' => $productData['quantity'],
                    ]);

                    $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
                }
            }

            // Tính thuế và tổng tiền
            $tax = $subTotal * 0.1; // Giả sử thuế 10%
            $discountAmount = 0;

            if (!empty($validatedData['discount_code_id'])) {
                $discountCode = DiscountCode::find($validatedData['discount_code_id']);
                if ($discountCode) {
                    if ($discountCode->amount) {
                        $discountAmount = $discountCode->amount;
                    } elseif ($discountCode->percentage) {
                        $discountAmount = ($subTotal * $discountCode->percentage) / 100;
                    }
                }
            }

            $total = $subTotal + $tax + $totalShipping - $discountAmount;

            // Cập nhật đơn hàng
            $order->update([
                'sub_total' => $subTotal,
                'total' => $total,
                'tax' => $tax,
                'discount_amount' => $discountAmount,
                'ship_charge' => $totalShipping,
            ]);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function editOrder(Order $order)
    {
        $order->load(['shippingAddresses.orderDetails.product', 'user.addresses', 'discountCode']);
        $products = Product::all();
        $discountCodes = DiscountCode::where('is_active', true)->get()->filter(function ($code) {
            return $code->isValid();
        })->map(function ($code) {
            return [
                'id' => $code->id,
                'code' => $code->code,
                'type' => $code->amount ? 'amount' : 'percentage',
                'amount' => $code->amount,
                'percentage' => $code->percentage,
            ];
        });
        return compact('order', 'products', 'discountCodes');
    }


    // public function updateOrder(Request $request, Order $order)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Cập nhật thông tin cơ bản của đơn hàng
    //         $order->update([

    //             'payment_method' => $request->payment_method,
    //             'discount_code_id' => $request->discount_code_id,
    //         ]);

    //         // Xóa các địa chỉ giao hàng và chi tiết đơn hàng cũ
    //         $order->shippingAddresses()->delete();
    //         $order->orderDetails()->delete();

    //         $subTotal = 0;
    //         $totalShippingCharge = 0;


    //         foreach ($request->shipping_addresses as $addressData) {
    //             $shippingAddress = $order->shippingAddresses()->create([
    //                 'phone_number' => $addressData['phone_number'],
    //                 'city' => $addressData['city'],
    //                 'district' => $addressData['district'],
    //                 'ward' => $addressData['ward'],
    //                 'address' => $addressData['address'],
    //                 'ship_charge' => $addressData['ship_charge'],
    //             ]);

    //             $totalShippingCharge += $addressData['ship_charge'];

    //             foreach ($addressData['products'] as $productData) {
    //                 $product = Product::findOrFail($productData['product_id']);
    //                 $orderDetail = $order->orderDetails()->create([
    //                     'shipping_address_id' => $shippingAddress->id,
    //                     'product_id' => $product->product_id,
    //                     'product_name' => $product->name,
    //                     'price_buy' => $product->price,
    //                     'quantity' => $productData['quantity'],
    //                 ]);
    //                 $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
    //             }
    //         }

    //         // Tính thuế và tổng tiền
    //         $tax = $subTotal * 0.1;
    //         $discountAmount = 0;
    //         if ($request->discount_code_id) {
    //             $discountCode = DiscountCode::find($request->discount_code_id);
    //             $discountAmount = $discountCode->amount ?? ($subTotal * $discountCode->percentage / 100);
    //         }

    //         $total = $subTotal + $tax + $totalShippingCharge - $discountAmount;

    //         // Cập nhật đơn hàng
    //         $order->update([
    //             'sub_total' => $subTotal,
    //             'total' => $total,
    //             'tax' => $tax,
    //             'discount_amount' => $discountAmount,
    //         ]);

    //         DB::commit();
    //         return $order;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
    //     }
    // }

    public function updateOrder(Request $request, Order $order)
    {
        DB::beginTransaction();
        try {
            // Cập nhật thông tin cơ bản của đơn hàng
            $order->update([
                'user_id' => $request->user_id,
                'payment_method' => $request->payment_method,
                'phone_number' => $request->shipping_addresses[0]['phone_number'] ?? '',
            ]);

            // Xử lý mã giảm giá
            $discountAmount = 0;
            if ($request->filled('discount_code_id')) {
                $discountCode = DiscountCode::findOrFail($request->discount_code_id);
                if (!$discountCode->isValid()) {
                    return back()->withErrors(['discount_code' => trans('orders.discount_error')])->withInput();
                }
                $order->discount_code_id = $discountCode->id;
                $discountAmount = $discountCode->amount ?? ($order->sub_total * $discountCode->percentage / 100);
            } else {
                $order->discount_code_id = null;
            }

            $subTotal = 0;
            $totalShipping = 0;

            $updatedAddressIds = [];
            $updatedProductIds = [];

            // Xử lý địa chỉ giao hàng và sản phẩm
            foreach ($request->shipping_addresses as $index => $addressData) {
                $shippingAddress = $order->shippingAddresses()->updateOrCreate(
                    ['id' => $addressData['id'] ?? null],
                    [
                        'phone_number' => $addressData['phone_number'],
                        'city' => $addressData['city'],
                        'district' => $addressData['district'],
                        'ward' => $addressData['ward'],
                        'address' => $addressData['address'],
                        'ship_charge' => $addressData['ship_charge'],
                    ]
                );

                $updatedAddressIds[] = $shippingAddress->id;
                $totalShipping += $addressData['ship_charge'];

                // Xử lý sản phẩm trong đơn hàng
                if (isset($addressData['products'])) {
                    foreach ($addressData['products'] as $productId => $productData) {
                        $product = Product::findOrFail($productId);
                        $orderDetail = OrderDetail::updateOrCreate(
                            [
                                'order_id' => $order->order_id,
                                'product_id' => $productId,
                                'shipping_address_id' => $shippingAddress->id,
                            ],
                            [
                                'product_name' => $product->name,
                                'price_buy' => $product->price,
                                'quantity' => $productData['quantity'],
                            ]
                        );

                        $updatedProductIds[] = $orderDetail->id;
                        $subTotal += $orderDetail->price_buy * $orderDetail->quantity;
                    }
                }
            }

            // Xóa các chi tiết đơn hàng không còn trong request
            OrderDetail::where('order_id', $order->order_id)
                ->whereNotIn('id', $updatedProductIds)
                ->delete();

            // Xóa các địa chỉ giao hàng không còn trong đơn hàng
            $addressesToDelete = $order->shippingAddresses()->whereNotIn('id', $updatedAddressIds)->get();
            foreach ($addressesToDelete as $address) {
                // Xóa các chi tiết đơn hàng liên quan đến địa chỉ này
                $address->orderDetails()->delete();
                // Sau đó xóa địa chỉ
                $address->delete();
            }

            // Tính thuế và tổng tiền
            $tax = $subTotal * 0.1; // Giả sử thuế 10%
            $total = $subTotal + $tax + $totalShipping - $discountAmount;

            // Cập nhật tổng tiền đơn hàng
            $order->update([
                'sub_total' => $subTotal,
                'total' => $total,
                'tax' => $tax,
                'discount_amount' => $discountAmount,
                'ship_charge' => $totalShipping,
            ]);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi cập nhật đơn hàng: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật đơn hàng. Vui lòng thử lại.'])->withInput();
        }
    }

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
                "added_at" => now()
            ];
        }

        session()->put('cart', $cart);
        return ['message' => trans('orders.cart_item_added')];
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            return [
                'success' => true,
                'message' => trans('orders.cart_item_removed'),
                'cart' => $cart,
                'total' => $total
            ];
        }

        return ['success' => false, 'message' => trans('orders.cart_item_not_found')];
    }

    public function showCart()
    {
        $cart = session()->get('cart', []);
        return $cart;
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

            return [
                'success' => true,
                'cart' => $cart,
                'total' => $total
            ];
        }

        return ['success' => false, 'message' => trans('orders.cart_item_not_found')];
    }

    public function showCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        $user = auth()->user()->load('addresses');
        $userAddresses = $user->addresses;

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', trans('orders.cart_empty_checkout'));
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

        $initial_ship_charge = 10000;
        $total_ship_charge = 0;
        $index = 0;
        foreach ($cart as $productId => &$item) {
            $item['ship_charge'] = round($initial_ship_charge * (1 + 0.1 * $index));
            $total_ship_charge += $item['ship_charge'];
            $index++;
        }

        $tax = round($discounted_subtotal * 0.1);
        $total = $discounted_subtotal + $tax + $total_ship_charge;

        // Lưu dữ liệu địa chỉ giao hàng vào session
        $shippingAddresses = [];
        foreach ($cart as $productId => $item) {
            $shippingAddresses[$productId] = [
                'type' => 'existing',
                'address_id' => null,
                'address' => null,
                'city' => null,
                'district' => null,
                'ward' => null,
                'phone_number' => null
            ];
        }
        $request->session()->put('shipping_addresses', $shippingAddresses);

        return compact('cart', 'user', 'sub_total', 'tax', 'total_ship_charge', 'total', 'discount_amount', 'discount_code', 'shippingAddresses', 'userAddresses');
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

        $user = auth()->user();
        $cart = session('cart', []);
        $shippingAddresses = $request->input('shipping_addresses', []);

        // Get shipping addresses from session
        $savedShippingAddresses = session('shipping_addresses', []);

        foreach ($cart as $productId => &$item) {
            if (isset($shippingAddresses[$productId]['type'])) {
                if ($shippingAddresses[$productId]['type'] === 'existing') {
                    $addressId = $shippingAddresses[$productId]['address_id'];
                    $address = UserAddress::find($addressId);

                    if ($address) {
                        $item['shipping_address'] = $address->address . ', ' . $address->ward . ', ' . $address->district . ', ' . $address->city;
                        $shippingAddresses[$productId]['address'] = $address->address;
                        $shippingAddresses[$productId]['city'] = $address->city;
                        $shippingAddresses[$productId]['district'] = $address->district;
                        $shippingAddresses[$productId]['ward'] = $address->ward;
                        $shippingAddresses[$productId]['phone_number'] = $address->phone_number;
                    } else {
                        // Xử lý nếu không tìm thấy địa chỉ
                        return back()->withErrors(['shipping_addresses' => 'Địa chỉ giao hàng không hợp lệ.'])->withInput();
                    }
                } elseif ($shippingAddresses[$productId]['type'] === 'new') {
                    // Đối với địa chỉ mới
                    $item['shipping_address'] = $shippingAddresses[$productId]['address'] . ', ' .
                        $shippingAddresses[$productId]['ward'] . ', ' .
                        $shippingAddresses[$productId]['district'] . ', ' .
                        $shippingAddresses[$productId]['city'];
                }
            } elseif (isset($savedShippingAddresses[$productId])) {
                // Use saved shipping addresses
                $item['shipping_address'] = $savedShippingAddresses[$productId]['address'] . ', ' .
                    $savedShippingAddresses[$productId]['ward'] . ', ' .
                    $savedShippingAddresses[$productId]['district'] . ', ' .
                    $savedShippingAddresses[$productId]['city'];
            }
        }

        $sub_total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $discount_amount = 0;
        if ($request->discount_code) {
            $discount = DiscountCode::where('code', $request->discount_code)->first();
            if ($discount && $discount->isValid()) {
                $discount_amount = $discount->amount ?? ($sub_total * $discount->percentage / 100);
            } else {
                return back()->withErrors(['discount_code' => trans('orders.discount_error')])->withInput();
            }
        }
        $discounted_subtotal = $sub_total - $discount_amount;

        $initial_ship_charge = 10000;
        $total_ship_charge = 0;
        $index = 0;
        foreach ($cart as $productId => &$item) {
            $item['ship_charge'] = round($initial_ship_charge * (1 + 0.1 * $index));
            $total_ship_charge += $item['ship_charge'];
            $index++;
        }

        $tax = round($discounted_subtotal * 0.1);
        $total = $discounted_subtotal + $tax + $total_ship_charge;

        // Lưu dữ liệu preview vào session
        session([
            'order_preview' => [
                'user' => $user,
                'validatedData' => $validatedData,
                'cart' => $cart,
                'sub_total' => $sub_total,
                'tax' => $tax,
                'ship_charge' => $total_ship_charge,
                'discount_amount' => $discount_amount,
                'total' => $total,
                'shippingAddresses' => $shippingAddresses
            ]
        ]);

        return [
            'user' => $user,
            'validatedData' => $validatedData,
            'cart' => $cart,
            'sub_total' => $sub_total,
            'tax' => $tax,
            'ship_charge' => $total_ship_charge,
            'discount_amount' => $discount_amount,
            'total' => $total,
            'shippingAddresses' => $shippingAddresses
        ];
    }

    public function placeOrder(Request $request)
    {
        // Ensure the user accessed the preview page before placing an order
        $previewData = session('order_preview');
        if (!$previewData) {
            return redirect()->route('orders.cart')->with('error', trans('orders.order_failed'));
        }

        $validatedData = $previewData['validatedData'];
        $cart = $previewData['cart'];
        $user = auth()->user();

        // Start transaction
        DB::beginTransaction();

        try {
            $order = new Order();
            $order->user_id = $user->id;
            $order->user_name = $user->name;
            $order->user_email = $user->email;
            $order->phone_number = $validatedData['phone_number'];
            $order->order_number = 'ORD-' . $user->id . '-' . Str::random(4);
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
                    // Validate that address_id is set
                    if (!$addressData['address_id']) {
                        throw new \Exception('Address ID is required for existing address type.');
                    }

                    $userAddress = UserAddress::findOrFail($addressData['address_id']);
                    $shippingAddress = new ShippingAddress([
                        'order_id' => $order->order_id,
                        'phone_number' => $userAddress->phone_number,
                        'address' => $userAddress->address,
                        'city' => $userAddress->city,
                        'district' => $userAddress->district,
                        'ward' => $userAddress->ward,
                        'ship_charge' => (int) $details['ship_charge'], // Ensure ship_charge is an integer
                    ]);
                } else {
                    $shippingAddress = new ShippingAddress([
                        'order_id' => $order->order_id,
                        'phone_number' => $addressData['phone_number'],
                        'address' => $addressData['address'],
                        'city' => $addressData['city'],
                        'district' => $addressData['district'],
                        'ward' => $addressData['ward'],
                        'ship_charge' => (int) $details['ship_charge'], // Ensure ship_charge is an integer
                    ]);

                    // Add new address to user_addresses
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

            // Clear session after processing
            session()->forget(['cart', 'order_preview']);

            // Commit transaction
            DB::commit();

            return redirect()->route('products.index')->with('success', trans('orders.order_success'));

        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollback();
            return redirect()->route('orders.cart')->with('error', trans('orders.order_failed'));
        }
    }


    public function checkDiscount(Request $request)
    {
        $discountCode = $request->input('discount_code');
        $discount = DiscountCode::where('code', $discountCode)->first();

        if ($discount && $discount->isValid()) {
            return [
                'success' => true,
                'discount' => [
                    'amount' => $discount->amount,
                    'percentage' => $discount->percentage
                ]
            ];
        }

        return [
            'success' => false,
            'message' => trans('orders.discount_error')
        ];
    }
}
