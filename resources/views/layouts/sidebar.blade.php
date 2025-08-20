<!-- resources/views/layouts/sidebar.blade.php -->

<div class="p-4 border-b">
    <h2 class="text-lg font-bold">Menu</h2>
</div>

<nav class="p-4">
    <ul class="space-y-2">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>
        </li>

        <!-- Admin Only Menus -->
        @role('admin')
            {{-- <li>
                <a href="{{ route('admin.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Admin
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('roles.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('roles.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Roles
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('permissions.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('permissions.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Permissions
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('menus.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('menus.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Menu Items
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('departments.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('departments.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Departments
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('menu-permission.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('menu-permission.*') ? 'bg-gray-200 font-semibold' : '' }}">
                    Menu Permissions
                </a>
            </li> --}}
        @endrole

        <!-- Dynamic Menus based on Role Permissions -->
        @php
            use App\Models\MenuRolePermission;
            use App\Models\Menu;

            $roleId = auth()->user()->roles->first()->id ?? null;

            $allowedMenuIds = MenuRolePermission::where('role_id', $roleId)
                ->where('action', 'view')
                ->pluck('menu_id')
                ->toArray();

            $menus = Menu::whereIn('id', $allowedMenuIds)
                ->whereNull('parent_id')
                ->orderBy('order','asc')
                ->orderBy('name')
                ->with([
                    'children' => function ($query) use ($allowedMenuIds) {
                        $query->whereIn('id', $allowedMenuIds)->orderBy('order');
                    },
                ])
                ->get();
        @endphp

        @foreach ($menus as $menu)
            <li>
                <a href="{{ $menu->url }}"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->is(ltrim($menu->url, '/')) ? 'bg-gray-200 font-semibold' : '' }}">
                    @if ($menu->icon)
                        <i class="{{ $menu->icon }} mr-2"></i>
                    @endif
                    {{ $menu->name }}
                </a>

                @if ($menu->children->count())
                    <ul class="ml-6 space-y-1">
                        @foreach ($menu->children as $child)
                            <li>
                                <a href="{{ $child->url }}"
                                    class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->is(ltrim($child->url, '/')) ? 'bg-gray-100 font-semibold' : '' }}">
                                    @if ($child->icon)
                                        <i class="{{ $child->icon }} mr-2"></i>
                                    @endif
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach

        <!-- Static Menu -->
        {{-- <li>
            <a href="#"
                class="block px-4 py-2 rounded hover:bg-gray-200">
                Reports
            </a>
        </li> --}}
    </ul>
</nav>
