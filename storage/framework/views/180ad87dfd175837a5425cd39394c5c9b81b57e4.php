

<?php $__env->startSection('title'); ?>
    <?php echo e($user->name); ?> :: <?php echo e(__('users.delete')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column stackable grid container">
        <div class="column">
            <div class="ui <?php echo e($inverted); ?> segment">
                <form  class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('backend.users.destroy', $user)); ?>">
                    <?php echo e(csrf_field()); ?>

                    <?php echo e(method_field('DELETE')); ?>

                    <button class="ui large red submit icon button">
                        <i class="trash icon"></i>
                        <?php echo e(__('users.delete')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>