@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Shareholder Details</h5>
                    <div>
                        <a href="{{ route('admin.shareholders.edit', $shareholder) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('admin.shareholders.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Personal Information</h6>
                            <p><strong>Name:</strong> {{ $shareholder->user->name }}</p>
                            <p><strong>Email:</strong> {{ $shareholder->user->email }}</p>
                            <p><strong>Registration Date:</strong> {{ $shareholder->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Shareholding Information</h6>
                            <p><strong>Company:</strong> {{ $shareholder->company->name }}</p>
                            <p><strong>Number of Shares:</strong> {{ number_format($shareholder->shares) }}</p>
                            <p><strong>Shares Owned:</strong> {{ number_format($shareholder->shares_owned) }}</p>
                            <p><strong>Account Status:</strong> 
                                <span class="badge badge-success">Active</span>
                            </p>
                        </div>
                    </div>

                    <hr>
                    
                    <h6>Recent Voting Activity</h6>
                    @if($shareholder->votes && $shareholder->votes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>AGM</th>
                                        <th>Voting Item</th>
                                        <th>Vote</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shareholder->votes->take(10) as $vote)
                                    <tr>
                                        <td>{{ $vote->votingItem->agm->title }}</td>
                                        <td>{{ $vote->votingItem->title }}</td>
                                        <td>{{ ucfirst($vote->vote_value) }}</td>
                                        <td>{{ $vote->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No voting activity yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
