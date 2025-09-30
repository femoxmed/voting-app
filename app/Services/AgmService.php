<?php

namespace App\Services;

use App\Events\AgmClosed;
use App\Models\Agm;
use App\Notifications\AgmCreatedNotification;
use App\Notifications\AgmLoginCredentialsNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\AgmActivatedNotification;
use Illuminate\Support\Facades\Notification;

class AgmService
{
    public function createAgm(array $data): Agm
    {
        return Agm::create($data);
    }

    public function activateAgm(Agm $agm): void
    {
        $agm->update(['status' => 'active']);

        // Send login credentials to shareholders
        $this->sendLoginCredentials($agm);

        // Notify about AGM activation
        $this->notifyAgmActivation($agm);
    }

    private function sendLoginCredentials(Agm $agm): void
    {
        $shareholders = $agm->company->shareholders()->with('user')->get();

        foreach ($shareholders as $shareholder) {
            $shareholder->user->notify(new AgmLoginCredentialsNotification($agm, $shareholder));
        }
    }

    private function notifyAgmActivation(Agm $agm): void
    {
        $shareholders = $agm->company->shareholders()->with('user')->get();

        foreach ($shareholders as $shareholder) {
            $shareholder->user->notify(new AgmActivatedNotification($agm));
        }
    }

    public function closeAgm(Agm $agm): void
    {
        try {
            DB::transaction(function () use ($agm) {
                // Close all open voting items associated with this AGM
                $agm->votingItems()
                    ->where('status', '!=', 'closed')
                    ->update(['status' => 'closed', 'completed_at' => now()]);

                // Mark the AGM itself as completed and set the end date
                $agm->update([
                    'status' => 'completed',
                    'end_date' => now(),
                ]);
            });

            // Dispatch a single event to handle all notifications
            AgmClosed::dispatch($agm);
        } catch (\Exception $e) {
            Log::error('Failed to close AGM.', [
                'agm_id' => $agm->id,
                'error' => $e->getMessage(),
            ]);
            // Re-throw the exception to be handled by the controller or global exception handler
            throw $e;
        }
    }
}
