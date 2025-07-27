<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Reorder;
use App\Models\Invoice;
use App\Models\CustomOrder;

class AgentDashboardController extends Controller
{
    public function index()
{
    $agent = Auth::guard('agent')->user();
    $agentId = $agent->id;

    // Submitted Orders (Custom + Reorder)
    $submittedOrders =
        CustomOrder::where('agent_id', $agentId)->where('status', 'submitted')->count()
        + Reorder::where('agent_id', $agentId)->where('status', 'submitted')->count();

    // In Design (Custom only)
    $inDesignOrders =
        CustomOrder::where('agent_id', $agentId)->where('status', 'assigned')->count();

    // Design to Approve
    $designsToApprove =
        CustomOrder::where('agent_id', $agentId)->where('status', 'design-submitted-to-agent')->count();

    // Waiting for Production (use correct statuses)
    $waitingProductionOrders =
        CustomOrder::where('agent_id', $agentId)->whereIn('status', ['assigned-to-planner'])->count();

    // In Production (Custom + Reorder)
    $inProductionOrders =
        CustomOrder::where('agent_id', $agentId)->where('status', 'in-production')->count()
        + Reorder::where('agent_id', $agentId)->where('status', 'in-production')->count();

    // Completed (Custom + Reorder)
    $completedOrders =
        CustomOrder::where('agent_id', $agentId)->where('status', 'completed')->count()
        + Reorder::where('agent_id', $agentId)->where('status', 'completed')->count();

    // Unpaid Orders (Custom + Reorder)
    $unpaidOrders = Invoice::where('status', 'unpaid')
        ->where(function ($q) use ($agentId) {
            $q->whereHas('customOrder', fn($q) => $q->where('agent_id', $agentId))
            ->orWhereHas('reorder', fn($q) => $q->where('agent_id', $agentId));
        })
        ->count();

    // Paid Orders (Custom + Reorder)
    $paidOrders = Invoice::whereIn('status', ['paid', 'pending_cheque'])
        ->where(function ($q) use ($agentId) {
            $q->whereHas('customOrder', fn($q) => $q->where('agent_id', $agentId))
            ->orWhereHas('reorder', fn($q) => $q->where('agent_id', $agentId));
        })
        ->count();

    return view('agent.dashboard', compact(
        'agent',
        'submittedOrders',
        'inDesignOrders',
        'designsToApprove',
        'waitingProductionOrders',
        'inProductionOrders',
        'completedOrders',
        'unpaidOrders',
        'paidOrders'
    ));
}

}
