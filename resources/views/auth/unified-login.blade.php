@extends('layouts.guest')

@section('title', 'Unified Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">

        {{-- System Logo --}}
        <div class="flex justify-center mb-3">
            <img src="{{ asset('images/TEH logo.png') }}" alt="System Logo" class="h-30">
        </div>

        {{-- Title --}}
        <h1 class="text-center text-lg font-semibold mb-1">Welcome to Book Ordering System</h1>


        {{-- Flash Success --}}
        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="flex justify-center mb-6 border-b border-gray-200">
            <button onclick="switchTab('staff')" id="tab-staff"
                class="px-4 py-2 text-sm font-medium border-b-2 border-blue-600 text-blue-600">Staff</button>
            <button onclick="switchTab('agent')" id="tab-agent"
                class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-300">School Agent</button>
        </div>

        {{-- Staff Login Form --}}
        <form method="POST" action="{{ route('login') }}" id="form-staff">
            @csrf

            {{-- Staff Login Error --}}
            @if ($errors->has('login') && old('login_type') === 'staff')
                <div class="bg-red-100 text-red-800 p-2 rounded mb-4 text-sm">
                    {{ $errors->first('login') }}
                </div>
            @endif

            {{-- Username --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">Staff Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror">
                @error('username')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-2">
                <label class="block text-sm text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me & Forgot --}}
            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span>Remember Me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
            </div>

            <input type="hidden" name="login_type" value="staff">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition">Login as Staff</button>
        </form>

        {{-- Agent Login Form --}}
        <form method="POST" action="{{ route('agent.login.submit') }}" id="form-agent" class="hidden">
            @csrf

            {{-- Agent Login Error --}}
            @if ($errors->has('login') && old('login_type') === 'agent')
                <div class="bg-red-100 text-red-800 p-2 rounded mb-4 text-sm">
                    {{ $errors->first('login') }}
                </div>
            @endif

            {{-- Username --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">Agent Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror">
                @error('username')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-2">
                <label class="block text-sm text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me & Forgot --}}
            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span>Remember Me</span>
                </label>
                <a href="{{ route('agent.password.request') }}" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
            </div>

            <div class="text-sm text-center mb-4">
                Don’t have an account? <a href="{{ route('agent.register') }}" class="text-blue-500 hover:underline">Register as Agent</a>
            </div>

            <input type="hidden" name="login_type" value="agent">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition">Login as Agent</button>
        </form>
    </div>
</div>

{{-- Tab Switcher Script --}}
<script>
    function switchTab(tab) {
        const tabs = ['staff', 'agent'];
        tabs.forEach(t => {
            document.getElementById('form-' + t).classList.add('hidden');
            document.getElementById('tab-' + t).classList.remove('border-blue-600', 'text-blue-600');
            document.getElementById('tab-' + t).classList.add('text-gray-500');
        });
        document.getElementById('form-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.add('border-blue-600', 'text-blue-600');
        document.getElementById('tab-' + tab).classList.remove('text-gray-500');
    }

    window.onload = () => {
        const loginType = @json(old('login_type'));
        if (loginType === 'agent') switchTab('agent');
    };
</script>
@endsection
