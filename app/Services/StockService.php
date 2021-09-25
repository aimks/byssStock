<?php
namespace App\Services;

use App\Models\StockAsset;
use App\Models\StockDetail;
use App\Models\StockHolding;
use App\Models\StockRecord;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class StockService
{
    // 网易接口
    const SINA_API_URL = 'https://hq.sinajs.cn/list={prefixCode}';
    // 新浪接口
    const NET_API_URL = 'http://quotes.money.163.com/service/chddata.html?code={prefixCode}&start={start}&end={end}&fields=TCLOSE';

    /**
     * 获取股票信息
     * @param string $code
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStockInfo(string $code)
    {
        $prefixCode = $this->getPrefixCode($code);
        return $this->getStockInfoBySina($prefixCode);
    }

    /**
     * 获取股票前缀
     * @param string $code
     * @param bool $isWy
     * @return string
     */
    private function getPrefixCode(string $code, bool $isNet = false)
    {
        $prefix = $isNet ? '1' : 'sz';
        if ($code[0] === '6') {
            $prefix = $isNet ? '0' : 'sh';
        }
        return $prefix . $code;
    }

    /**
     * 新浪接口获取股票信息
     * @param string $prefixCode
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getStockInfoBySina(string $prefixCode)
    {
        $url = Str::replaceFirst('{prefixCode}', $prefixCode, self::SINA_API_URL);
        $client = new Client();
        $response = $client->get($url);
        $result = $response->getBody()->getContents();
        $result = iconv('GB2312', 'UTF-8', $result);
        preg_match('/"([^"]*)"/', $result, $matches);
        $info = $matches[1];
        if (empty($info)) {
            return [];
        }
        $infoArr = explode(',', $info);
        $closePrice = round($infoArr[2], 2);
        // 算出最大持仓数量
        $amount =  floor(StockHolding::MAX_BUY_MONEY / ($closePrice * 100)) * 100;
        if ($amount == 0) {
            $amount = 100;
        }
        return [
            'code' => $infoArr[0],
            'close_price' => (string)$closePrice,
            'amount' => $amount,
        ];
    }

    /**
     * 网易接口获取股票信息
     * @param string $prefixCode
     * @param $date
     * @return array|float[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getStockInfoByNet(string $prefixCode, $date)
    {
        $date = date("Ymd", strtotime($date));
        $url = Str::replaceFirst('{prefixCode}', $prefixCode, self::NET_API_URL);
        $url = Str::replaceFirst('{start}', $date, $url);
        $url = Str::replaceFirst('{end}', $date, $url);
        $client = new Client();
        $response = $client->get($url);
        $result = $response->getBody()->getContents();
        $result = iconv('GB2312', 'UTF-8', $result);
        if (strpos($result, substr($prefixCode, -6)) === false) {
            return [];
        }
        $infoArr = explode(',', $result);
        $closePrice = end($infoArr);
        return [
            'close_price' => (float)$closePrice,
        ];
    }
    public function addRecord(string $code, string $name, string $type, string $closePrice, int $amount, string $operateAt)
    {
        // 加入记录
        $record = new StockRecord();
        $record->code = $code;
        $record->name = $name;
        $record->type = $type;
        $record->amount = $amount;
        $record->close_price = $closePrice;
        $record->operate_at = $operateAt;
        $record->save();
        switch ($type) {
            case StockRecord::TYPE_BUY:
                // 加入持仓表
                $holding = new StockHolding();
                $holding->code = $code;
                $holding->name = $name;
                $holding->amount = $amount;
                $holding->close_price = $closePrice;
                $holding->market_value = $amount * $closePrice;
                $holding->buy_at = $operateAt;
                $holding->save();
                // 更新资金余额
                ConfigService::setBalance(-$amount * $closePrice);
                break;
            case StockRecord::TYPE_SELL:
                // 删除持仓表
                $holding = $this->getHoldingByCode($code);
                $holding->delete();
                // 更新资金余额
                ConfigService::setBalance($amount * $closePrice);
                break;
            default:
                break;
        }
    }
    /**
     * 根据代码获取持仓
     * @param string $code
     * @return StockHolding|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getHoldingByCode(string $code)
    {
        return StockHolding::where('code', $code)->first();
    }

    /**
     * 获取所有持仓
     * @return \Illuminate\Support\Collection
     */
    public function getHoldings()
    {
        return StockHolding::orderBy('buy_at')->get();
    }
    /**
     * 根据时间获取资产信息
     * @param string $computeAt
     * @return StockAsset|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getAssetBySyncAt(string $syncAt)
    {
        return StockAsset::whereRaw("DATE_FORMAT(sync_at, '%Y-%m-%d') = ?", [$syncAt])->first();
    }
    /**
     * 同步股票明细
     * @param StockHolding $holding
     * @param string $date
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncStockDetail(StockHolding $holding, string $date)
    {

        $code = $this->getPrefixCode($holding->code, true);
        $info = $this->getStockInfoByNet($code, $date);
        if (empty($info)) {
            return;
        }
        $closePrice = $info['close_price'];
        // 同步持仓信息
        $holding->close_price = $closePrice;
        $holding->market_value = $closePrice * $holding->amount;
        $holding->sync_at = $date;
        $holding->save();
        // 同步股票明细
        $detail = new StockDetail();
        $detail->code = $holding->code;
        $detail->name = $holding->name;
        $detail->amount = $holding->amount;
        $detail->close_price = $holding->close_price;
        $detail->market_value = $holding->market_value;
        $detail->sync_at = $date;
        $detail->save();
    }

    /**
     * 同步股票资产
     * @param string $date
     */
    public function syncStockAsset(string $date)
    {
        $marketValueSum = StockDetail::whereRaw("DATE_FORMAT(sync_at, '%Y-%m-%d') = ?", [$date])->sum('market_value');
        // 当天没有市值，认为休市，同步资产为上一天
        if ($marketValueSum == 0) {
            $lastDate = date("Y-m-d", strtotime($date) - 86400);
            $lastAsset = $this->getAssetBySyncAt($lastDate);
            $marketValueSum = $lastAsset->market_value ?? 0;
        }
        // 新增资产
        $asset = new StockAsset();
        $asset->balance = ConfigService::getBalance();
        $asset->market_value = $marketValueSum;
        $asset->sync_at = $date;
        $asset->save();
        // 更新最后同步时间
        ConfigService::setLastSyncAt($date);
    }
}
