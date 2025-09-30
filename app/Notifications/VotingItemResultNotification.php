<?php

namespace App\Notifications;

use App\Models\VotingItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VotingItemResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public VotingItem $votingItem
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Voting Results: ' . $this->votingItem->title)
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('The voting has concluded for: ' . $this->votingItem->title)
            ->line('Total votes cast: ' . number_format($this->votingItem->total_votes));

        foreach ($this->votingItem->vote_breakdown as $option => $votes) {
            $percentage = $this->votingItem->total_votes > 0
                ? round(($votes / $this->votingItem->total_votes) * 100, 1)
                : 0;
            $message->line(ucfirst($option) . ': ' . number_format($votes) . ' votes (' . $percentage . '%)');
        }

        return $message->line('Thank you for your participation!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'voting_item_id' => $this->votingItem->id,
            'title' => 'Voting Concluded: ' . $this->votingItem->title,
            'message' => 'The voting session for "' . $this->votingItem->title . '" has concluded. Check the results.',
            'link' => route('shareholder.voting-items.show', $this->votingItem),
        ];
    }
}
