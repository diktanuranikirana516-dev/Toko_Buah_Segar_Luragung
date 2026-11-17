<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Pengaturan Profil Akun
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Kelola identitas akun, alamat email, dan keamanan kata sandi Anda</p>
            </div>
            <div>
                @if(Auth::user()->role === 'admin' || (method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin()))
                    <a href="{{ route('dashboard') }}" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-1.5 shadow-xs">
                        <span>&larr;</span>
                        <span>Dashboard Admin</span>
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-1.5 shadow-xs">
                        <span>&larr;</span>
                        <span>Dashboard Pelanggan</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. Kartu Info Ringkas Pengguna -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl">
                        👤
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">{{ Auth::user()->name }}</h3>
                        <p class="text-stone-500 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-stone-600 bg-stone-50 px-4 py-2 rounded-xl border border-stone-200">
                    <span>Role Pengguna:</span>
                    <span class="px-2.5 py-0.5 rounded-md uppercase font-bold text-[11px] {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ Auth::user()->role ?? 'Customer' }}
                    </span>
                </div>
            </div>

            <!-- 2. Formulir Update Data Profil -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- 3. Formulir Update Password -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <!-- 4. Formulir Hapus Akun -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
