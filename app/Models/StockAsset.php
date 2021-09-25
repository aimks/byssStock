<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockAsset
 *
 * @property int $id
 * @property string $balance 余额
 * @property string $market_value 市值
 * @property string|null $compute_at 计算时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereComputeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereMarketValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAsset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockAsset extends Model
{
    //
}
