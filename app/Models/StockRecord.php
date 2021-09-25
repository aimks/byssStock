<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockRecord
 *
 * @property int $id
 * @property string $code 股票代码
 * @property string $name 股票名称
 * @property string $type 类型,buy:买入,sell:卖出
 * @property int $amount 股票数量
 * @property string $close_price 收盘价
 * @property string|null $operate_at 操作时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereClosePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereOperateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockRecord extends Model
{
    const TYPE_BUY = 'buy';
    const TYPE_SELL = 'sell';
}
