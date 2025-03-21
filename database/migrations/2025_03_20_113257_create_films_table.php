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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); 
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); 
            $table->integer('durée');
            $table->string('langue');
            $table->integer('age_min'); 
            $table->string('bande_annonce')->nullable();
            $table->string('genre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
