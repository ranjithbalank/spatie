<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Menus Permissions') }}
            </h2>

            <a href="{{ route('dashboard') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>
        <hr class="mb-4">
        <!-- Top Controls: Create Button + Search -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <!-- Create Role Button -->


            <!-- Search bar -->
            {{-- <form method="GET" action="{{ route('menus.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Menu Items..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form> --}}
        </div>

        {{-- @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif --}}

        <form action="{{ route('menu-permission.index') }}" method="GET" class="mb-4">


            <div class="flex items-center w-full mb-4 gap-4">
                <!-- Role Selection -->
                <div class="flex items-center gap-2 flex-grow">
                    <label for="role_id" class="whitespace-nowrap text-danger fw-bold">Select Role:</label>
                    <select name="role_id" id="role_id" onchange="this.form.submit()"
                        class="border rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 w-25">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $selectedRoleId == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Create Menu Button -->
                <a href="{{ route('menus.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition whitespace-nowrap flex items-center gap-1">
                    Create Menu
                </a>
            </div>

        </form>


        <form action="{{ route('menu-permission.store') }}" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{ $selectedRoleId }}">

            <table class="min-w- border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Menu</th>
                        @foreach (['view','create','edit','delete','approve','self'] as $action)
                            <th class="border px-4 py-2 text-center capitalize">{{ $action }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if($menus->is_active = 1)
                    @foreach ($menus as $menu)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $menu->name }}</td>
                            @foreach (['view','create','edit','delete','approve','self'] as $action)
                                <td class="border px-4 py-2 text-center">
                                    <input type="checkbox" name="permissions[{{ $menu->id }}][]"
                                        value="{{ $action }}"
                                        {{ in_array($action, $permissions[$menu->id] ?? []) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Permissions</button>
            </div>
        </form>

    </x-slot>
</x-app-layout>
