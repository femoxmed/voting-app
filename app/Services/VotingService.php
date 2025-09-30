<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vote;
use App\Models\VotingItem;
use App\Notifications\VoteCastNotification;
use Illuminate\Support\Facades\DB;

class VotingService
{
    public function castVote(User $user, VotingItem $votingItem, string $voteOption): Vote
    {
        $shareholder = $user->shareholders()
            ->where('company_id', $votingItem->agm->company_id)
            ->first();

        if (!$shareholder) {
            throw new \Exception('You are not authorized to vote on this item.');
        }

        if (!$votingItem->agm->isActive()) {
            throw new \Exception('This AGM is not currently active.');
        }

        if (!$votingItem->isActive()) {
            throw new \Exception('This voting item is not currently active.');
        }
        if ($shareholder->hasVotedOn($votingItem)) {
            throw new \Exception('You have already voted on this item.');
        }

        return DB::transaction(function () use ($shareholder, $votingItem, $voteOption) {
            $vote = Vote::create([
                'voting_item_id' => $votingItem->id,
                'shareholder_id' => $shareholder->id,
                'vote_option' => $voteOption,
                'votes_cast' => $shareholder->shares_owned, // Number of votes = shares owned
                'voted_at' => now(),
            ]);

            // Send notification about vote cast
             $shareholder->user->notify(new VoteCastNotification($vote));

            return $vote;
        });
    }

    public function getVotingResults(VotingItem $votingItem): array
    {
        return [
            'total_votes' => $votingItem->total_votes,
            'breakdown' => $votingItem->vote_breakdown,
            'total_shareholders' => $votingItem->agm->company->shareholders()->count(),
            'voted_shareholders' => $votingItem->votes()->distinct('shareholder_id')->count(),
        ];
    }
}
