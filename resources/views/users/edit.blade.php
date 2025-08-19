<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Edit Users') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">
    </x-slot>
</x-app-layout>
