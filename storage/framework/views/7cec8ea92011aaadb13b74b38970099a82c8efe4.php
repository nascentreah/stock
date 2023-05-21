

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.deposit')); ?> :: <?php echo e(__('accounting::text.method_' . $payment_method->id)); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui stackable grid container">
        <div class="column">
            <div class="ui <?php echo e($inverted); ?> segment">
                <form id="deposit-form" class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('frontend.deposits.store', [Auth::user(), $payment_method])); ?>">
                    <?php echo e(csrf_field()); ?>

                    <div class="fields">
                        <div class="<?php echo e($payment_method->code == 'card' ? 'sixteen' : 'twelve'); ?> wide field <?php echo e($errors->has('amount') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.deposit_amount')); ?></label>
                            <div class="ui right labeled action input">
                                <input id="deposit-amount-input" type="text" name="amount" placeholder="<?php echo e(__('accounting::text.amount')); ?>" value="<?php echo e(old('amount', Request::get('amount'))); ?>" required autofocus>
                                <div class="ui label">
                                    <?php echo e($account->currency->code); ?>

                                </div>
                            </div>
                        </div>
                        <?php if($payment_method->code == 'card'): ?>
                            <input type="hidden" name="currency" value="<?php echo e($account->currency->code); ?>">
                        <?php else: ?>
                            <div class="four wide field <?php echo e($errors->has('amount') ? 'error' : ''); ?>">
                                <label><?php echo e(__('accounting::text.payment_currency')); ?></label>
                                <select id="deposit-currency-input" name="currency" class="ui selection search dropdown">
                                    <?php $__currentLoopData = $payment_method_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency); ?>" <?php echo e($currency == $account->currency->code ? 'selected="selected"' : ''); ?>><?php echo e($currency); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if($payment_method->code == 'card'): ?>
                        <div class="field <?php echo e($errors->has('card') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.card_number')); ?></label>
                            <div id="card-input-container"></div>
                        </div>
                        <div id="deposit-error-message" class="ui error message"></div>
                    <?php elseif($payment_method->code=='sofort'): ?>
                        <div class="field <?php echo e($errors->has('country') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.country')); ?></label>
                            <div id="deposit-country-dropdown" class="ui selection dropdown">
                                <input type="hidden" name="country">
                                <i class="dropdown icon"></i>
                                <div class="default text"><?php echo e(__('accounting::text.country')); ?></div>
                                <div class="menu">
                                    <div class="item" data-value="AT"><?php echo e(__('accounting::text.country_AT')); ?></div>
                                    <div class="item" data-value="BE"><?php echo e(__('accounting::text.country_BE')); ?></div>
                                    <div class="item" data-value="DE"><?php echo e(__('accounting::text.country_DE')); ?></div>
                                    <div class="item" data-value="NL"><?php echo e(__('accounting::text.country_NL')); ?></div>
                                    <div class="item" data-value="ES"><?php echo e(__('accounting::text.country_ES')); ?></div>
                                    <div class="item" data-value="IT"><?php echo e(__('accounting::text.country_IT')); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <button class="ui large <?php echo e($settings->color); ?> submit button">
                        <?php echo e(__('accounting::text.proceed')); ?>

                        <i class="right arrow icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php if($payment_method->code == 'card'): ?>
    <?php $__env->startPush('scripts'); ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var depositForm = document.getElementById('deposit-form');
            var depositAmountInput = document.getElementById('deposit-amount-input');
//            var depositCurrencyInput = document.getElementById('deposit-currency-input');
            var accountCurrency = '<?php echo e($account->currency->code); ?>';

            // Create a Stripe client.
            var stripe = Stripe('<?php echo e(config('accounting.stripe.public_key')); ?>');
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
                        name: '<?php echo e(auth()->user()->name); ?>',
                        email: '<?php echo e(auth()->user()->email); ?>'
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
    <?php $__env->stopPush(); ?>
<?php elseif($payment_method->code == 'sofort'): ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            $('#deposit-country-dropdown').dropdown('set selected', '<?php echo e(old('country')); ?>');
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>