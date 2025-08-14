<!-- resources/views/layouts/sidebar.blade.php -->
{{-- <aside class="w-64 bg-white shadow-md hidden md:block"> --}}
<div class="p-4 border-b">
    <h2 class="text-lg font-bold">Menu</h2>
</div>
<nav class="p-4">
    <ul class="space-y-2">
        <li>
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>
        </li>
        @role('admin')
            <li>
                <a href="{{ route('admin.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Admin
                </a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('roles.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Roles
                </a>
            </li>
            <li>
                <a href="{{ route('permissions.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('permissions.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Permissions
                </a>
            </li>
            <li>
                <a href="{{ route('menus.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('menu.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Menu Items
                </a>
            </li>
            <li>
                <a href="{{ route('menu-permission.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('menu.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Menu Permissions
                </a>
            </li>
            <li>
                <a
                href="{{ route('units.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('users.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Units
                </a>
            </li>
            <li>
                <a
                 {{-- href="{{ route('departments.index') }}" --}}
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('departments.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Departments
                </a>
            </li>
        @endrole

        <li>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">
                Reports
            </a>
        </li>
    </ul>
</nav>
{{-- </aside> --}}
