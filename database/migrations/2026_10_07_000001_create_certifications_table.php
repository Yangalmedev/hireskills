<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);              // e.g. "NC II - Plumbing"
            $table->string('issuer', 150);             // e.g. "TESDA"
            $table->string('credential_id', 80)->nullable();
            $table->date('issued_on');
            $table->date('expires_on')->nullable();
            $table->string('file_path');               // private storage path
            $table->string('file_mime', 100);          // image/jpeg, image/png, application/pdf
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
