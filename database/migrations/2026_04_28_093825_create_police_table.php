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
        Schema::create('police', function (Blueprint $table) {
            $table->id();
            $table->integer('province_id');
            $table->integer('city_id');
            $table->string('name_police')->nullable();
            $table->string('classification')->nullable();
            $table->string('level')->nullable();
            $table->string('category')->nullable();
            $table->text('location')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('hrs_of_operation')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->boolean('police_status')->default(true);
            $table->text('icon')->nullable();
            $table->timestamps();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('police');
    }
};
