@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>AGM Report: {{ $agm->title }}</h5>
                    <div>
                        <a href="{{ route('admin.reports.export', $agm) }}" class="btn btn-success">Export Report</a>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>AGM Information</h6>
                            <p><strong>Company:</strong> {{ $agm->company->name }}</p>
                            <p><strong>Date:</strong> {{ $agm->start_date->format('M d, Y') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($agm->status) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistics</h6>
                            <p><strong>Total Shareholders:</strong> {{ $report['total_shareholders'] }}</p>
                            <p><strong>Total Shares:</strong> {{ number_format($report['total_shares']) }}</p>
                            <p><strong>Overall Participation:</strong> {{ number_format($report['overall_participation'], 2) }}%</p>
                        </div>
                    </div>

                    <h6>Voting Results</h6>
                    @foreach($report['voting_results'] as $result)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6>{{ $result['item']->title }}</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $result['item']->description }}</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Total Votes:</strong> {{ number_format($result['total_votes']) }}</p>
                                        <p><strong>Participation Rate:</strong> {{ number_format($result['participation_rate'], 2) }}%</p>
                                        <p><strong>Voter Count:</strong> {{ $result['voter_count'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                    <h6>Vote Breakdown:</h6>
                                        @php
                                            $breakdown = (array) ($result['breakdown'] ?? []);
                                        @endphp
                                        @if(count($breakdown))
                                            @foreach($breakdown as $option => $count)
                                                <p>{{ ucfirst($option) }}: {{ number_format($count) }} votes</p>
                                            @endforeach
                                        @else
                                            <p class="text-muted">No votes recorded.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
