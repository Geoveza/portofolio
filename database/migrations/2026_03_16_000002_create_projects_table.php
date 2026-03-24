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
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('category')->default('web3');
            $table->json('technologies')->nullable();
            $table->string('image_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('contract_address')->nullable();
            $table->string('blockchain')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('status')->default('published');
            $table->timestamps();
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