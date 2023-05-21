

<?php $__env->startSection('title'); ?>
    <?php echo e(__('auth.verify')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable <?php echo e($inverted); ?> grid container">
        <div class="column">
            <?php if(session('resent')): ?>
                <message message="<?php echo e(__('auth.email_verification_sent')); ?>" class="positive">
                    <?php echo e(__('app.success')); ?>

                </message>
            <?php endif; ?>
            <p>
                <?php echo e(__('auth.email_verification_message')); ?>

                <?php echo e(__('auth.email_verification_message2')); ?>, <a href="<?php echo e(route('verification.resend')); ?>"><?php echo e(__('auth.email_verification_message3')); ?></a>.
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>