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
        Schema::create('delete_file_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->references('id')->on('servers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('file_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delete_file_queues');
    }
};
