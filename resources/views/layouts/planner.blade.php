<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Planner Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

<div class="flex h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md border-r border-gray-200 fixed top-0 left-0 h-screen z-10">
        <!-- Dynamic Planner Name -->
        <div class="p-6 text-center font-bold text-base text-gray-700 border-b">
            {{ Auth::user()->name }}
        </div>

        <nav class="px-4 py-4 space-y-2">
            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
               class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('profile.edit') ? 'bg-gray-200 font-semibold' : '' }}">
                Profile
            </a>

            <a href="{{ url('/planner-dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->is('planner-dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('planner.orders.assigned') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('planner.orders.assigned') ? 'bg-gray-200 font-semibold' : '' }}">
                View Assigned Orders
            </a>

            <a href="{{ route('planner.orders.scheduled') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('planner.orders.scheduled') ? 'bg-gray-200 font-semibold' : '' }}">
                Scheduled Orders
            </a>

            <a href="{{ route('planner.orders.inProduction') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('planner.orders.inProduction') ? 'bg-gray-200 font-semibold' : '' }}">
                In Production Orders
            </a>

            <a href="{{ route('planner.orders.completed') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('planner.orders.completed') ? 'bg-gray-200 font-semibold' : '' }}">
                Completed Orders
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 rounded text-red-600 hover:bg-red-100">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="ml-64 flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>
</div>

</body>
</html>
