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
        Schema::create('csv_file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('original_file_name');
            $table->string('new_file_name');
            $table->string('file_path');
            $table->string('md5_hash');
            $table->uuid('batch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_file_uploads');
    }
};
