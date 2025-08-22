<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Leave Details') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            @can('create leaves')
                <a href="{{ route('leaves.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    + Create Leaves
                </a>
            @endcan
            <form method="GET" action="{{ route('designations.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Leaves..."
                    value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>
    </x-slot>
</x-app-layout>
