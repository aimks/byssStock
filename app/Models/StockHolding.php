<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockHolding
 *
 * @property int $id
 * @property string $code 股票代码
 * @property string $name 股票名称
 * @property int $amount 股票数量
 * @property string $close_price 股票收盘价
 * @property string $market_value 市值，收盘价*数量
 * @property string|null $buy_at 买入时间
 * @property string|null $sync_at 价格更新时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereBuyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereClosePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereMarketValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockHolding whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockHolding extends Model
{
    const MAX_BUY_MONEY = 50000;
}
