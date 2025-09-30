<?php

namespace App\Http\Controllers;

use App\Models\Agm;
use App\Models\VotingItem;
use App\Services\VotingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    public function __construct(
        private VotingService $votingService
    ) {}

    /**
     * Display the specified AGM for a shareholder.
     *
     * @param \App\Models\Agm $agm
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showAgm(Agm $agm)
    {
        $shareholder = auth()->user()->shareholders()->where('company_id', $agm->company_id)->first();

        if (!$shareholder) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this AGM.');
        }

        $agm->load(['votingItems']);

        return view('voting.show-agm', compact('agm', 'shareholder'));
    }

    /**
     * Display the specified voting item for a shareholder to vote on.
     *
     * @param \App\Models\VotingItem $votingItem
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVotingItem(VotingItem $votingItem)
    {
        $shareholder = auth()->user()->shareholders()->where('company_id', $votingItem->agm->company_id)->first();

        if (!$shareholder) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this voting item.');
        }

        return view('voting.show-voting-item', compact('votingItem', 'shareholder'));
    }

    public function vote(Request $request, VotingItem $votingItem)
    {
        $validated = $request->validate([
            'vote_option' => 'required',
        ]);

        try {
            $this->votingService->castVote(
                auth()->user(),
                $votingItem,
                $validated['vote_option']
            );

            return back()->with('success', 'Vote cast successfully!');
        } catch (\Exception $e) {
         error_log($e);
            // Log the detailed error for the developer
            Log::error('Failed to cast vote.', [
                'user_id' => auth()->id(),
                'voting_item_id' => $votingItem->id,
                'error' => $e->getMessage(),
            ]);
            error_log($e);


            // Return a generic, user-friendly error message
            return back()->with('error', 'An unexpected error occurred while casting your vote. Please try again.');
        }
    }
}
