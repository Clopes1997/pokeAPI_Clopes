<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('move_pokemon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id')->constrained('pokemons')->onDelete('cascade');
            $table->foreignId('move_id')->constrained()->onDelete('cascade');
            $table->unique(['pokemon_id', 'move_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('move_pokemon');
    }
};
