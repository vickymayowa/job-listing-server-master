<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Auto-incrementing ID column
            $table->string('name');
            $table->string('email')->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('password');
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};