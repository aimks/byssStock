<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_profits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->default('')->comment('股票代码');
            $table->string('name', 50)->default('')->comment('股票名称');
            $table->integer('amount')->default(0)->comment('股票数量');
            $table->decimal('buy_price', 10, 2)->comment('股票买入价');
            $table->decimal('sell_price', 10, 2)->comment('股票卖出价');
            $table->decimal('profit', 10, 2)->comment('股票收益');
            $table->date('sync_at')->nullable()->comment('同步时间');
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
        Schema::dropIfExists('stock_profits');
    }
}
