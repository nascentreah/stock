<a href="<?php echo e(route('frontend.account.show', [Auth::user()])); ?>" class="item">
    <i class="list alternate outline icon"></i>
    <?php echo e(__('accounting::text.account')); ?>

</a>
<a href="<?php echo e(route('frontend.deposits.index', [Auth::user()])); ?>" class="item">
    <i class="arrow alternate circle down outline icon"></i>
    <?php echo e(__('accounting::text.deposits')); ?>

</a>
<a href="<?php echo e(route('frontend.withdrawals.index', [Auth::user()])); ?>" class="item">
    <i class="arrow alternate circle up outline icon"></i>
    <?php echo e(__('accounting::text.withdrawals')); ?>

</a>