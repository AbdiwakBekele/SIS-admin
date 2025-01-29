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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique();

            $table->string('domain', 100)->nullable();
            $table->string('custom_domain', 100)->nullable();
            $table->string('database', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->boolean('is_trial')->default(0);
            $table->date('expiry_date')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('cascade');
            $table->json('plan_details')->nullable();

            $table->string('email', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('contact_number', 100)->nullable();
            $table->json('address')->nullable();

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
        Schema::dropIfExists('tenants');
    }
};
