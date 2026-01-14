<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhataBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khata_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khatas_id');
            $table->string('bill_date')->nullable();
            $table->string('bill_no')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('khatas_id')
                ->references('id')
                ->on('khatas')
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
        Schema::dropIfExists('khata_bills');
    }
}
