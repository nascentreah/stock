<a href="<?php echo e(route('backend.accounts.index')); ?>" class="item<?php echo e(strpos(Route::currentRouteName(),'backend.accounts.')!==FALSE ? ' active' : ''); ?>">
    <?php echo e(__('accounting::text.accounts')); ?>

    <i class="list alternate outline icon"></i>
</a>
<a href="<?php echo e(route('backend.deposits.index')); ?>" class="item<?php echo e(strpos(Route::currentRouteName(),'backend.deposits.')!==FALSE ? ' active' : ''); ?>">
    <?php echo e(__('accounting::text.deposits')); ?>

    <i class="arrow alternate circle down outline icon"></i>
</a>
<a href="<?php echo e(route('backend.withdrawals.index')); ?>" class="item<?php echo e(strpos(Route::currentRouteName(),'backend.withdrawals.')!==FALSE ? ' active' : ''); ?>">
    <?php echo e(__('accounting::text.withdrawals')); ?>

    <i class="arrow alternate circle up outline icon"></i>
</a>