<?php

namespace App\Console\Commands;

use App\Mail\RemainingDaysNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProductRentUpdateDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-rent-update-days';

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
        $tasks = \App\Models\ProductRentHouse::all();

        foreach ($tasks as $task) {
            if ($task->remaining_days > 0 && $task->approved == 1) {
                $task->remaining_days -= 1;
                $task->save();

                if ($task->remaining_days == 2) {
                    $user = \App\Models\User::find($task->user_id);

                    if ($user) {
                        Mail::to($user->email)->send(new RemainingDaysNotification($task));
                        $this->info('Email đã được gửi cho: ' . $user->email);
                    } else {
                        $this->error('Không tìm thấy người dùng với ID: ' . $task->user_id);
                    }
                }
            }
        }

        $this->info('Cập nhật số ngày còn lại đã hoàn tất!');
    }
}
