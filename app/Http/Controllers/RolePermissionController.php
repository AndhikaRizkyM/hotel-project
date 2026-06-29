<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index()
    {
        // Load roles dynamically from the database
        $rolesData = Role::orderBy('id')->get();
        $roles = $rolesData->pluck('name')->toArray();
        
        $menus = [
            'Master Data' => [
                'master_users' => 'Users Management',
                'master_room_types' => 'Room Types Management',
                'master_rooms' => 'Rooms Management',
                'master_fb_menus' => 'F&B Menu Management',
                'master_laundry_services' => 'Laundry Services Management',
            ],
            'Front Office' => [
                'fo_availability' => 'Room Availability',
                'fo_reservations' => 'Bookings (Reservations)',
                'fo_guests' => 'Guest Profiles',
            ],
            'Housekeeping' => [
                'hk_tasks' => 'Housekeeping Hub',
                'hk_laundry' => 'Laundry Board',
            ],
            'Food & Beverage' => [
                'fb_breakfast' => 'Breakfast List',
                'fb_orders' => 'F&B Orders',
            ],
            'Reports' => [
                'reports' => 'Analytics Reports',
            ],
        ];

        // Retrieve existing permissions mapped as role => [menu_key1, menu_key2, ...]
        $permissions = RolePermission::all()->groupBy('role')->map(function ($item) {
            return $item->pluck('menu_key')->toArray();
        })->toArray();

        return view('master.roles.index', compact('rolesData', 'roles', 'menus', 'permissions'));
    }

    public function update(Request $request)
    {
        // permissions input format: ['FO' => ['fo_availability' => 'on', 'fo_reservations' => 'on'], ...]
        $submittedPermissions = $request->input('permissions', []);
        
        // Find all roles dynamically
        $allRoles = Role::pluck('name')->toArray();
        // Keep Admin locked for security
        $editableRoles = array_filter($allRoles, function($role) {
            return $role !== 'Admin';
        });

        DB::transaction(function () use ($submittedPermissions, $editableRoles) {
            // Delete existing permissions for editable roles
            RolePermission::whereIn('role', $editableRoles)->delete();

            // Insert new permissions
            foreach ($editableRoles as $role) {
                if (isset($submittedPermissions[$role]) && is_array($submittedPermissions[$role])) {
                    foreach (array_keys($submittedPermissions[$role]) as $menuKey) {
                        RolePermission::create([
                            'role' => $role,
                            'menu_key' => $menuKey,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('master.roles.index')->with('success', 'Role permissions updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'alpha_num', 'max:50', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:100'],
        ]);

        Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        return redirect()->route('master.roles.index')->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
        ]);

        $role->update([
            'display_name' => $request->display_name,
        ]);

        return redirect()->route('master.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $defaultRoles = ['Admin', 'FO', 'HK', 'FB'];

        if (in_array($role->name, $defaultRoles)) {
            return redirect()->route('master.roles.index')->with('error', 'You cannot delete default system roles.');
        }

        DB::transaction(function () use ($role) {
            // Remove permissions mapped to this role
            RolePermission::where('role', $role->name)->delete();
            
            // Delete the role
            $role->delete();
        });

        return redirect()->route('master.roles.index')->with('success', 'Role and its permissions deleted successfully.');
    }
}
