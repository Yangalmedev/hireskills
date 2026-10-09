<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hire_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();

            // the job
            $table->string('title', 120);
            $table->text('description');
            $table->string('barangay')->nullable();        // where the job is (Abuyog barangay)
            $table->string('address', 150)->nullable();    // purok / landmark
            $table->date('preferred_date')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->string('budget_type', 10)->nullable(); // per_hour | per_day | per_job

            // the flow
            $table->string('status', 12)->default('pending')->index(); // pending|accepted|declined|cancelled|completed
            $table->string('freelancer_reply', 500)->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hire_requests');
    }
};
