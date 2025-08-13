<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
            </h2>

            <a href="{{ route('roles.index') }}" class="text-sm text-red-700 no-underline">
                &larr; Back
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

        <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST" class="space-y-6">
            @csrf
            @if (isset($role))
                @method('PUT')
            @endif

            <!-- Role Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700"><b>Role Name</b></label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}"
                    placeholder="Enter role name"
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required />
            </div>

            <!-- Permissions -->
            <div>
                <label class="block text-sm font-medium text-gray-700"><b>Assign Permissions</b></label>
                <div class="mt-2 space-y-1">
                    @foreach ($permissions as $permission)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                {{ isset($role) && $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                class="form-checkbox">
                            <span class="ml-2">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                    {{ isset($role) ? 'Update Role' : 'Create Role' }}
                </button>
            </div>
        </form>
    </x-slot>
</x-app-layout>
