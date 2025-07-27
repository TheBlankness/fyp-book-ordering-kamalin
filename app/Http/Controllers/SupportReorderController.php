<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reorder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SupportReorderController extends Controller
{
    
    public function index(Request $request)
    {
        $orderStatus = $request->input('order_status');
        $invoiceStatus = $request->input('invoice_status');

        $reorders = \App\Models\Reorder::with(['agent', 'school', 'originalOrder', 'invoice'])
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

        return view('support.reorders.index', compact('reorders', 'orderStatus', 'invoiceStatus'));
    }


    public function show($id)
    {
        $reorder = Reorder::with(['agent', 'school', 'originalOrder', 'items.book'])->findOrFail($id);

        return view('support.reorders.show', compact('reorder'));
    }

public function downloadPO($id)
{
    $reorder = \App\Models\Reorder::with(['items.book', 'agent', 'school', 'issuedBy'])->findOrFail($id);

    // Save issued_by if it's not already saved
    if (!$reorder->issued_by) {
        $reorder->issued_by = auth()->id();
        $reorder->save();

        // 🔁 Reload relationship immediately after save
        $reorder->load('issuedBy');
    }

    $supportName = $reorder->issuedBy->name ?? '—';

    $pdf = Pdf::loadView('pdf.po-reorder', [
        'reorder' => $reorder,
        'supportName' => $supportName,
    ]);

    return $pdf->download('PO_Reorder_' . $reorder->id . '.pdf');
}


    public function assignToPlanner($id)
    {
        $reorder = \App\Models\Reorder::findOrFail($id);

        // Only allow if it's still in submitted status
        if ($reorder->status !== 'submitted') {
            return back()->with('error', 'This reorder has already been assigned.');
        }

        $reorder->status = 'assigned-to-planner';
        $reorder->save();

        return redirect()->route('support.reorders.index')->with('success', 'Reorder assigned to planner successfully.');
    }






}
