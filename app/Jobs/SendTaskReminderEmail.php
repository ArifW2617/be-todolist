<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendTaskReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kirim email ke user
        Mail::send('emails.reminder', ['task' => $this->task], function ($message) {
            $message->to($this->user->email);
            $message->subject('Task Reminder');
        });
    }
}
