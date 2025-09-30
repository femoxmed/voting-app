@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Shareholders Management</h5>
                    <a href="{{ route('admin.shareholders.create') }}" class="btn btn-primary">Add New Shareholder</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Shares</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shareholders as $shareholder)
                                <tr>
                                    <td>{{ $shareholder->user->name }}</td>
                                    <td>{{ $shareholder->user->email }}</td>
                                    <td>{{ $shareholder->company->name }}</td>
                                    <td>{{ number_format($shareholder->shares_owned) }}</td>
                                    <td>{{ $shareholder->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.shareholders.show', $shareholder) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('admin.shareholders.edit', $shareholder) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No shareholders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $shareholders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
