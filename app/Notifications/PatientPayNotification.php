<?php

namespace App\Notifications;

use App\Models\Admin;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PatientPayNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Reservation $order)
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
        $message = $this->order->patient->fullName . ' paid to the service provider (' . $this->order->provider->fullName
            . ') for a service/product' .  ' in the amount of $' . $this->order->price;
        $route = $notifiable instanceof Admin ? 'admin.reservations.show' : 'provider.reservations.show';
        return (new MailMessage)
            ->subject('Patient Make Payment')
            ->line($message)
            ->action('View Order', route($route, $this->order->id))
            ->line('Thank you for using our application!');
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
