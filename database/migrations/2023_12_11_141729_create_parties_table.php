<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('party_code')->nullable();
            $table->text('address')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('mobile')->nullable();
            $table->string('type')->default('party')->nullable();
            // $table->string('round_1')->default(1);
            // $table->string('round_2')->default(1);
            // $table->string('round_3')->default(1);
            // $table->string('fancy_1')->default(1);
            // $table->string('fancy_2')->default(1);
            // $table->string('fancy_3')->default(1);
            // $table->string('fancy_4')->default(1);
            // $table->string('fancy_5')->default(1);
            // $table->string('fancy_6')->default(1);
            // $table->string('fancy_7')->default(1);
            // $table->string('fancy_8')->default(1);
            $table->string('is_active')->default(1)->nullable();
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
        Schema::dropIfExists('parties');
    }
}
