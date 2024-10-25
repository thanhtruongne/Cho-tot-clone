<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRemainingDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-remaining-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = \App\Models\Task::all();
    
        foreach ($tasks as $task) {
            // Giảm số ngày còn lại
            if ($task->remaining_days > 0) {
                $task->remaining_days -= 1;
                $task->save();
            }
        }
    
        $this->info('Remaining days updated!');
    }
}
