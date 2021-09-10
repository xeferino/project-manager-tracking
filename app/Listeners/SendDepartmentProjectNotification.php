<?php

namespace App\Listeners;

use App\Models\User;
use App\Mail\NotificationProject;
use App\Jobs\SendMailNotifyProject;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ProjectDepartmentProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDepartmentProjectNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectDepartmentProcessed  $event
     * @return void
     */
    public function handle(ProjectDepartmentProcessed $event)
    {
        foreach ($event->department->users as $user) {
            Mail::to($user->email)->send(new NotificationProject(User::find($user->id), $event->project, $event->department));
        }
    }
}
