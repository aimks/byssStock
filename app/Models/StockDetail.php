<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockDetail
 *
 * @property int $id
 * @property string $code 股票代码
 * @property string $name 股票名称
 * @property int $amount 股票数量
 * @property string $close_price 股票收盘价
 * @property string $market_value 市值，收盘价*数量
 * @property string|null $sync_at 同步时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereClosePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereMarketValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockDetail extends Model
{
    //
}
