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
        Schema::create('ringkasans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->unique();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('mindmaps');
            $table->foreign('mindmaps')->references('id')->on('mindmaps')->onDelete('cascade')->onUpdate('cascade');
            $table->string('ringkasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringkasans');
    }
};
