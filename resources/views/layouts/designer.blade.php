<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Designer Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md border-r border-gray-200 fixed top-0 left-0 h-screen z-10">
        <!-- Display Designer Name -->
        <div class="p-6 text-center font-bold text-base text-gray-700 border-b">
            {{ Auth::user()->name }}
        </div>

        <nav class="px-4 py-4 space-y-2">
            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
               class="block py-2 px-4 rounded hover:bg-gray-100 {{ request()->routeIs('profile.edit') ? 'bg-gray-200 font-semibold' : '' }}">
                Profile
            </a>

            <!-- Dashboard -->
            <a href="{{ url('/designer-dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->is('designer-dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>

            <!-- View Design Tasks -->
            <a href="{{ url('/designer/orders') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->is('designer/orders*') ? 'bg-gray-200 font-semibold' : '' }}">
                View Design Tasks
            </a>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="pt-4">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded hover:bg-red-100 text-red-600">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>
</div>

</body>
</html>
