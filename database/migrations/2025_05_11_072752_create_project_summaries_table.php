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
        Schema::create('project_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('description');
            $table->text('notes')->nullable();
            $table->json('documentation')->nullable(); // untuk foto-foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_summaries');
    }
};
