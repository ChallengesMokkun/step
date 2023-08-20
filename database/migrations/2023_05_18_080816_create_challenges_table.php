<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('step_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->integer('current_step')->default(0);
            $table->boolean('clear_flg')->default(false);
            $table->boolean('num_change_flg')->default(false);
            $table->boolean('retry_flg')->default(false);
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('challenges', function (Blueprint $table) {
        $table->dropForeign('challenges_user_id_foreign');
        $table->dropColumn('user_id');
        $table->dropForeign('challenges_step_id_foreign');
        $table->dropColumn('step_id');
      });
      Schema::dropIfExists('challenges');
    }
};
