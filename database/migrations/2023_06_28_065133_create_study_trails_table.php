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
        Schema::connection('mysql1')->create('study_trails', function (Blueprint $table) {
            $table->id();
            $table->integer('study_id');
            $table->string('study_no');
            $table->string('sponsor_study_no');
            $table->string('sponsor');
            $table->longText('study_text');
            $table->integer('study_design');
            $table->integer('study_sub_type');
            $table->integer('subject_type');
            $table->integer('blinding_status');
            $table->integer('no_of_subject');
            $table->integer('no_of_male_subjects');
            $table->integer('no_of_female_subjects');
            $table->double('no_of_female_subjects',10,2);
            $table->integer('cr_location');
            $table->integer('clinical_word_location');
            $table->string('additional_requirement');
            $table->double('quotation_amount',10,2);
            $table->tinyInteger('cdisc_require');
            $table->tinyInteger('tlf_require');
            $table->integer('study_type');
            $table->integer('complexity');
            $table->integer('study_condition');
            $table->integer('priority');
            $table->integer('no_of_groups');
            $table->integer('no_of_periods');
            $table->double('total_housing',10,2);
            $table->double('pre_housing',10,4);
            $table->double('post_housing',10,4);
            $table->integer('br_location');
            $table->date('study_no_allocation_date');
            $table->date('tentative_study_start_date');
            $table->date('tentative_study_end_date');
            $table->date('tentative_imp_date');
            $table->integer('project_manager');
            $table->string('principle_investigator');
            $table->integer('bioanalytical_investigator');
            $table->integer('total_sponsor_queries');
            $table->integer('open_sponsor_queries');
            $table->integer('regulatory_queries');
            $table->enum('study_result', ['NA', 'PASS', 'FAIL']);
            $table->integer('special_notes');
            $table->longText('remark')->nullable();
            $table->bigInteger('token_number');
            $table->tinyInteger('sap_require');
            $table->tinyInteger('ecrf_require');
            $table->tinyInteger('btif_require');
            $table->tinyInteger('csbe_require')->default(0);
            $table->integer('group_study');
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
        Schema::dropIfExists('study_trails');
    }
};
