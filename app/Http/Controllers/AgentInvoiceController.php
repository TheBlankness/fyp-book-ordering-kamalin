<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentInvoiceController extends Controller
{
    public function show($id)
    {
        $invoice = \App\Models\Invoice::with(['customOrder.agent', 'customOrder.items', 'reorder.agent', 'reorder.items'])
            ->findOrFail($id);

        $agentId = auth()->guard('agent')->user()->id;

        if ($invoice->customOrder && $invoice->customOrder->agent_id !== $agentId) {
            abort(403, 'Unauthorized access.');
        }

        if ($invoice->reorder && $invoice->reorder->agent_id !== $agentId) {
            abort(403, 'Unauthorized access.');
        }

        return view('agent.invoices.show', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = \App\Models\Invoice::with(['customOrder.agent', 'customOrder.items', 'reorder.agent', 'reorder.items'])
            ->findOrFail($id);

        $agentId = auth()->guard('agent')->user()->id;

        if ($invoice->customOrder && $invoice->customOrder->agent_id !== $agentId) {
            abort(403, 'Unauthorized access.');
        }

        if ($invoice->reorder && $invoice->reorder->agent_id !== $agentId) {
            abort(403, 'Unauthorized access.');
        }

        $type = $invoice->customOrder ? 'custom' : 'reorder';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('support.invoices.pdf', [
            'order' => $invoice->{$type},
            'type' => $type,
            'invoice' => $invoice,
        ]);

        return $pdf->download('Invoice_'.$invoice->invoice_number.'.pdf');
    }



}
