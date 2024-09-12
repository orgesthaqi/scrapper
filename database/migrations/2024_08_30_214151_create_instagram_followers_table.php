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
        Schema::create('instagram_followers', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_account');
            $table->string('full_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->bigInteger('account_id');
            $table->string('username');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->text('profile_pic_url')->nullable();
            $table->boolean('exported')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_scrapers');
    }
};
