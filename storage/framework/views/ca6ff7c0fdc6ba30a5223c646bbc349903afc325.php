<div class="row">
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> statistic">
                <div class="value">
                    <?php echo e($accounts_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.accounts_count')); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> green statistic">
                <div class="value">
                    <?php echo e($positive_balance_accounts_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.positive_balance_accounts_count')); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> red statistic">
                <div class="value">
                    <?php echo e($zero_balance_accounts_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.zero_balance_accounts_count')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> statistic">
                <div class="value">
                    <?php echo e($deposits_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.deposits_count')); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> green statistic">
                <div class="value">
                    <?php echo e($completed_deposits_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.completed_deposits_count')); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui <?php echo e($inverted); ?> segment">
            <div class="ui <?php echo e($inverted); ?> red statistic">
                <div class="value">
                    <?php echo e($pending_deposits_count); ?>

                </div>
                <div class="label">
                    <?php echo e(__('accounting::text.pending_deposits_count')); ?>

                </div>
            </div>
        </div>
    </div>
</div>