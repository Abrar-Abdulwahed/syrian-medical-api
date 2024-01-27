<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProviderReviewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $accept, public Reservation $reservation)
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
        $acceptMSG = 'Congratulations! Your reservation has been accepted by the service provider, know more by clicking below button.';
        $rejectedMSG = 'Unfortunately, Your reservation has been refused for some reason, click button to review your order';
        $message = $this->accept ? $acceptMSG : $rejectedMSG;

        $mailMessage = (new MailMessage)
            ->subject('Updates On Your Reservation')
            ->line($message);


        $mailMessage->action('Your Order', route('patient.reservations.show', $this->reservation->id));

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
