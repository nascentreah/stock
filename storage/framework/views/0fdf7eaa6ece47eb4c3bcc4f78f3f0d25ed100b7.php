<div class="title">
    <i class="dropdown icon"></i>
    <?php echo e(__('settings.addon')); ?>: <?php echo e(__('accounting::text.accounting')); ?>

</div>

<div class="content">
    <div class="field">
        <label><?php echo e(__('accounting::text.account_currency')); ?></label>
        <div id="account-currency-dropdown" class="ui selection search dropdown">
            <input type="hidden" name="ACCOUNT_CURRENCY">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item" data-value="<?php echo e($currency->code); ?>"><?php echo e($currency->code); ?> &mdash; <?php echo e($currency->name); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.paypal_mode')); ?></label>
        <div id="paypal-mode-dropdown" class="ui selection dropdown">
            <input type="hidden" name="PAYPAL_MODE">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                <div class="item" data-value="sandbox"><?php echo e(__('accounting::text.sandbox')); ?></div>
                <div class="item" data-value="live"><?php echo e(__('accounting::text.live')); ?></div>
            </div>
        </div>
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.paypal_user')); ?></label>
        <input type="text" name="PAYPAL_USER" value="<?php echo e(config('accounting.paypal.user')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.paypal_password')); ?></label>
        <input type="text" name="PAYPAL_PASSWORD" value="<?php echo e(config('accounting.paypal.password')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.paypal_signature')); ?></label>
        <input type="text" name="PAYPAL_SIGNATURE" value="<?php echo e(config('accounting.paypal.signature')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.stripe_public_key')); ?></label>
        <input type="text" name="STRIPE_PUBLIC_KEY" value="<?php echo e(config('accounting.stripe.public_key')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.stripe_secret_key')); ?></label>
        <input type="text" name="STRIPE_SECRET_KEY" value="<?php echo e(config('accounting.stripe.secret_key')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.stripe_webhook_secret')); ?></label>
        <?php if(!config('accounting.stripe.webhook_secret')): ?>
            <div class="ui message">
                <i class="info icon"></i> <?php echo e(__('accounting::text.stripe_webhook_secret_message', ['url' => route('webhook.deposits.event')])); ?>

            </div>
        <?php endif; ?>
        <input type="text" name="STRIPE_WEBHOOK_SECRET" value="<?php echo e(config('accounting.stripe.webhook_secret')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.coinpayments_merchant_id')); ?></label>
        <?php if(!config('accounting.coinpayments.merchant_id')): ?>
            <div class="ui message">
                <i class="info icon"></i> <?php echo e(__('accounting::text.coinpayments_merchant_id_message')); ?>

            </div>
        <?php endif; ?>
        <input type="text" name="COINPAYMENTS_MERCHANT_ID" value="<?php echo e(config('accounting.coinpayments.merchant_id')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.coinpayments_public_key')); ?></label>
        <input type="text" name="COINPAYMENTS_PUBLIC_KEY" value="<?php echo e(config('accounting.coinpayments.public_key')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.coinpayments_private_key')); ?></label>
        <input type="text" name="COINPAYMENTS_PRIVATE_KEY" value="<?php echo e(config('accounting.coinpayments.private_key')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.coinpayments_secret_key')); ?></label>
        <?php if(!config('accounting.coinpayments.secret_key')): ?>
            <div class="ui message">
                <i class="info icon"></i> <?php echo e(__('accounting::text.coinpayments_secret_key_message', ['string' => str_random(20)])); ?>

            </div>
        <?php endif; ?>
        <input type="text" name="COINPAYMENTS_SECRET_KEY" value="<?php echo e(config('accounting.coinpayments.secret_key')); ?>" placeholder="">
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.deposit_methods_enabled')); ?></label>
        <div id="deposit-methods-dropdown" class="ui multiple selection search dropdown">
            <input type="hidden" name="nonenv[deposit_methods]">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                <?php $__currentLoopData = $deposit_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item" data-value="<?php echo e($deposit_method->code); ?>"><?php echo e(__('accounting::text.method_' . $deposit_method->id)); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="field">
        <label><?php echo e(__('accounting::text.withdrawal_methods_enabled')); ?></label>
        <div id="withdrawal-methods-dropdown" class="ui multiple selection search dropdown">
            <input type="hidden" name="nonenv[withdrawal_methods]">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                <?php $__currentLoopData = $withdrawal_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item" data-value="<?php echo e($withdrawal_method->code); ?>"><?php echo e(__('accounting::text.withdrawal_method_' . $withdrawal_method->id)); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="field">
        <label>
            <?php echo e(__('accounting::text.withdrawal_cryptocurrencies')); ?>

            <span class="tooltip" data-tooltip="<?php echo e(__('accounting::text.withdrawal_cryptocurrencies_tooltip')); ?>">
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

<?php $__env->startPush('scripts'); ?>
    <script>
        $('#account-currency-dropdown').dropdown('set selected', '<?php echo e(config('accounting.currency')); ?>');
        $('#paypal-mode-dropdown').dropdown('set selected', '<?php echo e(config('accounting.paypal.mode')); ?>');
        <?php if(!empty($enabled_deposit_methods)): ?>
            $('#deposit-methods-dropdown').dropdown('set selected', [<?php echo '"'.implode('","', $enabled_deposit_methods).'"'; ?>]);
        <?php endif; ?>
        <?php if(!empty($enabled_withdrawal_methods)): ?>
            $('#withdrawal-methods-dropdown').dropdown('set selected', [<?php echo '"'.implode('","', $enabled_withdrawal_methods).'"'; ?>]);
        <?php endif; ?>
        <?php if(config('accounting.withdrawal.cryptocurrencies')): ?>
            $('#withdrawal-cryptocurrencies-dropdown').dropdown('set selected', '<?php echo e(config('accounting.withdrawal.cryptocurrencies')); ?>'.split(','));
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>