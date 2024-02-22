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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('email_id');
            $table->string('mobile');
            $table->string('password');
            $table->string('login_id');
            $table->string('employee_code');
            $table->string('department');
            $table->string('department_no');
            $table->string('designation');
            $table->string('designation_no');
            $table->string('role_id');
            $table->string('multiple_roles');
            $table->integer('location_id');
            $table->tinyInteger('is_hod')->default(0);
            $table->enum('login_status', ['SUCCESS', 'FAIL']);
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
        Schema::dropIfExists('admins');
    }
};
