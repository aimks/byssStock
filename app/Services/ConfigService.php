<?php
namespace App\Services;

use App\Models\Config;

class ConfigService
{
    /**
     * 获取余额
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed|string
     */
    public static function getBalance()
    {
        $config = Config::where('key', Config::STOCK_BALANCE)->first();
        return $config->value ?? 0;
    }

    /**
     * 更新余额
     * @param float $money
     */
    public static function updateBalance(float $money)
    {
        $config = Config::where('key', Config::STOCK_BALANCE)->first();
        $config->value += $money;
        $config->save();
    }
}
