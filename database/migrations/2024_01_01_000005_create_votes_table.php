<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('shareholder_id')->constrained()->onDelete('cascade');
            $table->string('vote_option');
            $table->integer('votes_cast');
            $table->timestamp('voted_at');
            $table->timestamps();
            
            $table->unique(['voting_item_id', 'shareholder_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};