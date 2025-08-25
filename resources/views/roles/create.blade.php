<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                Role and Permission Management
            </h2>
        </div>
        {{-- <hr class=""> --}}
    </x-slot>
@section('content')
    <!-- The form is now a standard form and not a modal -->
    <div class="w-full mx-auto my-auto bg-white rounded-lg shadow-xl overflow-hidden p-6">
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
                    class="mt-2 block w-25 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required />
            </div>

            <!-- Permissions with Dynamic Accordion -->
            <div class="mb-4">
                <label class="form-label"><b>Assign Permissions</b></label>
                {{-- Updated container to use a grid for the permission groups --}}
                <div id="permissions-accordion" class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @php
                        // Group permissions by a custom logic.
                        $groupedPermissions = $permissions->groupBy(function($item) {
                            $name = strtolower($item->name);
                            if (str_contains($name, 'my profile')) {
                                return 'my profile';
                            }
                            if (str_contains($name, 'menu items')) {
                                return 'menu items';
                            }
                            // Simplified checks for menu-related permissions
                            if (str_contains($name, 'menu permission')) {
                                return 'menu permissions';
                            }
                            if (str_contains($name, 'menu items')) {
                                return 'menu items';
                            }
                            if (str_contains($name, 'roles & permissions')) {
                                return 'roles & permissions';
                            }
                            $parts = explode(' ', $name);
                            return isset($parts[1]) ? $parts[1] : 'other';
                        });
                    @endphp

                    @foreach ($groupedPermissions as $groupName => $groupPermissions)
                        <div class="bg-red-50 rounded-lg shadow-sm">
                            {{-- This div now acts as a static header, not a toggle --}}
                            <div class="flex justify-start items-center w-full p-4 text-left font-semibold text-gray-700 rounded-lg">
                                Menu :&nbsp;<span class="group-title text-danger">{{ ucwords($groupName) }}</span>
                            </div>

                            <!-- The content div is now always visible -->
                            <div class="permission-group grid grid-cols-1 gap-4 p-4 border-t border-gray-200">
                                @foreach ($groupPermissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $permission->id }}"
                                            class="permission-checkbox form-checkbox text-indigo-600 rounded-sm focus:ring-indigo-500 h-4 w-4"
                                            {{ (isset($role) && $role->hasPermissionTo($permission->name)) ? 'checked' : '' }}>
                                        <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                            {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
    @endsection
</x-app-layout>
