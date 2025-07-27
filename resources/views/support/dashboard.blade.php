@extends('layouts.support')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6">TAN ENG HONG SDN. BHD.</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Pending Registrations</p>
            <p class="text-3xl text-blue-600 mt-2">{{ $counts['pending_registrations'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Submitted Orders</p>
            <p class="text-3xl text-gray-600 mt-2">{{ $counts['pending'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Waiting to Assign</p>
            <p class="text-3xl text-yellow-600 mt-2">{{ $counts['waiting_to_assign'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Assigned (In Design)</p>
            <p class="text-3xl text-purple-600 mt-2">{{ $counts['assigned'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Sent to Planner</p>
            <p class="text-3xl text-blue-500 mt-2">{{ $counts['assigned_to_planner'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">In Production</p>
            <p class="text-3xl text-orange-600 mt-2">{{ $counts['in_production'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Completed Orders</p>
            <p class="text-3xl text-indigo-600 mt-2">{{ $counts['completed'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Unpaid Invoices</p>
            <p class="text-3xl text-red-600 mt-2">{{ $counts['unpaid'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Paid Invoices</p>
            <p class="text-3xl text-emerald-600 mt-2">{{ $counts['paid'] }}</p>
        </div>
    </div>
</div>
@endsection
