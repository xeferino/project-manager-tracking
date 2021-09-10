<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinnaclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binnacles', function (Blueprint $table) {
            $table->id();
            $table->longText('observation')->nullable($value=true);
            $table->foreignId('project_id')->nullable($value=true);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreignId('02kk')->nullable($value=true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('department_send_id')->nullable($value=true);
            $table->foreign('department_send_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreignId('department_received_id')->nullable($value=true);
            $table->foreign('department_received_id')->references('id')->on('departments')->onDelete('cascade');
            $table->enum('status',['COMPLETADO', 'PENDIENTE', 'CREADO', 'RECHAZADO', 'ENVIADO', 'ARCHIVADO', 'APROBADO', 'DEVUELTO', 'ANULADO']);
            $table->integer('annexes');
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
        Schema::dropIfExists('binnacles');
        Schema::table('binnacles', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['department_send_id']);
            $table->dropColumn('department_send_id');
            $table->dropForeign(['department_received_id']);
            $table->dropColumn('department_received_id');
        });
    }
}
