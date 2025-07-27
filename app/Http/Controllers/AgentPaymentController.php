<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;


class AgentPaymentController extends Controller
{
    // 1. Show payment method selection page
    public function selectMethod($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        return view('agent.payments.select-method', compact('invoice'));
    }

    // 2. Handle method selection and redirect
    public function handleMethod(Request $request, $invoiceId)
    {
        $method = $request->input('method');
        if ($method === 'online_banking') {
            return redirect()->route('agent.payments.online', $invoiceId);
        } elseif ($method === 'cheque') {
            return redirect()->route('agent.payments.cheque', $invoiceId);
        } else {
            return back()->with('error', 'Please select a payment method.');
        }
    }

    // 3. Online Banking page (simulate payment gateway for now)
    public function onlinePaymentSuccess($invoiceId)
    {
        $invoice = \App\Models\Invoice::findOrFail($invoiceId);
        $invoice->status = 'paid';
        $invoice->save();

        // Notify support (e.g., email)
        // You can use Laravel notification, event, or just send an email for now
        Mail::to('support@example.com')->send(new \App\Mail\PaymentReceived($invoice));

        return redirect()->route('agent.payments.show', $invoiceId)
            ->with('success', 'Payment successful! Support has been notified.');
    }

    // 4. Cheque form
    public function chequeForm($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        return view('agent.payments.cheque', compact('invoice'));
    }

    // 5. Handle cheque upload
    public function chequeUpload(Request $request, $invoiceId)
    {
        $request->validate([
            'cheque_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $invoice = \App\Models\Invoice::with(['customOrder.items', 'reorder.items'])->findOrFail($invoiceId);

        // ✅ Always recalculate total
        $total = 0;

        if ($invoice->customOrder) {
            foreach ($invoice->customOrder->items as $item) {
                $total += ($item->price ?? 0) * ($item->quantity ?? 1);
            }
            $total += $invoice->customOrder->delivery_fee ?? 0;
        } elseif ($invoice->reorder) {
            foreach ($invoice->reorder->items as $item) {
                $total += ($item->price ?? 0) * ($item->quantity ?? 1);
            }
            $total += $invoice->reorder->delivery_fee ?? 0;
        }

        $invoice->total_amount = $total;

        // ✅ Store cheque proof
        if ($request->hasFile('cheque_proof')) {
            $path = $request->file('cheque_proof')->store('cheque_proofs', 'public');
            $invoice->cheque_proof = $path;
        }

        $invoice->status = 'pending_cheque';
        $invoice->save();

        // Notify support
        Mail::to('support@example.com')->send(new \App\Mail\ChequeProofUploaded($invoice));

        return redirect()->route('agent.payments.show', $invoiceId)
            ->with('success', 'Cheque proof uploaded and total amount saved. Support has been notified.');
    }

    // 6. (Optional) Existing method: Show invoice/payment status
    public function show($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        return view('agent.payments.show', compact('invoice'));
    }

    // Show the Online Banking page
    public function onlineBanking($invoiceId)
    {
        $agentId = auth()->guard('agent')->user()->id;
        $invoice = Invoice::with(['customOrder', 'reorder'])->findOrFail($invoiceId);

        $valid = false;

        if ($invoice->customOrder && $invoice->customOrder->agent_id === $agentId) {
            $valid = true;
        }

        if ($invoice->reorder && $invoice->reorder->agent_id === $agentId) {
            $valid = true;
        }

        if (!$valid) {
            abort(403, 'Unauthorized');
        }

        return view('agent.payments.online', compact('invoice'));
    }

}
