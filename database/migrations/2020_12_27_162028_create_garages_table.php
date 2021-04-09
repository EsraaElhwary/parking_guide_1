<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garages', function (Blueprint $table) {
            $table->string('id');
            $table->string('city');
            $table->string('street');
            $table->string('b_number');
            $table->string('capacity');
            $table->string('name');
            //$table->string('owner_id');
            $table->timestamps();
           	 	 		 	
           /* $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garages');
    }
}
