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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->enum('genre', ['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'])->nullable();
            $table->enum('status', ['Draft', 'Mixing', 'Selesai'])->nullable();
            $table->unsignedBigInteger('note_id')->nullable();
            $table->timestamps();

            $table->foreign('note_id')->references('id')->on('notes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};