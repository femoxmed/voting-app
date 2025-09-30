@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cast Your Vote</h5>
                    <a href="{{ route('shareholder.agms.show', $votingItem->agm) }}" class="btn btn-secondary btn-sm">Back to AGM</a>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h4 class="card-title">{{ $votingItem->title }}</h4>
                    <p class="card-text text-muted">{{ $votingItem->description }}</p>

                    <hr>

                    @if($shareholder->hasVotedOn($votingItem))
                        <div class="alert alert-success text-center">
                            <h5 class="alert-heading">You have already voted on this item.</h5>
                            <p>Thank you for your participation.</p>
                        </div>
                    @elseif(!$votingItem->agm->isActive() || !$votingItem->isActive())
                         <div class="alert alert-warning text-center">
                            <h5 class="alert-heading">Voting is currently closed for this item.</h5>
                        </div>
                    @else
                        <form action="{{ route('voting.vote', $votingItem) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <p><strong>Please select your vote:</strong></p>
                                @if($votingItem->type === 'yes_no')
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vote_option" id="vote_yes" value="Yes" required>
                                        <label class="form-check-label" for="vote_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vote_option" id="vote_no" value="No">
                                        <label class="form-check-label" for="vote_no">No</label>
                                    </div>
                                @else
                                    @foreach($votingItem->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vote_option" id="vote_{{ Str::slug($option) }}" value="{{ $option }}" required>
                                        <label class="form-check-label" for="vote_{{ Str::slug($option) }}">{{ $option }}</label>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Vote ({{ number_format($shareholder->shares_owned) }} shares)</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
