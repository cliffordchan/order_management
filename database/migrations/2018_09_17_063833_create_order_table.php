<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderTable
 */
class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('origin_latitude', 16, 14);
            $table->decimal('origin_longitude', 17, 14);
            $table->decimal('destination_latitude', 16, 14);
            $table->decimal('destination_longitude', 17, 14);
            $table->integer('distance')->unsigned();
            $table->enum('status', ['UNASSIGN', 'ASSIGNED', 'COMPLETED', 'CANCELLED'])->default('UNASSIGN');
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
        Schema::dropIfExists('orders');
    }
}
