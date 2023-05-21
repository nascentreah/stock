<div class="ui stackable grid container">
    <div class="column">
        <div class="ui labels">
            <?php if($competition->payouts): ?>
                <span class="ui <?php echo e($settings->color); ?> popup-trigger icon label">
                    <i class="dollar sign icon"></i>
                    <?php echo e(__('app.cash_prizes')); ?>

                </span>
                <?php $__env->startComponent('components.popups.payout', ['competition' => $competition]); ?>
                <?php echo $__env->renderComponent(); ?>
            <?php endif; ?>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="balance scale icon"></i><?php echo e(__('app.leverage')); ?> <?php echo e($competition->_leverage); ?>:1</span>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="weight icon"></i><?php echo e(__('app.lot_size')); ?> <?php echo e($competition->_lot_size); ?></span>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="chart bar icon"></i><?php echo e(__('app.volume')); ?> <?php echo e($competition->_volume_min); ?> - <?php echo e($competition->_volume_max); ?></span>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="users icon"></i><?php echo e(__('app.slots_taken')); ?> <?php echo e($competition->slots_taken); ?> / <?php echo e($competition->slots_max); ?></span>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="money bill alternate outline icon"></i><?php echo e(__('app.start_balance')); ?> <?php echo e($competition->_start_balance); ?> <?php echo e($competition->currency->code); ?></span>
            <span class="ui <?php echo e($settings->color); ?> basic label"><i class="flag outline icon"></i><?php echo e(__('app.status')); ?> <?php echo e(__('app.competition_status_'.$competition->status)); ?></span>
        </div>
        <?php if(!$competition->assets->isEmpty()): ?>
            <span class="ui basic label"><?php echo e(__('app.you_can_trade')); ?></span>
            <div class="ui tiny horizontal list">
                <?php $__currentLoopData = $competition->assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <img class="ui avatar image" src="<?php echo e($asset->logo_url); ?>">
                        <div class="content">
                            <div class="header"><?php echo e($asset->name); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <div class="ui secondary <?php echo e($settings->color); ?> pointing stackable <?php echo e($inverted); ?> menu">
            <?php if($competition->status == \App\Models\Competition::STATUS_IN_PROGRESS && $participant): ?>
                <a href="<?php echo e(route('frontend.competitions.show', $competition)); ?>" class="item <?php echo e(Route::currentRouteName()=='frontend.competitions.show' ? 'active' : ''); ?>">
                    <?php echo e(__('app.trading')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('frontend.competitions.leaderboard', $competition)); ?>" class="item <?php echo e(Route::currentRouteName()=='frontend.competitions.leaderboard' ? 'active' : ''); ?>">
                <?php echo e(__('app.leaderboard')); ?>

            </a>
            <?php if($participant): ?>
                <a href="<?php echo e(route('frontend.competitions.history', $competition)); ?>" class="item <?php echo e(Route::currentRouteName()=='frontend.competitions.history' ? 'active' : ''); ?>">
                    <?php echo e(__('app.history')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>
</div>