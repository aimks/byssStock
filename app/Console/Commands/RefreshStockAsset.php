<?php

namespace App\Console\Commands;

use App\Models\StockRecord;
use App\Services\StockService;
use Illuminate\Console\Command;

class RefreshStockAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =
        'refresh:stock-asset {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新股票资产，谨慎使用，无法复原';

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
        return;
        $stockService = new StockService();
        $date = $this->argument('date') ?? date('Y-m-d');
        $syncDate = '2021-01-04';
        while ($syncDate <= $date) {
            $records = StockRecord::where('operate_at', $syncDate)->orderBy('id')->get();
            foreach ($records as $record) {
                $stockService->updateStockHoldingsAndProfit($record);
            }
            $this->call('sync:stock-asset', ['date' => $syncDate]);
            $syncDate = date('Y-m-d', strtotime($syncDate) + 86400);
        }
    }
}
