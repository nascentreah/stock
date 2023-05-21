

<?php $__env->startSection('title'); ?>
    <?php echo e(__('app.trades')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column stackable grid container">
        <div class="column">
            <?php if($trades->isEmpty()): ?>
                <div class="ui segment">
                    <p><?php echo e(__('app.trades_empty')); ?></p>
                </div>
            <?php else: ?>
                <table class="ui selectable tablet stackable <?php echo e($inverted); ?> table">
                    <thead>
                    <tr>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'id', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.id')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'competition', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.competition')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'asset', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.asset')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'direction', 'sort' => $sort, 'order' => $order]); ?>
                        <?php echo e(__('app.buy_sell')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'lot', 'sort' => $sort, 'order' => $order]); ?>
                        <?php echo e(__('app.lot_size')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'volume', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.volume')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'price_open', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.open_price')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'price_close', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.close_price')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'margin', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.margin')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'pnl', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.pnl')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order]); ?>
                            <?php echo e(__('app.created_at')); ?>

                        <?php echo $__env->renderComponent(); ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td data-title="<?php echo e(__('app.id')); ?>">
                                <a href="<?php echo e(route('backend.trades.edit', $trade)); ?>"><?php echo e($trade->id); ?></a>
                            </td>
                            <td data-title="<?php echo e(__('app.competition')); ?>">
                                <a href="<?php echo e(route('backend.competitions.edit', $trade->competition)); ?>">
                                    <?php echo e($trade->competition->title); ?>

                                </a>
                            </td>
                            <td data-title="<?php echo e(__('app.asset')); ?>">
                                <a href="<?php echo e(route('backend.assets.edit', $trade->asset)); ?>" class="nowrap">
                                    <img src="<?php echo e($trade->asset->logo_url); ?>" class="ui avatar image">
                                    <?php echo e($trade->asset->symbol); ?>

                                </a>
                            </td>
                            <td data-title="<?php echo e(__('app.buy_sell')); ?>">
                                <i class="arrow <?php echo e($trade->direction == \App\Models\Trade::DIRECTION_BUY ? 'up green' : 'down red'); ?> icon"></i>
                                <?php echo e(__('app.trade_direction_' . $trade->direction)); ?>

                            </td>
                            <td data-title="<?php echo e(__('app.lot_size')); ?>"><?php echo e($trade->_lot_size); ?></td>
                            <td data-title="<?php echo e(__('app.volume')); ?>"><?php echo e($trade->_volume); ?></td>
                            <td data-title="<?php echo e(__('app.open_price')); ?>"><?php echo e($trade->_price_open); ?></td>
                            <td data-title="<?php echo e(__('app.close_price')); ?>"><?php echo e($trade->_price_close); ?></td>
                            <td data-title="<?php echo e(__('app.margin')); ?>"><?php echo e($trade->_margin); ?></td>
                            <td data-title="<?php echo e(__('app.pnl')); ?>" class="<?php echo e($trade->pnl > 0 ? 'positive' : ($trade->pnl < 0 ? 'negative' : '')); ?>">
                                <?php echo e($trade->_pnl); ?>

                            </td>
                            <td data-title="<?php echo e(__('app.created_at')); ?>">
                                <?php echo e($trade->created_at->diffForHumans()); ?>

                                <span data-tooltip="<?php echo e($trade->created_at); ?>">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="right aligned column">
            <?php echo e($trades->appends(['sort' => $sort])->appends(['order' => $order])->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>