@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Recent & Upcoming AGMs</h5>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($recentAgms->isEmpty())
                <p class="text-center text-muted">There are no recent or upcoming AGMs for your company at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>AGM Title</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAgms as $agm)
                                <tr>
                                    <td>{{ $agm->title }}</td>
                                    <td>{{ $agm->start_date->format('M d, Y H:i A') }}</td>
                                    <td>
                                        <x-status-badge :status="$agm->status" />
                                    </td>
                                    <td>
                                        <a href="{{ route('shareholder.agms.show', $agm) }}" class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
