@extends('layouts.frontend')

@section('title')
    {{ __('accounting::text.withdrawal') }} :: {{ __('accounting::text.withdrawal_method_' . $withdrawal_method->id) }}
@endsection

@section('content')
    <div class="ui stackable grid container">
        <div class="column">
            <div class="ui {{ $inverted }} segment">
                <form id="withdrawal-form" class="ui {{ $inverted }} form" method="POST" action="{{ route('frontend.withdrawals.store', [Auth::user(), $withdrawal_method]) }}">
                    {{ csrf_field() }}
                    <div class="field {{ $errors->has('amount') ? 'error' : '' }}">
                        <label>{{ __('accounting::text.amount') }}</label>
                        <div class="ui right labeled input">
                            <input id="withdrawal-amount-input" type="number" name="amount" placeholder="{{ __('accounting::text.amount') }}" value="{{ old('amount', Request::get('amount')) }}" required autofocus>
                            <div class="ui basic {{ $inverted }} label">
                                {{ $account->currency->code }}
                            </div>
                        </div>
                    </div>
                    @if($withdrawal_method->code == 'paypal')
                        <div class="field {{ $errors->has('details.email') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.email') }}</label>
                            <input type="email" name="details[email]" placeholder="{{ __('accounting::text.email') }}" value="{{ old('details.email') }}" required autofocus>
                        </div>
                    @elseif($withdrawal_method->code=='wire')
                        <div class="field {{ $errors->has('details.name') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.name') }}</label>
                            <input type="text" name="details[name]" placeholder="{{ __('accounting::text.name') }}" value="{{ old('details.name', Auth::user()->name) }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.bank_iban') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.bank_iban') }}</label>
                            <input type="text" name="details[bank_iban]" placeholder="{{ __('accounting::text.bank_iban') }}" value="{{ old('details.bank_iban') }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.bank_swift') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.bank_swift') }}</label>
                            <input type="text" name="details[bank_swift]" placeholder="{{ __('accounting::text.bank_swift') }}" value="{{ old('details.bank_swift') }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.bank_name') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.bank_name') }}</label>
                            <input type="text" name="details[bank_name]" placeholder="{{ __('accounting::text.bank_name') }}" value="{{ old('details.bank_name') }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.bank_branch') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.bank_branch') }}</label>
                            <input type="text" name="details[bank_branch]" placeholder="{{ __('accounting::text.bank_branch') }}" value="{{ old('details.bank_branch') }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.bank_address') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.bank_address') }}</label>
                            <input type="text" name="details[bank_address]" placeholder="{{ __('accounting::text.bank_address') }}" value="{{ old('details.bank_address') }}" required autofocus>
                        </div>
                        <div class="field {{ $errors->has('details.comments') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.comments') }}</label>
                            <textarea rows="3" name="details[comments]" placeholder="{{ __('accounting::text.comments') }}" autofocus>
                                {{ old('details.comments') }}
                            </textarea>
                        </div>
                    @elseif($withdrawal_method->code=='crypto')
                        <div class="field {{ $errors->has('details.crypto_address') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.crypto_address') }}</label>
                            <div class="ui action input">
                            <input type="text" name="details[crypto_address]" placeholder="{{ __('accounting::text.crypto_address') }}" value="{{ old('details.crypto_address') }}" required autofocus>
                                <select name="details[cryptocurrency]" class="ui selection search dropdown">
                                    @foreach(explode(',', config('accounting.withdrawal.cryptocurrencies')) as $cryptocurrency)
                                        <option>{{ $cryptocurrency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <button class="ui large {{ $settings->color }} submit button">
                        {{ __('accounting::text.proceed') }}
                        <i class="right arrow icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection