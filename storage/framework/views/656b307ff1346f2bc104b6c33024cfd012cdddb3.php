

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.withdrawal')); ?> <?php echo e($withdrawal->id); ?> (<?php echo e(__('accounting::text.withdrawal_method_' . $withdrawal->withdrawal_method_id)); ?>) :: <?php echo e(__('accounting::text.edit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <div class="ui one column stackable grid container">
            <div class="column">
                <div class="ui <?php echo e($inverted); ?> segment">
                    <form class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('backend.withdrawals.update', $withdrawal)); ?>">
                        <?php echo e(csrf_field()); ?>

                        <?php echo e(method_field('PUT')); ?>

                        <div class="field <?php echo e($errors->has('amount') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.amount')); ?></label>
                            <div class="ui right labeled input">
                                <input type="number" name="amount" placeholder="<?php echo e(__('accounting::text.amount')); ?>" value="<?php echo e(old('amount', $withdrawal->amount)); ?>" required autofocus <?php echo e($editable ? '' : 'disabled'); ?>>
                                <div class="ui basic label">
                                    <?php echo e($withdrawal->account->currency->code); ?>

                                </div>
                            </div>
                        </div>
                        <?php $__currentLoopData = $withdrawal->payment_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="field">
                                <label><?php echo e(__('accounting::text.' . $code)); ?></label>
                                <div class="ui right labeled input">
                                    <input type="text" value="<?php echo e($detail); ?>" disabled>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="field <?php echo e($errors->has('status') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.status')); ?></label>
                            <div id="withdrawal-status-dropdown" class="ui selection <?php echo e($editable ? '' : 'disabled'); ?> dropdown">
                                <input type="hidden" name="status">
                                <i class="dropdown icon"></i>
                                <div class="default text"></div>
                                <div class="menu">
                                    <?php if($editable): ?>
                                        <div class="item" data-value="<?php echo e(\Packages\Accounting\Models\Withdrawal::STATUS_CREATED); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_CREATED)); ?></div>
                                        <div class="item" data-value="<?php echo e(\Packages\Accounting\Models\Withdrawal::STATUS_IN_PROGRESS); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_IN_PROGRESS)); ?></div>
                                        <div class="item" data-value="<?php echo e(\Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED)); ?></div>
                                        <div class="item" data-value="<?php echo e(\Packages\Accounting\Models\Withdrawal::STATUS_REJECTED); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_REJECTED)); ?></div>
                                        <div class="item" data-value="<?php echo e(\Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED)); ?></div>
                                    <?php else: ?>
                                        <div class="item" data-value="<?php echo e($withdrawal->status); ?>"><?php echo e(__('accounting::text.withdrawal_status_'.$withdrawal->status)); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="field <?php echo e($errors->has('comments') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.comments')); ?></label>
                            <textarea rows="5" name="comments" placeholder="<?php echo e(__('accounting::text.comments')); ?>" autofocus <?php echo e($editable ? '' : 'disabled'); ?>><?php echo e(old('details.comments', $withdrawal->comments)); ?></textarea>
                        </div>
                        <div class="field">
                            <label><?php echo e(__('accounting::text.created')); ?></label>
                            <div class="ui input">
                                <input value="<?php echo e($withdrawal->created_at); ?> (<?php echo e($withdrawal->created_at->diffForHumans()); ?>)" disabled>
                            </div>
                        </div>
                        <div class="field">
                            <label><?php echo e(__('accounting::text.created_by')); ?></label>
                            <div class="ui input">
                                <input value="<?php echo e($withdrawal->account->user->name); ?>" disabled>
                            </div>
                        </div>
                        <div class="field">
                            <label><?php echo e(__('accounting::text.updated')); ?></label>
                            <div class="ui input">
                                <input value="<?php echo e($withdrawal->updated_at); ?> (<?php echo e($withdrawal->updated_at->diffForHumans()); ?>)" disabled>
                            </div>
                        </div>
                        <button class="ui large <?php echo e($settings->color); ?> submit <?php echo e($editable ? '' : 'disabled'); ?> button">
                            <i class="save icon"></i>
                            <?php echo e(__('accounting::text.save')); ?>

                        </button>
                    </form>
                </div>
            </div>
            <div class="column">
                <a href="<?php echo e(route('backend.withdrawals.index')); ?>"><i class="left arrow icon"></i> <?php echo e(__('accounting::text.back_all_withdrawals')); ?></a>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $('#withdrawal-status-dropdown').dropdown('set selected', '<?php echo e(old('status', $withdrawal->status)); ?>');
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>