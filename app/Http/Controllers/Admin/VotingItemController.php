<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\VotingItemClosed;
use App\Models\Agm;


class VotingItemController extends Controller
{
    public function create(Agm $agm)
    {
        return view('admin.voting-items.create', compact('agm'));
    }

    public function store(Request $request, Agm $agm)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:yes_no,multiple_choice',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'string|max:255',
        ]);

        $validated['agm_id'] = $agm->id;

        if ($validated['type'] === 'yes_no') {
            $validated['options'] = ['Yes', 'No'];
        }

        VotingItem::create($validated);

        return redirect()->route('admin.agms.show', $agm)
            ->with('success', 'Voting item created successfully.');
    }

    public function show(VotingItem $votingItem)
    {
        $votingItem->load(['agm.company', 'votes.shareholder.user']);
        return view('admin.voting-items.show', compact('votingItem'));
    }

    public function edit(VotingItem $votingItem)
    {
        return view('admin.voting-items.edit', compact('votingItem'));
    }

    public function update(Request $request, VotingItem $votingItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,active,closed',
        ]);
        error_log($validated['title']);
        $votingItem->update($validated);
        //dd($request->all());
        return redirect()->route('admin.voting-items.show', $votingItem)
            ->with('success', 'Voting item updated successfully.');
    }

    public function close(VotingItem $votingItem)
    {
        try {
            // 1. Update the voting item status to 'closed' and set the completion time.
            $votingItem->update([
                'status' => 'closed',
                'completed_at' => now(),
            ]);

            // 2. Dispatch an event to handle notifications
            VotingItemClosed::dispatch($votingItem);

            return redirect()->back()->with('success', 'Voting has been closed and result notifications are being sent.');
        } catch (\Exception $e) {
            Log::error('Failed to close voting item.', [
                'voting_item_id' => $votingItem->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An unexpected error occurred while closing the voting item. Please check the logs for more details.');
        }
    }
}
