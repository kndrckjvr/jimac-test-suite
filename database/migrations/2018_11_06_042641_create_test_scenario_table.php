<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestScenarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_scenario', function (Blueprint $table) {
            $table->increments('id');
            $table->string('test_scenario_id')->unique();
            $table->string('test_scenario_name');
            $table->longText('test_scenario_description');
            $table->longText('test_scenario_steps');
            $table->longText('expected_results');
            $table->longText('actual_results');
            $table->longText('remarks');
            $table->integer('status');
            $table->integer('module_id');
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
        Schema::dropIfExists('test_scenario');
    }
}
