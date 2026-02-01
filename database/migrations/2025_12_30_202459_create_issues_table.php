<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kapans_id')->index();
            $table->integer('kapan_parts_id');
            $table->integer('diamonds_id');
            $table->integer('designation_id');
            $table->integer('worker_id');
            $table->decimal('issue_weight', 10, 2)->default(0.00);
            $table->string('issue_date')->nullable();
            $table->decimal('return_weight', 10, 2)->default(0.00);
            $table->string('return_date')->nullable();
            $table->string('is_return')->default(0)->nullable();
            $table->string('r_shape')->nullable();
            $table->string('r_color')->nullable();
            $table->string('r_clarity')->nullable();
            $table->string('r_cut')->nullable();
            $table->string('r_polish')->nullable();
            $table->string('r_symmetry')->nullable();
            $table->boolean('is_non_certi')->default(0);
            $table->string('certi_no')->nullable();
            $table->string('Certificate_url')->nullable();
            $table->string('availability')->nullable();
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('total_price')->nullable();
            $table->string('image_link')->nullable();
            $table->string('video_link')->nullable();
            $table->string('depth_percent')->nullable();
            $table->string('table_percent')->nullable();
            $table->string('fluorescence_intensity')->nullable();
            $table->string('lab')->nullable();
            $table->string('measurements')->nullable();
            $table->string('bgm')->nullable();
            $table->string('fancy_color')->nullable();
            $table->string('fancy_color_intensity')->nullable();
            $table->string('cut_grade')->nullable();
            $table->string('h_and_a')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('eye_clean')->nullable();
            $table->string('growth_type')->nullable();

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
        Schema::dropIfExists('issues');
    }
}
