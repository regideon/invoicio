@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-[480px]">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center gap-2 mb-2">
                <div class="bg-blue-600 size-10 rounded-xl flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-2xl">receipt_long</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Invoicio</h2>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow-xl rounded-2xl p-8 border border-slate-200">
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-2">Create your account</h1>
                <p class="text-slate-500">Join Invoicio to start managing your invoices seamlessly.</p>
            </div>

            <form method="POST" action="/register" class="space-y-5">
                @csrf

                {{-- Full Name --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Full Name</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
                        <input
                            type="text" name="name" value="{{ old('name') }}"
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 h-12 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 text-sm"
                        />
                    </div>
                    @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input
                            type="email" name="email" value="{{ old('email') }}"
                            placeholder="name@company.com"
                            class="w-full pl-10 pr-4 h-12 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 text-sm"
                        />
                    </div>
                    @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                        <input
                            type="password" name="password" id="password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-12 h-12 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 text-sm"
                        />
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <span class="material-symbols-outlined text-xl" id="eye-icon">visibility</span>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Confirm Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock_reset</span>
                        <input
                            type="password" name="password_confirmation"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 h-12 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 text-sm"
                        />
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold h-12 rounded-lg transition-colors shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2">
                    Create Account
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-slate-600">
                    Already have an account?
                    <a href="/login" class="text-blue-600 font-semibold hover:underline">Login</a>
                </p>
            </div>
        </div>

        <footer class="mt-8 flex justify-center gap-6 text-sm text-slate-400">
            <a href="#" class="hover:text-slate-600">Support</a>
            <a href="#" class="hover:text-slate-600">Security</a>
        </footer>
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