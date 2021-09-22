<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalProfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_professions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('professional_id')->unique();
            $table->foreign('professional_id')->references('id')->on('professionals');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions');
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
        Schema::dropIfExists('professional_professions');
    }
}
