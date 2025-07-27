<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SchoolAgent;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var SchoolAgent $agent */
        $agent = Auth::guard('agent')->user();
        return view('agent.profile.edit', compact('agent'));
    }

    public function update(Request $request)
    {
        /** @var SchoolAgent $agent */
        $agent = Auth::guard('agent')->user();

        $request->validate([
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'website' => 'nullable|string|max:255',
            'business_address' => 'required|string|max:1000',
            'personal_email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'lhdn_tax_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',
            'einvoice_registered' => 'nullable|string|max:50',
            'einvoice_email' => 'nullable|string|max:255',
        ]);

        $agent->update([
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'website' => $request->website,
            'business_address' => $request->business_address,
            'personal_email' => $request->personal_email,
            'phone_number' => $request->phone_number,
            'lhdn_tax_number' => $request->lhdn_tax_number,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'einvoice_registered' => $request->einvoice_registered,
            'einvoice_email' => $request->einvoice_email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }


    public function updatePassword(Request $request)
    {
        /** @var SchoolAgent $agent */
        $agent = Auth::guard('agent')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, $agent->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $agent->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
