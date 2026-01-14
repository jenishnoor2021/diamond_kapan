<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKapanPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kapan_parts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('kapans_id')->index();
            $table->string('name');
            $table->decimal('weight', 10, 2)->nullable();
            $table->integer('part_no');
            $table->string('is_active')->default(1)->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('kapans_id')
                ->references('id')
                ->on('kapans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kapan_parts');
    }
}
