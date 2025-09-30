<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAgmCreatedNotificationJob;
use App\Models\Agm;
use App\Models\Company;
use App\Events\AgmClosed;
use App\Http\Controllers\Admin\VotingItemController;
use App\Services\AgmService;
use Illuminate\Http\Request;

class AgmController extends Controller
{
    public function __construct(
        private AgmService $agmService
    ) {}

    public function index()
    {
        $agms = Agm::with('company')->latest()->paginate(15);
        return view('admin.agms.index', compact('agms'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('admin.agms.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now',
        ]);

        $agm = $this->agmService->createAgm($validated);

        // Dispatch a job to send notifications after the response is sent
        SendAgmCreatedNotificationJob::dispatch($agm);

        return redirect()->route('admin.agms.show', $agm)
            ->with('success', 'AGM created successfully.');
    }

    public function show(Agm $agm)
    {
        $agm->load(['company', 'votingItems.votes']);
        return view('admin.agms.show', compact('agm'));
    }

    public function edit(Agm $agm)
    {
        $companies = Company::all();
        return view('admin.agms.edit', compact('agm', 'companies'));
    }

    public function update(Request $request, Agm $agm)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'status' => 'required|in:draft,active,completed',
        ]);

        $agm->update($validated);

        return redirect()->route('admin.agms.show', $agm)
            ->with('success', 'AGM updated successfully.');
    }

    public function destroy(Agm $agm)
    {
        $agm->delete();
        return redirect()->route('admin.agms.index')
            ->with('success', 'AGM deleted successfully.');
    }

    /**
     * Close an AGM and all of its open voting items.
     *
     * @param \App\Models\Agm $agm
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(Agm $agm, VotingItemController $votingItemController)
    {
        $this->agmService->closeAgm($agm);

        return redirect()->route('admin.agms.show', $agm)->with('success', 'AGM has been closed. Result notifications are being sent to shareholders.');
    }

}
