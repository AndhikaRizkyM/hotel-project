<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Room Types Table
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->integer('size'); // in m2
            $table->text('description')->nullable();
            $table->decimal('price_per_night', 12, 2);
            $table->boolean('breakfast_included')->default(false);
            $table->boolean('extra_bed_available')->default(true);
            $table->text('facilities')->nullable(); // JSON or comma-separated list
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Rooms Table
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->integer('floor');
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->string('status', 2)->default('A'); // A=Available, O=Occupied, D=Dirty, C=Cleaning, M=Maintenance, R=Reserved, B=Blocked
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Guests Table
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_number'); // NIK / Passport
            $table->date('birth_date');
            $table->string('gender', 10);
            $table->text('address')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->timestamps();
        });

        // 4. Reservations Table
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->decimal('room_charge_per_night', 12, 2);
            $table->decimal('total_room_charge', 12, 2);
            $table->decimal('tax', 12, 2)->default(0.00); // e.g. 10%
            $table->decimal('service_charge', 12, 2)->default(0.00); // e.g. 5%
            $table->decimal('total_charge', 12, 2);
            $table->string('status', 4)->default('RSV'); // RSV=Reserved, CI=Checked In, CO=Checked Out, CAN=Cancelled, NS=No Show
            $table->timestamps();
        });

        // 5. Deposits Table
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // Cash, Debit Card, Credit Card, Transfer, QRIS
            $table->string('type')->default('payment'); // payment, refund
            $table->timestamp('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Guest Folios Table
        Schema::create('guest_folios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->timestamps();
        });

        // 7. Guest Folio Items Table
        Schema::create('guest_folio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_folio_id')->constrained('guest_folios')->onDelete('cascade');
            $table->string('item_type'); // Room Charge, Breakfast, Food & Beverage, Extra Bed, Laundry, Damage Charge, Lost Item Charge, Miscellaneous Charge
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('reference_id')->nullable(); // Reference to order, extra bed request, etc.
            $table->timestamps();
        });

        // 8. F&B Menus Table
        Schema::create('fb_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // food, beverage
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. F&B Orders Table
        Schema::create('fb_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->string('status')->default('Pending'); // Pending, Preparing, Ready, Delivered, Cancelled
            $table->timestamp('order_date');
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        // 10. F&B Order Items Table
        Schema::create('fb_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fb_order_id')->constrained('fb_orders')->onDelete('cascade');
            $table->foreignId('fb_menu_id')->constrained('fb_menus')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 11. Laundry Services Table
        Schema::create('laundry_services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Wash Only, Dry Clean, Iron Only, Express, etc.
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 12. Laundry Orders Table
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('laundry_service_id')->constrained('laundry_services')->onDelete('cascade');
            $table->string('status')->default('Pending'); // Pending, Collected, Washing, Ready, Delivered, Cancelled
            $table->timestamp('order_date');
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        // 13. Laundry Order Items Table
        Schema::create('laundry_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained('laundry_orders')->onDelete('cascade');
            $table->string('item_name'); // Kemeja, Kaos, Celana, Jaket, Dress, Handuk, etc.
            $table->integer('qty');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 14. Laundry Damage Reports Table
        Schema::create('laundry_damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained('laundry_orders')->onDelete('cascade');
            $table->string('item_name');
            $table->string('issue_type'); // damage, lost
            $table->text('description')->nullable();
            $table->decimal('compensation_amount', 12, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, resolved
            $table->timestamps();
        });

        // 15. Extra Bed Requests Table
        Schema::create('extrabed_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price_per_night', 12, 2)->default(150000.00);
            $table->integer('num_nights');
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('requested'); // requested, installed, removed
            $table->timestamp('request_date');
            $table->timestamps();
        });

        // 16. Housekeeping Tasks Table
        Schema::create('housekeeping_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('task_type'); // cleaning_checkout, cleaning_daily, inspection
            $table->string('status')->default('pending'); // pending, cleaning, ready_for_inspection, completed
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });

        // 17. Room Inspections Table
        Schema::create('room_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housekeeping_task_id')->constrained('housekeeping_tasks')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('result'); // passed, failed
            $table->string('status_after_inspection'); // Available, Maintenance
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 18. Damage Reports Table
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('reported_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('estimated_cost', 12, 2);
            $table->boolean('is_charged_to_folio')->default(false);
            $table->string('status')->default('pending'); // pending, repaired
            $table->timestamps();
        });

        // 19. Lost Found Reports Table
        Schema::create('lost_found_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('reported_by_user_id')->constrained('users')->onDelete('cascade');
            $table->text('item_description');
            $table->text('location_found')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('status')->default('lost'); // lost, claimed
            $table->timestamp('claim_date')->nullable();
            $table->timestamps();
        });

        // 20. Maintenance Requests Table
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('reported_by_user_id')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->decimal('estimated_cost', 12, 2)->default(0.00);
            $table->timestamp('completion_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
        Schema::dropIfExists('lost_found_reports');
        Schema::dropIfExists('damage_reports');
        Schema::dropIfExists('room_inspections');
        Schema::dropIfExists('housekeeping_tasks');
        Schema::dropIfExists('extrabed_requests');
        Schema::dropIfExists('laundry_damage_reports');
        Schema::dropIfExists('laundry_order_items');
        Schema::dropIfExists('laundry_orders');
        Schema::dropIfExists('laundry_services');
        Schema::dropIfExists('fb_order_items');
        Schema::dropIfExists('fb_orders');
        Schema::dropIfExists('fb_menus');
        Schema::dropIfExists('guest_folio_items');
        Schema::dropIfExists('guest_folios');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
    }
};
