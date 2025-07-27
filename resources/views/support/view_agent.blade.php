@extends('layouts.app')

@section('title', 'View Agent Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Agent Registration Details</h1>

        <div class="mb-6">
            <strong>Registration ID:</strong> {{ $agent->registration_id }}<br>
            <strong>Registration Status:</strong> {{ ucfirst($agent->registration_status) }}
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-2">Company Information</h2>
        <p><strong>Company Name:</strong> {{ $agent->company_name }}</p>
        <p><strong>SSM Number:</strong> {{ $agent->ssm_number }}</p>
        <p><strong>Old Reg No:</strong> {{ $agent->company_reg_old }}</p>
        <p><strong>TIN:</strong> {{ $agent->tin_number }}</p>
        <p><strong>SST Number:</strong> {{ $agent->sst_number }}</p>
        <p><strong>MSIC Code:</strong> {{ $agent->msic_code }}</p>
        <p><strong>Business Activity:</strong> {{ $agent->business_activity }}</p>
        <p><strong>Business Type:</strong> {{ $agent->business_type }}</p>
        <p><strong>Incorporation Date:</strong> {{ $agent->incorporation_date }}</p>
        <p><strong>Company Email:</strong> {{ $agent->email }}</p>
        <p><strong>Company Phone:</strong> {{ $agent->company_phone }}</p>
        <p><strong>Website:</strong> {{ $agent->website }}</p>
        <p><strong>Business Address:</strong> {{ $agent->business_address }}</p>
        <p><strong>E-Invoice Phase:</strong> {{ $agent->einvoice_phase }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Contact Person</h2>
        <p><strong>Full Name:</strong> {{ $agent->full_name }}</p>
        <p><strong>IC/Passport:</strong> {{ $agent->ic_number }}</p>
        <p><strong>Designation:</strong> {{ $agent->designation }}</p>
        <p><strong>Personal Email:</strong> {{ $agent->personal_email }}</p>
        <p><strong>Phone Number:</strong> {{ $agent->phone_number }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Tax & Bank Info</h2>
        <p><strong>LHDN Tax No:</strong> {{ $agent->lhdn_tax_number }}</p>
        <p><strong>Bank Name:</strong> {{ $agent->bank_name }}</p>
        <p><strong>Account Number:</strong> {{ $agent->bank_account_number }}</p>
        <p><strong>Account Holder:</strong> {{ $agent->bank_account_holder }}</p>
        <p><strong>E-Invoice Registered:</strong> {{ $agent->einvoice_registered }}</p>
        <p><strong>E-Invoice Email:</strong> {{ $agent->einvoice_email }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Attachments</h2>
        <p><strong>SSM Certificate:</strong>
            <a href="{{ asset('storage/' . $agent->ssm_certificate) }}" target="_blank" class="text-blue-600 underline">View</a>
        </p>
        @if($agent->company_logo)
        <p><strong>Company Logo:</strong><br>
            <img src="{{ asset('storage/' . $agent->company_logo) }}" alt="Company Logo" class="h-24 mt-2">
        </p>
        @endif
        <p><strong>Bank Proof:</strong>
            @if($agent->bank_proof)
                <a href="{{ asset('storage/' . $agent->bank_proof) }}" target="_blank" class="text-blue-600 underline">View</a>
            @else
                Not uploaded.
            @endif
        </p>

        {{-- Approval Controls --}}
        <div class="mt-6">
            @if ($agent->registration_status === 'pending')
                <div class="flex gap-4">
                    <form method="POST" action="{{ route('staff.approve-agent', $agent->id) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Approve</button>
                    </form>

                    <form method="POST" action="{{ route('staff.reject-agent', $agent->id) }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Reject</button>
                    </form>
                </div>
            @elseif ($agent->registration_status === 'approved')
                <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded font-semibold">
                    Approved
                </span>
            @elseif ($agent->registration_status === 'rejected')
                <span class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded font-semibold">
                    Rejected
                </span>
            @endif
        </div>
        {{-- Back Button --}}
        <div class="mt-6">
            <a href="{{ url('/staff/pending-agents') }}"
            class="inline-block bg-gray-300 text-gray-800 text-sm py-2 px-4 rounded hover:bg-gray-400">
                ← Back to Pending Agents
            </a>
        </div>
    </div>
</div>
@endsection
