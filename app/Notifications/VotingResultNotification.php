<?php

namespace App\Notifications;

use App\Models\VotingItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VotingResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected VotingItem $votingItem;
    protected array $results;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\VotingItem $votingItem
     * @param array $results
     */
    public function __construct(VotingItem $votingItem, array $results)
    {
        $this->votingItem = $votingItem;
        $this->results = $results;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Voting Results: ' . $this->votingItem->title)
            ->line('The voting session for the following item has now concluded:')
            ->line('**' . $this->votingItem->title . '**')
            ->line('Here are the final results:');

        if (empty($this->results)) {
            $mailMessage->line('No votes were cast.');
        } else {
            foreach ($this->results as $result) {
                $mailMessage->line('- ' . $result['option'] . ': ' . $result['votes'] . ' votes (' . $result['percentage'] . '%)');
            }
        }

        $mailMessage->action('View AGM Details', route('admin.agms.show', $this->votingItem->agm_id));
        $mailMessage->line('Thank you for your participation.');

        return $mailMessage;
    }
}
