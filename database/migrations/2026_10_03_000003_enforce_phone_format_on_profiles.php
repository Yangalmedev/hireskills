<?php

use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Clean what is already stored: keep valid numbers (fixing +63 formats),
        //    clear anything invalid so the person simply re-enters it.
        foreach (['freelancer_profiles', 'employer_profiles'] as $tableName) {
            DB::table($tableName)->whereNotNull('phone')->orderBy('id')->each(function ($row) use ($tableName) {
                $clean = PhoneNumber::normalize($row->phone);
                $clean = PhoneNumber::isValid($clean) ? $clean : null;

                if ($clean !== $row->phone) {
                    DB::table($tableName)->where('id', $row->id)->update(['phone' => $clean]);
                }
            });
        }

        // 2. Database-level limit: a phone can never be longer than 11 characters.
        Schema::table('freelancer_profiles', fn (Blueprint $t) => $t->string('phone', 11)->nullable()->change());
        Schema::table('employer_profiles', fn (Blueprint $t) => $t->string('phone', 11)->nullable()->change());
    }

    public function down(): void
    {
        Schema::table('freelancer_profiles', fn (Blueprint $t) => $t->string('phone')->nullable()->change());
        Schema::table('employer_profiles', fn (Blueprint $t) => $t->string('phone')->nullable()->change());
    }
};
