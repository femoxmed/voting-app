<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agm;
use App\Models\Company;
use App\Models\Shareholder;
use App\Models\Vote;
use App\Models\VotingItem;

class DashboardController extends Controller
{

    public function index()
    {

        $user = auth()->user();

        // If the user is a shareholder, show the shareholder dashboard.
        if ($user->role === 'shareholder') {
            $shareholder = $user->shareholders()->with('company')->first();

            $recentAgms = Agm::where('company_id', $shareholder->company_id)
                ->whereIn('status', ['upcoming', 'active', 'completed'])
                ->latest('start_date')
                ->get();
            return view('dashboard-shareholder', compact('recentAgms'));

        } else {
        // If the user is an admin, show the admin dashboard.
        $stats = [
            'total_companies' => Company::count(),
            'active_agms' => Agm::where('status', 'active')->count(),
            'total_shareholders' => Shareholder::count(),
            'total_votes_cast' => Vote::sum('votes_cast'),
            'active_voting_items' => VotingItem::whereHas('agm', function($query) {
                $query->where('status', 'active');
            })->where('status', 'active')->count(),
            'shareholders_voted_today' => Vote::whereDate('voted_at', today())
                ->distinct('shareholder_id')->count(),
        ];

        $recentAgms = Agm::with('company')
            ->latest()
            ->take(5)
            ->get();

        $recentVotes = Vote::with(['shareholder.user', 'votingItem.agm'])
            ->latest()
            ->take(10)
            ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentAgms', 'recentVotes'));
    }
}
