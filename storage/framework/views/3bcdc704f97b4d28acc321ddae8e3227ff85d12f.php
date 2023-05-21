

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.accounts')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <?php if($accounts->isEmpty()): ?>
                <div class="ui message"><?php echo e(__('accounting::text.accounts_empty2')); ?></div>
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
                            <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'status', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('accounting::text.status')); ?>

                            <?php echo $__env->renderComponent(); ?>
                            <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'balance', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                            <?php echo e(__('accounting::text.balance')); ?>

                            <?php echo $__env->renderComponent(); ?>
                            <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                                <?php echo e(__('accounting::text.created')); ?>

                            <?php echo $__env->renderComponent(); ?>
                            <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'updated', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned']); ?>
                                <?php echo e(__('accounting::text.updated')); ?>

                            <?php echo $__env->renderComponent(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td data-title="<?php echo e(__('accounting::text.user')); ?>">
                                    <a href="<?php echo e(route('backend.users.edit', [$account->user_id])); ?>">
                                        <?php echo e($account->user_name); ?>

                                    </a>
                                </td>
                                <td><?php echo e($account->code); ?></td>
                                <td><i class="<?php echo e($account->status == Packages\Accounting\Models\Account::STATUS_ACTIVE ? 'check green' : 'red ban'); ?> large icon"></i> <?php echo e(__('accounting::text.status_' . $account->status)); ?></td>
                                <td class="right aligned"><?php echo e($account->_balance); ?> <?php echo e($account->currency_code); ?></td>
                                <td class="right aligned">
                                    <?php echo e($account->created_at->diffForHumans()); ?>

                                    <span data-tooltip="<?php echo e($account->created_at); ?>">
                                        <i class="calendar outline tooltip icon"></i>
                                    </span>
                                </td>
                                <td class="right aligned">
                                    <?php echo e($account->updated_at->diffForHumans()); ?>

                                    <span data-tooltip="<?php echo e($account->updated_at); ?>">
                                        <i class="calendar outline tooltip icon"></i>
                                    </span>
                                </td>
                                <td data-title="<?php echo e(__('accounting::text.user')); ?>">
                                    <a href="javascript:;">
                                        Fund balance
                                    </a>
                                </td>
                                <!--<?php echo e(route('backend.users.edit', [$account->user_id])); ?>-->
                                 <tr>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
            </table>
            <?php endif; ?>
        </div>
        <div class="right aligned column">
            <?php echo e($accounts->appends(['sort' => $sort])->appends(['order' => $order])->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>