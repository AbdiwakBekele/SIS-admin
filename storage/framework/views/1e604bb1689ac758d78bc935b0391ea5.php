<div>
    <!--[if BLOCK]><![endif]--><?php if($plans->count()): ?>
        <section id="pricing" class="pricing">

            <div class="container">

                <header class="section-header">
                    <h2>Pricing</h2>
                    <p>Check our Pricing</p>
                </header>

                <div class="d-flex justify-content-between align-items-center my-4">
                    <div class="d-flex">
                        <span class="mx-2">Monthly</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" value="1" type="checkbox" role="switch" id="frequency"
                                wire:model.live="frequency">
                            <label class="form-check-label" for="frequency">Annual</label>
                        </div>
                    </div>

                    <div>
                        <select wire:model.live="defaultCurrency" class="form-select" aria-label="Select Currency">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($currency); ?>"><?php echo e($currency); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>

                <div class="row gy-4">

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="<?php echo e($columns); ?>">
                            <div class="box">
                                <!--[if BLOCK]><![endif]--><?php if($plan->getFeature('is_featured')): ?>
                                    <span class="featured">Featured</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <h3 style="color: #07d5c0;"><?php echo e($plan->name); ?> </h3>
                                <div class="price">
                                    <sup><?php echo e(Arr::get($defaultCurrencyDetail, 'symbol')); ?></sup><?php echo e($plan->getPrice($defaultCurrency, $defaultFrequency)); ?><span>
                                        / <?php echo e($defaultFrequency == 'monthly' ? 'Mo' : 'Yr'); ?></span>
                                </div>
                                <ul>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $plan->description_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($item); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </ul>
                                <!--[if BLOCK]><![endif]--><?php if(!$plan->getFeature('is_free') && Arr::get($plan->tax, 'is_enabled') && Arr::get($plan->tax, 'is_exclusive')): ?>
                                    <p class="text-sm"><?php echo e(Arr::get($plan->tax, 'label')); ?>

                                        <?php echo e(Arr::get($plan->tax, 'rate')); ?>%
                                        <?php echo e(trans('plan.props.tax_type_exclusive')); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <a href="#signup" class="btn-buy scrollto">Buy Now</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                </div>

            </div>

        </section><!-- End Pricing Section -->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /Users/abdiwakb/Herd/IK_admin/resources/views/livewire/pricing.blade.php ENDPATH**/ ?>