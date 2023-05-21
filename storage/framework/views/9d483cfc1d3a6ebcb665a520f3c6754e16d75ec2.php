

<?php $__env->startSection('title'); ?>
    <?php echo e(__('app.markets')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <data-feed></data-feed>
    <div class="ui tablet stackable grid container">
        <?php if($assets->isEmpty()): ?>
            <div class="sixteen wide column">
                <div class="ui segment">
                    <p><?php echo e(__('app.assets_empty')); ?></p>
                </div>
            </div>
        <?php else: ?>
            <?php if($markets->count() > 1): ?>
                <div class="five wide column">
                    <div id="market-dropdown" class="ui selection fluid dropdown">
                        <i class="dropdown icon"></i>
                        <div class="default text"></div>
                        <div class="menu">
                            <?php $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('frontend.assets.index')); ?>?market=<?php echo e($market->code); ?>" data-value="<?php echo e($market->code); ?>" class="item"><i class="<?php echo e($market->country_code); ?> flag"></i><?php echo e($market->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="sixteen wide column">
                <assets-table :assets-list="<?php echo e($assets->getCollection()); ?>" class="ui selectable <?php echo e($inverted); ?> table">
                    <template slot="header">
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'symbol', 'sort' => $sort, 'order' => $order, 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.name')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'price', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.price')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'change_abs', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.change_abs')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'change_pct', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.change_pct')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'market_cap', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.market_cap')); ?>

                        <?php echo $__env->renderComponent(); ?>
                        <?php $__env->startComponent('components.tables.sortable-column', ['id' => 'trades_count', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]]); ?>
                        <?php echo e(__('app.trades')); ?>

                        <?php echo $__env->renderComponent(); ?>
                    </template>
                </assets-table>
            </div>
            <div class="sixteen wide right aligned column">
                <?php echo e($assets->appends(['sort' => $sort])->appends(['order' => $order])->appends(['market' => $selected_market])->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $('#market-dropdown').dropdown('set selected', '<?php echo e($selected_market); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>