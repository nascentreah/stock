

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.withdrawals')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <?php if($withdrawals->isEmpty()): ?>
                <div class="ui segment">
                    <p><?php echo e(__('accounting::text.withdrawals_empty2')); ?></p>
                </div>
            <?php else: ?>
                <table class="ui selectable tablet stackable <?php echo e($inverted); ?> table">
                    <thead>
                    <tr>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'user', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('accounting::text.user')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'account', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('accounting::text.account')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'withdrawal_method', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('accounting::text.withdrawal_method')); ?>

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
                    <?php $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td data-title="<?php echo e(__('accounting::text.user')); ?>">
                                <a href="<?php echo e(route('backend.users.edit', [$withdrawal->user_id])); ?>">
                                    <?php echo e($withdrawal->user_name); ?>

                                </a>
                            </td>
                            <td data-title="<?php echo e(__('accounting::text.account')); ?>"><?php echo e($withdrawal->account_code); ?></td>
                            <td data-title="<?php echo e(__('accounting::text.withdrawal_method')); ?>">
                                <?php echo e(__('accounting::text.withdrawal_method_' . $withdrawal->withdrawal_method_id)); ?>

                                <?php if(!empty($withdrawal->payment_details)): ?>
                                    <span data-tooltip="<?php echo e(implode(', ', array_values($withdrawal->payment_details))); ?>">
                                        <i class="info tooltip icon"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td data-title="<?php echo e(__('accounting::text.status')); ?>" class="<?php echo e($withdrawal->status == Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED ? 'positive' : (in_array($withdrawal->status, [Packages\Accounting\Models\Withdrawal::STATUS_REJECTED, Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED]) ? 'negative' : '')); ?>"><?php echo e(__('accounting::text.withdrawal_status_' . $withdrawal->status)); ?></td>
                            <td data-title="<?php echo e(__('accounting::text.amount')); ?>" class="right aligned">
                                <?php echo e($withdrawal->_amount); ?> <?php echo e($withdrawal->account_currency_code); ?>

                            </td>
                            <td data-title="<?php echo e(__('accounting::text.created')); ?>" class="right aligned">
                                <?php echo e($withdrawal->created_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($withdrawal->created_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td data-title="<?php echo e(__('accounting::text.updated')); ?>" class="right aligned">
                                <?php echo e($withdrawal->updated_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($withdrawal->updated_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td class="right aligned tablet-and-below-center">
                                <a class="ui icon <?php echo e($settings->color); ?> basic button" href="<?php echo e(route('backend.withdrawals.edit', $withdrawal)); ?>">
                                    <i class="edit icon"></i>
                                    <?php echo e(__('accounting::text.edit')); ?>

                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="right aligned column">
            <?php echo e($withdrawals->appends(['sort' => $sort])->appends(['order' => $order])->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>