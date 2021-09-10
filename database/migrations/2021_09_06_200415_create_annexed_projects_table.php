<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnexedProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annexed_projects', function (Blueprint $table) {
            $table->id();
            $table->string('file_name_original');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_path_delete');
            $table->string('file_size');
            $table->string('file_type');
            $table->longText('observation')->nullable();
            $table->string('annexed_name');
            $table->foreignId('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('annexed_projects');
        Schema::table('annexed_projects', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
}
