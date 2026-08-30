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
        Schema::table('products', function (Blueprint $table) {
            $table->string('availability_status')->default('in_stock')->after('image_url');
            $table->unsignedInteger('stock_quantity')->nullable()->after('availability_status');
            $table->unsignedInteger('minimum_order_quantity')->default(1)->after('stock_quantity');
            $table->unsignedInteger('lead_time_days')->nullable()->after('minimum_order_quantity');
            $table->index(['availability_status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['availability_status', 'is_active']);
            $table->dropColumn(['availability_status', 'stock_quantity', 'minimum_order_quantity', 'lead_time_days']);
        });
    }
};
