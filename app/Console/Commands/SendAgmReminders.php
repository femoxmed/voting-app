<?php

namespace App\Console\Commands;

use App\Models\Agm;
use App\Notifications\AgmReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendAgmReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agm:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to shareholders for AGMs starting in one hour.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        error_log('Sending AGM reminders...');

        $this->info('Checking for AGMs starting soon...');

        // Find AGMs with a status of 'upcoming' or 'active' that are starting between 59 and 60 minutes from now.

       $start = now('Africa/Lagos')->addMinutes(55)->startOfMinute();
       $end   = now('Africa/Lagos')->addMinutes(65)->endOfMinute();

        $agms = Agm::whereIn('status', ['draft', 'active'])
            ->where('reminder_sent', false)
            ->whereBetween('start_date', [$start, $end])
            ->with('company.shareholders.user')
            ->get();

        if ($agms->isEmpty()) {
            error_log('empty');
            $this->info('No AGMs starting in the next hour.');
            return Command::SUCCESS;
        }

        foreach ($agms as $agm) {
            $this->info("Found AGM: {$agm->title}. Notifying shareholders of {$agm->company->name}.");

            // We need to notify the User, not the Shareholder model directly.
            $users = $agm->company->shareholders->map->user->filter();

            if ($users->isNotEmpty()) {
                try {
                    Notification::send($users, new AgmReminderNotification($agm));
                    $this->info("Sent reminders to {$users->count()} shareholders for AGM: {$agm->title}.");
                    $agm->update(['reminder_sent' => true]);
                } catch (\Exception $e) {
                    $this->error("Failed to send reminders for AGM: {$agm->title}.");
                    Log::error('AGM Reminder Sending Failed', [
                        'agm_id' => $agm->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info('Finished sending AGM reminders.');
        return Command::SUCCESS;
    }
}
