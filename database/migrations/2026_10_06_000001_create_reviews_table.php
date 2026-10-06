<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // the employer who wrote it
            $table->unsignedTinyInteger('rating');                          // 1 to 5
            $table->text('comment');
            $table->timestamps();

            // one review per employer per freelancer (editing replaces it)
            $table->unique(['freelancer_profile_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
