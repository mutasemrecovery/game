@extends('layouts.user')

@section('content')
<section class="privacy-policy-section">
    <div class="container">
        <div class="privacy-policy-header">
            <h1 class="privacy-policy-title">{{ __('messages.Privacy Policy') }}</h1>
        </div>

        <div class="privacy-policy-content">
            <div class="privacy-policy-section">
                <h2>{{$locale === 'en' ? $page->title_en : $page->title_ar}}</h2>
                <p>{!! $locale === 'en' ? $page->content_en : $page->content_ar !!}</p>
            </div>

        </div>
    </div>
</section>
@endsection