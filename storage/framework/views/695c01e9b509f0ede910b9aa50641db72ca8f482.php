

<?php $__env->startSection('title'); ?>
    <?php echo e($user->name); ?> :: <?php echo e(__('users.edit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column stackable grid container">
        <div class="column">
            <div class="ui <?php echo e($inverted); ?> segment">
                <form class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('backend.users.update', $user)); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <?php echo e(method_field('PUT')); ?>

                    <image-upload-input name="avatar" image-url="<?php echo e($user->avatar_url); ?>" default-image-url="<?php echo e(asset('images/avatar.jpg')); ?>" class="<?php echo e($errors->has('avatar') ? 'error' : ''); ?>" color="<?php echo e($settings->color); ?>">
                        <?php echo e(__('users.avatar')); ?>

                    </image-upload-input>
                    <div class="field <?php echo e($errors->has('name') ? 'error' : ''); ?>">
                        <label><?php echo e(__('users.name')); ?></label>
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input type="text" name="name" placeholder="<?php echo e(__('users.name')); ?>" value="<?php echo e(old('name', $user->name)); ?>" required autofocus>
                        </div>
                    </div>
                    <div class="field <?php echo e($errors->has('role') ? 'error' : ''); ?>">
                        <label><?php echo e(__('users.role')); ?></label>
                        <div id="user-role-dropdown" class="ui selection dropdown">
                            <input type="hidden" name="role">
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                <div class="item" data-value="<?php echo e(App\Models\User::ROLE_USER); ?>"><i class="grey user icon"></i> <?php echo e(__('users.role_'.App\Models\User::ROLE_USER)); ?></div>
                                <div class="item" data-value="<?php echo e(App\Models\User::ROLE_ADMIN); ?>"><i class="grey user secret icon"></i> <?php echo e(__('users.role_'.App\Models\User::ROLE_ADMIN)); ?></div>
                                <div class="item" data-value="<?php echo e(App\Models\User::ROLE_BOT); ?>"><i class="grey user circle outline icon"></i> <?php echo e(__('users.role_'.App\Models\User::ROLE_BOT)); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="field <?php echo e($errors->has('status') ? 'error' : ''); ?>">
                        <label><?php echo e(__('users.status')); ?></label>
                        <div id="user-status-dropdown" class="ui selection dropdown">
                            <input type="hidden" name="status">
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                <div class="item" data-value="<?php echo e(App\Models\User::STATUS_ACTIVE); ?>"><i class="grey check icon"></i> <?php echo e(__('users.status_'.App\Models\User::STATUS_ACTIVE)); ?></div>
                                <div class="item" data-value="<?php echo e(App\Models\User::STATUS_BLOCKED); ?>"><i class="grey ban icon"></i> <?php echo e(__('users.status_'.App\Models\User::STATUS_BLOCKED)); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="field <?php echo e($errors->has('email') ? 'error' : ''); ?>">
                        <label><?php echo e(__('users.email')); ?></label>
                        <div class="ui left icon input">
                            <i class="mail icon"></i>
                            <input type="email" name="email" placeholder="<?php echo e(__('users.email')); ?>" value="<?php echo e(old('email', $user->email)); ?>" required autofocus>
                        </div>
                    </div>
                    <div class="field <?php echo e($errors->has('password') ? 'error' : ''); ?>">
                        <label><?php echo e(__('users.password')); ?></label>
                        <div class="ui left icon input">
                            <i class="key icon"></i>
                            <input type="password" name="password" placeholder="<?php echo e(__('users.password_placeholder')); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label><?php echo e(__('users.last_login_time')); ?></label>
                        <div class="ui left icon input">
                            <i class="clock outline icon"></i>
                            <input value="<?php echo e($user->last_login_time); ?> (<?php echo e($user->last_login_time->diffForHumans()); ?>)" disabled="disabled">
                        </div>
                    </div>
                    <div class="field">
                        <label><?php echo e(__('users.last_login_ip')); ?></label>
                        <div class="ui left icon input">
                            <i class="globe icon"></i>
                            <input value="<?php echo e($user->last_login_ip); ?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="field">
                        <label><?php echo e(__('users.created_at')); ?></label>
                        <div class="ui left icon input">
                            <i class="clock outline icon"></i>
                            <input value="<?php echo e($user->created_at); ?> (<?php echo e($user->created_at->diffForHumans()); ?>)" disabled="disabled">
                        </div>
                    </div>
                    <div class="field">
                        <label><?php echo e(__('users.updated_at')); ?></label>
                        <div class="ui left icon input">
                            <i class="clock outline icon"></i>
                            <input value="<?php echo e($user->updated_at); ?> (<?php echo e($user->updated_at->diffForHumans()); ?>)" disabled="disabled">
                        </div>
                    </div>
                    <div class="field">
                        <label><?php echo e(__('users.email_verified_at')); ?></label>
                        <div class="ui left icon input">
                            <i class="clock outline icon"></i>
                            <input value="<?php echo e($user->email_verified_at ? $user->email_verified_at . ' (' . $user->email_verified_at->diffForHumans() . ')' : __('users.never')); ?>" disabled="disabled">
                        </div>
                    </div>
                    <button class="ui large <?php echo e($settings->color); ?> submit icon button">
                        <i class="save icon"></i>
                        <?php echo e(__('users.save')); ?>

                    </button>
                    <a href="<?php echo e(route('backend.users.delete', $user)); ?>" class="ui large red submit right floated icon button">
                        <i class="trash icon"></i>
                        <?php echo e(__('users.delete')); ?>

                    </a>
                </form>
            </div>
        </div>
        <div class="column">
            <a href="<?php echo e(url()->previous()); ?>"><i class="left arrow icon"></i> <?php echo e(__('users.back_all')); ?></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $('#user-role-dropdown').dropdown('set selected', '<?php echo e($user->role); ?>');
        $('#user-status-dropdown').dropdown('set selected', '<?php echo e($user->status); ?>');
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>