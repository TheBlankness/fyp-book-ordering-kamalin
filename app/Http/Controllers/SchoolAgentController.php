<?php

namespace App\Http\Controllers;

use App\Models\SchoolAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SchoolAgentController extends Controller
{
    public function create()
    {
        return view('agent.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Company info
            'company_name' => 'required|string|max:255',
            'ssm_number' => 'required|regex:/^[0-9]{12}$/|unique:school_agents',
            'company_reg_old' => 'nullable|string|max:50',
            'tin_number' => 'required|regex:/^[0-9]{10,12}$/',
            'sst_number' => 'required|regex:/^ST-\d{6}$/',
            'msic_code' => 'required|regex:/^\d{5}$/',
            'business_activity' => 'required|string|max:255',
            'business_type' => 'required|string|in:Sole Proprietor,Partnership,Sdn. Bhd.,Berhad,LLP',
            'incorporation_date' => 'required|date',
            'email' => 'required|email|unique:school_agents',
            'company_phone' => 'required|regex:/^\d{9,11}$/',
            'website' => 'nullable|url|max:255',
            'business_address' => 'required|string|max:1000',
            'einvoice_phase' => 'required|in:2024-08-01,2025-01-01,2025-07-01',

            // Personal info
            'full_name' => 'required|string|max:100',
            'ic_number' => 'required|regex:/^\d{6}-\d{2}-\d{4}$/',
            'designation' => 'required|string|in:Director,Manager,Owner,Partner,Agent,Others',
            'personal_email' => 'required|email|max:100',
            'phone_number' => 'required|regex:/^\d{9,11}$/',

            // Tax & bank info
            'lhdn_tax_number' => 'required|regex:/^[A-Z]{1,2}[0-9]{7,10}$/|max:15',
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|numeric',
            'bank_account_holder' => 'required|string|max:100',
            'einvoice_registered' => 'required|in:Yes,No',
            'einvoice_email' => 'required|email|max:100',

            // Login info
            'username' => 'required|string|max:50|unique:school_agents',
            'password' => 'required|string|min:8|confirmed',

            // Files
            'ssm_certificate' => 'required|file|mimes:pdf|max:2048',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_proof' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ], [
            // Custom error messages
            'ssm_number.regex' => 'Company Reg No must be exactly 12 digits.',
            'tin_number.regex' => 'TIN must be 10–12 digits.',
            'sst_number.regex' => 'SST Number must follow format ST-XXXXXX.',
            'msic_code.regex' => 'MSIC Code must be exactly 5 digits.',
            'ic_number.regex' => 'IC/Passport must be in format like 900101-14-5678.',
            'company_phone.regex' => 'Phone number must be 9–11 digits.',
            'phone_number.regex' => 'Phone number must be 9–11 digits.',
        ]);

        $schoolAgent = new SchoolAgent();
        $schoolAgent->fill($request->except([
            'password', 'password_confirmation',
            'ssm_certificate', 'company_logo', 'bank_proof'
        ]));
        $schoolAgent->password = Hash::make($request->password);
        $schoolAgent->registration_status = 'Pending';
        $schoolAgent->registration_date = now();

        // Handle uploads
        if ($request->hasFile('ssm_certificate')) {
            $schoolAgent->ssm_certificate = $request->file('ssm_certificate')->store('certificates', 'public');
        }

        if ($request->hasFile('company_logo')) {
            $schoolAgent->company_logo = $request->file('company_logo')->store('logos', 'public');
        }

        if ($request->hasFile('bank_proof')) {
            $schoolAgent->bank_proof = $request->file('bank_proof')->store('bank_proofs', 'public');
        }

        $schoolAgent->save();

        // Generate registration ID
        $schoolAgent->registration_id = 'REG-' . str_pad($schoolAgent->id, 4, '0', STR_PAD_LEFT);
        $schoolAgent->save();

        return redirect()->back()->with('success', 'Registration submitted! Awaiting approval from Sales Support.');
    }

    public function pendingAgents()
    {
        $agents = SchoolAgent::where('registration_status', 'Pending')->get();
        return view('support.pending_agent', compact('agents'));
    }

    public function approve($id)
    {
        $agent = SchoolAgent::findOrFail($id);
        $agent->registration_status = 'Approved';
        $agent->save();

        $this->sendNotification($agent, 'approved');

        return redirect()->back()->with('success', 'Agent approved successfully.');
    }

    public function reject($id)
    {
        $agent = SchoolAgent::findOrFail($id);
        $agent->registration_status = 'Rejected';
        $agent->save();

        $this->sendNotification($agent, 'rejected');

        return redirect()->back()->with('success', 'Agent rejected successfully.');
    }

    public function show($id)
    {
        $agent = SchoolAgent::findOrFail($id);
        return view('support.view_agent', compact('agent'));
    }

    /**
     * Notify school agent via email only
     */
    protected function sendNotification(SchoolAgent $agent, $status)
    {
        $subject = "Your Registration Has Been " . ucfirst($status);
        $message = "Hi {$agent->full_name},\n\nYour registration (ID: {$agent->registration_id}) has been {$status}.";

        try {
            Mail::raw($message, function ($mail) use ($agent, $subject) {
                $mail->to($agent->email)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error("Email sending failed: " . $e->getMessage());
        }
    }
}
