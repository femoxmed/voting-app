{{-- filepath: resources/views/admin/voting-items/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ $votingItem->title }} - Results</h5>
                    <div>
                        <a href="{{ route('admin.reports.agm', $votingItem->agm_id) }}" class="btn btn-info me-2">
                            View AGM Report
                        </a>
                        <a href="{{ route('admin.voting-items.edit', $votingItem) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('admin.agms.show', $votingItem->agm) }}" class="btn btn-secondary">Back to AGM</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Voting Item Details</h6>
                            <p><strong>AGM:</strong> {{ $votingItem->agm->title }}</p>
                            <p><strong>Company:</strong> {{ $votingItem->agm->company->name }}</p>
                            <p><strong>Description:</strong> {{ $votingItem->description }}</p>
                            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $votingItem->type)) }}</p>
                            <p><strong>Status:</strong>
                                @if ($votingItem->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif ($votingItem->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($votingItem->status === 'closed')
                                    <span class="badge bg-danger">Closed</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Voting Statistics</h6>
                            <p><strong>Total Votes Cast:</strong> {{ number_format($votingItem->total_votes) }}</p>
                            <p><strong>Number of Voters:</strong> {{ $votingItem->voter_count }}</p>
                            <p><strong>Participation Rate:</strong> {{ number_format($votingItem->participation_rate, 2) }}%</p>
                            <p><strong>Total Possible Votes:</strong> {{ number_format($votingItem->agm->company->shareholders()->sum('shares_owned')) }}</p>
                        </div>
                    </div>

                    <h6>Vote Breakdown</h6>
                    @if($votingItem->vote_breakdown)
                        <div class="row">
                            @foreach($votingItem->vote_breakdown as $option => $votes)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ ucfirst($option) }}</h5>
                                        <h3 class="text-primary">{{ number_format($votes) }}</h3>
                                        <p class="text-muted">votes</p>
                                        @if($votingItem->total_votes > 0)
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ ($votes / $votingItem->total_votes) * 100 }}%">
                                                    {{ number_format(($votes / $votingItem->total_votes) * 100, 1) }}%
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">No votes have been cast yet.</div>
                    @endif

                    <hr>

                    <h6>Individual Votes</h6>
                    @if($votingItem->votes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Shareholder</th>
                                        <th>Vote Option</th>
                                        <th>Votes Cast</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($votingItem->votes as $vote)
                                    <tr>
                                        <td>
                                            {{ $vote->shareholder->user->name }}
                                            <br><small class="text-muted">{{ $vote->shareholder->shareholder_id }}</small>
                                        </td>
                                        <td>{{ ucfirst($vote->vote_option) }}</td>
                                        <td>{{ number_format($vote->votes_cast) }}</td>
                                        <td>{{ $vote->voted_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No individual votes recorded yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
