@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $stats['total_companies'] }}</h3>
                                    <p class="text-muted">Companies</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $stats['active_agms'] }}</h3>
                                    <p class="text-muted">Active AGMs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-info">{{ $stats['total_shareholders'] }}</h3>
                                    <p class="text-muted">Shareholders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">{{ number_format($stats['total_votes_cast']) }}</h3>
                                    <p class="text-muted">Total Votes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent AGMs -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent AGMs</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAgms as $agm)
                                        <tr>
                                            <td>{{ $agm->title }}</td>
                                            <td>{{ $agm->company->name }}</td>
                                            <td>
                                                <x-status-badge :status="$agm->status" />
                                            </td>

                                            <td>{{ $agm->start_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.agms.show', $agm) }}" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
