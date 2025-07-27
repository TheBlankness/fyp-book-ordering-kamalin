<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignedOrderController extends Controller
{
    public function index(Request $request)
    {
        $designerId = auth()->id();
        $status = $request->input('status');

        $orders = \App\Models\CustomOrder::whereIn('status', [
                'assigned',
                'design-submitted-to-agent',
                'rejected-by-agent'
            ])
            ->where('assigned_designer_id', $designerId)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends(['status' => $status]);

        return view('designer.orders.index', compact('orders', 'status'));
    }



    public function show($id)
    {
        $order = \App\Models\CustomOrder::where('id', $id)
            ->whereIn('status', ['assigned', 'design-submitted-to-agent', 'rejected-by-agent'])
            ->where('assigned_designer_id', auth()->id())
            ->with(['agent', 'conversation']) // Load agent and conversation
            ->firstOrFail();

        return view('designer.orders.show', compact('order'));
    }


    public function uploadDesign(Request $request, $id)
    {
        $request->validate([
            'design_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $order = CustomOrder::where('id', $id)
            ->where('assigned_designer_id', auth()->id())
            ->firstOrFail();

        // Store design
        $path = $request->file('design_file')->store('designs', 'public');

        $order->design_file = $path;
        $order->status = 'design-submitted-to-agent';
        $order->save();

        return redirect()->route('designer.orders.index')->with('success', 'Design submitted successfully.');
    }

    public function dashboard()
    {
        $designerId = auth()->id();

        $counts = [
            'new_designs' => \App\Models\CustomOrder::where('status', 'assigned')
                                ->where('assigned_designer_id', $designerId)
                                ->count(),

            'rejected_designs' => \App\Models\CustomOrder::where('status', 'rejected-by-agent')
                                ->where('assigned_designer_id', $designerId)
                                ->count(),
        ];

        return view('designer.dashboard', compact('counts'));
    }

}
