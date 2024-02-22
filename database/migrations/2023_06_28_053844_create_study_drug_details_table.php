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
        Schema::create('study_drug_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('study_id');
            $table->bigInteger('drug_id');
            $table->bigInteger('dosage_form_id');
            $table->double('dosage', 10, 2);
            $table->string('drug_strength');
            $table->bigInteger('uom_id');
            $table->enum('type', ['TEST', 'REFERENCE'])->nullable();
            $table->string('manufacturedby')->nullable();
            $table->string('remarks')->nullable();
            $table->bigInteger('action_by_id')->nullable();
            $table->bigInteger('action_by_role_id')->nullable();
            $table->string('action')->nullable();
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
        Schema::dropIfExists('study_drug_details');
    }
};
