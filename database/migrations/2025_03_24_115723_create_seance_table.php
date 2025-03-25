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
        Schema::create('seance', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('salle_id');
            $table->unsignedBigInteger('film_id');
            $table->dateTime('date_heure'); 
            $table->enum('type', ['Normale', 'VIP']);
            $table->timestamps();

            $table->foreign('salle_id')->references('id')->on('salles')->onDelete('cascade');
            $table->foreign('film_id')->references('id')->on('films')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance');
    }
};
