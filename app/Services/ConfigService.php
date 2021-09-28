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
     * 设置余额
     * @param float $money
     */
    public static function setBalance(float $money)
    {
        $config = Config::where('key', Config::STOCK_BALANCE)->first();
        $config->value = sprintf("%01.2f", $config->value + $money);
        $config->save();
    }

    /**
     * 获取最后同步时间
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
     */
    public static function getLastSyncAt()
    {
        $config = Config::where('key', Config::STOCK_LAST_SYNC_AT)->first();
        return $config->value;
    }

    /**
     * 设置最后同步时间
     * @param string $syncAt
     */
    public static function setLastSyncAt(string $syncAt)
    {
        $config = Config::where('key', Config::STOCK_LAST_SYNC_AT)->first();
        $config->value = $syncAt;
        $config->save();
    }
}
