<!-- resources/views/layouts/sidebar.blade.php -->

<nav class="p-4">
    <ul class="space-y-2">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>
        </li>

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
                ->orderBy('order', 'asc')
                ->orderBy('name')
                ->with([
                    'children' => function ($query) use ($allowedMenuIds) {
                        $query->whereIn('id', $allowedMenuIds)->orderBy('order');
                    },
                ])
                ->get();
        @endphp

        @foreach ($menus as $menu)
            {{-- For bypassing the ijp in the menu --}}
            @if (($menu->name === 'IJP-EXPORT') | ($menu->name === 'IJP-IMPORT'))
                @continue
            @endif
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
