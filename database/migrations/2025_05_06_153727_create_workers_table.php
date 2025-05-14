<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('worker_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->date('birth_date')->nullable();
            $table->string('position');
            $table->string('address');
            $table->string('contact', 20);
            $table->string('emergency_contact', 20)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->enum('employment_status', ['pekerja_tetap', 'dipecat', 'pekerja_lepas'])->default('pekerja_tetap');
            $table->foreignId('construction_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
