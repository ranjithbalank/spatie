<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <!-- Title on the left -->
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Admin') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr;{{ __('Back') }}
            </a>
        </div>

    </x-slot>


    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Welcome to the Admin Dashboard</h1>
                        <p class="mb-4">Here you can manage roles and permissions.</p>
                        <div class="space-y-4">
                            <a href="{{ route('roles.index') }}"
                                class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                                Manage Roles
                            </a>
                            <a href="{{ route('permissions.index') }}"
                                class="inline-block px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                                Manage Permissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
</x-app-layout>
