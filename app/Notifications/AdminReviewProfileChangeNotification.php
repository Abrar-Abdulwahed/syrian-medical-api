<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminReviewProfileChangeNotification extends Notification
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
        $acceptMSG = 'Congratulations! Your application to change profile data has been accepted, you can go to your profile by clicking below button.';
        $rejectedMSG = 'Unfortunately, Your application to change data in your profile has been rejected';
        $message = $this->accept ? $acceptMSG : $rejectedMSG;

        $mailMessage = (new MailMessage)
            ->subject('Updates On Your Application to Change Profile')
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
