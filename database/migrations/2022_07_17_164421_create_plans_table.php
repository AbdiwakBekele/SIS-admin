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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique();

            $table->string('name', 100)->nullable();
            $table->string('code', 20)->nullable();
            $table->integer('position')->default(0);
            $table->json('features')->nullable();
            $table->json('tax')->nullable();
            $table->json('pricing')->nullable();
            $table->text('description')->nullable();
            $table->text('inclusion')->nullable();
            $table->text('exclusion')->nullable();

            $table->json('meta')->nullable();
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
        Schema::dropIfExists('plans');
    }
};
