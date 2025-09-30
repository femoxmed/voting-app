<?php

namespace App\Notifications;

use App\Models\Agm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgmCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Agm $agm
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New AGM Scheduled: ' . $this->agm->title)
            ->greeting('Dear ' . $notifiable->name)
            ->line('A new Annual General Meeting has been scheduled for ' . $this->agm->company->name)
            ->line('AGM Title: ' . $this->agm->title)
            ->line('Date: ' . $this->agm->start_date->format('F j, Y \a\t g:i A'))
            ->line('You will be able to participate in voting once the AGM becomes active.')
            ->action('View AGM Details', url('/voting'))
            ->line('Thank you for your participation!');
    }

    public function toArray($notifiable): array
    {
        return [
            'agm_id' => $this->agm->id,
            'title' => $this->agm->title,
            'company' => $this->agm->company->name,
            'start_date' => $this->agm->start_date,
        ];
    }
}