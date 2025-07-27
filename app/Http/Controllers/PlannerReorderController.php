<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reorder;


class PlannerReorderController extends Controller
{
 public function index()
    {
        $reorders = Reorder::with(['agent', 'school'])
            ->where('status', 'assigned-to-planner')
            ->latest()
            ->get();

        return view('planner.reorders.index', compact('reorders'));
    }

public function show($id)
{
    $reorder = Reorder::with(['agent', 'school', 'items.book'])->findOrFail($id);
    return view('planner.reorders.show', compact('reorder'));
}

}

