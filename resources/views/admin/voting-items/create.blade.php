@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Add Voting Item to {{ $agm->title }}</h5>
                    <a href="{{ route('admin.agms.show', $agm) }}" class="btn btn-secondary">Back to AGM</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.agms.voting-items.store', $agm) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}"
                                   placeholder="e.g., Color of new building" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Voting Type</label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                    id="type" name="type" required onchange="toggleOptions()">
                                <option value="">Select voting type</option>
                                <option value="yes_no" {{ old('type') == 'yes_no' ? 'selected' : '' }}>Yes/No</option>
                                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="options-section" style="display: none;">
                            <label class="form-label">Voting Options</label>
                            <div id="options-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="options[]"
                                           placeholder="e.g., Red" value="{{ old('options.0') }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">Remove</button>
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="options[]"
                                           placeholder="e.g., Yellow" value="{{ old('options.1') }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">Remove</button>
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="options[]"
                                           placeholder="e.g., Blue" value="{{ old('options.2') }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addOption()">
                                Add Another Option
                            </button>
                            @error('options')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.agms.show', $agm) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Voting Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOptions() {
    const type = document.getElementById('type').value;
    const optionsSection = document.getElementById('options-section');
    const optionInputs = optionsSection.querySelectorAll('input[name^="options"]');

    if (type === 'multiple_choice') {
        optionsSection.style.display = 'block';
        optionInputs.forEach(input => {
            input.disabled = false;
        });
    } else {
        optionsSection.style.display = 'none';
        optionInputs.forEach(input => {
            input.disabled = true;
        });
    }
}




function addOption() {
    const container = document.getElementById('options-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="options[]" placeholder="Enter option">
        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">Remove</button>
    `;
    container.appendChild(div);
}

function removeOption(button) {
    const container = document.getElementById('options-container');
    if (container.children.length > 2) {
        button.parentElement.remove();
    } else {
        alert('You must have at least 2 options for multiple choice voting.');
    }
}

// Show options if multiple_choice is selected on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleOptions();
});
</script>
@endsection
