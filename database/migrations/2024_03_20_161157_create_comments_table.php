<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('concern')->default(0);
            $table->float('concern_score')->default(0); 
            $table->unsignedInteger('comment_reports')->default(0);
            $table->string('comment_text');
            $table->float('fear')->default(0); 
            $table->float('anger')->default(0); 
            $table->float('anticipation')->default(0); 
            $table->float('trust')->default(0);
            $table->float('surprise')->default(0); 
            $table->float('positive')->default(0); 
            $table->float('negative')->default(0); 
            $table->float('sadness')->default(0); 
            $table->float('disgust')->default(0); 
            $table->float('joy')->default(0); 
            $table->float('neutral')->default(0); 
            $table->float('compound')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
