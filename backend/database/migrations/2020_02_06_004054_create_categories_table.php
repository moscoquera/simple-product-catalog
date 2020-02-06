<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('name',255);
            $table->text('image',255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();

        });

        Schema::table('categories',function (Blueprint $table){
            $table->foreign('parent_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories',function (Blueprint $table){
           $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('categories');
    }
}
