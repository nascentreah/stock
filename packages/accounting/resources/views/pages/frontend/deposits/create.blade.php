@extends('layouts.frontend')

@section('title')
    {{ __('accounting::text.deposit') }} :: {{ __('accounting::text.method_' . $payment_method->id) }}
@endsection

@section('content')
    <div class="ui stackable grid container">
        <div class="column">
            <div class="ui {{ $inverted }} segment">
                <form id="deposit-form" class="ui {{ $inverted }} form" method="POST" action="{{ route('frontend.deposits.store', [Auth::user(), $payment_method]) }}">
                    {{ csrf_field() }}
                    <div class="fields">
                        <div class="{{ $payment_method->code == 'card' ? 'sixteen' : 'twelve' }} wide field {{ $errors->has('amount') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.deposit_amount') }}</label>
                            <div class="ui right labeled action input">
                                <input id="deposit-amount-input" type="text" name="amount" placeholder="{{ __('accounting::text.amount') }}" value="{{ old('amount', Request::get('amount')) }}" required autofocus>
                                <div class="ui label">
                                    {{ $account->currency->code }}
                                </div>
                            </div>
                        </div>
                        @if($payment_method->code == 'card')
                            <input type="hidden" name="currency" value="{{ $account->currency->code }}">
                        @else
                            <div class="four wide field {{ $errors->has('amount') ? 'error' : '' }}">
                                <label>{{ __('accounting::text.payment_currency') }}</label>
                                <select id="deposit-currency-input" name="currency" class="ui selection search dropdown">
                                    @foreach($payment_method_currencies as $currency)
                                        <option value="{{ $currency }}" {{ $currency == $account->currency->code ? 'selected="selected"' : '' }}>{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    @if($payment_method->code == 'card')
                        <div class="field {{ $errors->has('card') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.card_number') }}</label>
                            <div id="card-input-container"></div>
                        </div>
                        <div id="deposit-error-message" class="ui error message"></div>
                    @elseif($payment_method->code=='sofort')
                        <div class="field {{ $errors->has('country') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.country') }}</label>
                            <div id="deposit-country-dropdown" class="ui selection dropdown">
                                <input type="hidden" name="country">
                                <i class="dropdown icon"></i>
                                <div class="default text">{{ __('accounting::text.country') }}</div>
                                <div class="menu">
                                    <div class="item" data-value="AT">{{ __('accounting::text.country_AT') }}</div>
                                    <div class="item" data-value="BE">{{ __('accounting::text.country_BE') }}</div>
                                    <div class="item" data-value="DE">{{ __('accounting::text.country_DE') }}</div>
                                    <div class="item" data-value="NL">{{ __('accounting::text.country_NL') }}</div>
                                    <div class="item" data-value="ES">{{ __('accounting::text.country_ES') }}</div>
                                    <div class="item" data-value="IT">{{ __('accounting::text.country_IT') }}</div>
                                </div>
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

@if($payment_method->code == 'card')
    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var depositForm = document.getElementById('deposit-form');
            var depositAmountInput = document.getElementById('deposit-amount-input');
//            var depositCurrencyInput = document.getElementById('deposit-currency-input');
            var accountCurrency = '{{ $account->currency->code }}';

            // Create a Stripe client.
            var stripe = Stripe('{{ config('accounting.stripe.public_key') }}');
            var stripeErrorContainer = document.getElementById('deposit-error-message');

            // Create an instance of Elements.
            var stripeElements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var stripeCardElementStyle = {
                base: {
                    color: '#000',
                    lineHeight: '14px',
                    fontFamily: 'inherit',
                    fontSize: '14px',
                    '::placeholder': {
                        color: '#BFBFBF'
                    },
                    fontSmoothing: 'antialiased'
                },
                invalid: {
                    color: '#9F3A38',
                    iconColor: '#9F3A38'
                }
            };

            // Create an instance of the card Element.
            var card = stripeElements.create('card', {style: stripeCardElementStyle});

            // Add an instance of the card Element into the `card-input-container` <div>.
            card.mount('#card-input-container');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                if (event.error) {
                    stripeErrorContainer.textContent = event.error.message;
                    stripeErrorContainer.style.display = 'block';
                } else {
                    stripeErrorContainer.textContent = '';
                    stripeErrorContainer.style.display = 'none';
                }
            });

            // Handle form submission.
            depositForm.addEventListener('submit', function(event) {
                event.preventDefault();

//                var currency = depositCurrencyInput.value;
                var amount = parseFloat(depositAmountInput.value);
                // if it's not a zero-decimal currency multiply by 100
                if(['BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','UGX','VND','VUV','XAF','XOF','XPF'].indexOf(accountCurrency) > -1)
                    amount = Math.round(amount);
                else
                    amount = Math.round(amount * 100);

                var stripeSourceParameters = {
                    amount:  amount,
                    currency: accountCurrency,
                    owner: {
                        name: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}'
                    }
                };

                // create card source (it's required by Stripe to be done client-side)
                stripe.createSource(card, stripeSourceParameters).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        stripeErrorContainer.textContent = result.error.message;
                        stripeErrorContainer.style.display = 'block';
                    } else {
                        stripeErrorContainer.style.display = 'none';
                        // Send the source ID to the server.
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'source_id');
                        hiddenInput.setAttribute('value', result.source.id);
                        depositForm.appendChild(hiddenInput);

                        // Submit the form to the server to complete the payment
                        depositForm.submit();
                    }
                });
            });
        </script>
    @endpush
@elseif($payment_method->code == 'sofort')
    @push('scripts')
        <script>
            $('#deposit-country-dropdown').dropdown('set selected', '{{ old('country') }}');
        </script>
    @endpush
@endif