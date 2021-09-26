<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyPriceToStockHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_holdings', function (Blueprint $table) {
            $table->decimal('buy_price', 10, 2)->nullable()->comment('股票买入价')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_holdings', function (Blueprint $table) {
            $table->dropColumn('buy_price');
        });
    }
}
