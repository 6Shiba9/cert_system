<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // เพิ่ม unique constraint สำหรับ activity_id + student_id
            $table->unique(['activity_id', 'student_id'], 'unique_activity_student');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropUnique('unique_activity_student');
        });
    }
};