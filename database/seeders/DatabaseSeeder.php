<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Default Users with custom passwords based on role
        User::create([
            'name' => 'Hotel Administrator',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Front Office Staff',
            'email' => 'fo@hotel.com',
            'password' => Hash::make('fo123'),
            'role' => 'FO',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Housekeeping Staff',
            'email' => 'hk@hotel.com',
            'password' => Hash::make('hk123'),
            'role' => 'HK',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'F&B Staff',
            'email' => 'fb@hotel.com',
            'password' => Hash::make('fb123'),
            'role' => 'FB',
            'status' => 'active',
        ]);

        // 2. Seed Room Types
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'capacity' => 2,
                'size' => 20,
                'description' => 'A cozy room equipped with essential amenities, perfect for single travelers or couples.',
                'price_per_night' => 350000.00,
                'breakfast_included' => false,
                'extra_bed_available' => true,
                'facilities' => 'Queen Bed, TV, AC, WiFi, Bathroom Shower, Mineral Water',
                'status' => 'active',
            ],
            [
                'name' => 'Deluxe Room',
                'capacity' => 2,
                'size' => 25,
                'description' => 'Spacious deluxe room offering standard amenities plus a smart television and a working desk.',
                'price_per_night' => 500000.00,
                'breakfast_included' => false,
                'extra_bed_available' => true,
                'facilities' => 'Queen Bed, Smart TV, AC, WiFi, Work Desk, Bathroom Shower, Mineral Water',
                'status' => 'active',
            ],
            [
                'name' => 'Superior Room',
                'capacity' => 2,
                'size' => 30,
                'description' => 'Premium comfort room with a King Bed, Work Desk, and high-speed internet.',
                'price_per_night' => 700000.00,
                'breakfast_included' => false,
                'extra_bed_available' => true,
                'facilities' => 'King Bed, Smart TV, AC, WiFi, Work Desk, Shower, Mineral Water',
                'status' => 'active',
            ],
            [
                'name' => 'Studio Room',
                'capacity' => 2,
                'size' => 35,
                'description' => 'Modern studio setup including a mini pantry, mini fridge, and a luxurious bathtub.',
                'price_per_night' => 950000.00,
                'breakfast_included' => true,
                'extra_bed_available' => true,
                'facilities' => 'King Bed, Sofa, Smart TV, Mini Pantry, Mini Fridge, Bathtub, WiFi, AC',
                'status' => 'active',
            ],
            [
                'name' => 'Suite Room',
                'capacity' => 4,
                'size' => 50,
                'description' => 'Elite suite room featuring a separated living room area, bathtub, and mini pantry. Breakfast included.',
                'price_per_night' => 1500000.00,
                'breakfast_included' => true,
                'extra_bed_available' => true,
                'facilities' => 'Living Room, King Bed, Bathtub, Smart TV, Mini Pantry, Breakfast, WiFi, AC',
                'status' => 'active',
            ],
            [
                'name' => 'Connecting Room',
                'capacity' => 6,
                'size' => 60,
                'description' => 'Two interconnected rooms designed for families or larger groups. Breakfast included.',
                'price_per_night' => 2000000.00,
                'breakfast_included' => true,
                'extra_bed_available' => false,
                'facilities' => 'Two Connected Rooms, Two Bathrooms, Smart TV, Mini Pantry, Family Area, Breakfast, WiFi, AC',
                'status' => 'active',
            ],
        ];

        $insertedTypes = [];
        foreach ($roomTypes as $type) {
            $id = DB::table('room_types')->insertGetId(array_merge($type, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $insertedTypes[$type['name']] = $id;
        }

        // 3. Seed Rooms
        $rooms = [
            // Floor 1
            ['room_number' => '101', 'floor' => 1, 'room_type_id' => $insertedTypes['Standard Room'], 'status' => 'A'],
            ['room_number' => '102', 'floor' => 1, 'room_type_id' => $insertedTypes['Standard Room'], 'status' => 'A'],
            ['room_number' => '103', 'floor' => 1, 'room_type_id' => $insertedTypes['Standard Room'], 'status' => 'A'],
            ['room_number' => '104', 'floor' => 1, 'room_type_id' => $insertedTypes['Standard Room'], 'status' => 'A'],
            ['room_number' => '105', 'floor' => 1, 'room_type_id' => $insertedTypes['Deluxe Room'], 'status' => 'A'],
            ['room_number' => '106', 'floor' => 1, 'room_type_id' => $insertedTypes['Deluxe Room'], 'status' => 'A'],
            // Floor 2
            ['room_number' => '201', 'floor' => 2, 'room_type_id' => $insertedTypes['Deluxe Room'], 'status' => 'A'],
            ['room_number' => '202', 'floor' => 2, 'room_type_id' => $insertedTypes['Deluxe Room'], 'status' => 'A'],
            ['room_number' => '203', 'floor' => 2, 'room_type_id' => $insertedTypes['Superior Room'], 'status' => 'A'],
            ['room_number' => '204', 'floor' => 2, 'room_type_id' => $insertedTypes['Superior Room'], 'status' => 'A'],
            ['room_number' => '205', 'floor' => 2, 'room_type_id' => $insertedTypes['Studio Room'], 'status' => 'A'],
            // Floor 3
            ['room_number' => '301', 'floor' => 3, 'room_type_id' => $insertedTypes['Studio Room'], 'status' => 'A'],
            ['room_number' => '302', 'floor' => 3, 'room_type_id' => $insertedTypes['Suite Room'], 'status' => 'A'],
            ['room_number' => '303', 'floor' => 3, 'room_type_id' => $insertedTypes['Suite Room'], 'status' => 'A'],
            ['room_number' => '304', 'floor' => 3, 'room_type_id' => $insertedTypes['Connecting Room'], 'status' => 'A'],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->insert(array_merge($room, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 4. Seed F&B Menu Items
        $menus = [
            ['name' => 'Nasi Goreng Spesial', 'type' => 'food', 'price' => 35000.00],
            ['name' => 'Mie Goreng Jawa', 'type' => 'food', 'price' => 30000.00],
            ['name' => 'Club Sandwich', 'type' => 'food', 'price' => 45000.00],
            ['name' => 'Sop Buntut', 'type' => 'food', 'price' => 85000.00],
            ['name' => 'Ayam Bakar Taliwang', 'type' => 'food', 'price' => 55000.00],
            ['name' => 'Es Teh Manis', 'type' => 'beverage', 'price' => 10000.00],
            ['name' => 'Kopi Tubruk', 'type' => 'beverage', 'price' => 15000.00],
            ['name' => 'Orange Juice', 'type' => 'beverage', 'price' => 20000.00],
            ['name' => 'Avocado Juice', 'type' => 'beverage', 'price' => 25000.00],
            ['name' => 'Mineral Water', 'type' => 'beverage', 'price' => 8000.00],
        ];

        foreach ($menus as $menu) {
            DB::table('fb_menus')->insert(array_merge($menu, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. Seed Laundry Services
        $laundryServices = [
            ['name' => 'Wash Only', 'price' => 15000.00],
            ['name' => 'Dry Clean', 'price' => 30000.00],
            ['name' => 'Iron Only', 'price' => 10000.00],
            ['name' => 'Express Laundry', 'price' => 40000.00],
            ['name' => 'Regular Laundry', 'price' => 20000.00],
        ];

        foreach ($laundryServices as $service) {
            DB::table('laundry_services')->insert(array_merge($service, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
