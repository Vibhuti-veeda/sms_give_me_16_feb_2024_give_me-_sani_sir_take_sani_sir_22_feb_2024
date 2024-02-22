<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql1')->create('study_schedule_trails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('study_id');
            $table->bigInteger('activity_id');
            $table->bigInteger('activity_name');
            $table->integer('require_days');
            $table->tinyInteger('is_milestone');
            $table->date('scheduled_start_date');
            $table->date('actual_start_date');
            $table->date('scheduled_end_date');
            $table->date('actual_end_date');
            $table->text('start_delay_remark');
            $table->text('end_delay_remark');
            $table->string('remarks');
            $table->integer('group_no');
            $table->integer('period_no');
            $table->integer('activity_type');
            $table->integer('version_id');
            $table->integer('created_by_user_id');
            $table->integer('updated_by_user_id');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_schedule_trails');
    }
};
