<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamonds', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('kapans_id');
            $table->unsignedBigInteger('kapan_parts_id');
            $table->string('diamond_name', 50)->default('A');
            $table->string('janger_no')->nullable();
            $table->string('barcode_number')->nullable();
            $table->decimal('weight', 10, 2)->default(0.00);
            $table->decimal('prediction_weight', 10, 2)->default(0.00);
            $table->string('shape')->nullable();
            $table->string('color')->nullable();
            $table->string('clarity')->nullable();
            $table->string('cut')->nullable();
            $table->string('polish')->nullable();
            $table->string('symmetry')->nullable();
            $table->string('status')->nullable();
            $table->tinyInteger('ready_for_sell')->default(0);
            $table->string('delevery_date')->nullable();

            $table->timestamps();

            // Unique constraint (A, B, C duplicate prevent)
            // $table->unique(
            //     ['kapan_id', 'kapan_parts_id', 'diamond_name'],
            //     'unique_kapan_part_diamond'
            // );

            // Foreign key constraints
            $table->foreign('kapans_id')
                ->references('id')
                ->on('kapans')
                ->onDelete('cascade');

            $table->foreign('kapan_parts_id')
                ->references('id')
                ->on('kapan_parts')
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
        Schema::dropIfExists('diamonds');
    }
}
