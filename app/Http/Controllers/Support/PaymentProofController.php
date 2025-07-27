<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\CustomOrder;
use App\Models\Reorder;

class PaymentProofController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with([
            'customOrder.agent',
            'reorder.agent',
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('support.payment-proofs.index', compact('invoices'));
    }
}
