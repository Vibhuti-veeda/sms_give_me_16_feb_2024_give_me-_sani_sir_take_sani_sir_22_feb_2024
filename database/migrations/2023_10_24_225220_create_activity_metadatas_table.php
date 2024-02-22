<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityMetadatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_metadatas', function (Blueprint $table) {
            $table->id();
            $table->integer('activity_id');
            $table->integer('control_id');
            $table->string('source_value')->nullable();
            $table->string('source_question')->nullable();
            $table->tinyInteger('is_mandatory')->default(0);
            $table->string('input_validation')->nullable();
            $table->enum('is_activity', ['S', 'E']);
            $table->integer('created_by_user_id')->nullable();
            $table->integer('updated_by_user_id')->nullable();
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
        Schema::dropIfExists('activity_metadatas');
    }
}
