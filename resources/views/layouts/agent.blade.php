<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Panel - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex">
        <!-- Sidebar (fixed) -->
        <aside class="w-64 h-screen fixed top-0 left-0 bg-white border-r border-gray-200 shadow-sm z-10">
            <div class="p-6 text-center font-bold text-base text-gray-700">
                {{ Auth::guard('agent')->user()->company_name }}
            </div>
            <nav class="px-4">
                <!-- Profile -->
                <a href="{{ route('agent.profile.edit') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.profile.edit') ? 'bg-gray-200 font-semibold' : '' }}">
                    Profile
                </a>

                <!-- Dashboard -->
                <a href="{{ route('agent.dashboard') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                    Dashboard
                </a>

                <!-- Place Customized Order -->
                <a href="{{ route('agent.orders.customized') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.orders.customized') ? 'bg-gray-200 font-semibold' : '' }}">
                    Place Customized Order
                </a>

                <!-- Review Design -->
                <a href="{{ route('agent.orders.submitted') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.orders.submitted') ? 'bg-gray-200 font-semibold' : '' }}">
                    Review Design
                </a>

                <!-- Reorder -->
                <a href="{{ route('agent.orders.pastSchools') }}"
                class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.orders.pastSchools') ? 'bg-gray-200 font-semibold' : '' }}">
                    Reorder
                </a>

                <!-- View Order Status -->
                <a href="{{ route('agent.orders.status') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.orders.status') ? 'bg-gray-200 font-semibold' : '' }}">
                    View Order Status
                </a>

                <!-- View Invoice -->
                <a href="{{ route('agent.invoices.index') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.invoices.index') ? 'bg-gray-200 font-semibold' : '' }}">
                    View Invoice
                </a>

                <!-- View Past Orders -->
                <a href="{{ route('agent.orders.past') }}"
                   class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('agent.orders.past') ? 'bg-gray-200 font-semibold' : '' }}">
                    View Past Orders
                </a>

                <!-- Logout -->
                <form action="{{ route('agent.logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 text-left text-red-600 hover:bg-red-100 rounded">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content (scrollable) -->
        <main class="ml-64 flex-1 min-h-screen p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
