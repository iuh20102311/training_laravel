<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Order;


class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->all($request);

        if ($request->ajax()) {
            return view('orders.index', compact('orders'))
                ->fragment('orders-list');
        }

        return view('orders.index', compact('orders'));
    }

    
    public function show(Order $order, )
    {
        $order = $this->orderRepository->showOrder($order);
        $groupedDetails = $order->orderDetails->groupBy('shipping_address_id');
        return view('orders.show', compact('order','groupedDetails'));
    }

    public function destroy(Order $order)
    {
        $response = $this->orderRepository->deleteOrder($order);
        return $response; // Trả về JSON response từ repository
    }

    public function updateStatus(Request $request, Order $order)
    {
        $response = $this->orderRepository->updateStatus($request, $order);
        return $response; 
    }

    public function create()
    {
        $data = $this->orderRepository->createOrder();
        return view('orders.create', $data); // Truyền dữ liệu từ repository vào view
    }

    public function store(CreateOrderRequest $request)
    {
        $order = $this->orderRepository->storeOrder($request);
        return redirect()->route('orders.index')->with('success', trans('orders.order_created_success'));
    }

    public function edit(Order $order)
    {
        $data = $this->orderRepository->editOrder($order);
        return view('orders.edit', $data); 
    }

    public function update(Request $request, Order $order)
    {
        $order = $this->orderRepository->updateOrder($request, $order);
        return redirect()->route('orders.index')->with('success', trans('orders.order_updated_success'));
    }

    public function addToCart(Request $request)
    {
        $response = $this->orderRepository->addToCart($request);
        return response()->json($response); // Trả về JSON response từ repository
    }

    public function removeFromCart(Request $request)
    {
        $response = $this->orderRepository->removeFromCart($request);
        return response()->json($response); 
    }


    public function showCart()
    {
        $cart = $this->orderRepository->showCart();
        return view('orders.cart', compact('cart'));
    }

    public function updateQuantity(Request $request)
    {
        $response = $this->orderRepository->updateQuantity($request);
        return response()->json($response); 
    }


    public function showCheckout(Request $request)
    {
        $data = $this->orderRepository->showCheckout($request);
        return view('orders.checkout', $data);
    }

    public function placeOrder(Request $request)
    {
        return $this->orderRepository->placeOrder($request);
    }

    public function checkDiscount(Request $request)
    {
        $response = $this->orderRepository->checkDiscount($request);
        return response()->json($response);
    }


    public function preview(Request $request)
    {
        $sessionData = $this->orderRepository->preview($request);
        return view('orders.preview', $sessionData);
    }
}
