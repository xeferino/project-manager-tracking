<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectWordFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_word_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_created_id')->nullable($value = true);
            $table->foreign('user_created_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('user_accepted_id')->nullable($value = true);
            $table->foreign('user_accepted_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('step')->nullable($value = true);
            $table->foreignId('wordflow_id')->nullable($value = true);
            $table->foreign('wordflow_id')->references('id')->on('wordflows')->onDelete('cascade');
            $table->foreignId('wordflow_department_id')->nullable($value = true);
            $table->foreign('wordflow_department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('wordflow_department')->nullable($value = true);
            $table->string('wordflow_status')->nullable($value = true);
            $table->foreignId('project_id')->nullable($value = true);
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
        Schema::dropIfExists('project_word_flows');
        Schema::table('project_word_flows', function (Blueprint $table) {
            $table->dropForeign(['user_created']);
            $table->dropColumn('user_created');
            $table->dropForeign(['user_accepted']);
            $table->dropColumn('user_accepted');
            $table->dropForeign(['wordflow_id']);
            $table->dropColumn('wordflow_id');
            $table->dropForeign(['wordflow_department_id']);
            $table->dropColumn('wordflow_department_id');
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
}
