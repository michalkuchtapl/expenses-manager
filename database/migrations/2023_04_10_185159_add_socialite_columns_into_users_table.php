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
            $table->string('facebook_id')->nullable();
            $table->text('facebook_token')->nullable();
            $table->text('facebook_refresh_token')->nullable();
            $table->string('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_id',
                'facebook_token',
                'facebook_refresh_token',
                'google_id',
                'google_token',
                'google_refresh_token'
            ]);
        });
    }
};
