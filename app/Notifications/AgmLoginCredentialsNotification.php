<?php

namespace App\Notifications;

use App\Models\Agm;
use App\Models\Shareholder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgmLoginCredentialsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Agm $agm,
        private Shareholder $shareholder
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('AGM Login Credentials - ' . $this->agm->title)
            ->greeting('Dear ' . $notifiable->name)
            ->line('The AGM "' . $this->agm->title . '" is now active and ready for voting.')
            ->line('Your login credentials:')
            ->line('Email: ' . $notifiable->email)
            ->line('Shareholder ID: ' . $this->shareholder->shareholder_id)
            ->line('Voting Power: ' . number_format($this->shareholder->shares_owned) . ' votes per item')
            ->action('Login to Vote', route('voting.index'))
            ->line('Please keep your credentials secure and participate in the voting process.');
    }
}