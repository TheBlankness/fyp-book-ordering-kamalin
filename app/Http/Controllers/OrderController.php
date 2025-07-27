<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CustomOrder;
use App\Models\CustomOrderItem;
use App\Models\SchoolAgent;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Book;
use App\Models\Invoice;
use App\Models\Reorder;
use App\Models\ReorderItem;
use Illuminate\Support\Str;
use Carbon\Carbon;



class OrderController extends Controller
{
    /**
     * Display all custom orders for Sales Support.
     */
public function index(Request $request)
{
    $orderStatus = $request->input('order_status');
    $invoiceStatus = $request->input('invoice_status');

    $orders = \App\Models\CustomOrder::with(['agent', 'invoice'])
        ->when($orderStatus, function ($query) use ($orderStatus) {
            $query->where('status', $orderStatus);
        })
        ->when($invoiceStatus, function ($query) use ($invoiceStatus) {
            $query->whereHas('invoice', function ($q) use ($invoiceStatus) {
                $q->where('status', $invoiceStatus);
            });
        })
        ->latest()
        ->paginate(10)
        ->appends([
            'order_status' => $orderStatus,
            'invoice_status' => $invoiceStatus,
        ]);

    return view('orders.index', compact('orders', 'orderStatus', 'invoiceStatus'));
}



    /**
     * Show form to edit a specific custom order.
     */
    public function edit($id)
    {
        $order = CustomOrder::findOrFail($id);

        // Only allow editing if order is still editable
        if (!$order->canEdit()) {
            abort(403, 'This order can no longer be edited.');
        }

        return view('support.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = CustomOrder::findOrFail($id);

        // Only allow updating if order is still editable
        if (!$order->canEdit()) {
            abort(403, 'This order can no longer be edited.');
        }

        // Validate only the fields we need (assign to designer)
        $request->validate([
            'school_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Update the basic info
        $order->school_name = $request->school_name;
        $order->notes = $request->notes;

        // Update the status to "designing" since assigned to designer
        $order->status = 'designing';

        $order->save();

        return redirect()->route('support.orders')->with('success', 'Order assigned to designer and status updated.');
    }





    /**
     * Show catalog to place customized order (Agent).
     */
    public function customizedCatalog()
    {
        $books = [
            ['id' => 1, 'title' => 'Single Line', 'color' => 'Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'single-line.jpg'],
            ['id' => 2, 'title' => 'Small Square', 'color' => 'Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'small-square.jpg'],
            ['id' => 3, 'title' => 'Medium Small Square', 'color' => 'Blue', 'cover' => 'Hard Cover', 'pages' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'medium-small-square.jpg'],
            ['id' => 4, 'title' => 'Medium Square', 'color' => 'Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'medium-square.jpg'],
            ['id' => 5, 'title' => 'R/B 4 Line', 'color' => 'Blue', 'cover' => 'Hard Cover', 'pages' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'rb4-line.jpg'],
            ['id' => 6, 'title' => 'Music Book', 'color' => 'Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'music.jpg'],
            ['id' => 7, 'title' => 'Single Line', 'color' => 'Red', 'cover' => 'Card', 'pages' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'single-line-red.jpg'],
            ['id' => 8, 'title' => 'Small Square', 'color' => 'Green', 'cover' => 'Card', 'pages' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'small-square-green.jpg'],
            ['id' => 9, 'title' => 'Medium Small Square', 'color' => 'Yellow', 'cover' => 'Hard Cover', 'pages' => 80, 'gsm' => 55, 'price' => 2.70, 'image' => 'medium-small-square-yellow.jpg'],
            ['id' => 10, 'title' => 'Medium Square', 'color' => 'Purple', 'cover' => 'Card', 'pages' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'medium-square-purple.jpg'],
            ['id' => 11, 'title' => 'R/B 4 Line', 'color' => 'Orange', 'cover' => 'Card', 'pages' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'rb4-line-orange.jpg'],
            ['id' => 12, 'title' => 'Music Book', 'color' => 'Pink', 'cover' => 'Hard Cover', 'pages' => 80, 'gsm' => 55, 'price' => 2.60, 'image' => 'music-pink.jpg'],
            ['id' => 13, 'title' => 'Single Line', 'color' => 'Green', 'cover' => 'Card', 'pages' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'single-line-green.jpg'],
            ['id' => 14, 'title' => 'Small Square', 'color' => 'Purple', 'cover' => 'Card', 'pages' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'small-square-purple.jpg'],
            ['id' => 15, 'title' => 'Medium Small Square', 'color' => 'Orange', 'cover' => 'Hard Cover', 'pages' => 100, 'gsm' => 55, 'price' => 2.80, 'image' => 'medium-small-square-orange.jpg'],
            ['id' => 16, 'title' => 'Medium Square', 'color' => 'Red', 'cover' => 'Card', 'pages' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'medium-square-red.jpg'],
            ['id' => 17, 'title' => 'R/B 4 Line', 'color' => 'Black', 'cover' => 'Card', 'pages' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'rb4-line-black.jpg'],
            ['id' => 18, 'title' => 'Music Book', 'color' => 'Grey', 'cover' => 'Card', 'pages' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'music-grey.jpg'],
            ['id' => 19, 'title' => 'Half Line', 'color' => 'Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'half-line.jpg'],
            ['id' => 20, 'title' => 'Large Square', 'color' => 'Red', 'cover' => 'Hard Cover', 'pages' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'large-square.jpg'],
            ['id' => 21, 'title' => 'General Exercise Book', 'color' => 'Sky Blue', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'general-exercise.jpg'],
            ['id' => 22, 'title' => 'Writing Book', 'color' => 'Brown', 'cover' => 'Card', 'pages' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'writing.jpg'],
            ['id' => 23, 'title' => 'General Exercise Book', 'color' => 'Silver', 'cover' => 'Card', 'pages' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'general-exercise-silver.jpg'],
            ['id' => 24, 'title' => 'Writing Book', 'color' => 'Maroon', 'cover' => 'Hard Cover', 'pages' => 80, 'gsm' => 55, 'price' => 2.60, 'image' => 'writing-maroon.jpg'],
        ];

        return view('agent.custom-order', compact('books'));
    }

    /**
     * Add one item to the custom cart.
     */
    public function submitCustomizedOrder(Request $request)
    {
        $book = \App\Models\Book::findOrFail($request->id);

        $cart = session()->get('custom_order_cart', []);

        $found = false;

        // Check if the book already exists in the cart
        foreach ($cart as $index => $item) {
            if ($item['book_id'] == $book->id) {
                // If found, update the quantity
                $cart[$index]['quantity'] += (int)$request->quantity;
                $found = true;
                break;
            }
        }

        // If not found, add as a new item
        if (!$found) {
            $cart[] = [
                'book_id' => $book->id,
                'quantity' => (int)$request->quantity,
                'title' => $book->bookType,
                'cover' => $book->coverType,
            ];
        }

        session(['custom_order_cart' => $cart]);

        return redirect()->back()->with('success', 'Book added to cart.');
    }


    /**
     * View current items in cart.
     */
    public function customOrderCart()
    {
        $cart = session('custom_order_cart', []);

        // Attach full book details to each item
        $cartWithBooks = collect($cart)->map(function ($item) {
            $book = \App\Models\Book::find($item['book_id']);
            return [
                'book' => $book,
                'quantity' => $item['quantity'],
            ];
        });

        return view('agent.cart', ['cart' => $cartWithBooks]);
    }


    /**
     * Update or remove cart items.
     */
    public function updateCart(Request $request)
    {
        $cart = session('custom_order_cart', []);

        if ($request->has('remove')) {
            $removeIndex = (int)$request->input('remove');
            if (isset($cart[$removeIndex])) {
                unset($cart[$removeIndex]);
                $cart = array_values($cart);
            }
            session(['custom_order_cart' => $cart]);
            return redirect()->route('agent.custom-order.cart.view')->with('success', 'Item removed from cart.');
        }

        if ($request->has('cart')) {
            foreach ($request->input('cart') as $index => $item) {
                if (isset($cart[$index])) {
                    $cart[$index]['quantity'] = (int)$item['quantity'];
                    $cart[$index]['title'] = $item['title'];
                    $cart[$index]['cover'] = $item['cover'];
                }
            }
            session(['custom_order_cart' => $cart]);
            return redirect()->route('agent.custom-order.cart.view')->with('success', 'Cart updated successfully.');
        }

        return redirect()->route('agent.custom-order.cart.view')->with('success', 'No changes made.');
    }

    /**
     * Show design selection page.
     */
    public function selectDesign()
    {
        $cart = session('custom_order_cart', []);
        if (empty($cart)) {
            return redirect()->route('agent.custom-order.cart.view')->with('error', 'Your cart is empty.');
        }

        $templates = [
            ['name' => 'Modern Blue', 'image' => 'modern-blue.jpg'],
            ['name' => 'Classic Red', 'image' => 'classic-red.jpg'],
            ['name' => 'Floral Pink', 'image' => 'floral-pink.jpg'],
            ['name' => 'Minimal Black', 'image' => 'minimal-black.jpg'],
            ['name' => 'School Theme', 'image' => 'school-theme.jpg'],
        ];

        return view('agent.select-design', compact('templates'));
    }

    /**
     * Final submission of custom order.
     */
public function submitDesign(Request $request)
{
    $request->validate([
        'selected_template' => 'required|string',
        'school_name' => 'required|string|max:255',
        'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'notes' => 'nullable|string',
        'delivery_option' => 'required|in:pickup,delivery',
        'delivery_address' => 'required_if:delivery_option,delivery|max:1000',
    ]);

    // Find or create the school, get its id
    $school = \App\Models\School::firstOrCreate(
        ['name' => $request->school_name],
        []
    );

    $cart = session('custom_order_cart', []);
    if (empty($cart)) {
        return redirect()->route('agent.custom-order.cart.view')->with('error', 'Your cart is empty.');
    }

    // Count total quantity of all books
    $totalBooks = array_sum(array_column($cart, 'quantity'));
    $deliveryFee = 0;
    if ($request->delivery_option === 'delivery') {
        // Example rule: RM200 for every 1000 books (or part thereof)
        $deliveryFee = ceil($totalBooks / 1000) * 200;
    }

    $logoPath = null;
    if ($request->hasFile('school_logo')) {
        $logoPath = $request->file('school_logo')->store('school_logos', 'public');
    }

    // === Calculate Total Amount (books + delivery) ===
    $totalAmount = 0;
    foreach ($cart as $item) {
        $book = \App\Models\Book::find($item['book_id']);
        $price = $book ? $book->price : 0;
        $quantity = $item['quantity'] ?? 1;
        $totalAmount += $price * $quantity;
    }
    $totalAmount += $deliveryFee; // include delivery in total


    // Create the custom order using school_id
    $customOrder = CustomOrder::create([
        'agent_id' => Auth::guard('agent')->user()->id,
        'school_id' => $school->id,
        'design_template' => $request->selected_template,
        'school_logo_path' => $logoPath,
        'notes' => $request->notes,
        'delivery_option' => $request->delivery_option,
        'delivery_address' => $request->delivery_option === 'delivery' ? $request->delivery_address : null,
        'delivery_fee' => $deliveryFee,
        'total_amount' => $totalAmount,
        'submitted_at' => now(),
        'status' => 'submitted',
        'order_type' => 'custom',
    ]);

    $agent = Auth::guard('agent')->user();

    // Create the main order entry
    $order = \App\Models\Order::create([
        'custom_order_id' => $customOrder->id,
        'agent_id' => $agent->id,
        'status' => 'submitted',
        'order_number' => 'ORD-' . strtoupper(uniqid()),
        'submitted_at' => now(),
    ]);

    foreach ($cart as $item) {
        $book = \App\Models\Book::find($item['book_id']);

        \App\Models\CustomOrderItem::create([
            'custom_order_id' => $customOrder->id,
            'book_id' => $item['book_id'],
            'quantity' => $item['quantity'],
            'title' => $item['title'] ?? 'Untitled',
            'cover' => $item['cover'] ?? 'Unknown',
            'price' => $book ? $book->price : 0,
        ]);
    }

    // Clear the cart session
    session()->forget('custom_order_cart');

    return redirect()->route('agent.dashboard')->with('success', 'Your custom order has been submitted!');
}

    public function viewSubmittedOrders()
    {
        $agent = Auth::guard('agent')->user();

        $submittedOrders = \App\Models\CustomOrder::where('agent_id', $agent->id)
            ->whereIn('status', ['design-submitted-to-agent'])
            ->orderByDesc('submitted_at')
            ->get();

        return view('agent.submitted-orders', compact('submittedOrders'));
    }

    public function agentEdit($id)
    {
        $agent = Auth::guard('agent')->user();

        $order = \App\Models\CustomOrder::where('id', $id)
            ->where('agent_id', $agent->id)
            ->firstOrFail();

        // Prevent editing if not allowed
        if (!$order->canEdit()) {
            abort(403, 'This order can no longer be edited.');
        }

        $items = $order->items; // assumes you have a relation set up

        return view('agent.orders.edit', compact('order', 'items'));
    }

    public function agentUpdate(Request $request, $id)
    {
        $agent = Auth::guard('agent')->user();

        $order = \App\Models\CustomOrder::where('id', $id)
            ->where('agent_id', $agent->id)
            ->firstOrFail();

        if (!$order->canEdit()) {
            abort(403, 'This order can no longer be edited.');
        }

        $request->validate([
            'school_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $order->school_name = $request->school_name;
        $order->notes = $request->notes;
        $order->save();

        return redirect()->route('agent.orders.submitted')->with('success', 'Order updated successfully.');
    }

     // View all orders for Sales Support
    public function supportOrders()
    {
        $orders = CustomOrder::with('agent')->latest()->get();
        return view('support.orders.index', compact('orders'));
    }

    // View specific order details
    public function show($id)
    {
        $order = CustomOrder::with(['agent', 'items'])->findOrFail($id);
        return view('support.orders.show', compact('order'));
    }


    public function assignToDesigner($id)
    {
        $order = \App\Models\CustomOrder::findOrFail($id);

        if ($order->status !== 'waiting-to-assign') {
            return back()->with('error', 'Order must be waiting to assign before assigning to designer.');
        }

        $order->status = 'assigned';
        $order->assigned_designer_id = 2; // <- Hardcoded for now (use the designer's user ID)
        $order->save();

        return back()->with('success', 'Order assigned to designer.');
    }

    public function downloadPO($id)
    {
        $customOrder = \App\Models\CustomOrder::with([
            'items.book',
            'agent',
            'school',
            'issuedBy'
        ])->findOrFail($id);

        // Set status if needed (optional)
        if ($customOrder->status !== 'waiting-to-assign') {
            $customOrder->status = 'waiting-to-assign';
        }

        // Save issued_by ONLY ONCE
        if (!$customOrder->issued_by) {
            $customOrder->issued_by = auth()->id();
            $customOrder->save();

            // Reload relationship
            $customOrder->load('issuedBy');
        }

        // Get support name
        $supportName = $customOrder->issuedBy->name ?? '—';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('support.orders.po', [
            'order' => $customOrder,
            'supportName' => $supportName,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('PO_Order_' . $customOrder->id . '.pdf');
    }

public function agentView($id)
{
    $agentId = auth()->guard('agent')->user()->id;

    $customOrder = \App\Models\CustomOrder::with(['items.book', 'school'])
        ->where('id', $id)
        ->where('agent_id', $agentId)
        ->first();

    if ($customOrder) {
        $order = $customOrder;
    } else {
        $reorder = \App\Models\Reorder::with(['items.book', 'school', 'originalOrder.items.book'])
            ->where('id', $id)
            ->where('agent_id', $agentId)
            ->first();

        if ($reorder) {
            $order = $reorder;
            $original = $reorder->originalOrder;
            $order->school_logo_path = $original->school_logo_path ?? null;
            $order->design_template = $original->design_template ?? null;
            $order->notes = $original->notes ?? null;

            if ($order->items->isEmpty() && $original->items->isNotEmpty()) {
                $order->items = $original->items;
            }
        } else {
            abort(404, 'Order not found.');
        }
    }

    // Detect which page it came from
    if (request()->is('agent/orders/past/*')) {
        return view('agent.orders.past-show', compact('order'));
    }

    return view('agent.orders.show', compact('order'));
}



    public function approveDesign($id)
    {
        $order = CustomOrder::findOrFail($id);

        if ($order->status !== 'design-submitted-to-agent') {
            return redirect()->back()->with('error', 'This order cannot be approved.');
        }

        // Change status to assigned-to-planner (so planner will see it)
        $order->status = 'assigned-to-planner';
        $order->save();

        return redirect()->route('agent.orders.submitted')->with('success', 'Design approved and forwarded to planner.');
    }


    public function rejectDesign($id)
    {
        $order = CustomOrder::findOrFail($id);

        if ($order->status !== 'design-submitted-to-agent') {
            return redirect()->back()->with('error', 'This order cannot be rejected.');
        }

        $order->status = 'rejected-by-agent';
        $order->save();

        return redirect()->route('agent.orders.submitted')->with('error', 'Design rejected and sent back to designer.');
    }

    public function plannerAssignedOrders()
    {

        $allOrders = \App\Models\CustomOrder::where('status', 'approved-by-agent')
            ->orderByDesc('submitted_at')
            ->with(['agent', 'school'])
            ->get();

        return view('planner.orders.index', compact('allOrders'));
    }

    public function viewAssignedToPlanner()
    {
        //Only show orders that are still assigned, not scheduled
        $statuses = ['assigned-to-planner'];

        $customOrders = \App\Models\CustomOrder::with(['agent', 'school'])
            ->whereIn('status', $statuses)
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'custom';
                return $arr;
            });

        $reorders = \App\Models\Reorder::with(['agent', 'school'])
            ->whereIn('status', $statuses)
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'reorder';
                return $arr;
            });

        $allOrders = $customOrders->concat($reorders)->sortByDesc('submitted_at')->values();

        return view('planner.orders.index', ['allOrders' => $allOrders]);
    }

    public function showScheduleForm($type, $id)
    {
        // Only allow 'custom' or 'reorder' as types
        if (!in_array($type, ['custom', 'reorder'])) {
            abort(404, 'Invalid order type');
        }

        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::with(['agent', 'school'])->findOrFail($id);
        } else {
            $order = \App\Models\Reorder::with(['agent', 'school', 'items.book'])->findOrFail($id);
        }

        $isScheduled = in_array($order->status, ['scheduled', 'in-production', 'completed']);

        return view('planner.orders.schedule', [
            'order' => $order,
            'type' => $type,
            'isScheduled' => $isScheduled,
        ]);
    }

    public function downloadChecklist($type, $id)
    {
        if (!in_array($type, ['custom', 'reorder'])) {
            abort(404, 'Invalid type');
        }

        $order = $type === 'custom'
            ? \App\Models\CustomOrder::with(['school', 'agent', 'items.book'])->findOrFail($id)
            : \App\Models\Reorder::with(['school', 'agent', 'items.book'])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('planner.orders.checklist-pdf', [
            'order' => $order,
            'type' => $type,
        ]);

        return $pdf->download("Checklist_Order_{$order->id}.pdf");
    }

    public function downloadDesignFile($type, $id)
    {
        $order = $type === 'custom'
            ? CustomOrder::with('school')->findOrFail($id)
            : Reorder::with('school')->findOrFail($id);

        $pdf = Pdf::loadView('planner.orders.design-file-pdf', [
            'order' => $order,
            'type' => $type,
        ]);

        return $pdf->download("Design_Order_{$order->id}.pdf");
    }

    public function saveSchedule(Request $request, $type, $id)
    {
        // Step 1: Validate only date format, not date logic yet
        $request->validate([
            'production_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Step 2: Manual timezone-aware logic
        $productionDate = Carbon::parse($request->input('production_date'));
        $today = Carbon::today('Asia/Kuala_Lumpur');

        if ($productionDate->lt($today)) {
            return redirect()->back()
                ->withErrors(['production_date' => 'You cannot select a past date.'])
                ->withInput();
        }

        // Step 3: Proceed with save
        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::findOrFail($id);
        } elseif ($type === 'reorder') {
            $order = \App\Models\Reorder::findOrFail($id);
        } else {
            abort(404, 'Invalid order type');
        }

        $order->production_date = $request->input('production_date');
        $order->planner_notes = $request->input('notes');
        $order->status = 'scheduled';
        $order->save();

        return redirect()->back()->with('success', ucfirst($type) . ' order scheduled successfully!');
    }


    public function create()
    {
        $schools = \App\Models\School::all();
        // Pass the schools to the view
        return view('agent.orders.create', compact('schools'));
    }

    public function scheduledOrders()
    {
        $customOrders = \App\Models\CustomOrder::with(['agent', 'school'])
            ->where('status', 'scheduled')
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'custom';
                return $arr;
            });

        $reorders = \App\Models\Reorder::with(['agent', 'school'])
            ->where('status', 'scheduled')
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'reorder';
                return $arr;
            });

        $allOrders = $customOrders->concat($reorders);

        return view('planner.orders.scheduled', ['allOrders' => $allOrders]);
    }


    public function updateStatus(Request $request, $type, $id)
    {
        $status = $request->input('status'); // Comes from <input type="hidden" name="status" ...>

        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::findOrFail($id);
        } elseif ($type === 'reorder') {
            $order = \App\Models\Reorder::findOrFail($id);
        } else {
            abort(404, 'Invalid order type');
        }

        if (in_array($status, ['in-production', 'completed'])) {
            $order->status = $status;
            $order->save();
            return redirect()->back()->with('success', 'Order status updated.');
        } else {
            return redirect()->back()->with('error', 'Invalid status.');
        }
    }

    public function inProductionOrders()
    {
        // Custom Orders
        $customOrders = \App\Models\CustomOrder::with(['agent', 'school'])
            ->where('status', 'in-production')
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'custom';
                return $arr;
            });

        // Reorders
        $reorders = \App\Models\Reorder::with(['agent', 'school'])
            ->where('status', 'in-production')
            ->latest()
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'reorder';
                return $arr;
            });

        // Combine both
        $allOrders = $customOrders->concat($reorders);

        // Pass $allOrders to the blade
        return view('planner.orders.in-production', compact('allOrders'));
    }


    public function completedOrders()
    {
        // Get custom orders
        $customOrders = \App\Models\CustomOrder::with(['agent', 'school'])
            ->where('status', 'completed')
            ->orderBy('production_date', 'asc')
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'custom';
                return $arr;
            });

        // Get reorder orders
        $reorders = \App\Models\Reorder::with(['agent', 'school'])
            ->where('status', 'completed')
            ->orderBy('production_date', 'asc')
            ->get()
            ->map(function ($order) {
                $arr = $order->toArray();
                $arr['type'] = 'reorder';
                return $arr;
            });

        // Combine both
        $orders = $customOrders->concat($reorders);

        return view('planner.orders.completed', ['orders' => $orders]);
    }


    public function previewInvoice($type, $id)
    {
        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::with(['items', 'agent', 'school', 'invoice'])->findOrFail($id);
        } elseif ($type === 'reorder') {
            $order = \App\Models\Reorder::with(['items', 'agent', 'school', 'invoice'])->findOrFail($id);
        } else {
            abort(404, 'Order type not found');
        }

        // Generate invoice if not exists
        if (!$order->invoice) {
            $lastInvoice = \App\Models\Invoice::orderBy('id', 'desc')->first();
            $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoiceNumber = 'INV' . date('Ymd') . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // ✅ Include delivery fee if stored on order
            $itemTotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            $deliveryFee = $order->delivery_fee ?? 0;

            $totalAmount = $itemTotal + $deliveryFee;

            $invoice = \App\Models\Invoice::create([
                'order_id'        => $order->id,
                'order_type'      => $type,
                'custom_order_id' => $type === 'custom' ? $order->id : null,
                'reorder_id'      => $type === 'reorder' ? $order->id : null,
                'total_amount'    => $totalAmount,
                'invoice_number'  => $invoiceNumber,
                'status'          => 'draft',
            ]);

            $order->refresh();
        }

        return view('support.orders.preview-invoice', [
            'order' => $order,
            'type'  => $type
        ]);
    }

    public function saveInvoice(Request $request, $type, $id)
    {
        // Step 1: Load the correct order model based on type
        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::with('items', 'invoice')->findOrFail($id);
        } elseif ($type === 'reorder') {
            $order = \App\Models\Reorder::with('items', 'invoice')->findOrFail($id);
        } else {
            abort(404, 'Order type not found');
        }

        // Step 2: Check if invoice already exists
        $invoice = $order->invoice;

        // Step 3: If invoice doesn't exist, create one
        if (!$invoice) {
            $invoiceData = [
                'order_id'       => $order->id,
                'order_type'     => $type,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'issue_date'     => now(),
                'total_amount'   => 0,
                'status'         => 'draft',
            ];

            if ($type === 'custom') {
                $invoiceData['custom_order_id'] = $order->id;
            } elseif ($type === 'reorder') {
                $invoiceData['reorder_id'] = $order->id;
            }

            $invoice = \App\Models\Invoice::create($invoiceData);
            $order->refresh();
        }

        // Step 4: Save updated quantities & prices for items
        foreach ($order->items as $item) {
            $item->quantity = isset($request->quantities[$item->id]) ? (int) $request->quantities[$item->id] : $item->quantity;
            $item->price = isset($request->prices[$item->id]) ? (float) $request->prices[$item->id] : $item->price;
            $item->save();
        }

        // Step 5: Handle additional charges
        $additionalCharges = [];
        $add_titles = $request->input('add_titles', []);
        $add_types = $request->input('add_types', []);
        $add_quantities = $request->input('add_quantities', []);
        $add_prices = $request->input('add_prices', []);

        $subtotal = 0;
        $extraChargeTotal = 0;

        // Calculate subtotal from updated items
        foreach ($order->items as $item) {
            $subtotal += ($item->quantity ?? 0) * ($item->price ?? 0);
        }

        // Calculate subtotal from additional charges
        for ($i = 0; $i < count($add_titles); $i++) {
            $qty = isset($add_quantities[$i]) ? (float) $add_quantities[$i] : 1;
            $price = isset($add_prices[$i]) ? (float) $add_prices[$i] : 0;

            $additionalCharges[] = [
                'title'    => $add_titles[$i] ?? '',
                'type'     => $add_types[$i] ?? '',
                'quantity' => $qty,
                'price'    => $price,
            ];

            $extraChargeTotal += $qty * $price;
        }

        // Get delivery fee from the order
        $deliveryFee = $order->delivery_fee ?? 0;

        // Step 6: Calculate grand total
        $grandTotal = $subtotal + $extraChargeTotal + $deliveryFee;

        // Step 7: Save updated invoice
        $invoice->additional_charges = json_encode($additionalCharges);
        $invoice->total_amount = $grandTotal;
        $invoice->status = 'draft';
        $invoice->save();

        return back()->with('success', 'Invoice saved as draft.');
    }

    public function sendInvoice(Request $request, $type, $id)
    {
        if ($type === 'custom') {
            $order = \App\Models\CustomOrder::with(['items', 'invoice', 'agent'])->findOrFail($id);
        } elseif ($type === 'reorder') {
            $order = \App\Models\Reorder::with(['items', 'invoice', 'agent'])->findOrFail($id);
        } else {
            abort(404, 'Order type not found');
        }

        $invoice = $order->invoice;

        // Prevent duplicate invoice sending
        if ($invoice && $invoice->status === 'unpaid') {
            return back()->with('error', 'Invoice already sent for this order.');
        }

        // 🧠 Calculate item total
        $totalAmount = $order->items->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 1);
        });

        // 🧠 Include additional charges
        $additionalCharges = [];
        if ($invoice && $invoice->additional_charges) {
            $additionalCharges = json_decode($invoice->additional_charges, true);
        }

        if (!empty($additionalCharges)) {
            foreach ($additionalCharges as $charge) {
                $totalAmount += ($charge['price'] ?? 0) * ($charge['quantity'] ?? 1);
            }
        }

        // ✅ Include delivery fee if present
        $deliveryFee = $order->delivery_fee ?? 0;
        $totalAmount += $deliveryFee;

        if (!$invoice) {
            $invoice = \App\Models\Invoice::create([
                'order_id'        => $order->id,
                'order_type'      => $type,
                'custom_order_id' => $type === 'custom' ? $order->id : null,
                'reorder_id'      => $type === 'reorder' ? $order->id : null,
                'invoice_number'  => 'INV-' . strtoupper(uniqid()),
                'issue_date'      => now(),
                'total_amount'    => $totalAmount,
                'status'          => 'unpaid',
            ]);
        } else {
            $invoice->status = 'unpaid';
            $invoice->issue_date = now();
            $invoice->total_amount = $totalAmount;
            $invoice->save();
        }

        // Redirect
        if ($type === 'custom') {
            return redirect()->route('support.orders.show', $order->id)
                ->with('success', 'Invoice sent to agent!');
        } else {
            return redirect()->route('support.reorders.show', $order->id)
                ->with('success', 'Invoice sent to agent!');
        }
    }


    public function downloadInvoice($type, $id)
    {
        $order = $type === 'custom'
            ? \App\Models\CustomOrder::with(['invoice', 'items.book', 'agent', 'school'])->findOrFail($id)
            : \App\Models\Reorder::with(['invoice', 'items.book', 'agent', 'school'])->findOrFail($id);

        if (!$order->invoice || $order->invoice->status !== 'unpaid') {
            abort(403, 'Invoice not sent or does not exist.');
        }

        $invoice = $order->invoice;

        $pdf = Pdf::loadView('support.invoices.pdf', compact('order', 'type', 'invoice'));
        return $pdf->download('Invoice_' . $invoice->invoice_number . '.pdf');
    }

    // Show past custom orders for this agent (reorder source)
    public function pastOrders()
        {
            $agentId = auth()->guard('agent')->user()->id;

            // Load custom orders with invoice and items
            $customOrders = \App\Models\CustomOrder::with(['items', 'invoice'])
                ->where('agent_id', $agentId)
                ->whereIn('status', ['completed', 'paid', 'submitted'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Load reorders with invoice and items
            $reorders = \App\Models\Reorder::with(['items', 'invoice'])
                ->where('agent_id', $agentId)
                ->whereIn('status', ['completed', 'paid', 'submitted'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('agent.orders.past-orders', compact('customOrders', 'reorders'));
        }



    public function reorder($id)
    {
        $agentId = auth()->guard('agent')->user()->id;

        $customOrder = \App\Models\CustomOrder::where('id', $id)
            ->where('agent_id', $agentId)
            ->with('items')
            ->firstOrFail();

        // Prepare cart items
        $cart = [];
        foreach ($customOrder->items as $item) {
            $cart[] = [
                'book_id'   => $item->book_id,
                'title'     => $item->title,
                'cover'     => $item->cover,
                'quantity'  => $item->quantity,
                'book'      => \App\Models\Book::find($item->book_id),
            ];
        }
        session(['custom_order_cart' => $cart]);

        // Save previous design info in session
        session([
            'reorder_design_info' => [
                'school_name'       => $customOrder->school->name ?? '',
                'selected_template' => $customOrder->design_template,
                'school_logo_path'  => $customOrder->school_logo_path,
                'notes'             => $customOrder->notes,
                'delivery_option'   => $customOrder->delivery_option,
                'delivery_address'  => $customOrder->delivery_address,
            ]
        ]);

        return redirect()->route('agent.custom-order.cart.view')
            ->with('success', 'Order items and design copied from previous order! You can proceed to checkout.');
    }

    // Show unique schools the agent has ever ordered for (no duplicates)
    public function pastSchools(Request $request)
    {
        $agentId = auth()->guard('agent')->user()->id;
        $search = $request->input('school');

        // Fetch completed custom orders with pending_cheque invoices
        $schoolOrders = \App\Models\CustomOrder::where('agent_id', $agentId)
            ->whereNotNull('school_id')
            ->where('status', 'completed')
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'pending_cheque');
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('school', function ($q) use ($search) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            })
            ->with(['school', 'invoice'])
            ->orderByDesc('created_at')
            ->get()
            ->unique('school_id'); // Ensure one row per school

        return view('agent.orders.past-schools', compact('schoolOrders', 'search'));
    }


    // When agent clicks "Order" next to a school: find the last custom order for that school, copy design info, go to catalog.
    public function reorderBySchool($school_id)
    {
        $agentId = auth()->guard('agent')->user()->id;

        $lastOrder = \App\Models\CustomOrder::where('agent_id', $agentId)
            ->where('school_id', $school_id)
            ->latest('created_at')
            ->first();

        if (!$lastOrder) {
            return back()->with('error', 'No previous order found for this school.');
        }

        session([
            'reorder_design_info' => [
                'school_name'       => $lastOrder->school->name ?? '',
                'selected_template' => $lastOrder->design_template,
                'school_logo_path'  => $lastOrder->school_logo_path,
                'design_file'       => $lastOrder->design_file,
                'notes'             => $lastOrder->notes,
                'delivery_option'   => $lastOrder->delivery_option,
                'delivery_address'  => $lastOrder->delivery_address,
            ],
            'reorder_school_id' => $school_id,
            'reorder_original_order_id' => $lastOrder->id,
        ]);

        session()->forget('custom_order_cart');

        return redirect()->route('agent.orders.reorder.catalog')
            ->with('success', 'Please select books to reorder for ' . ($lastOrder->school->name ?? 'the school') . '. The previous design will be reused.');
    }

    public function viewReorderCart()
    {
        $cart = session('custom_order_cart', []);
        return view('agent.orders.reorder-cart', compact('cart'));
    }

public function submitReorder(Request $request)
{


    $agentId = auth()->guard('agent')->user()->id;
    $cart = session('custom_order_cart', []);
    $design = session('reorder_design_info', []);
    $originalOrderId = session('reorder_original_order_id');

    if (!$originalOrderId && session('reorder_school_id')) {
        $lastOrder = \App\Models\CustomOrder::where('agent_id', $agentId)
            ->where('school_id', session('reorder_school_id'))
            ->latest('created_at')
            ->first();

        if ($lastOrder) {
            $originalOrderId = $lastOrder->id;
        }
    }

    if (empty($cart) || empty($design)) {
        return redirect()->route('agent.orders.reorder.cart')
            ->with('error', 'Cart or design information missing. Please try again.');
    }

    $validated = $request->validate([
        'delivery_option' => 'required|in:pickup,delivery',
        'delivery_address' => 'required_if:delivery_option,delivery|max:1000',
    ]);

    $school = \App\Models\School::firstOrCreate(
        ['name' => $design['school_name']],
        []
    );

    $totalBooks = array_sum(array_column($cart, 'quantity'));
    $deliveryFee = $validated['delivery_option'] === 'delivery'
        ? ceil($totalBooks / 1000) * 200
        : 0;

    // ✅ Save reorder info
    $reorder = \App\Models\Reorder::create([
        'original_custom_order_id' => $originalOrderId ?? null,
        'agent_id' => $agentId,
        'school_id' => $school->id,
        'design_file' => $design['design_file'] ?? null,
        'notes' => $design['notes'] ?? null,
        'delivery_option' => $validated['delivery_option'],
        'delivery_address' => $validated['delivery_option'] === 'delivery' ? $validated['delivery_address'] : null,
        'delivery_fee' => $deliveryFee,
        'status' => 'submitted',
        'submitted_at' => now(),
    ]);

    // ✅ Save into orders table
    \App\Models\Order::create([
        'order_type'      => 'reorder',
        'reorder_id'      => $reorder->id,
        'custom_order_id' => null,
        'agent_id'        => $agentId,
        'status'          => 'submitted',
        'order_number'    => 'ORD-' . strtoupper(Str::random(12)),
        'submitted_at'    => now(),
    ]);

    // ✅ Save reorder items
    foreach ($cart as $item) {
        $book = \App\Models\Book::find($item['book_id']);

        \App\Models\ReorderItem::create([
            'reorder_id' => $reorder->id,
            'book_id' => $item['book_id'],
            'title' => $item['title'] ?? $book->bookType ?? 'Untitled',
            'cover' => $item['cover'] ?? $book->coverType ?? 'Unknown',
            'quantity' => $item['quantity'] ?? 1,
            'price' => $book->price ?? 0,
        ]);
    }

    session()->forget('custom_order_cart');
    session()->forget('reorder_design_info');
    session()->forget('reorder_original_order_id');

    return redirect()->route('agent.dashboard')->with('success', 'Your reorder has been submitted!');
}


    public function addToReorderCart(Request $request)
    {
        $cart = session('custom_order_cart', []);

        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity', 1);

        $book = \App\Models\Book::find($bookId);
        if ($book) {
            $found = false;
            for ($i = 0; $i < count($cart); $i++) {
                if ($cart[$i]['book_id'] == $bookId) {
                    $cart[$i]['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = [
                    'book_id' => $book->id,
                    'book' => $book,
                    'quantity' => $quantity,
                    'title' => $book->bookType,
                    'cover' => $book->coverType,
                ];
            }
            session(['custom_order_cart' => $cart]);
        }

        // Redirect back to reorder catalog, NOT cart
        return redirect()->route('agent.orders.reorder.catalog')
            ->with('success', 'Book added to cart!');
    }


    public function viewReorderCatalog()
    {
        $books = \App\Models\Book::all();
        return view('agent.orders.reorder-catalog', compact('books'));
    }

    public function updateReorderCart(Request $request)
    {
        // Update the quantities or handle removal in the reorder cart
        $cart = session('custom_order_cart', []);
        $updatedCart = [];

        // Loop through the posted cart and update quantities
        if ($request->has('cart')) {
            foreach ($request->input('cart') as $item) {
                $bookId = $item['book_id'] ?? null;
                $qty = isset($item['quantity']) ? max(1, (int)$item['quantity']) : 1;

                // Find matching item in the current cart
                foreach ($cart as $cartItem) {
                    if ($cartItem['book_id'] == $bookId) {
                        $cartItem['quantity'] = $qty;
                        $updatedCart[] = $cartItem;
                        break;
                    }
                }
            }
        }

        // Save updated cart to session
        session(['custom_order_cart' => $updatedCart]);

        return redirect()->route('agent.orders.reorder.cart')->with('success', 'Cart updated!');
    }

    public function viewStatus(Request $request)
    {
        $agentId = auth()->guard('agent')->user()->id;
        $status = $request->input('status');

        // Custom Orders
        $customOrders = \App\Models\CustomOrder::with(['items.book', 'school'])
            ->where('agent_id', $agentId)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereDoesntHave('invoice', function ($query) {
                $query->where('status', 'pending_cheque');
            })
            ->latest()
            ->get()
            ->each(function ($order) {
                $order->setAttribute('type', 'custom');
            });

        // Reorders
        $reorders = \App\Models\Reorder::with(['items.book', 'school'])
            ->where('agent_id', $agentId)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereDoesntHave('invoice', function ($query) {
                $query->where('status', 'pending_cheque');
            })
            ->latest()
            ->get()
            ->each(function ($order) {
                $order->setAttribute('type', 'reorder');
            });

        // Merge & Sort
        $orders = $customOrders->merge($reorders)->sortByDesc('submitted_at');

        return view('agent.orders.status', compact('orders', 'status'));
    }



    public function viewPastOrders(Request $request)
    {
        $agentId = auth()->guard('agent')->user()->id;
        $schoolName = $request->input('school');

        $customOrders = \App\Models\CustomOrder::with(['items.book', 'invoice', 'school'])
            ->where('agent_id', $agentId)
            ->where('status', 'completed')
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'pending_cheque');
            })
            ->when($schoolName, function ($query) use ($schoolName) {
                $query->whereHas('school', function ($q) use ($schoolName) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($schoolName) . '%']);
                });
            })
            ->get();

        $reorders = \App\Models\Reorder::with(['items.book', 'originalOrder', 'invoice', 'school'])
            ->where('agent_id', $agentId)
            ->where('status', 'completed')
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'pending_cheque');
            })
            ->when($schoolName, function ($query) use ($schoolName) {
                $query->whereHas('school', function ($q) use ($schoolName) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($schoolName) . '%']);
                });
            })
            ->get();

        return view('agent.orders.past', compact('customOrders', 'reorders', 'schoolName'));
    }


    public function plannerDashboard()
    {
        $counts = [
            'assigned' => \App\Models\CustomOrder::where('status', 'assigned-to-planner')->count()
                            + \App\Models\Reorder::where('status', 'assigned-to-planner')->count(),

            'scheduled' => \App\Models\CustomOrder::where('status', 'scheduled')->count()
                            + \App\Models\Reorder::where('status', 'scheduled')->count(),

            'in_production' => \App\Models\CustomOrder::where('status', 'in-production')->count()
                            + \App\Models\Reorder::where('status', 'in-production')->count(),

            'completed' => \App\Models\CustomOrder::where('status', 'completed')->count()
                            + \App\Models\Reorder::where('status', 'completed')->count(),
        ];

        return view('planner.dashboard', compact('counts'));
    }

    public function storeReorder(Request $request)
    {
        // Create Reorder first
        $reorder = Reorder::create([
            'school_id' => $request->school_id,
            'agent_id' => auth()->id(),
            'status' => 'submitted',
            // add other fields here...
        ]);

        // Create corresponding record in orders table
        \App\Models\Order::create([
            'reorder_id' => $reorder->id,
            'agent_id' => auth()->id(),
            'status' => 'submitted',
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            // 'custom_order_id' => null, // not needed
        ]);

        return redirect()->route('support.reorders.index')->with('success', 'Reorder created successfully.');
    }



}
