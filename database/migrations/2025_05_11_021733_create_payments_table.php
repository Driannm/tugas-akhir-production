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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ['cash', 'credit_tempo', 'transfer', 'qris', 'lainnya']);
            $table->enum('status', ['lunas', 'belum_lunas', 'cicilan']);
            $table->date('payment_date');
            $table->string('reference_number')->nullable();
            $table->string('proof_file')->nullable();
            $table->text('note')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('installment_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
