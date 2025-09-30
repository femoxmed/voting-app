<?php

namespace App\Notifications;

use App\Models\Agm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class AgmReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The AGM instance.
     *
     * @var \App\Models\Agm
     */
    public $agm;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Agm $agm)
    {
        $this->agm = $agm;
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
        $token = Password::broker()->createToken($notifiable);
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]));

        return (new MailMessage)
                    ->subject('Reminder: AGM for ' . $this->agm->company->name . ' is starting soon')
                    ->line('This is a reminder that the Annual General Meeting for ' . $this->agm->company->name . ' is scheduled to start in approximately one hour.')
                    ->line('AGM Title: ' . $this->agm->title)
                    ->line('Start Time: ' . $this->agm->start_date->format('M d, Y H:i A T'))
                    ->line('To ensure you can log in, please use the button below to set or reset your password if needed.')
                    ->action('Set or Reset Password', $resetUrl)
                    ->line('Thank you for your participation.');
    }
}
