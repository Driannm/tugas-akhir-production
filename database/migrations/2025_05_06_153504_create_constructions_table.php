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
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->string('construction_name');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status_construction', ['sedang_berlangsung', 'selesai', 'dibatalkan'])->default('sedang_berlangsung');
            $table->string('location')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('client_name')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('progress_percentage')->default(0);
            $table->json('documentations')->nullable();
            $table->string('contract_file')->nullable();
            $table->string('type_of_construction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constructions');
    }
};
