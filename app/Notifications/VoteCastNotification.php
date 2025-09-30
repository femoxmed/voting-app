<?php

namespace App\Notifications;

use App\Models\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoteCastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Vote $vote
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Vote Cast Confirmation')
            ->greeting('Dear ' . $notifiable->name)
            ->line('Your vote has been successfully cast for: ' . $this->vote->votingItem->title)
            ->line('Vote: ' . $this->vote->vote_option)
            ->line('Votes Cast: ' . number_format($this->vote->votes_cast))
            ->line('AGM: ' . $this->vote->votingItem->agm->title)
            ->line('Thank you for your participation!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Vote cast for: ' . $this->vote->votingItem->title,
            'vote_option' => $this->vote->vote_option,
            'votes_cast' => $this->vote->votes_cast,
        ];
    }
}