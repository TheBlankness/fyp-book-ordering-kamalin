@php
    $layout = 'layouts.app'; // fallback

    if (Auth::check()) {
        $role = Auth::user()->role;

        if ($role === 'designer') {
            $layout = 'layouts.designer';
        } elseif ($role === 'planner') {
            $layout = 'layouts.planner';
        } else {
            $layout = 'layouts.support'; // default for others like 'support'
        }
    }
@endphp

@extends($layout)

@section('content')
<div class="py-10 px-8"> {{-- Removed ml/pl-64/72 completely --}}
    <a href="javascript:history.back()"
       class="inline-flex items-center mb-6 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
        Back
    </a>

    <div class="space-y-6">
        {{-- Profile Info --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Password --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
