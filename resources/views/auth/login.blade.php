@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-10">
            <div class="bg-blue-600/10 p-3 rounded-xl mb-3">
                <span class="material-symbols-outlined text-blue-600 text-4xl">description</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Invoicio</h1>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 md:p-10">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-2">Welcome back</h2>
                <p class="text-slate-500 text-sm">Please enter your details to sign in to your account.</p>
            </div>

            <form method="POST" action="/login" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2" for="email">Email address</label>
                    <input
                        type="email" name="email" id="email"
                        value="{{ old('email') }}"
                        placeholder="name@company.com"
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm"
                    />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-slate-700" for="password">Password</label>
                    </div>
                    <div class="relative">
                        <input
                            type="password" name="password" id="password"
                            placeholder="••••••••"
                            class="block w-full px-4 py-3 rounded-lg border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm"
                        />
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <span class="material-symbols-outlined text-xl" id="eye-icon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600"/>
                    <label for="remember" class="ml-2 text-sm text-slate-600">Remember me for 30 days</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    Sign In
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="mt-8 text-center text-sm text-slate-600">
            No account?
            <a href="/register" class="font-semibold text-blue-600 hover:underline">Register now</a>
        </p>

        <div class="mt-6 flex justify-center gap-6 text-xs text-slate-400">
            <a href="#" class="hover:text-slate-600">Privacy Policy</a>
            <a href="#" class="hover:text-slate-600">Terms of Service</a>
            <a href="#" class="hover:text-slate-600">Help Center</a>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
@endsection