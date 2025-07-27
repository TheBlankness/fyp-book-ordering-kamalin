@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Reset Your Password</h2>

    <p class="text-sm text-gray-600 mb-4">
        Forgot your password? No problem. Just let us know your staff account email and we’ll email you a password reset link.
    </p>

    {{-- Session Status Message --}}
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="text"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                   value="{{ old('email') }}" autofocus>

            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-medium text-sm">
                Email Password Reset Link
            </button>
        </div>
    </form>
</div>
@endsection
