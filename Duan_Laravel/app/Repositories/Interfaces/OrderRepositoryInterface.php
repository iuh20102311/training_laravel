<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function all(Request $request);
    public function showOrder(Order $order);
    public function deleteOrder(Order $order);
    public function updateStatus(Request $request, Order $order);
    public function createOrder();
    public function storeOrder(Request $request);
    public function editOrder(Order $order);
    public function updateOrder(Request $request, Order $order);
    public function addToCart(Request $request);
    public function removeFromCart(Request $request);
    public function showCart();
    public function updateQuantity(Request $request);
    public function showCheckout(Request $request);
    public function placeOrder(Request $request);
    public function checkDiscount(Request $request);
    public function preview(Request $request);
}
