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
        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('requested_quantity')->nullable()->after('assigned_to');
            $table->string('machine_model')->nullable()->after('requested_quantity');
            $table->string('material')->nullable()->after('machine_model');
            $table->string('priority')->default('normal')->after('status');
            $table->decimal('quoted_amount', 12, 2)->nullable()->after('priority');
            $table->timestamp('quoted_at')->nullable()->after('quoted_amount');
            $table->timestamp('next_follow_up_at')->nullable()->after('quoted_at');
            $table->text('internal_notes')->nullable()->after('next_follow_up_at');
            $table->index(['status', 'next_follow_up_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['status', 'next_follow_up_at']);
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn(['requested_quantity', 'machine_model', 'material', 'priority', 'quoted_amount', 'quoted_at', 'next_follow_up_at', 'internal_notes']);
        });
    }
};
