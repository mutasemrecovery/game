<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('name_en');
            $table->string('description_en');
            $table->string('name_ar');
            $table->string('description_ar');
            $table->double('selling_price');
            $table->tinyInteger('status')->default(1); //1  active //2 not active
            $table->timestamp('deleted_at')->nullable(); 
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
        Schema::dropIfExists('products');
    }
};
