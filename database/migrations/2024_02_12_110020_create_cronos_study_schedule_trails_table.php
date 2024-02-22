<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronosStudyScheduleTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql1')->create('cronos_study_schedule_trails', function (Blueprint $table) {
            $table->id();
            $table->string('study_no');
            $table->integer('period_no');
            $table->dateTime('actual_check_in_start_date_time')->nullable();
            $table->dateTime('actual_check_in_end_date_time')->nullable();
            $table->integer('check_in_subjects')->nullable();
            $table->dateTime('actual_dosing_start_date_time')->nullable();
            $table->dateTime('actual_dosing_end_date_time')->nullable();
            $table->integer('dosing_subjects')->nullable();
            $table->dateTime('actual_last_sample_start_date_time')->nullable();
            $table->dateTime('actual_last_sample_end_date_time')->nullable();
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
        Schema::connection('mysql1')->dropIfExists('cronos_study_schedule_trails');
    }
}
