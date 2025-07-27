@extends('layouts.designer')

@section('title', 'Designer Dashboard')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">TAN ENG HONG SDN. BHD.</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">New Design Tasks</p>
            <p class="text-4xl text-blue-600 mt-2">{{ $counts['new_designs'] }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Rejected Designs</p>
            <p class="text-4xl text-red-600 mt-2">{{ $counts['rejected_designs'] }}</p>
        </div>

    </div>

</div>
@endsection

