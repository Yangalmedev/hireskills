<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('freelancer_profiles', function (Blueprint $table) {
            $table->string('messenger', 50)->nullable()->after('phone'); // Facebook / Messenger username or ID
            $table->string('gmail', 100)->nullable()->after('messenger');
        });
    }

    public function down(): void
    {
        Schema::table('freelancer_profiles', function (Blueprint $table) {
            $table->dropColumn(['messenger', 'gmail']);
        });
    }
};
