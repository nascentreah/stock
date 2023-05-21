

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.account')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <table class="ui selectable tablet stackable <?php echo e($inverted); ?> table">
                <thead>
                    <tr>
                        <th><?php echo e(__('accounting::text.code')); ?></th>
                        <th><?php echo e(__('accounting::text.status')); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.balance')); ?>, <?php echo e($account->currency->code); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.created')); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.updated')); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-title="<?php echo e(__('accounting::text.code')); ?>"><?php echo e($account->code); ?></td>
                        <td data-title="<?php echo e(__('accounting::text.status')); ?>"><i class="<?php echo e($account->status == Packages\Accounting\Models\Account::STATUS_ACTIVE ? 'check green' : 'red ban'); ?> large icon"></i> <?php echo e(__('accounting::text.status_' . $account->status)); ?></td>
                        <td data-title="<?php echo e(__('accounting::text.balance')); ?>" class="right aligned"><?php echo e($account->_balance); ?></td>
                        <td data-title="<?php echo e(__('accounting::text.created')); ?>" class="right aligned">
                            <?php echo e($account->created_at->diffForHumans()); ?>

                            <span data-tooltip="<?php echo e($account->created_at); ?>">
                                <i class="calendar outline tooltip icon"></i>
                            </span>
                        </td>
                        <td data-title="<?php echo e(__('accounting::text.updated')); ?>" class="right aligned">
                            <?php echo e($account->updated_at->diffForHumans()); ?>

                            <span data-tooltip="<?php echo e($account->updated_at); ?>">
                                <i class="calendar outline tooltip icon"></i>
                            </span>
                        </td>
                        <td class="right aligned mobile-center">
                            <?php if(!$payment_methods->isEmpty()): ?>
                                <div class="ui small compact <?php echo e($inverted); ?> menu">
                                    <div class="ui dropdown item">
                                        <?php echo e(__('accounting::text.deposit')); ?>

                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('frontend.deposits.create', [Auth::user(), $payment_method])); ?>" class="item"><?php echo e(__('accounting::text.method_' . $payment_method->id)); ?></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(!$withdrawal_methods->isEmpty()): ?>
                                <div class="ui small compact <?php echo e($inverted); ?> menu">
                                    <div class="ui dropdown item">
                                        <?php echo e(__('accounting::text.withdraw')); ?>

                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            <?php $__currentLoopData = $withdrawal_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('frontend.withdrawals.create', [Auth::user(), $withdrawal_method])); ?>" class="item"><?php echo e(__('accounting::text.withdrawal_method_' . $withdrawal_method->id)); ?></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php if(!$transactions->isEmpty()): ?>
            <div class="column">
                <h2 class="ui header"><?php echo e(__('accounting::text.transactions')); ?></h2>
                <table class="ui selectable tablet stackable <?php echo e($inverted); ?> table">
                    <thead>
                    <tr>
                        <th><?php echo e(__('accounting::text.type')); ?></th>
                        <th><?php echo e(__('accounting::text.reference')); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.amount')); ?>, <?php echo e($account->currency->code); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.running_balance')); ?>, <?php echo e($account->currency->code); ?></th>
                        <th class="right aligned"><?php echo e(__('accounting::text.created')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td data-title="<?php echo e(__('accounting::text.type')); ?>"><?php echo e(__('accounting::text.transaction_type_' . $transaction->type)); ?></td>
                            <td data-title="<?php echo e(__('accounting::text.reference')); ?>">
                                <?php echo e($transaction->transactionable ? $transaction->transactionable->title : ''); ?>

                            </td>
                            <td data-title="<?php echo e(__('accounting::text.amount')); ?>" class="right aligned">
                                <?php echo e($transaction->_amount); ?>

                            </td>
                            <td data-title="<?php echo e(__('accounting::text.running_balance')); ?>" class="right aligned">
                                <i class="long arrow alternate <?php echo e($transaction->amount > 0 ? 'green up' : 'red down'); ?> icon"></i>
                                <?php echo e($transaction->_balance); ?>

                            </td>
                            <td data-title="<?php echo e(__('accounting::text.created')); ?>" class="right aligned">
                                <?php echo e($transaction->created_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($transaction->created_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="right aligned column">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>