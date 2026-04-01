<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-[#0d1b4b] leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-10 bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/6 sm:rounded-[2rem]">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/6 sm:rounded-[2rem]">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/6 sm:rounded-[2rem]">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
