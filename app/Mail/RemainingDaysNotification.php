<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemainingDaysNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task; // Tạo biến public để truyền dữ liệu vào view

    /**
     * Create a new message instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Thông báo về ngày còn lại')
                    ->view('emails.remaining_days_notification');
    }
}
