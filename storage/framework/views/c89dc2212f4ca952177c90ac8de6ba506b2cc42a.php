

<?php $__env->startSection('title'); ?>
    <?php echo e(__('accounting::text.withdrawal')); ?> :: <?php echo e(__('accounting::text.withdrawal_method_' . $withdrawal_method->id)); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui stackable grid container">
        <div class="column">
            <div class="ui <?php echo e($inverted); ?> segment">
                <form id="withdrawal-form" class="ui <?php echo e($inverted); ?> form" method="POST" action="<?php echo e(route('frontend.withdrawals.store', [Auth::user(), $withdrawal_method])); ?>">
                    <?php echo e(csrf_field()); ?>

                    <div class="field <?php echo e($errors->has('amount') ? 'error' : ''); ?>">
                        <label><?php echo e(__('accounting::text.amount')); ?></label>
                        <div class="ui right labeled input">
                            <input id="withdrawal-amount-input" type="number" name="amount" placeholder="<?php echo e(__('accounting::text.amount')); ?>" value="<?php echo e(old('amount', Request::get('amount'))); ?>" required autofocus>
                            <div class="ui basic <?php echo e($inverted); ?> label">
                                <?php echo e($account->currency->code); ?>

                            </div>
                        </div>
                    </div>
                    <?php if($withdrawal_method->code == 'paypal'): ?>
                        <div class="field <?php echo e($errors->has('details.email') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.email')); ?></label>
                            <input type="email" name="details[email]" placeholder="<?php echo e(__('accounting::text.email')); ?>" value="<?php echo e(old('details.email')); ?>" required autofocus>
                        </div>
                    <?php elseif($withdrawal_method->code=='wire'): ?>
                        <div class="field <?php echo e($errors->has('details.name') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.name')); ?></label>
                            <input type="text" name="details[name]" placeholder="<?php echo e(__('accounting::text.name')); ?>" value="<?php echo e(old('details.name', Auth::user()->name)); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.bank_iban') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.bank_iban')); ?></label>
                            <input type="text" name="details[bank_iban]" placeholder="<?php echo e(__('accounting::text.bank_iban')); ?>" value="<?php echo e(old('details.bank_iban')); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.bank_swift') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.bank_swift')); ?></label>
                            <input type="text" name="details[bank_swift]" placeholder="<?php echo e(__('accounting::text.bank_swift')); ?>" value="<?php echo e(old('details.bank_swift')); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.bank_name') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.bank_name')); ?></label>
                            <input type="text" name="details[bank_name]" placeholder="<?php echo e(__('accounting::text.bank_name')); ?>" value="<?php echo e(old('details.bank_name')); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.bank_branch') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.bank_branch')); ?></label>
                            <input type="text" name="details[bank_branch]" placeholder="<?php echo e(__('accounting::text.bank_branch')); ?>" value="<?php echo e(old('details.bank_branch')); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.bank_address') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.bank_address')); ?></label>
                            <input type="text" name="details[bank_address]" placeholder="<?php echo e(__('accounting::text.bank_address')); ?>" value="<?php echo e(old('details.bank_address')); ?>" required autofocus>
                        </div>
                        <div class="field <?php echo e($errors->has('details.comments') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.comments')); ?></label>
                            <textarea rows="3" name="details[comments]" placeholder="<?php echo e(__('accounting::text.comments')); ?>" autofocus>
                                <?php echo e(old('details.comments')); ?>

                            </textarea>
                        </div>
                    <?php elseif($withdrawal_method->code=='crypto'): ?>
                        <div class="field <?php echo e($errors->has('details.crypto_address') ? 'error' : ''); ?>">
                            <label><?php echo e(__('accounting::text.crypto_address')); ?></label>
                            <div class="ui action input">
                            <input type="text" name="details[crypto_address]" placeholder="<?php echo e(__('accounting::text.crypto_address')); ?>" value="<?php echo e(old('details.crypto_address')); ?>" required autofocus>
                                <select name="details[cryptocurrency]" class="ui selection search dropdown">
                                    <?php $__currentLoopData = explode(',', config('accounting.withdrawal.cryptocurrencies')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cryptocurrency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option><?php echo e($cryptocurrency); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <button class="ui large <?php echo e($settings->color); ?> submit button">
                        <?php echo e(__('accounting::text.proceed')); ?>

                        <i class="right arrow icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>