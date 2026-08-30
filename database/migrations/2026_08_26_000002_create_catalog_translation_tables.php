<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['category_id', 'locale']);
        });
        Schema::create('brand_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['brand_id', 'locale']);
        });
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('summary');
            $table->longText('description');
            $table->json('specifications')->nullable();
            $table->unique(['product_id', 'locale']);
        });
        DB::table('categories')->orderBy('id')->each(fn ($row) => DB::table('category_translations')->insert(['category_id' => $row->id, 'locale' => 'en', 'name' => $row->name, 'description' => $row->description]));
        DB::table('brands')->orderBy('id')->each(fn ($row) => DB::table('brand_translations')->insert(['brand_id' => $row->id, 'locale' => 'en', 'name' => $row->name, 'description' => $row->description]));
        DB::table('products')->orderBy('id')->each(fn ($row) => DB::table('product_translations')->insert(['product_id' => $row->id, 'locale' => 'en', 'name' => $row->name, 'summary' => $row->summary, 'description' => $row->description, 'specifications' => $row->specifications]));
        Schema::table('categories', fn (Blueprint $table) => $table->dropColumn(['name', 'description']));
        Schema::table('brands', fn (Blueprint $table) => $table->dropColumn(['name', 'description']));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['name', 'summary', 'description', 'specifications']));
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->default('');
            $table->text('description')->nullable();
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->string('name')->default('');
            $table->text('description')->nullable();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->default('');
            $table->text('summary')->default('');
            $table->longText('description')->default('');
            $table->json('specifications')->nullable();
        });
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('brand_translations');
        Schema::dropIfExists('category_translations');
    }
};
