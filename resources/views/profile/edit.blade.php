@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">{{ __('Profile') }}</h2>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Profile Information</h5>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-3 text-muted">Update your account's profile information and email address.</p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Update Password</h5>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-3 text-muted">Ensure your account is using a long, random password to stay secure.</p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="text-danger">Delete Account</h5>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-3 text-muted">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
