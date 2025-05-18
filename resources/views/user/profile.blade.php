@extends('layouts.user')

@section('content')
<section class="profile-section">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-user-info">
                <div class="profile-user-text">
                    <h3 class="profile-username">{{ $user->name }}</h3>
                    <p class="profile-email">{{ $user->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">{{ __('messages.edit') }}</a>
        </div>
        
        <div class="profile-info-grid">
            <div class="profile-info-block">
                <label class="profile-info-label">{{ __('messages.full_name') }}</label>
                <div class="profile-info-box">{{ $user->name }}</div>
            </div>
            <div class="profile-info-block">
                <label class="profile-info-label">{{ __('messages.phone_number') }}</label>
                <div class="profile-info-box">{{ $user->phone ?? __('messages.pending') }}</div>
            </div>
        </div>
    </div>
</section>
@endsection