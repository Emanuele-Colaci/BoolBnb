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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title',200);
            $table->string('slug');
            $table->tinyInteger('n_rooms')->nullable();
            $table->tinyInteger('n_beds')->nullable();
            $table->tinyInteger('n_bathrooms')->nullable();
            $table->smallInteger('mq')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 8, 6)->nullable();
            $table->decimal('longitude', 8, 6)->nullable();
            $table->string('img')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('visible')->default(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('apartments');
    }
};
