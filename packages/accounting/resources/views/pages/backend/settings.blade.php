<div class="title">
    <i class="dropdown icon"></i>
    {{ __('settings.addon') }}: {{ __('accounting::text.accounting') }}
</div>

<div class="content">
    <div class="field">
        <label>{{ __('accounting::text.account_currency') }}</label>
        <div id="account-currency-dropdown" class="ui selection search dropdown">
            <input type="hidden" name="ACCOUNT_CURRENCY">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                @foreach($currencies as $currency)
                    <div class="item" data-value="{{ $currency->code }}">{{ $currency->code }} &mdash; {{ $currency->name }}</div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="field">
        <label>{{ __('accounting::text.paypal_mode') }}</label>
        <div id="paypal-mode-dropdown" class="ui selection dropdown">
            <input type="hidden" name="PAYPAL_MODE">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                <div class="item" data-value="sandbox">{{ __('accounting::text.sandbox') }}</div>
                <div class="item" data-value="live">{{ __('accounting::text.live') }}</div>
            </div>
        </div>
    </div>
    <div class="field">
        <label>{{ __('accounting::text.paypal_user') }}</label>
        <input type="text" name="PAYPAL_USER" value="{{ config('accounting.paypal.user') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.paypal_password') }}</label>
        <input type="text" name="PAYPAL_PASSWORD" value="{{ config('accounting.paypal.password') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.paypal_signature') }}</label>
        <input type="text" name="PAYPAL_SIGNATURE" value="{{ config('accounting.paypal.signature') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.stripe_public_key') }}</label>
        <input type="text" name="STRIPE_PUBLIC_KEY" value="{{ config('accounting.stripe.public_key') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.stripe_secret_key') }}</label>
        <input type="text" name="STRIPE_SECRET_KEY" value="{{ config('accounting.stripe.secret_key') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.stripe_webhook_secret') }}</label>
        @if(!config('accounting.stripe.webhook_secret'))
            <div class="ui message">
                <i class="info icon"></i> {{ __('accounting::text.stripe_webhook_secret_message', ['url' => route('webhook.deposits.event')]) }}
            </div>
        @endif
        <input type="text" name="STRIPE_WEBHOOK_SECRET" value="{{ config('accounting.stripe.webhook_secret') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.coinpayments_merchant_id') }}</label>
        @if(!config('accounting.coinpayments.merchant_id'))
            <div class="ui message">
                <i class="info icon"></i> {{ __('accounting::text.coinpayments_merchant_id_message') }}
            </div>
        @endif
        <input type="text" name="COINPAYMENTS_MERCHANT_ID" value="{{ config('accounting.coinpayments.merchant_id') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.coinpayments_public_key') }}</label>
        <input type="text" name="COINPAYMENTS_PUBLIC_KEY" value="{{ config('accounting.coinpayments.public_key') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.coinpayments_private_key') }}</label>
        <input type="text" name="COINPAYMENTS_PRIVATE_KEY" value="{{ config('accounting.coinpayments.private_key') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.coinpayments_secret_key') }}</label>
        @if(!config('accounting.coinpayments.secret_key'))
            <div class="ui message">
                <i class="info icon"></i> {{ __('accounting::text.coinpayments_secret_key_message', ['string' => str_random(20)]) }}
            </div>
        @endif
        <input type="text" name="COINPAYMENTS_SECRET_KEY" value="{{ config('accounting.coinpayments.secret_key') }}" placeholder="">
    </div>
    <div class="field">
        <label>{{ __('accounting::text.deposit_methods_enabled') }}</label>
        <div id="deposit-methods-dropdown" class="ui multiple selection search dropdown">
            <input type="hidden" name="nonenv[deposit_methods]">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                @foreach($deposit_methods as $deposit_method)
                    <div class="item" data-value="{{ $deposit_method->code }}">{{ __('accounting::text.method_' . $deposit_method->id) }}</div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="field">
        <label>{{ __('accounting::text.withdrawal_methods_enabled') }}</label>
        <div id="withdrawal-methods-dropdown" class="ui multiple selection search dropdown">
            <input type="hidden" name="nonenv[withdrawal_methods]">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                @foreach($withdrawal_methods as $withdrawal_method)
                    <div class="item" data-value="{{ $withdrawal_method->code }}">{{ __('accounting::text.withdrawal_method_' . $withdrawal_method->id) }}</div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="field">
        <label>
            {{ __('accounting::text.withdrawal_cryptocurrencies') }}
            <span class="tooltip" data-tooltip="{{ __('accounting::text.withdrawal_cryptocurrencies_tooltip') }}">
                <i class="question circle outline icon"></i>
            </span>
        </label>
        <div id="withdrawal-cryptocurrencies-dropdown" class="ui editable multiple search selection dropdown">
            <input type="hidden" name="WITHDRAWAL_CRYPTOCURRENCIES">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#account-currency-dropdown').dropdown('set selected', '{{ config('accounting.currency') }}');
        $('#paypal-mode-dropdown').dropdown('set selected', '{{ config('accounting.paypal.mode') }}');
        @if(!empty($enabled_deposit_methods))
            $('#deposit-methods-dropdown').dropdown('set selected', [{!! '"'.implode('","', $enabled_deposit_methods).'"' !!}]);
        @endif
        @if(!empty($enabled_withdrawal_methods))
            $('#withdrawal-methods-dropdown').dropdown('set selected', [{!! '"'.implode('","', $enabled_withdrawal_methods).'"' !!}]);
        @endif
        @if(config('accounting.withdrawal.cryptocurrencies'))
            $('#withdrawal-cryptocurrencies-dropdown').dropdown('set selected', '{{ config('accounting.withdrawal.cryptocurrencies') }}'.split(','));
        @endif
    </script>
@endpush