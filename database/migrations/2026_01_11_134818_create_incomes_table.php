<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khatas_id');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('income_date')->nullable();
            $table->string('income_type')->default('cash')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
