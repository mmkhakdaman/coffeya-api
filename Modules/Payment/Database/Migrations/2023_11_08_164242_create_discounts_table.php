<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('admin_id');
            $table->string('code');
            $table->integer('usage_limitation')->default(0);
            $table->integer('user_limitation')->default(0);

            $table->integer('percent')->default(0);

            $table->integer('price')->default(0);

            $table->timestamp('expire_at')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

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
        Schema::dropIfExists('discounts');
    }
};
