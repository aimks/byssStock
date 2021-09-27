<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockProfit
 *
 * @property int $id
 * @property string $code 股票代码
 * @property string $name 股票名称
 * @property int $amount 股票数量
 * @property string $buy_price 股票买入价
 * @property string $sell_price 股票卖出价
 * @property string $profit 股票收益
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereBuyPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $buy_at 买入时间
 * @property string|null $sell_at 卖出时间
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereBuyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockProfit whereSellAt($value)
 */
class StockProfit extends Model
{
    //
}
