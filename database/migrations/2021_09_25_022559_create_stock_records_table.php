<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->default('')->comment('股票代码');
            $table->string('name', 50)->default('')->comment('股票名称');
            $table->string('type', 50)->default('buy')->comment('类型,buy:买入,sell:卖出');
            $table->integer('amount')->default(0)->comment('股票数量');
            $table->decimal('close_price', 10, 2)->comment('股票收盘价');
            $table->timestamp('operate_at')->nullable()->comment('操作时间');
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
        Schema::dropIfExists('stock_records');
    }
}
