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
        // 1. Add guarantee fields to reservations table if they don't exist
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'guarantee_type')) {
                $table->string('guarantee_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('reservations', 'guarantee_detail')) {
                $table->string('guarantee_detail')->nullable()->after('guarantee_type');
            }
        });

        // 2. Add reservation_id to housekeeping_tasks table if it doesn't exist
        if (!Schema::hasColumn('housekeeping_tasks', 'reservation_id')) {
            Schema::table('housekeeping_tasks', function (Blueprint $table) {
                $table->foreignId('reservation_id')->nullable()->after('room_id')->constrained('reservations')->onDelete('cascade');
            });
        }

        // 3. Add reservation_id to damage_reports table if it doesn't exist
        if (!Schema::hasColumn('damage_reports', 'reservation_id')) {
            Schema::table('damage_reports', function (Blueprint $table) {
                $table->foreignId('reservation_id')->nullable()->after('guest_id')->constrained('reservations')->onDelete('cascade');
            });
        }

        // 4. Add reservation_id to lost_found_reports table if it doesn't exist
        if (!Schema::hasColumn('lost_found_reports', 'reservation_id')) {
            Schema::table('lost_found_reports', function (Blueprint $table) {
                $table->foreignId('reservation_id')->nullable()->after('reported_by_user_id')->constrained('reservations')->onDelete('cascade');
            });
        }

        // 5. Create breakfast_records table if it doesn't exist
        if (!Schema::hasTable('breakfast_records')) {
            Schema::create('breakfast_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
                $table->date('date');
                $table->string('status')->default('Pending'); // Pending, Preparing, Delivered, Skipped
                $table->integer('pax')->default(1);
                $table->text('notes')->nullable();
                $table->json('timeline')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakfast_records');

        Schema::table('lost_found_reports', function (Blueprint $table) {
            if (Schema::hasColumn('lost_found_reports', 'reservation_id')) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            }
        });

        Schema::table('damage_reports', function (Blueprint $table) {
            if (Schema::hasColumn('damage_reports', 'reservation_id')) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            }
        });

        Schema::table('housekeeping_tasks', function (Blueprint $table) {
            // Note: Since reservation_id might have existed prior to this migration,
            // we should be careful. But standard rollback drops it.
            if (Schema::hasColumn('housekeeping_tasks', 'reservation_id')) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            }
        });

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'guarantee_type')) {
                $table->dropColumn('guarantee_type');
            }
            if (Schema::hasColumn('reservations', 'guarantee_detail')) {
                $table->dropColumn('guarantee_detail');
            }
        });
    }
};
