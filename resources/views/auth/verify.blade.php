@extends('layouts.frontend')

@section('title')
    {{ __('auth.verify') }}
@endsection

@section('content')
    <div class="ui one column tablet stackable {{ $inverted }} grid container">
        <div class="column">
            @if (session('resent'))
                <message message="{{ __('auth.email_verification_sent') }}" class="positive">
                    {{ __('app.success') }}
                </message>
            @endif
            <p>
                {{ __('auth.email_verification_message') }}
                {{ __('auth.email_verification_message2') }}, <a href="{{ route('verification.resend') }}">{{ __('auth.email_verification_message3') }}</a>.
            </p>
        </div>
    </div>
@endsection