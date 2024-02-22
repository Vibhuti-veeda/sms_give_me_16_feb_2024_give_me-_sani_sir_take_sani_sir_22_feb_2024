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
        Schema::connection('mysql1')->create('sponsor_master_trails', function (Blueprint $table) {
            $table->id();
            $table->integer('sponsor_master_id');
            $table->string('sponsor_name');
            $table->string('sponsor_address');
            $table->string('sponsor_type');
            $table->string('contact_person_1');
            $table->bigInteger('contact_mobile_1');
            $table->string('contact_email_1');
            $table->string('contact_person_2');
            $table->bigInteger('contact_mobile_2');
            $table->string('contact_email_2');
            $table->bigInteger('landline_no');
            $table->longText('remarks');
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
        Schema::dropIfExists('sponsor_master_trails');
    }
};
