<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Mail\NotificationProject;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendMailNotifyProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;
    public $department;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($project, $department)
    {
        $this->project      = $project;
        $this->department   = $department;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->department->users as $user) {
            Mail::to($user->email)->send(new NotificationProject(User::find($user->id), $this->project, $this->department));
        }
    }
}
