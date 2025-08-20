<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuRolePermission;
use Illuminate\Support\Str;

class CheckMenuPermission
{
    protected $actionMap = [
        'index' => 'view',
        'show' => 'view',
        // 'create' => 'create',
        // 'store' => 'create',
        // 'edit' => 'edit',
        // 'update' => 'edit',
        // 'destroy' => 'delete',
    ];

    public function handle(Request $request, Closure $next, $action = null)
    {
        $user = $request->user();

        // Allow public access to specific routes
        if (!$user && $this->isPublicRoute($request)) {
            return $next($request);
        }

        if (!$user || $user->roles->isEmpty()) {
            return $this->denyAccess();
        }

        // Find menu by either exact URL, base path, or route name
        $menu = $this->findMenu($request);

        if (!$menu) {
            return $this->menuNotFound();
        }

        // Determine action (use provided or auto-detect)
        $action = $action ?? $this->determineAction($request);

        // Check permissions
        $hasPermission = MenuRolePermission::where('menu_id', $menu->id)
            ->whereIn('role_id', $user->roles->pluck('id'))
            ->where('action', $action)
            ->exists();

        return $hasPermission ? $next($request) : $this->denyAccess();
    }

    protected function findMenu(Request $request)
    {
        $path = '/' . ltrim($request->path(), '/');

        // 1. Try exact URL match first
        if ($menu = Menu::where('url', $path)->first()) {
            return $menu;
        }

        // 2. Try base path match (e.g., /units/create → /units)
        $segments = explode('/', trim($path, '/'));
        if (count($segments) > 1) {
            $basePath = '/' . $segments[0];
            if ($menu = Menu::where('url', $basePath)->first()) {
                return $menu;
            }
        }

        // 3. Fallback to route name matching
        if ($routeName = $request->route()->getName()) {
            $baseName = preg_replace('/\.(index|create|store|show|edit|update|destroy)$/', '', $routeName);
            return Menu::where('route_name', $baseName)->first();
        }

        return null;
    }

    protected function determineAction(Request $request): string
    {
        // Check route name first
        if ($routeName = $request->route()->getName()) {
            foreach ($this->actionMap as $key => $action) {
                if (Str::endsWith($routeName, $key)) {
                    return $action;
                }
            }
        }

        // Fallback to HTTP method
        return match ($request->getMethod()) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'edit',
            'DELETE' => 'delete',
            default => 'view',
        };
    }

    protected function isPublicRoute(Request $request): bool
    {
        $publicRoutes = [
            'login',
            'register',
            'password.request',
            'password.email',
            'password.reset'
        ];

        return in_array($request->route()->getName(), $publicRoutes);
    }

    protected function denyAccess()
    {
        return abort(403, 'You are not authorized to access this resource.');
    }

    protected function menuNotFound()
    {
        return abort(404, 'The requested menu item was not found.');
    }
}
