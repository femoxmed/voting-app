@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit AGM</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agms.update', $agm) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.agms._form', ['agm' => $agm])

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach(['draft', 'pending', 'active', 'completed'] as $status)
                                    <option value="{{ $status }}" {{ old('status', $agm->status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.agms.show', $agm) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update AGM</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
