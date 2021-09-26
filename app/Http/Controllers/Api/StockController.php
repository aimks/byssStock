<?php

namespace App\Http\Controllers\Api;

use App\Models\StockRecord;
use App\Services\ConfigService;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    /**
     * @var StockService
     */
    protected $stockService;

    public function __construct()
    {
        $this->stockService = new StockService();
    }
    /**
     * 操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function operate(Request $request)
    {
        $code = $request->input('code', '');
        $name = $request->input('name', '');
        $type = $request->input('type', '');
        $closePrice = $request->input('close_price', '');
        $amount = $request->input('amount', 100);
        $operateAt = $request->input('operate_at', '');
        $holding = $this->stockService->getHoldingByCode($code);
        if ($type == StockRecord::TYPE_BUY) {
            $balance = ConfigService::getBalance();
            if ($balance < $amount * $closePrice) {
                return $this->error('资金余额不足，不能买入！');
            }
            if ($holding) {
                return $this->error('该股票持仓已经存在，不能买入！');
            }
        }
        if ($type == StockRecord::TYPE_SELL) {
            if (!$holding) {
                return $this->error('该股票持仓不存在，不能卖出！');
            }
            // 卖出时为持仓数量
            $amount = $holding->amount;
        }
        $this->stockService->addRecord($code, $name, $type, $closePrice, $amount, $operateAt);
        return $this->ok([], '操作成功！');
    }

    /**
     * 查询股票信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockInfo(Request $request)
    {
        $code = $request->input('code', '');
        if (!$code) {
            return $this->error('没有股票代码！');
        }
        $stockInfo = $this->stockService->getStockInfo($code);
        if (!$stockInfo) {
            return $this->error('没有找到股票信息！');
        }
        return $this->ok([
            'item' => $stockInfo,
        ]);
    }

    /**
     * 获取所有持仓
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHoldings()
    {
        $holdings = $this->stockService->getHoldings();
        return $this->ok([
            'list' => $holdings,
        ]);
    }

    /**
     * 获取资产图表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetsChart()
    {
        $chart = $this->stockService->getAssetsChart();
        return $this->ok([
            'item' => $chart,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
