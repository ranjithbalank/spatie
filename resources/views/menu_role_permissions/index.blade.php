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
            {{-- <a href="{{ route('menus.create') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                + Create Menu
            </a> --}}

            <!-- Search bar -->
            {{-- <form method="GET" action="{{ route('menus.index') }}" class="flex items-end gap-2 w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search Menu Items..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form> --}}
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('menu-permission.index') }}" method="GET" class="mb-4">
            <label for="role_id">Select Role:</label>
            <select name="role_id" id="role_id" onchange="this.form.submit()" class="border rounded p-1 w-25">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $selectedRoleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <form action="{{ route('menu-permission.store') }}" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{ $selectedRoleId }}">

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Menu</th>
                        @foreach (['view', 'create', 'edit', 'delete', 'approve'] as $action)
                            <th class="border px-4 py-2 text-center capitalize">{{ $action }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $menu->name }}</td>
                            @foreach (['view', 'create', 'edit', 'delete', 'approve'] as $action)
                                <td class="border px-4 py-2 text-center">
                                    <input type="checkbox" name="permissions[{{ $menu->id }}][]"
                                        value="{{ $action }}"
                                        {{ in_array($action, $permissions[$menu->id] ?? []) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Permissions</button>
            </div>
        </form>

    </x-slot>
</x-app-layout>
