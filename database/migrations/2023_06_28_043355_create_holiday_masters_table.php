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
        Schema::create('holiday_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('holiday_year');
            $table->string('holiday_name');
            $table->enum('holiday_type', ['HOLIDAY', 'WEEKOFF']);
            $table->date('holiday_date');
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
        Schema::dropIfExists('holiday_masters');
    }
};
