<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronosDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('cronos_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('study_no');
            $table->integer('period');
            $table->dateTime('check_in_start')->nullable();
            $table->dateTime('check_in_end')->nullable();
            $table->integer('check_in_subject')->nullable();
            $table->dateTime('dosing_start')->nullable();
            $table->dateTime('dosing_end')->nullable();
            $table->integer('dosing_subject')->nullable();
            $table->dateTime('last_sample_start')->nullable();
            $table->dateTime('last_sample_end')->nullable();
            $table->string('is_paper_based_study')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_delete')->default(0);
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
        Schema::connection('mysql2')->dropIfExists('cronos_data');
    }
}
