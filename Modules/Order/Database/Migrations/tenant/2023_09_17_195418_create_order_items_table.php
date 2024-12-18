<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\Modules\Order\Entities\Order::class);

            $table->foreignIdFor(\Modules\Product\Entities\Product::class);

            $table->unsignedBigInteger('customer_id');

            $table->bigInteger('price')->default(0);
            $table->integer('quantity')->default(0);

            $table->bigInteger('total')->default(0);

            $table->softDeletes();
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
        Schema::dropIfExists('order_items');
    }
};
