<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('freelancer_profiles', function (Blueprint $table) {
            $table->string('barangay')->nullable()->after('address')->index();
        });

        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->string('barangay')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('freelancer_profiles', fn (Blueprint $t) => $t->dropColumn('barangay'));
        Schema::table('employer_profiles', fn (Blueprint $t) => $t->dropColumn('barangay'));
    }
};
