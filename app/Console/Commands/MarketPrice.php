<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\PriceUpdated;
use Log;

class MarketPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:getPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get last price of BTC';

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
        event(new PriceUpdated(date('Y-m-d H:i:s')));
    }
}
