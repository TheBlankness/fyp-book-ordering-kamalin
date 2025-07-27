<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register as School Agent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="text-center mt-8">
        <h1 class="text-3xl font-bold text-gray-800">Register as Agent Now</h1>
    </div>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            @if (session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('agent.register.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Company Details -->
                <h3 class="text-lg font-semibold mb-2">Company Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input-group label="Company Name" name="company_name" required hint="e.g. ABC Enterprise" />
                    <x-input-group label="Company Reg No (New)" name="ssm_number" required hint="e.g. 202201234567" />
                    <x-input-group label="Company Reg No (Old)" name="company_reg_old" hint="e.g. 123456-V" />
                    <x-input-group label="TIN (Tax Identification Number)" name="tin_number" hint="e.g. SG1234567A" />
                    <x-input-group label="SST Number" name="sst_number" hint="e.g. ST-123456" />
                    <x-input-group label="MSIC Code" name="msic_code" hint="e.g. 58110" />
                    <x-input-group label="Business Activity Description" name="business_activity" hint="e.g. Printing of books" />

                    <!-- Business Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Business Type</label>
                        <select name="business_type" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select type</option>
                            <option value="Sole Proprietor" {{ old('business_type') == 'Sole Proprietor' ? 'selected' : '' }}>Sole Proprietor</option>
                            <option value="Partnership" {{ old('business_type') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="Sdn. Bhd." {{ old('business_type') == 'Sdn. Bhd.' ? 'selected' : '' }}>Sdn. Bhd.</option>
                            <option value="Berhad" {{ old('business_type') == 'Berhad' ? 'selected' : '' }}>Berhad</option>
                            <option value="LLP" {{ old('business_type') == 'LLP' ? 'selected' : '' }}>LLP</option>
                        </select>
                        @error('business_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <x-input-group label="Incorporation Date" name="incorporation_date" type="date" hint="e.g. 2020-05-01" />
                    <x-input-group label="Company Email" name="email" type="email" required hint="e.g. info@company.com" />
                    <x-input-group label="Company Phone" name="company_phone" required hint="e.g. 0123456789" />
                    <x-input-group label="Website" name="website" hint="e.g. https://example.com" />
                </div>

                <!-- Business Address -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Business Address</label>
                    <textarea name="business_address" required class="block w-full rounded-md border-gray-300 shadow-sm mt-1" placeholder="e.g. No.12, Jalan ABC, Taman DEF, 40100 Shah Alam, Selangor">{{ old('business_address') }}</textarea>
                    @error('business_address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- E-Invoice Phase -->
                <h4 class="mt-6 text-sm font-medium text-gray-700">E-Invoice Implementation Phase</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label><input type="radio" name="einvoice_phase" value="2024-08-01" {{ old('einvoice_phase') == '2024-08-01' ? 'checked' : '' }} required> 1st Aug 2024</label>
                    <label><input type="radio" name="einvoice_phase" value="2025-01-01" {{ old('einvoice_phase') == '2025-01-01' ? 'checked' : '' }} required> 1st Jan 2025</label>
                    <label><input type="radio" name="einvoice_phase" value="2025-07-01" {{ old('einvoice_phase') == '2025-07-01' ? 'checked' : '' }} required> 1st July 2025</label>
                </div>
                @error('einvoice_phase') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

                <!-- Personal Info -->
                <h3 class="text-lg font-semibold mt-8 mb-2">Personal Info</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input-group label="Full Name" name="full_name" required hint="e.g. Kamal Irwan" />
                    <x-input-group label="IC/Passport Number" name="ic_number" required hint="e.g. 900101-14-5678" />

                    <!-- Designation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Designation</label>
                        <select name="designation" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select designation</option>
                            <option value="Director" {{ old('designation') == 'Director' ? 'selected' : '' }}>Director</option>
                            <option value="Manager" {{ old('designation') == 'Manager' ? 'selected' : '' }}>Manager</option>
                            <option value="Owner" {{ old('designation') == 'Owner' ? 'selected' : '' }}>Owner</option>
                            <option value="Partner" {{ old('designation') == 'Partner' ? 'selected' : '' }}>Partner</option>
                            <option value="Agent" {{ old('designation') == 'Agent' ? 'selected' : '' }}>Agent</option>
                            <option value="Others" {{ old('designation') == 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                        @error('designation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <x-input-group label="Personal Email" name="personal_email" type="email" required hint="e.g. kamal@email.com" />
                    <x-input-group label="Phone Number" name="phone_number" required hint="e.g. 0198765432" />
                </div>

                <!-- Tax & Bank Info -->
                <h3 class="text-lg font-semibold mt-8 mb-2">Tax & Bank Info</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input-group label="LHDN Tax Number" name="lhdn_tax_number" required hint="e.g. SG1234567A" />

                    <!-- Bank Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <select name="bank_name" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select bank</option>
                            @foreach (["Maybank","CIMB Bank","RHB Bank","Public Bank","Bank Islam","Hong Leong Bank","Ambank","Bank Rakyat","UOB Bank","HSBC Bank","OCBC Bank","Standard Chartered"] as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <x-input-group label="Bank Account Number" name="bank_account_number" required hint="e.g. 123456789012" />
                    <x-input-group label="Account Holder Name" name="bank_account_holder" required hint="e.g. Kamal Irwan" />
                </div>

                <!-- e-Invoice Status -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Is e-Invoice Registered?</label>
                    <select name="einvoice_registered" required class="block w-full rounded-md border-gray-300 shadow-sm mt-1">
                        <option value="">Select</option>
                        <option value="Yes" {{ old('einvoice_registered') == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('einvoice_registered') == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('einvoice_registered') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <x-input-group label="e-Invoice Email / Endpoint" name="einvoice_email" class="mt-4" hint="e.g. invoice@endpoint.my" />

                <!-- Login Info -->
                <h3 class="text-lg font-semibold mt-8 mb-2">Login Info</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input-group label="Username" name="username" required hint="e.g. ABCenterprise" />
                    <x-input-group label="Password" name="password" type="password" required />

                    <!-- Updated Confirm Password field with error message fix -->
                    <div>
                        <x-input-group label="Confirm Password" name="password_confirmation" type="password" required />
                        @if ($errors->has('password') && str_contains($errors->first('password'), 'confirmation'))
                            <p class="text-sm text-red-600 mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Attachments -->
                <h3 class="text-lg font-semibold mt-8 mb-2">Attachments</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- SSM Certificate --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SSM Certificate (PDF)</label>
                        <label for="ssm_certificate" class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md cursor-pointer hover:bg-indigo-700">
                            Choose PDF
                        </label>
                        <input type="file" name="ssm_certificate" id="ssm_certificate" accept="application/pdf" required class="hidden"
                            onchange="updateFileName('ssm_certificate')">
                        <p id="ssm_certificate_name" class="text-sm text-gray-600 mt-1">No file chosen</p>
                        <p class="text-xs text-gray-500 mt-1">PDF format only</p>
                        @error('ssm_certificate') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Company Logo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Logo (Image)</label>
                        <label for="company_logo" class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md cursor-pointer hover:bg-indigo-700">
                            Choose Image
                        </label>
                        <input type="file" name="company_logo" id="company_logo" accept="image/*" required class="hidden"
                            onchange="updateFileName('company_logo')">
                        <p id="company_logo_name" class="text-sm text-gray-600 mt-1">No file chosen</p>
                        <p class="text-xs text-gray-500 mt-1">e.g. PNG, JPG format</p>
                        @error('company_logo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Bank Proof --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Proof (PDF/Image)</label>
                        <label for="bank_proof" class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md cursor-pointer hover:bg-indigo-700">
                            Upload File
                        </label>
                        <input type="file" name="bank_proof" id="bank_proof" accept="application/pdf,image/*" required class="hidden"
                            onchange="updateFileName('bank_proof')">
                        <p id="bank_proof_name" class="text-sm text-gray-600 mt-1">No file chosen</p>
                        <p class="text-xs text-gray-500 mt-1">e.g. Bank header screenshot or PDF statement</p>
                        @error('bank_proof') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- JS to show selected filename -->
                <script>
                    function updateFileName(inputId) {
                        const input = document.getElementById(inputId);
                        const label = document.getElementById(`${inputId}_name`);
                        if (input.files.length > 0) {
                            label.textContent = input.files[0].name;
                        } else {
                            label.textContent = 'No file chosen';
                        }
                    }
                </script>

                <!-- Submit -->
                <div class="mt-6 flex justify-between">
                    <a href="{{ url('/unified-login') }}" class="inline-block bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded">Back</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Submit Registration</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
