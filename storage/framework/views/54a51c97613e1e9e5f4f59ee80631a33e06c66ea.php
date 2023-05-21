

<?php $__env->startSection('title'); ?>
    <?php echo e(__('license.registration')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column stackable grid container">
        <?php if(!env('PURCHASE_CODE')): ?>
            <div class="column">
                <div class="ui warning message">
                    <?php echo e(__('license.warning')); ?>

                </div>
            </div>
        <?php endif; ?>
        <div class="column">
            <form class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('backend.license.register')); ?>">
                <?php echo csrf_field(); ?>
                <div class="field">
                    <label><?php echo e(__('license.purchase_code')); ?></label>
                    <input type="text" name="code" placeholder="xxx-yyy-zzz" value="<?php echo e(old('code', env('PURCHASE_CODE'))); ?>" required>
                </div>
                <div class="field">
                    <label><?php echo e(__('license.email')); ?></label>
                    <input type="text" name="email" placeholder="someone@example.net" value="<?php echo e(old('email', env('LICENSEE_EMAIL'))); ?>" required>
                </div>
                <button class="ui <?php echo e($settings->color); ?> submit button"><?php echo e(__('license.register')); ?></button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>