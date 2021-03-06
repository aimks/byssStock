<?php
namespace App\Services;

use App\Models\StockAsset;
use App\Models\StockDetail;
use App\Models\StockHolding;
use App\Models\StockProfit;
use App\Models\StockRecord;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class StockService
{
    const API_TYPE_SINA = 'sina';
    const API_TYPE_NET = 'net';
    const API_TYPE_SOHO = 'soho';
    // 网易接口
    const API_URL_SINA = 'https://hq.sinajs.cn/list={prefixCode}';
    // 新浪接口
    const API_URL_NET = 'http://quotes.money.163.com/service/chddata.html?code={prefixCode}&start={start}&end={end}&fields=TCLOSE';
    const API_URL_SOHO = 'https://q.stock.sohu.com/hisHq?code={prefixCode}&start={start}&end={end}&stat=1&order=D&period=d&callback=historySearchHandler&rt=jsonp';

    /**
     * 获取股票信息
     * @param string $code
     * @param string $date
     * @param bool $isSync
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStockInfo(string $code, string $date, bool $isSync = false)
    {
        $info = $this->getStockInfoBySina($code);
        if (empty($info)) {
            return [];
        }
        $isToday = date('Y-m-d') === date("Y-m-d", strtotime($date));
        $closePrice = $info['close_price'];
        // 时间不是今天，拿历史数据的股价
        if (!$isToday) {
            $historyInfo = $this->getStockInfoByNet($code, $date);
            $closePrice = $historyInfo['close_price'] ?? 0;
        }
        if ($closePrice == 0) {
            return [
                'name' => $info['name'],
                'close_price' => 0,
                'amount' => 0,
            ];
        }
        // 同步操作并且是今天，拿今天的最新价，即收盘价
        if ($isSync && $isToday) {
            $closePrice = $info['now_price'];
        }
        // 算出最大持仓数量
        $amount =  floor(StockHolding::MAX_BUY_MONEY / ($closePrice * 100) + 1) * 100;
        return [
            'name' => $info['name'],
            'close_price' => (string)$closePrice,
            'amount' => $amount,
        ];
    }

    /**
     * 获取股票前缀
     * @param string $code
     * @param string $type
     * @return string
     */
    private function getPrefixCode(string $code, string $type = self::API_TYPE_SINA)
    {
        $isShCode = $code[0] === '6';
        switch ($type) {
            case self::API_TYPE_SINA:
                $prefix = $isShCode ? 'sh' : 'sz';
                break;
            case self::API_TYPE_NET:
                $prefix = $isShCode ? '0' : '1';
                break;
            case self::API_TYPE_SOHO:
                $prefix = 'cn_';
                break;
            default:
                $prefix = '';
                break;
        }
        return $prefix . $code;
    }

    /**
     * 新浪接口获取股票信息
     * @param string $code
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getStockInfoBySina(string $code)
    {
        $prefixCode = $this->getPrefixCode($code, self::API_TYPE_SINA);
        $url = Str::replaceFirst('{prefixCode}', $prefixCode, self::API_URL_SINA);
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
        $nowPrice = round($infoArr[2], 2);
        if ($closePrice == 0) {
            return [];
        }
        return [
            'name' => $infoArr[0],
            'close_price' => (string)$closePrice,
            'now_price' => $nowPrice,
        ];
    }

    /**
     * 网易接口获取股票信息
     * @param string $code
     * @param $date
     * @return array|float[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getStockInfoByNet(string $code, $date)
    {
        $prefixCode = $this->getPrefixCode($code, self::API_TYPE_NET);
        $date = date("Ymd", strtotime($date));
        $url = Str::replaceFirst('{prefixCode}', $prefixCode, self::API_URL_NET);
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

    /**
     * 搜狐股票接口获取股票信息
     * @param string $code
     * @param $date
     * @return array|float[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getStockInfoBySoho(string $code, $date)
    {
        $prefixCode = $this->getPrefixCode($code, self::API_TYPE_SOHO);
        $date = date("Ymd", strtotime($date));
        $url = Str::replaceFirst('{prefixCode}', $prefixCode, self::API_URL_SOHO);
        $url = Str::replaceFirst('{start}', $date, $url);
        $url = Str::replaceFirst('{end}', $date, $url);
        $client = new Client();
        $response = $client->get($url);
        $result = $response->getBody()->getContents();
        $result = iconv('GB2312', 'UTF-8', $result);
        preg_match('/\((.*?)\)/', $result, $matches);
        $info = $matches[1] ?? '{}';
        $stockInfo = json_decode($info, true);
        if (empty($stockInfo)) {
            return [];
        }
        $stock = $stockInfo[0];
        if (empty($stock['hq'][0])) {
            return [];
        }
        $closePrice = $stock['hq'][0][2];
        return [
            'close_price' => (float)$closePrice,
        ];
    }

    /**
     * 更新股票持仓和收益
     * @param StockRecord $record
     * @throws \Exception
     */
    public function updateStockHoldingsAndProfit(StockRecord $record)
    {
        switch ($record->type) {
            case StockRecord::TYPE_BUY:
                // 加入持仓表
                $holding = new StockHolding();
                $holding->code = $record->code;
                $holding->name = $record->name;
                $holding->amount = $record->amount;
                $holding->buy_price = $record->close_price;
                $holding->close_price = $record->close_price;
                $holding->market_value = $record->amount * $record->close_price;
                $holding->buy_at = $record->operate_at;
                $holding->save();
                // 更新资金余额
                ConfigService::setBalance(-$holding->market_value);
                break;
            case StockRecord::TYPE_SELL:
                $holding = $this->getHoldingByCode($record->code);
                // 加入收益表
                $profit = new StockProfit();
                $profit->code = $holding->code;
                $profit->name = $holding->name;
                $profit->amount = $holding->amount;
                $profit->buy_price = $holding->buy_price;
                $profit->sell_price = $record->close_price;
                $profit->profit = ($profit->sell_price - $profit->buy_price) * $profit->amount;
                $profit->buy_at = $holding->buy_at;
                $profit->sell_at = $record->operate_at;
                $profit->save();
                // 删除持仓表
                $holding->delete();
                // 更新资金余额
                ConfigService::setBalance($record->amount * $record->close_price);
                break;
            default:
                break;
        }
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
        $this->updateStockHoldingsAndProfit($record);
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
        return StockAsset::where('sync_at', $syncAt)->first();
    }

    /**
     * 获取所有资产
     * @return \Illuminate\Support\Collection
     */
    public function getAssets()
    {
        return StockAsset::orderBy('sync_at')->get();
    }
    /**
     * 同步股票明细
     * @param StockHolding $holding
     * @param string $date
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncStockDetail(StockHolding $holding, string $date)
    {
        $info = $this->getStockInfo($holding->code, $date, true);
        if (empty($info) || $info['amount'] == 0) {
            return;
        }
        $closePrice = (float)$info['close_price'];
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
        $balance = ConfigService::getBalance();
        $marketValueSum = StockDetail::where('sync_at', $date)->sum('market_value');
        // 当天没有市值，认为休市，同步资产为上一天
        if ($marketValueSum == 0) {
            $lastDate = date("Y-m-d", strtotime($date) - 86400);
            $lastAsset = $this->getAssetBySyncAt($lastDate);
            $balance = $lastAsset->balance ?? 0;
            $marketValueSum = $lastAsset->market_value ?? 0;
        }
        // 新增资产
        $asset = new StockAsset();
        $asset->balance = $balance;
        $asset->market_value = $marketValueSum;
        $asset->sync_at = $date;
        $asset->save();
        // 更新最后同步时间
        ConfigService::setLastSyncAt($date);
    }
    public function getAssetsChart()
    {
        $assets = $this->getAssets();
        $xData = [];
        $sData = [];
        foreach ($assets as $asset) {
            $xData[] = $asset->sync_at;
            $sData[] = $asset->balance + $asset->market_value;
        }
        return [
            'tooltip' => [
                'trigger' => 'axis',
            ],
            'dataZoom' => [
                [
                    'type' => 'inside',
                    'start' => 60,
                    'end' => 100,
                ],
                [
                    'type' => 'slider',
                    'start' => 60,
                    'end' => 100,
                ],
            ],
            'xAxis' => [
                'type' => 'category',
                'data' => $xData,
            ],
            'yAxis' => [
                'type' => 'value',
                'min' =>  500000,
                'axisLabel' => [
                    'inside' => true,
                ],
            ],
            'series' => [
                [
                    'data' => $sData,
                    'type' => 'line',
                    'smooth' => true,
                    'name' => '资产',
                    'areaStyle' => [],
                ],
            ],
        ];
    }

    /**
     * 根据搜索获取记录
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getRecordsBySearch(int $page, int $pageSize)
    {
        $query = StockRecord::query();
        $total = $query->count();
        $query->orderByDesc('id');
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return [$total, $list];
    }

    /**
     * 根据搜索获取收益
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProfitsBySearch(int $page, int $pageSize)
    {
        $query = StockProfit::query();
        $total = $query->count();
        $query->orderByDesc('profit');
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return [$total, $list];
    }
}
