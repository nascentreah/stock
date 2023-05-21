

<?php $__env->startSection('title'); ?>
    <?php echo e(__('auth.reset')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('before-auth'); ?>
    <div class="page-background"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth'); ?>
    <div class="ui middle aligned center aligned grid centered-container">
        <div class="column">
            <div class="ui segment">
                <h2 class="ui <?php echo e($settings->color); ?> image header">
                    <a href="<?php echo e(route('frontend.index')); ?>">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" class="image">
                    </a>
                    <div class="content">
                        <?php echo e(__('app.app_name')); ?>

                        <div class="sub header"><?php echo e(__('auth.reset_header')); ?></div>
                    </div>
                </h2>
                <?php $__env->startComponent('components.session.messages'); ?>
                <?php echo $__env->renderComponent(); ?>
                <loading-form v-cloak inline-template>
                    <form class="ui form" method="POST" action="<?php echo e(route('password.request')); ?>" @submit="disableButton">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                        <div class="disabled field<?php echo e($errors->has('email') ? ' error' : ''); ?>">
                            <div class="ui left icon input">
                                <i class="mail icon"></i>
                                <input type="email" name="email" placeholder="<?php echo e(__('auth.email')); ?>" value="<?php echo e($email ?? old('email')); ?>" required autofocus>
                            </div>
                        </div>
                        <div class="field<?php echo e($errors->has('password') ? ' error' : ''); ?>">
                            <div class="ui left icon input">
                                <i class="key icon"></i>
                                <input type="password" name="password" placeholder="<?php echo e(__('auth.password')); ?>" required>
                            </div>
                        </div>
                        <div class="field<?php echo e($errors->has('password') ? ' error' : ''); ?>">
                            <div class="ui left icon input">
                                <i class="key icon"></i>
                                <input type="password" name="password_confirmation" placeholder="<?php echo e(__('auth.password_confirm')); ?>" required>
                            </div>
                        </div>
                        <button :class="[{disabled: submitted, loading: submitted}, 'ui <?php echo e($settings->color); ?> fluid large submit button']"><?php echo e(__('auth.save_password')); ?></button>
                    </form>
                </loading-form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>