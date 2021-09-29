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
        $operateAt = date("Y-m-d", strtotime($operateAt));
        $lastDate = date("Y-m-d", strtotime($operateAt) - 86400);
        $lastAsset = $this->stockService->getAssetBySyncAt($lastDate);
        $asset = $this->stockService->getAssetBySyncAt($operateAt);
        if ($asset) {
            return $this->error('当前日期资产已经同步，不能操作！');
        }
        if (!$lastAsset) {
            return $this->error('当前日期上一天资产未同步，不能操作！');
        }
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
            if (is_null($holding->buy_price)) {
                return $this->error('股票买入价未同步，不能卖出！');
            }
            if ($holding->buy_at >= $operateAt) {
                return $this->error('股票卖出时间小于买入时间，不能卖出！');
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
        $operateAt = $request->input('operate_at', '');
        if (!$code) {
            return $this->error('没有股票代码！');
        }
        $stockInfo = $this->stockService->getStockInfo($code, $operateAt);
        if (!$stockInfo) {
            return $this->error('没有找到股票信息！');
        }
        if ($stockInfo['amount'] == 0) {
            return $this->error('当前日期不开市，请更换日期后操作！');
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
     * 获取所有记录
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecords(Request $request)
    {
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 10);
        list($total, $list) = $this->stockService->getRecordsBySearch($page, $pageSize);
        return $this->ok([
            'total' => $total,
            'list' => $list,
        ]);
    }

    /**
     * 获取所有收益
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfits(Request $request)
    {
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 10);
        list($total, $list) = $this->stockService->getProfitsBySearch($page, $pageSize);
        return $this->ok([
            'total' => $total,
            'list' => $list,
        ]);
    }
}
