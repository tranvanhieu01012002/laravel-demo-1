<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RemoveKeyRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $prefixKey = "presence-room";
        if (Redis::del(Redis::keys($prefixKey.'*'))) {
            $this->info('Success!');
        } else {
            $this->error("can not delete");
        }
    }
}
