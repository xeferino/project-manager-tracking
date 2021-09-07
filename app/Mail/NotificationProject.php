<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationProject extends Mailable /* implements ShouldQueue */
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    public $department;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $project, $department)
    {
        $this->user         = $user;
        $this->project      = $project;
        $this->department   = $department;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.projects.notify', ['user' => $this->user, 'project' => $this->project, 'project' => $this->project]);
    }
}
