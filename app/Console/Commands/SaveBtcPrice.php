<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Indodax;
use Storage;
use Log;

class SaveBtcPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:saveBtcPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get last price of BTC and save it into price history';

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
        $lastPrice = Indodax::lastBtcIdrPrice()->ticker ?? null;
        if($lastPrice != null) {
            $lastPrice = [
                (int) $lastPrice->server_time *1000,
                (int) $lastPrice->last,
            ];
            Storage::append('public/btc_idr_history.json', ','.json_encode($lastPrice,  JSON_NUMERIC_CHECK));        
        }
    }
}
