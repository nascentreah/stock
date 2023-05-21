

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.deposits')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <?php if($deposits->isEmpty()): ?>
                <div class="ui segment">
                    <p><?php echo __('accounting::text.deposits_empty', ['href' => route('frontend.account.show', [Auth::user()])]); ?></p>
                </div>
            <?php else: ?>
                <table class="ui selectable tablet stackable <?php echo e($inverted); ?> table">
                    <thead>
                    <tr>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'payment_method', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('accounting::text.payment_method')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'status', 'sort' => $sort, 'order' => $order]); ?>
                        <?php echo e(__('accounting::text.status')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'amount', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                            <?php echo e(__('accounting::text.amount')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                            <?php echo e(__('accounting::text.created')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'updated', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                            <?php echo e(__('accounting::text.updated')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td data-title="<?php echo e(__('accounting::text.payment_method')); ?>"><?php echo e(__('accounting::text.method_' . $deposit->payment_method_id)); ?></td>
                            <td data-title="<?php echo e(__('accounting::text.status')); ?>" class="<?php echo e($deposit->status == Packages\Accounting\Models\Deposit::STATUS_COMPLETED ? 'positive' : ($deposit->status == Packages\Accounting\Models\Deposit::STATUS_CANCELLED ? 'negative' : '')); ?>"><?php echo e(__('accounting::text.deposit_status_' . $deposit->status)); ?></td>
                            <td data-title="<?php echo e(__('accounting::text.amount')); ?>" class="right aligned">
                                <?php if($deposit->account_currency_code != $deposit->payment_currency_code): ?>
                                    <span data-tooltip="<?php echo e(__('accounting::text.deposit_amount_tooltip', ['amount' => $deposit->_payment_amount, 'ccy' => $deposit->payment_currency_code, 'ccy1' => $deposit->account_currency_code, 'ccy2' => $deposit->payment_currency_code, 'x' => $deposit->payment_fx_rate])); ?>">
                                        <i class="calculator tooltip icon"></i>
                                    </span>
                                <?php endif; ?>
                                <?php echo e($deposit->_amount); ?> <?php echo e($deposit->account_currency_code); ?>

                            </td>
                            <td data-title="<?php echo e(__('accounting::text.created')); ?>" class="right aligned">
                                <?php echo e($deposit->created_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($deposit->created_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td data-title="<?php echo e(__('accounting::text.updated')); ?>" class="right aligned">
                                <?php echo e($deposit->updated_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($deposit->updated_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td class="right aligned mobile-center">
                                <a href="<?php echo e(route('frontend.deposits.create', [Auth::user(), $deposit->payment_method_id, 'amount' => $deposit->amount])); ?>" class="ui tiny icon <?php echo e($settings->color); ?> button">
                                    <i class="redo alternate icon"></i>
                                    <?php echo e(__('accounting::text.repeat')); ?>

                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="right aligned column">
            <?php echo e($deposits->appends(['sort' => $sort])->appends(['order' => $order])->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>