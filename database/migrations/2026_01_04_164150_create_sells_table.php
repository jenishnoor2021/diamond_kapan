<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            // Relation
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('diamonds_id');

            // Sell Details
            $table->decimal('rate_per_ct', 10, 2);
            $table->decimal('dollar_rate', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('less_brokerage', 12, 2)->nullable();
            $table->decimal('final_amount', 12, 2);

            // Extra Info
            $table->string('parties_id')->nullable();
            $table->string('parties_name')->nullable();
            $table->string('payment_type'); // COD / Credit / Advance
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');

            $table->string('broker_id')->nullable();
            $table->string('broker_name')->nullable();
            $table->string('mobile_no')->nullable();

            $table->date('sell_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
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
        Schema::dropIfExists('sells');
    }
}
