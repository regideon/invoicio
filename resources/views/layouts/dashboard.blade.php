<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Invoicio' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#f6f6f8] text-slate-900 min-h-screen" style="font-family: 'Inter', sans-serif;">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="hidden lg:flex w-64 flex-col bg-[#1e293b] text-slate-300 fixed top-0 left-0 h-full z-30">

        {{-- Logo --}}
        <div class="p-6 flex items-center gap-3">
            <div class="bg-blue-600 p-2 rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">Invoicio</h1>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all
                      {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="material-symbols-outlined text-xl">dashboard</span>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-white">
                <span class="material-symbols-outlined text-slate-400 text-xl">description</span>
                <span>Invoices</span>
            </a>
            <a href="{{ route('clients.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all
                    {{ request()->routeIs('clients.*') ? 'text-white bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="material-symbols-outlined text-xl">group</span>
                <span>Clients</span>
            </a>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-white">
                <span class="material-symbols-outlined text-slate-400 text-xl">inventory_2</span>
                <span>Products</span>
            </a>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-white">
                <span class="material-symbols-outlined text-slate-400 text-xl">payments</span>
                <span>Payments</span>
            </a>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-white">
                <span class="material-symbols-outlined text-slate-400 text-xl">bar_chart</span>
                <span>Reports</span>
            </a>
        </nav>

        {{-- Settings --}}
        <div class="p-4 border-t border-slate-700/50">
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-white">
                <span class="material-symbols-outlined text-slate-400 text-xl">settings</span>
                <span>Settings</span>
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

        {{-- TOP NAVBAR --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                {{-- Mobile menu button --}}
                <button class="lg:hidden text-slate-500">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="text-lg font-semibold lg:hidden">@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="flex items-center gap-4">
                {{-- User info --}}
                <div class="flex items-center gap-3 border-r border-slate-200 pr-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">Admin Account</p>
                    </div>
                    <div class="size-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-slate-500 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        <span class="text-sm font-medium hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="p-4 lg:p-8 space-y-8">
            {{-- @yield('content') --}}
            {{ $slot }}
        </main>

    </div>
</div>

@livewireScripts
</body>
</html>