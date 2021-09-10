<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnexedProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annexed_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annexed_processes');
        Schema::table('annexed_processes', function (Blueprint $table) {
            $table->dropForeign(['process_id']);
            $table->dropColumn('process_id');
        });
    }
}
