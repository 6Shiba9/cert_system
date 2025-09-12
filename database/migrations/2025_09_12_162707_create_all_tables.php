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
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'user'])->default('user');
            $table->timestamps();
        });

        // Agency table
        Schema::create('agency', function (Blueprint $table) {
            $table->id('agency_id');
            $table->string('agency_name');
            $table->timestamps();
        });

        // Branches table
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->string('branch_name');
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')->references('agency_id')->on('agency')->onDelete('cascade');
            $table->timestamps();
        });

        // Activity table
        Schema::create('activity', function (Blueprint $table) {
            $table->id('activity_id');
            $table->string('activity_name');
            $table->decimal('position_x', 8, 2)->nullable();
            $table->decimal('position_y', 8, 2)->nullable();
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('branch_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('certificate_img')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('access_code', 10)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('agency_id')->references('agency_id')->on('agency');
            $table->foreign('branch_id')->references('branch_id')->on('branches');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        // Participants table for storing names from Excel
        Schema::create('participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->unsignedBigInteger('activity_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('student_id')->nullable();
            $table->string('certificate_token', 32)->unique();
            $table->boolean('certificate_generated')->default(false);
            $table->timestamps();
            
            $table->foreign('activity_id')->references('activity_id')->on('activity')->onDelete('cascade');
        });

        // Download logs table
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('participant_id');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('downloaded_at');
            $table->timestamps();
            
            $table->foreign('participant_id')->references('participant_id')->on('participants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('activity');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('agency');
        Schema::dropIfExists('users');
    }
};
