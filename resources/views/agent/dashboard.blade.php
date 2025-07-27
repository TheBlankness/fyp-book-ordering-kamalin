@extends('layouts.agent')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">TAN ENG HONG SDN. BHD.</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Submitted Orders</p>
            <p class="text-3xl text-blue-600 mt-2">{{ $submittedOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">In Design</p>
            <p class="text-3xl text-yellow-500 mt-2">{{ $inDesignOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Designs to Approve</p>
            <p class="text-3xl text-pink-500 mt-2">{{ $designsToApprove }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Waiting for Production</p>
            <p class="text-3xl text-orange-500 mt-2">{{ $waitingProductionOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">In Production</p>
            <p class="text-3xl text-indigo-600 mt-2">{{ $inProductionOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Completed Orders</p>
            <p class="text-3xl text-green-600 mt-2">{{ $completedOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Unpaid Orders</p>
            <p class="text-3xl text-red-600 mt-2">{{ $unpaidOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold">Paid Orders</p>
            <p class="text-3xl text-green-700 mt-2">{{ $paidOrders }}</p>
        </div>
    </div>
</div>

@endsection
