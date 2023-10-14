<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Order\Enums\OrderStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('customer_id');
            $table->foreignIdFor(\Modules\Table\Entities\Table::class)->nullable();

            $table->boolean('is_delivery')->default(false);
            $table->foreignIdFor(\Modules\Customer\Entities\Address::class)->nullable();

            $table->boolean('is_packaging')->default(false);

            $table->text('description')->nullable();

            $table->bigInteger('post_cost')->default(0);
            $table->bigInteger('order_price')->default(0);
            $table->bigInteger('total_price')->default(0);

            $table->timestamp('pending_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->enum('status', get_value_enums(OrderStatusEnum::cases()))->default(OrderStatusEnum::NOT_PAID->value);

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
        Schema::dropIfExists('orders');
    }
};
