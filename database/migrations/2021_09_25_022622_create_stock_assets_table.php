<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('balance', 10, 2)->comment('余额');
            $table->decimal('market_value', 10, 2)->comment('市值');
            $table->timestamp('compute_at')->nullable()->comment('计算时间');
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
        Schema::dropIfExists('stock_assets');
    }
}
