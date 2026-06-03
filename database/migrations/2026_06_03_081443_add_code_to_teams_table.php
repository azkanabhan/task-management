<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('name');
        });

        // Backfill existing teams
        $teams = DB::table('teams')->get();
        foreach ($teams as $team) {
            do {
                $code = 'T-' . strtoupper(Str::random(8));
            } while (DB::table('teams')->where('code', $code)->exists());

            DB::table('teams')->where('id', $team->id)->update(['code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
