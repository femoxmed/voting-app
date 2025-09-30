@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">AGM Details: {{ $agm->title }}</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $agm->description }}</p>
            <p><strong>Start Date:</strong> {{ $agm->start_date->format('M d, Y H:i A') }}</p>
            <p><strong>Status:</strong> <x-status-badge :status="$agm->status" /></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Voting Items</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="list-group">
                @forelse($agm->votingItems as $item)
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $item->title }}</h6>
                            <small class="text-muted">{{ str_replace('_', ' ', ucwords($item->type)) }}</small>
                        </div>
                        <div>
                            @if($shareholder->hasVotedOn($item))
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Voted</span>
                            @elseif($agm->status === 'active' && $item->status === 'active')
                                <a href="{{ route('shareholder.voting-items.show', $item) }}" class="btn btn-primary btn-sm">View & Vote</a>
                            @else
                                <x-status-badge :status="$item->status" />
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="list-group-item">
                        <p class="text-center text-muted mb-0">No voting items have been added to this AGM yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endpush
