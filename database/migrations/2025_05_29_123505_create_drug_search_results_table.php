<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugSearchResultsTable extends Migration
{
    public function up()
    {
        Schema::create('drug_search_results', function (Blueprint $table) {
            $table->id();
            $table->string('ndc_code')->unique();
            $table->string('brand_name');
            $table->string('generic_name');
            $table->string('labeler_name');
            $table->string('product_type');
            $table->string('source');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drug_search_results');
    }
}
