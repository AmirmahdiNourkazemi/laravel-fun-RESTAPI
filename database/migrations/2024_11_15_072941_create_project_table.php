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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->json('properties')->nullable();
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedBigInteger('min_invest');
            $table->unsignedBigInteger('fund_needed');
            $table->unsignedBigInteger('fund_achieved')->nullable();
            $table->float('expected_profit');
            $table->float('profit')->default(0);
            $table->unsignedSmallInteger('priority')->default(1);
            $table->uuid('uuid')->unique();
            $table->date('finish_at');
            $table->date('start_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
