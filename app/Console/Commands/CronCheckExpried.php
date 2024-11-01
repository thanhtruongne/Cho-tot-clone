<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronCheckExpried extends Command
{
    protected $signature = 'check:expried';

    protected $description = 'Chạy cron check expried tin đăng 2 tiếng 1 lần (0 */2 * * *)';

    protected $expression ='0 */2 * * * ';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $model = 
    }
}
