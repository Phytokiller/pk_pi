<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBathMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bath_measures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bath_id')->index();
            $table->decimal('sensor_1', 5, 2);
            $table->decimal('sensor_2', 5, 2);
            $table->boolean('door')->nullable();
            $table->boolean('boiler')->nullable();
            $table->time('elapsed_time');
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
        Schema::dropIfExists('bath_measures');
    }
}
