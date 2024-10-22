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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('agent_name');
            $table->string('field');
            $table->string('phone_number');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('fund_needed');
            $table->unsignedTinyInteger('anual_income');
            $table->unsignedTinyInteger('profit');
            $table->unsignedTinyInteger('bounced_check');
            $table->uuid('uuid')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
