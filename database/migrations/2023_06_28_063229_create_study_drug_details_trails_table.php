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
        Schema::connection('mysql1')->create('study_drug_details_trails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('study_id');
            $table->bigInteger('drug_id');
            $table->bigInteger('dosage_form_id');
            $table->string('dosage');
            $table->bigInteger('dosage_form_id');
            $table->string('uom_id');
            $table->enum('type', ['TEST', 'REFERENCE']);
            $table->string('manufacturedby');
            $table->string('remarks');
            $table->bigInteger('action_by_id');
            $table->bigInteger('action_by_role_id');
            $table->string('action');
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
        Schema::dropIfExists('study_drug_details_trails');
    }
};
