@extends('layouts.planner')

@section('title', 'Planner Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">TAN ENG HONG SDN. BHD.</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Assigned Orders</p>
            <p class="text-4xl text-indigo-600 mt-2">{{ $counts['assigned'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Scheduled Orders</p>
            <p class="text-4xl text-blue-600 mt-2">{{ $counts['scheduled'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">In Production</p>
            <p class="text-4xl text-orange-500 mt-2">{{ $counts['in_production'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Completed Orders</p>
            <p class="text-4xl text-green-600 mt-2">{{ $counts['completed'] }}</p>
        </div>

    </div>
</div>
@endsection
