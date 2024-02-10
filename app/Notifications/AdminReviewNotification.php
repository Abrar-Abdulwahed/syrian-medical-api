<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminReviewNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $accept)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $acceptMSG = 'Congratulations! Your application has been accepted, you can update your profile by clicking below button.';
        $rejectedMSG = 'Unfortunately, Your application has been rejected';
        $message = $this->accept ? $acceptMSG : $rejectedMSG;

        $mailMessage = (new MailMessage)
            ->subject('Update On Your Application')
            ->line($message);

        if ($this->accept) {
            $mailMessage->action('Your Profile', route('show.profile'));
        }

        $mailMessage->line('Thank you for using our application!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
