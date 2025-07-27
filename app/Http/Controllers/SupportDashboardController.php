<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\CustomOrder;
use App\Models\Reorder;
use App\Models\Invoice;
use App\Models\SchoolAgent;

class SupportDashboardController extends Controller
{
    public function index()
    {
        $counts = [
            // Count CustomOrders + Reorder with 'submitted' status
            'pending' =>
                CustomOrder::where('status', 'submitted')->count() +
                Reorder::where('status', 'submitted')->count(),


            // waiting-to-assign: custom + reorder
            'waiting_to_assign' =>
                CustomOrder::where('status', 'waiting-to-assign')->count() +
                Reorder::where('status', 'waiting-to-assign')->count(),

            // assigned (in design): custom + reorder
            'assigned' =>
                CustomOrder::where('status', 'assigned')->count() +
                Reorder::where('status', 'assigned')->count(),

            // sent to planner (after design approved): custom only
            'assigned_to_planner' =>
                CustomOrder::whereIn('status', ['assigned-to-planner', 'scheduled'])->count() +
                Reorder::whereIn('status', ['assigned-to-planner', 'scheduled'])->count(),

            // in production: custom + reorder
            'in_production' =>
                CustomOrder::where('status', 'in-production')->count() +
                Reorder::where('status', 'in-production')->count(),


            // completed: custom + reorder
            'completed' =>
                CustomOrder::where('status', 'completed')->count() +
                Reorder::where('status', 'completed')->count(),

            // unpaid invoices
            'unpaid' => Invoice::where('status', 'unpaid')->count(),

            // paid invoices
            'paid' => Invoice::whereIn('status', ['paid', 'pending_cheque'])->count(),

            // agent registrations pending approval
            'pending_registrations' => SchoolAgent::where('registration_status', 'Pending')->count(),
        ];

        return view('support.dashboard', compact('counts'));
    }
}
