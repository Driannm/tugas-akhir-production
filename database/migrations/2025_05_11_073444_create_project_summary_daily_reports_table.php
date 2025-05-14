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
        Schema::create('project_summary_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_summary_id')->constrained()->onDelete('cascade');
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_summary_daily_reports');
    }
};
