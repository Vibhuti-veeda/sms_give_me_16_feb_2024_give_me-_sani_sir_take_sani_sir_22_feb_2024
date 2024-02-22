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
        Schema::connection('mysql1')->create('location_master_trails', function (Blueprint $table) {
            $table->id();
            $table->integer('location_master_id');
            $table->string('location_name');
            $table->enum('holiday_type', ['CORPORATEOFFICE', 'CRSITE', 'BRSITE']);
            $table->string('location_address');
            $table->string('remarks');
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
        Schema::dropIfExists('location_master_trails');
    }
};
