<?php

namespace App\Listeners;

use App\Events\ProjectDepartmentProcessed;
use App\Jobs\SendMailNotifyProject;
use App\Mail\NotificationProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
        SendMailNotifyProject::dispatch();

       /* Mail::to('josegregoriolozadae@gmail.com')->send(new NotificationProject()); */

        /* $send = Mail::send('emails.projects.notify', ['event' => $event], function ($message) use ($event) {
            $message->to('josegregoriolozadae@gmail.com')->subject("Notificacion de proyecto nuevo al departamemto");
        }); */
    }
}
