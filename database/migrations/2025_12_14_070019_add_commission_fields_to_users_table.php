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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('role', ['admin', 'sales_person'])->default('sales_person')->after('password');
            $table->decimal('default_commission_rate', 10, 2)->default(500.00)->after('role');
            $table->enum('commission_type', ['fixed', 'percentage'])->default('fixed')->after('default_commission_rate');
            $table->boolean('is_active')->default(true)->after('commission_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'default_commission_rate', 'commission_type', 'is_active']);
        });
    }
};
