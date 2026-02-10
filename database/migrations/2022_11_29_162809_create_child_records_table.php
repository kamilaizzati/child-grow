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
        Schema::create('child_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('children_id');
            $table->foreign('children_id')->references('id')->on('children');
            $table->float('lingkar');
            $table->float('length');
            $table->float('weight');
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
        Schema::dropIfExists('child_records');
    }
};
