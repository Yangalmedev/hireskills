<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title', 120);                 // e.g. "Tiled bathroom, Brgy. Bito"
            $table->string('description', 500)->nullable();
            $table->string('file_path');                  // private storage path
            $table->string('file_mime', 100);             // image/jpeg, image/png, image/webp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
