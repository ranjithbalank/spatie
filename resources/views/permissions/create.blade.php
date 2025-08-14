<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($permissions) ? 'Edit Permissions' : 'Create Permissions' }}
            </h2>

            <a href="{{ route('permissions.index') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ isset($permissions) ? route('permissions.update', $permissions->id) : route('permissions.store') }}" method="POST"
            class="space-y-6">
            @csrf
            @if (isset($permissions))
                @method('PUT')
            @endif

            <!-- permissions Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700"><b>Permissions Name</b></label>
                <input type="text" name="name" id="name" value="{{ old('name', $permissions->name ?? '') }}"
                    placeholder="Enter permissions name"
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-25"
                    required />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                    {{ isset($permissions) ? 'Update Permissions' : 'Create Permissions' }}
                </button>
            </div>
        </form>
        </div>

        </div>
    </x-slot>
</x-app-layout>
