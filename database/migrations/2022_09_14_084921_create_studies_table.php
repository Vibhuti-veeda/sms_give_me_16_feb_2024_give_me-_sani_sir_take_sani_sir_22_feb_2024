<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->string('study_no');
            $table->string('sponsor_study_no')->nullable();
            $table->integer('sponsor');
            $table->longText('study_text');
            $table->integer('study_design');
            $table->integer('study_sub_type');
            $table->integer('subject_type');
            $table->integer('blinding_status');
            $table->integer('no_of_subject');
            $table->integer('no_of_male_subjects');
            $table->integer('no_of_female_subjects');
            $table->double('washout_period', 10, 2);
            $table->integer('cr_location');
            $table->integer('clinical_word_location')->nullable();
            $table->string('additional_requirement');
            $table->double('quotation_amount', 10, 2);
            $table->tinyInteger('cdisc_require')->default(0);
            $table->tinyInteger('tlf_require')->default(0);
            $table->integer('study_type');
            $table->integer('complexity');
            $table->integer('study_condition');
            $table->integer('priority');
            $table->integer('no_of_groups');
            $table->integer('no_of_periods');
            $table->double('total_housing', 10, 2);
            $table->double('pre_housing', 10, 4);
            $table->double('post_housing', 10, 4);
            $table->integer('br_location');
            $table->date('study_no_allocation_date')->nullable();
            $table->date('tentative_study_start_date')->nullable();
            $table->date('tentative_study_end_date')->nullable();
            $table->date('tentative_imp_date')->nullable();
            $table->integer('project_manager')->nullable();
            $table->string('principle_investigator')->nullable();
            $table->integer('bioanalytical_investigator');
            $table->integer('total_sponsor_queries')->nullable();
            $table->integer('open_sponsor_queries')->nullable();
            $table->integer('regulatory_queries')->nullable();      
            $table->enum('study_result', ['NA', 'PASS', 'FAIL'])->nullable();
            $table->integer('special_notes')->nullable();
            $table->longText('remark')->nullable();    
            $table->bigInteger('token_number')->nullable();      
            $table->enum('study_status', ['UPCOMING', 'ONGOING', 'COMPLETED'])->nullable();
            $table->tinyInteger('sap_require')->default(0);
            $table->tinyInteger('ecrf_require')->default(0);
            $table->tinyInteger('btif_require')->default(0);
            $table->tinyInteger('csbe_require')->default(0);
            $table->integer('group_study')->nullable();      
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
        Schema::dropIfExists('studies');
    }
}
