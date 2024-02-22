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
        Schema::create('activity_masters', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->integer('days_required');
            $table->integer('minimum_days_allowed');
            $table->integer('maximum_days_allowed');
            $table->string('next_activity');
            $table->integer('buffer_days');
            $table->string('responsibility');
            $table->string('previous_activity');
            $table->tinyInteger('is_milestone');
            $table->double('milestone_percentage',4,2);
            $table->integer('milestone_amount');
            $table->string('parent_activity');
            $table->enum('activity_days', ['CALENDAR', 'WORKING']);
            $table->integer('activity_type');
            $table->tinyInteger('is_parellel');
            $table->tinyInteger('is_dependent');  
            $table->tinyInteger('is_group_specific');  
            $table->tinyInteger('is_period_specific');  
            $table->integer('sequence_no');
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
        Schema::dropIfExists('activity_masters');
    }
};
