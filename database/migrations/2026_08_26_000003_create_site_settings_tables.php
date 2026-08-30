<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_url')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });

        Schema::create('site_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('site_name');
            $table->text('address')->nullable();
            $table->text('footer_description')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->unique(['site_setting_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_setting_translations');
        Schema::dropIfExists('site_settings');
    }
};
