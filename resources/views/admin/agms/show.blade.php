@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">AGM Details: {{ $agm->title }}</h5>
            <a href="{{ route('admin.agms.index') }}" class="btn btn-secondary">Back to AGMs</a>
        </div>
        <div class="card-body">
            <p><strong>Company:</strong> {{ $agm->company->name }}</p>
            <p><strong>Description:</strong> {{ $agm->description }}</p>
            <p><strong>Start Date:</strong> {{ $agm->start_date->format('M d, Y H:i A') }}</p>
            <p><strong>Status:</strong> <x-status-badge :status="$agm->status" /></p>
            @if($agm->status !== 'completed')
                <form method="POST" action="{{ route('admin.agms.close', $agm) }}" onsubmit="return confirm('Are you sure you want to close this entire AGM? This will close all open voting items and send result notifications.');">
                    @csrf
                    <button type="submit" class="btn btn-danger">Close Entire AGM</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Voting Items</h5>
            @if($agm->status !== 'completed')
                <a href="{{ route('admin.agms.voting-items.create', $agm) }}" class="btn btn-primary">Add Voting Item</a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agm->votingItems as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ str_replace('_', ' ', ucwords($item->type)) }}</td>
                            <td>
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td>
                                <a href="{{ route('admin.voting-items.show', $item) }}" class="btn btn-sm btn-info">View Results</a>
                                @if($item->status !== 'closed')
                                    <a href="{{ route('admin.voting-items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form method="POST" action="{{ route('admin.voting-items.close', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to close this voting item?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Close Voting</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Closed on {{ $item->completed_at ? $item->completed_at->format('M d, Y'): '' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No voting items have been added to this AGM yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
