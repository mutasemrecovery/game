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
        </div>
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="profile-info-grid">
                <div class="profile-info-block">
                    <label for="name" class="profile-info-label">{{ __('messages.full_name') }}</label>
                    <input type="text" id="name" name="name" class="profile-info-box" value="{{ $user->name }}" required>
                </div>
                
                <div class="profile-info-block">
                    <label for="phone" class="profile-info-label">{{ __('messages.phone_number') }}</label>
                    <input type="text" id="phone" name="phone" class="profile-info-box" value="{{ $user->phone }}">
                </div>
                
                <div class="profile-info-block">
                    <label for="current_password" class="profile-info-label">{{ __('messages.current_password') }}</label>
                    <input type="password" id="current_password" name="current_password" class="profile-info-box">
                    <p class="profile-info-hint">{{ __('messages.leave_blank_if_no_password_change') }}</p>
                </div>
                
                <div class="profile-info-block">
                    <label for="new_password" class="profile-info-label">{{ __('messages.new_password') }}</label>
                    <input type="password" id="new_password" name="new_password" class="profile-info-box">
                </div>
                
                <div class="profile-info-block">
                    <label for="new_password_confirmation" class="profile-info-label">{{ __('messages.confirm_password') }}</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="profile-info-box">
                </div>
            </div>
            
            <div class="profile-actions">
                <button type="submit" class="save-profile-btn">{{ __('messages.save') }}</button>
                <a href="{{ route('profile') }}" class="cancel-profile-btn">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</section>
@endsection