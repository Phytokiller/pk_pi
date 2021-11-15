<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('device_id')->nullable();
            $table->string('manager_url')->nullable();
            $table->decimal('offset_t1', 5, 2)->nullable()->default(0);
            $table->decimal('offset_t2', 5, 2)->nullable()->default(0);
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
        Schema::dropIfExists('settings');
    }
}
