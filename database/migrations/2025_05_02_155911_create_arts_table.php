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
        Schema::create('arts', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('image_path');
            $table->text('description')->nullable(); 
            $table->string('creator')->nullable()->default('UNKNOWN'); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->enum('art_status', ['LIVE', 'BUFFED', 'UNKNOWN'])->default('UNKNOWN'); 
            $table->enum('request_status', ['approved', 'rejected', 'pending'])->default('pending'); 
            $table->enum('art_type', ['street-art', 'mural', 'tag', 'sticker']);
            $table->integer('art_created_year')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['lat', 'lng'])->references(['lat', 'lng'])->on('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arts');
    }
};
