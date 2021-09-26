<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->default('')->comment('股票代码');
            $table->string('name', 50)->default('')->comment('股票名称');
            $table->integer('amount')->default(0)->comment('股票数量');
            $table->decimal('close_price', 10, 2)->comment('股票收盘价');
            $table->decimal('market_value', 10, 2)->comment('市值，收盘价*数量');
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
        Schema::dropIfExists('stock_details');
    }
}
