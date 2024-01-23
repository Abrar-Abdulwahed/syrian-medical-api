<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $make, public Reservation $reservation)
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
        $makeMSG = $this->reservation->patient->fullName . ' has reserved your product/service';
        $canceledMSG = 'Unfortunately, ' . $this->reservation->patient->fullName . ' has canceled the reservation.';
        $message = $this->make ? $makeMSG : $canceledMSG;
        $subject = $this->make ? 'New Reservation' : 'Canceled Reservation';

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->line($message)
            ->line('click button below to show details');

        //TODO: write the reservation route for the provider
        // if ($this->make) {
        //     $mailMessage->action('Your Profile', route('show.profile'));
        // }

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
