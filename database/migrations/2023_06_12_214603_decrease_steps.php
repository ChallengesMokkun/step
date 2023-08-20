<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('steps', function (Blueprint $table) {
        for($i = 11; $i <= 15; $i++){
          $table->dropColumn('step_'.$i);
          $table->dropColumn('step_detail_'.$i);
        }
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('steps', function (Blueprint $table) {
        for($i = 11; $i <= 15; $i++){
          $table->string('step_'.$i)->nullable();
          $table->text('step_detail_'.$i)->nullable();
        }
      });
    }
};
