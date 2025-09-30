@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>AGMs Management</h5>
                    <a href="{{ route('admin.agms.create') }}" class="btn btn-primary">Create New AGM</a>
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
                                    <th>Company</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agms as $agm)
                                <tr>
                                    <td>{{ $agm->title }}</td>
                                    <td>{{ $agm->company->name }}</td>
                                    <td>{{ $agm->start_date ? $agm->start_date->format('M d, Y H:i') : 'Not set' }}</td>
                                    <td>
                                        <x-status-badge :status="$agm->status" />
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.agms.show', $agm) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('admin.agms.edit', $agm) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No AGMs found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $agms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
