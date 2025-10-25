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
        Schema::create('mindmaps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user');
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title');
            $table->string('node');
            $table->string('parent_node')->nullable();
            $table->string('gambar_mindmap')->nullable();
            $table->string('ringkasan_pribadi')->nullable();
            $table->enum('type', ['brace', 'bubble', 'flow', 'multi', 'spider', 'custom'])->nullable();
            $table->enum('shareable', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mindmaps');
    }
};
