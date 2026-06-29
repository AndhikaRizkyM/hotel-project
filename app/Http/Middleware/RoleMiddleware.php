<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // If the user's role is in the list of allowed roles, proceed
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Or check dynamic DB permission based on route name
        $routeName = $request->route() ? $request->route()->getName() : null;
        if ($routeName) {
            $menuKey = $this->getMenuKeyForRoute($routeName);
            if ($menuKey && $user->canAccessMenu($menuKey)) {
                return $next($request);
            }
        }

        // Otherwise, abort with unauthorized message
        abort(403, 'Unauthorized access - you do not have the required permissions.');
    }

    /**
     * Map route names to menu keys.
     */
    private function getMenuKeyForRoute(string $routeName): ?string
    {
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.users.')) {
            return 'master_users';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.room-types.')) {
            return 'master_room_types';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.rooms.')) {
            return 'master_rooms';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.fb-menus.')) {
            return 'master_fb_menus';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.laundry-services.')) {
            return 'master_laundry_services';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'master.inspection-charges.')) {
            return 'hk_tasks';
        }
        if ($routeName === 'fo.availability') {
            return 'fo_availability';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'fo.reservations.')) {
            return 'fo_reservations';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'fo.print-')) {
            return 'fo_reservations';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'fo.guests.')) {
            return 'fo_guests';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.tasks')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.inspections.')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.damages.')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.lost-found.')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.maintenance.')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.extrabed.')) {
            return 'hk_tasks';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'hk.laundry.')) {
            return 'hk_laundry';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'fb.breakfast')) {
            return 'fb_breakfast';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'fb.orders.')) {
            return 'fb_orders';
        }
        if (\Illuminate\Support\Str::startsWith($routeName, 'reports.')) {
            return 'reports';
        }

        return null;
    }
}
