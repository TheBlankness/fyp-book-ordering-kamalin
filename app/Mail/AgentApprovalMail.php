<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $status;

    public function __construct($agent, $status)
    {
        $this->agent = $agent;
        $this->status = $status;
    }

    public function build()
    {
        $subject = $this->status === 'Approved' ? 'Your Registration is Approved' : 'Your Registration is Rejected';

        return $this->subject($subject)
                    ->view('emails.agent_status');
    }
}
