<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Transaction\Order;

class OrderExpiresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order_expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status on orders expired';

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
     * @return int
     */
    public function handle()
    {
        $hour = Carbon::now('-3')->hour . ':' . Carbon::now('-3')->minute;

        $orders = Order::select('orders.*')
            ->where('status', '=', 1)
            ->where('date', '<=', Carbon::now('-3'))
            ->where('hour', '<', $hour)
            ->get();

        foreach ($orders as $order) {
            $order->status = 3;
            $order->save();
        }
        
        return Command::SUCCESS;
    }
}
