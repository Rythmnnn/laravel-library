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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('member_number', 10)->unique();
            $table->string('phone_number', 16)->nullable()->unique();
            $table->text('address')->nullable();
            $table->enum('class_room', ['X PPLG', 'XI PPLG', 'XII PPLG 1', 'XII PPLG 2']);
            $table->date('start_register');
            $table->date('validate_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
