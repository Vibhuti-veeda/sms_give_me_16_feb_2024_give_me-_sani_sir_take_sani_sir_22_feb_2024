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
        Schema::create('activity_sloting_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('activity_id');
            $table->tinyInteger('is_cdisc');
            $table->string('study_design');
            $table->integer('no_from_subject');
            $table->integer('no_to_subject');
            $table->integer('no_of_days');
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
        Schema::dropIfExists('activity_sloting_masters');
    }
};
