<?php
namespace App\Services;

use App\Models\StockHolding;
use App\Models\StockRecord;
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
     * 获取股票信息
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
                ConfigService::updateBalance(-$amount * $closePrice);
                break;
            case StockRecord::TYPE_SELL:
                // 删除持仓表
                $holding = $this->getHoldingByCode($code);
                $holding->delete();
                // 更新资金余额
                ConfigService::updateBalance($amount * $closePrice);
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
}
