<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('rating');
            $table->unsignedBigInteger('professional_id');
            $table->foreign('professional_id')->references('id')->on('professionals');
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
        Schema::dropIfExists('professional_ratings');
    }
}
