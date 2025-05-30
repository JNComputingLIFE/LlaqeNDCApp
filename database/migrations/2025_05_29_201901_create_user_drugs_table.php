<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDrugsTable extends Migration
{
    public function up()
    {
        Schema::create('user_drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->string('ndc_code');
            $table->string('brand_name')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('labeler_name')->nullable();
            $table->string('product_type')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_drugs');
    }
}