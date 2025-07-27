@extends('layouts.support')

@section('title', 'Pending Agent Registrations')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Pending Agent Registrations</h1>

    @if ($agents->isEmpty())
        <p class="text-gray-600 text-sm">No pending registrations.</p>
    @else
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-xs font-semibold uppercase">
                    <tr>
                        <th class="px-6 py-3">Reg ID</th>
                        <th class="px-6 py-3">Company Name</th>
                        <th class="px-6 py-3">Contact Person</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agents as $agent)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $agent->registration_id }}</td>
                            <td class="px-6 py-4">{{ $agent->company_name }}</td>
                            <td class="px-6 py-4">{{ $agent->full_name }}</td>
                            <td class="px-6 py-4">{{ $agent->company_email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('staff.view-agent', $agent->id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-1.5 rounded">
                                        View
                                    </a>

                                    <form method="POST" action="{{ route('staff.approve-agent', $agent->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-1.5 rounded">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('staff.reject-agent', $agent->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-1.5 rounded">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
