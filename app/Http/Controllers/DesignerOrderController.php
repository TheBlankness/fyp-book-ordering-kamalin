<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DesignerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('assigned_to', Auth::id())
                       ->where('status', 'assigned')
                       ->with(['agent', 'items'])
                       ->latest()
                       ->get();

        return view('designer.orders.index', compact('orders'));
    }
}
