<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorLoginCodeNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $code)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Codigo de verificacion - Que Economico')
            ->greeting('Hola '.$notifiable->name)
            ->line('Usa este codigo para completar tu inicio de sesion:')
            ->line($this->code)
            ->line('El codigo vence en 10 minutos.');
    }
}
