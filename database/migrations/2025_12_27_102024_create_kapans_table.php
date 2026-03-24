<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKapansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kapans', function (Blueprint $table) {
            $table->id();
            $table->string('kapan_name');
            $table->decimal('kapan_weight', 10, 2)->nullable();
            $table->string('kapan_quantity')->nullable();
            $table->string('is_active')->default(1)->nullable();
            $table->decimal('per_carat_rate', 10, 2)->nullable();
            $table->decimal('doller_rate', 10, 2)->nullable();
            $table->decimal('total_rate', 10, 2)->nullable();
            $table->decimal('hpht_cost', 10, 2)->default(0)->nullable();
            $table->decimal('mfc_cost', 10, 2)->default(0)->nullable();
            $table->decimal('certificate_cost', 10, 2)->default(0)->nullable();
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
        Schema::dropIfExists('kapans');
    }
}
