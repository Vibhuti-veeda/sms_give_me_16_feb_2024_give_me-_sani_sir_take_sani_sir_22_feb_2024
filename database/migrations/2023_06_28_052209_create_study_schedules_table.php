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
        Schema::create('study_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('study_id')->nullable();
            $table->bigInteger('activity_id')->nullable();
            $table->string('activity_name')->nullable();
            $table->integer('activity_sequence_no')->nullable();
            $table->bigInteger('responsibility_id')->nullable();
            $table->integer('require_days')->nullable();
            $table->integer('minimum_days_allowed')->nullable();
            $table->integer('maximum_days_allowed')->nullable();
            $table->bigInteger('previous_activity_id')->nullable();
            $table->bigInteger('next_activity_id')->nullable();
            $table->tinyInteger('is_milestone')->default(0);
            $table->double('milestone_percentage', 10, 2)->nullable();
            $table->integer('milestone_amount')->nullable();
            $table->integer('buffer_days')->nullable();
            $table->date('scheduled_start_date')->nullable();
            $table->date('original_schedule_start_date')->nullable();    
            $table->date('actual_start_date')->nullable();
            $table->integer('start_difference')->nullable();
            $table->date('scheduled_end_date')->nullable();
            $table->date('original_schedule_end_date')->nullable();      
            $table->date('actual_end_date')->nullable();
            $table->text('start_delay_remark')->nullable();
            $table->text('end_delay_remark')->nullable();
            $table->integer('end_difference')->nullable();
            $table->bigInteger('reference_parent_activity_id')->nullable();
            $table->string('remarks')->nullable();
            $table->tinyInteger('is_dependent')->default(0);
            $table->tinyInteger('is_parellel')->default(0);
            $table->integer('group_no')->nullable();
            $table->integer('period_no')->nullable();
            $table->integer('is_group_specific')->nullable();
            $table->integer('is_period_specific')->nullable();
            $table->enum('activity_status', ['UPCOMING', 'ONGOING', 'DELAYINSTART', 'DELAYINEND', 'COMPLETED', 'COMPLETEDWITHSTARTDELAY', 'COMPLETEDWITHENDDELAY'])->nullable();
            $table->bigInteger('action_by_id')->nullable();
            $table->bigInteger('action_by_role_id')->nullable();
            $table->string('action')->nullable();
            $table->integer('activity_type')->nullable();
            $table->integer('created_by_user_id')->nullable();
            $table->integer('updated_by_user_id')->nullable();
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
        Schema::dropIfExists('study_schedules');
    }
};
