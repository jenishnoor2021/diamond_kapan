<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->text('address')->nullable();
            $table->string('sign')->nullable();
            $table->text('bank_info')->nullable();
            $table->string('cgst')->nullable();
            $table->string('sgst')->nullable();
            $table->text('extra_info')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('companies');
    }
}
