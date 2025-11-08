<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('download_logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('participant_id');
            $table->string('ip_address', 50)->nullable()->default('0');
            $table->text('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
            
            // Indexes
            $table->index('participant_id', 'idx_participant_id');
            $table->index('downloaded_at', 'idx_downloaded_at');
            
            // Foreign Key
            $table->foreign('participant_id', 'fk_download_logs_participant')
                  ->references('participant_id')
                  ->on('participants')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('download_logs');
    }
};