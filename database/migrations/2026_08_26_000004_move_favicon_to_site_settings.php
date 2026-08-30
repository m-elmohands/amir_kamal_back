<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('favicon_path')->nullable()->after('logo_url');
        });

        $faviconPath = DB::table('settings')->where('key', 'app_icon')->value('value');

        if ($faviconPath) {
            $settingsId = DB::table('site_settings')->value('id');

            if ($settingsId) {
                DB::table('site_settings')->where('id', $settingsId)->update(['favicon_path' => $faviconPath]);
            } else {
                DB::table('site_settings')->insert([
                    'favicon_path' => $faviconPath,
                    'maintenance_mode' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::dropIfExists('settings');
    }

    public function down(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        $faviconPath = DB::table('site_settings')->value('favicon_path');

        if ($faviconPath) {
            DB::table('settings')->insert([
                'key' => 'app_icon',
                'value' => $faviconPath,
                'group' => 'site',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('favicon_path');
        });
    }
};
