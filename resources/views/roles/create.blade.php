<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($role) ? 'Edit Role' : 'Create Role' }}
            </h2>

            <a href="{{ route('roles.index') }}" class="text-sm text-red-700 no-underline">
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
        <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST"
            class="space-y-6">
            @csrf
            @if (isset($role))
                @method('PUT')
            @endif

            <!-- Role Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700"><b>Role Name</b></label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}"
                    placeholder="Enter role name"
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-25"
                    required />
            </div>

            <!-- Permissions -->
            <div class="mb-4">
                <label class="form-label"><b>Assign Permissions</b></label>
                <div class="d-flex flex-column">
                    @foreach ($permissions as $permission)
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                value="{{ $permission->name }}" id="permission-{{ $permission->id }}"
                                {{ isset($role) && $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
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
        </div>

        </div>
    </x-slot>
</x-app-layout>
