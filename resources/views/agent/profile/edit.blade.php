@extends('layouts.agent')

@section('title', 'Profile')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-6">

    {{-- Company Details --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <h2 class="text-lg font-medium text-gray-900">Company Details</h2>

        <form method="POST" action="{{ route('agent.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-readonly-input label="Company Name" :value="$agent->company_name" />
                <x-readonly-input label="Company Reg No (New)" :value="$agent->ssm_number" />
                <x-readonly-input label="Company Reg No (Old)" :value="$agent->company_reg_old" />
                <x-readonly-input label="TIN Number" :value="$agent->tin_number" />
                <x-readonly-input label="SST Number" :value="$agent->sst_number" />
                <x-readonly-input label="MSIC Code" :value="$agent->msic_code" />
                <x-readonly-input label="Business Activity" :value="$agent->business_activity" />
                <x-readonly-input label="Business Type" :value="$agent->business_type" />
                <x-readonly-input label="Incorporation Date" :value="$agent->incorporation_date" />
                <x-editable-input label="Company Email" name="email" type="email" :value="$agent->email" required />
                <x-editable-input label="Company Phone" name="company_phone" :value="$agent->company_phone" required />
                <x-editable-input label="Website" name="website" :value="$agent->website" />
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Business Address</label>
                <textarea name="business_address" required class="block w-full rounded-md border-gray-300 shadow-sm mt-1">{{ $agent->business_address }}</textarea>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">E-Invoice Phase</label>
                <div class="mt-1">{{ $agent->einvoice_phase }}</div>
            </div>

            {{-- Personal Info --}}
            <h2 class="text-lg font-medium text-gray-900 mt-8">Personal Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <x-readonly-input label="Full Name" :value="$agent->full_name" />
                <x-readonly-input label="IC/Passport Number" :value="$agent->ic_number" />
                <x-readonly-input label="Designation" :value="$agent->designation" />
                <x-editable-input label="Personal Email" name="personal_email" type="email" :value="$agent->personal_email" required />
                <x-editable-input label="Phone Number" name="phone_number" :value="$agent->phone_number" required />
            </div>

            {{-- Tax & Bank Info --}}
            <h2 class="text-lg font-medium text-gray-900 mt-8">Tax & Bank Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <x-editable-input label="LHDN Tax Number" name="lhdn_tax_number" :value="$agent->lhdn_tax_number" />
                <x-editable-input label="Bank Name" name="bank_name" :value="$agent->bank_name" />
                <x-editable-input label="Bank Account Number" name="bank_account_number" :value="$agent->bank_account_number" />
                <x-editable-input label="Account Holder Name" name="bank_account_holder" :value="$agent->bank_account_holder" />
                <x-editable-input label="Is e-Invoice Registered?" name="einvoice_registered" :value="$agent->einvoice_registered" />
                <x-editable-input label="e-Invoice Email / Endpoint" name="einvoice_email" :value="$agent->einvoice_email" />
            </div>

            {{-- Login Info --}}
            <h2 class="text-lg font-medium text-gray-900 mt-8">Login Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <x-readonly-input label="Username" :value="$agent->username" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
        <form method="POST" action="{{ route('agent.profile.updatePassword') }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <x-password-input label="Current Password" name="current_password" required />
            <x-password-input label="New Password" name="password" required />
            <x-password-input label="Confirm Password" name="password_confirmation" required />

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 transition">
                    Save Password
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all resources and data will be permanently removed. Download any important data before proceeding.
        </p>
        <form method="POST" action="{{ route('agent.profile.destroy') }}" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 transition">
                Delete Account
            </button>
        </form>
    </div>

</div>
@endsection
