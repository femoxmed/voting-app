<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agm_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['yes_no', 'multiple_choice'])->default('yes_no');
            $table->json('options')->nullable();
            $table->enum('status', ['pending', 'active', 'closed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting_items');
    }
};
