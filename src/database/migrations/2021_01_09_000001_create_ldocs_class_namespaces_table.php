<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLdocsClassNamespacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ldocs_class_namespaces', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ldocs_class_type_id')->index();
            $table->string("name");
            $table->boolean("active")->default(1);
            $table->timestamps();
            $table->foreign('ldocs_class_type_id')->references('id')->on('ldocs_class_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ldocs_class_namespaces');
    }
}
