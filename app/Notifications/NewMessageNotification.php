<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $fromUser;
    public $conversationId;

    public function __construct($fromUser, $conversationId)
    {
        $this->fromUser = $fromUser;
        $this->conversationId = $conversationId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📩 New Message in Book Ordering System')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have received a new message from ' . $this->fromUser->name . '.')
            ->action('View Message', url('/chat/' . $this->conversationId))
            ->line('Please log in to reply to the message.');
    }
}
