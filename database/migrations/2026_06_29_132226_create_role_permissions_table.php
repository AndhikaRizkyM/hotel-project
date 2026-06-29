<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('menu_key');
            $table->timestamps();

            $table->unique(['role', 'menu_key']);
        });

        // Seed default permissions
        $defaultPermissions = [
            // Admin gets all menus
            ['role' => 'Admin', 'menu_key' => 'master_users'],
            ['role' => 'Admin', 'menu_key' => 'master_room_types'],
            ['role' => 'Admin', 'menu_key' => 'master_rooms'],
            ['role' => 'Admin', 'menu_key' => 'master_fb_menus'],
            ['role' => 'Admin', 'menu_key' => 'master_laundry_services'],
            ['role' => 'Admin', 'menu_key' => 'fo_availability'],
            ['role' => 'Admin', 'menu_key' => 'fo_reservations'],
            ['role' => 'Admin', 'menu_key' => 'fo_guests'],
            ['role' => 'Admin', 'menu_key' => 'hk_tasks'],
            ['role' => 'Admin', 'menu_key' => 'hk_laundry'],
            ['role' => 'Admin', 'menu_key' => 'fb_breakfast'],
            ['role' => 'Admin', 'menu_key' => 'fb_orders'],
            ['role' => 'Admin', 'menu_key' => 'reports'],

            // FO (Front Office)
            ['role' => 'FO', 'menu_key' => 'fo_availability'],
            ['role' => 'FO', 'menu_key' => 'fo_reservations'],
            ['role' => 'FO', 'menu_key' => 'fo_guests'],

            // HK (Housekeeping)
            ['role' => 'HK', 'menu_key' => 'hk_tasks'],
            ['role' => 'HK', 'menu_key' => 'hk_laundry'],

            // FB (Food & Beverage)
            ['role' => 'FB', 'menu_key' => 'fb_breakfast'],
            ['role' => 'FB', 'menu_key' => 'fb_orders'],
        ];

        foreach ($defaultPermissions as $perm) {
            DB::table('role_permissions')->insert(array_merge($perm, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
