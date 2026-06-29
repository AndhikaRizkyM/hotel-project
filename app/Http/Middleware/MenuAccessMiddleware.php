<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MenuAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $routeName = $request->route() ? $request->route()->getName() : null;

        if (!$routeName) {
            return $next($request);
        }

        // Map route name to menu key
        $menuKey = $this->getMenuKeyForRoute($routeName);

        if ($menuKey) {
            if (!$user->canAccessMenu($menuKey)) {
                abort(403, 'Unauthorized access - you do not have permission to access this menu.');
            }
        }

        return $next($request);
    }

    /**
     * Map route names to menu keys.
     */
    private function getMenuKeyForRoute(string $routeName): ?string
    {
        if (Str::startsWith($routeName, 'master.users.')) {
            return 'master_users';
        }
        if (Str::startsWith($routeName, 'master.room-types.')) {
            return 'master_room_types';
        }
        if (Str::startsWith($routeName, 'master.rooms.')) {
            return 'master_rooms';
        }
        if (Str::startsWith($routeName, 'master.fb-menus.')) {
            return 'master_fb_menus';
        }
        if (Str::startsWith($routeName, 'master.laundry-services.')) {
            return 'master_laundry_services';
        }
        if (Str::startsWith($routeName, 'master.inspection-charges.')) {
            return 'hk_tasks';
        }
        if ($routeName === 'fo.availability') {
            return 'fo_availability';
        }
        if (Str::startsWith($routeName, 'fo.reservations.')) {
            return 'fo_reservations';
        }
        if (Str::startsWith($routeName, 'fo.print-')) {
            return 'fo_reservations';
        }
        if (Str::startsWith($routeName, 'fo.guests.')) {
            return 'fo_guests';
        }
        if (Str::startsWith($routeName, 'hk.tasks')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.inspections.')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.damages.')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.lost-found.')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.maintenance.')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.extrabed.')) {
            return 'hk_tasks';
        }
        if (Str::startsWith($routeName, 'hk.laundry.')) {
            return 'hk_laundry';
        }
        if (Str::startsWith($routeName, 'fb.breakfast')) {
            return 'fb_breakfast';
        }
        if (Str::startsWith($routeName, 'fb.orders.')) {
            return 'fb_orders';
        }
        if (Str::startsWith($routeName, 'reports.')) {
            return 'reports';
        }

        return null;
    }
}
