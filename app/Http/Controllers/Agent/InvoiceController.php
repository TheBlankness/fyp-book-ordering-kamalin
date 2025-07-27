<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{

public function index()
{
    $invoices = Invoice::with(['customOrder', 'reorder'])
        ->where('status', 'unpaid')
        ->get();

    return view('agent.invoices.index', [
        'invoices' => $invoices
    ]);
}


}
