<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Panel - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex">
        <!-- Sidebar (fixed) -->
        <aside class="w-64 h-screen fixed top-0 left-0 bg-white border-r border-gray-200 shadow-sm z-10">
            <!-- Display Support Name -->
            <div class="p-6 text-center font-bold text-base text-gray-700">
                {{ Auth::user()->name }}
            </div>

            <nav class="px-4">
                <!-- Profile (highlight when on /profile) -->
                <a href="{{ route('profile.edit') }}"
                class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->is('profile') ? 'bg-gray-200 font-semibold' : '' }}">
                    Profile
                </a>

                <!-- Dashboard (highlight only on /support-dashboard) -->
                <a href="{{ url('/support-dashboard') }}"
                class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->is('support-dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                    Dashboard
                </a>

                <!-- Pending Agents -->
                <a href="{{ route('staff.pending-agents') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->is('staff/pending-agents') ? 'bg-gray-200 font-semibold' : '' }}">
                    Pending Agent Registrations
                </a>

                <!-- Manage Custom Orders -->
                <a href="{{ route('support.orders') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->is('support/orders') ? 'bg-gray-200 font-semibold' : '' }}">
                    Manage Custom Orders
                </a>

                <!-- Manage Reorders -->
                <a href="{{ route('support.reorders.index') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->is('support/reorders*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Manage Reorders
                </a>


                <!-- Payment Proofs -->
                <a href="{{ route('support.payment.proofs') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('support.payment.proofs') ? 'bg-gray-200 font-semibold' : '' }}">
                    Payment Proofs
                </a>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 text-left text-red-600 hover:bg-red-100 rounded">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 min-h-screen p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
