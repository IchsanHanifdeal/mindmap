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
        Schema::create('kata_kuncis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi');
            $table->unsignedBigInteger('user');
            $table->string('kata_kunci');

            $table->foreign('materi')->references('id')->on('materis')->onDelete('cascade');
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kata_kuncis');
    }
};
