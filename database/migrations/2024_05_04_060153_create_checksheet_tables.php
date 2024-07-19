<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksheetTables extends Migration
{
    public function up()
    {
        // Create mst_checksheet_sections table
        Schema::create('mst_checksheet_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name');
            $table->string('dept');
            $table->string('section');
            $table->string('no_document');
            $table->timestamps();
        });

        // Create mst_shops table
        Schema::create('mst_shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');
            $table->foreignId('section_id')->constrained('mst_checksheet_sections');
            $table->timestamps();
        });

        // Create mst_models table
        Schema::create('mst_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->foreignId('shop_id')->constrained('mst_shops');
            $table->timestamps();
        });

        // Create checksheet_headers table
        Schema::create('checksheet_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('mst_checksheet_sections');
            $table->string('department');
            $table->string('sub_section');
            $table->date('date');
            $table->integer('revision');
            $table->string('document_no');
            $table->string('shift');
            $table->string('created_by');
            $table->string('status');
            $table->timestamps();
        });

        // Create checksheet_details table
        Schema::create('checksheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_id')->constrained('checksheet_headers');
            $table->foreignId('shop_id')->constrained('mst_shops');
            $table->integer('planning_manpower');
            $table->integer('actual_manpower');
            $table->string('pic');
            $table->timestamps();
        });

        // Create checksheet_productions table
        Schema::create('checksheet_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_id')->constrained('checksheet_details');
            $table->foreignId('model_id')->constrained('mst_models');
            $table->integer('planning_production');
            $table->integer('actual_production');
            $table->integer('balance');
            $table->timestamps();
        });

        // Create mst_downtime_causes table
        Schema::create('mst_downtime_causes', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('pic');
            $table->timestamps();
        });

        // Create checksheet_downtimes table
        Schema::create('checksheet_downtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('checksheet_productions');
            $table->foreignId('cause_id')->constrained('mst_downtime_causes');
            $table->string('problem');
            $table->string('action');
            $table->timestamps();
        });

        // Create checksheet_not_goods table
        Schema::create('checksheet_not_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('checksheet_productions');
            $table->foreignId('model_id')->constrained('mst_models');
            $table->integer('quantity');
            $table->integer('repair');
            $table->integer('reject');
            $table->integer('total');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checksheet_not_goods');
        Schema::dropIfExists('checksheet_downtimes');
        Schema::dropIfExists('mst_downtime_causes');
        Schema::dropIfExists('checksheet_productions');
        Schema::dropIfExists('checksheet_details');
        Schema::dropIfExists('checksheet_headers');
        Schema::dropIfExists('mst_models');
        Schema::dropIfExists('mst_shops');
        Schema::dropIfExists('mst_checksheet_sections');
    }
}
