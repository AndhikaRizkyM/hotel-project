<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('profession')->nullable()->after('vehicle_no');
            $table->string('company')->nullable()->after('profession');
            $table->string('member_card_no')->nullable()->after('company');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('number_of_guests')->default(1)->after('check_out_time');
            $table->string('safety_deposit_box')->nullable()->after('number_of_guests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['profession', 'company', 'member_card_no']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['number_of_guests', 'safety_deposit_box']);
        });
    }
};
