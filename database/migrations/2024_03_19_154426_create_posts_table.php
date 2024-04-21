<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('text');
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('post_reports')->default(0);
            $table->unsignedInteger('concern')->default(0);
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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('posts');
    }
};
