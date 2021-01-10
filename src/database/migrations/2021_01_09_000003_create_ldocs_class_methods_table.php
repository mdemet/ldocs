<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLdocsClassMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ldocs_class_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ldocs_class_id')->index();
            $table->string("name");
            $table->string("url")->nullable();
            $table->text("description")->nullable();
            $table->boolean("active")->default(1);
            $table->timestamps();
            $table->foreign('ldocs_class_id')->references('id')->on('ldocs_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ldocs_class_methods');
    }
}
