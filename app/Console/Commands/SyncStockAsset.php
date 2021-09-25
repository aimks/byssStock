<?php

namespace App\Console\Commands;

use App\Services\StockService;
use Illuminate\Console\Command;

class SyncStockAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stock-asset {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步股票资产';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stockService = new StockService();
        $date = $this->argument('date') ?? date('Y-m-d');
        $asset = $stockService->getAssetBySyncAt($date);
        if ($asset) {
            $this->info('当前日期已经同步');
            return;
        }
        $holdings = $stockService->getHoldings();
        $this->info('股票持仓正在同步');
        \DB::beginTransaction();
        try {
            foreach ($holdings as $holding) {
                $stockService->syncStockDetail($holding, $date);
                $this->info('股票同步完成：' . $holding->name);
            }
            $stockService->syncStockAsset($date);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::info('股票同步失败：' . $e->getMessage());
        }
        $this->info('股票持仓同步完成');
    }
}
