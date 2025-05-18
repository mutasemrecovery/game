@extends('layouts.user')

@section('content')
<section class="signin-section">
  <div class="signin-content">
    <!-- ✅ Left side -->
    <div class="signin-left">
      <h2 class="signin-heading">{{ __('messages.Sign Up to') }}</h2>
      <h3 class="signin-subheading">The Dynamite Box</h3>
      <p class="signin-tagline">
        {{ __('messages.welcome_tagline') }}
      </p>
      <img src="{{ asset('assets_front/assets/images/register.png') }}" class="signin-dish" alt="Dish"/>
    </div>

    <!-- ✅ Right side -->
    <div class="signin-right">
      <div class="signin-box">
        <p class="signin-welcome">{{ __('messages.welcome_to') }} <span class="red">the dynamite box</span></p>
        <h2 class="signin-title">{{ __('messages.Sign Up') }}</h2>

        <form class="auth-form" action="{{ route('register') }}" method="post">
          @csrf
          <div class="auth-form-group">
            <label for="name">{{ __('messages.username') }}</label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              class="auth-input-full @error('name') is-invalid @enderror" 
              placeholder="{{ __('messages.username_placeholder') }}" 
              value="{{ old('name') }}" 
              required 
            />
            @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          
          <div class="auth-form-group">
            <label for="phone">{{ __('messages.contact_number') }}</label>
            <input 
              type="tel" 
              id="phone" 
              name="phone" 
              class="auth-input-full @error('phone') is-invalid @enderror" 
              placeholder="{{ __('messages.phone_placeholder') }}" 
              value="{{ old('phone') }}" 
              required 
            />
            @error('phone')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          
          <div class="auth-form-group">
            <label for="password">{{ __('messages.enter_password') }}</label>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="auth-input-full @error('password') is-invalid @enderror" 
              placeholder="{{ __('messages.password_placeholder') }}" 
              required 
            />
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          
          <div class="auth-options">
            <a href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
            <span>{{ __('messages.have_account') }} <a href="{{ route('login') }}">{{ __('messages.sign_in') }}</a></span>
          </div>
          
          <button type="submit" class="auth-submit-btn">{{ __('messages.Sign Up') }}</button>
        </form>
        
      </div>
    </div>
  </div>
</section>
@endsection