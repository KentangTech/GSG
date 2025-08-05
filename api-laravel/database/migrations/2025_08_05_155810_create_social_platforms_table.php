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
        Schema::create('social_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Instagram, Facebook, TikTok, dll
            $table->string('url');
            $table->unsignedBigInteger('medsos_team_id');
            $table->foreign('medsos_team_id')->references('id')->on('medsos_teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_platforms');
    }
};
