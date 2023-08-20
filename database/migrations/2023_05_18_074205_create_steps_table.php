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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->string('title');
            $table->string('phrase')->nullable();
            $table->integer('estimate');
            $table->integer('unit_id');
            $table->integer('total_step');
            $table->text('supplement')->nullable();
            $table->dateTime('edited_at');
            $table->boolean('public_flg')->default(false);
            $table->string('step_1');
            $table->text('step_detail_1');
            for($i = 2; $i <= 15; $i++){
              $table->string('step_'.$i)->nullable();
              $table->text('step_detail_'.$i)->nullable();
            }
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('steps', function (Blueprint $table) {
        $table->dropForeign('steps_user_id_foreign');
        $table->dropColumn('user_id');
        $table->dropForeign('steps_category_id_foreign');
        $table->dropColumn('category_id');
      });
      Schema::dropIfExists('steps');
    }
};
