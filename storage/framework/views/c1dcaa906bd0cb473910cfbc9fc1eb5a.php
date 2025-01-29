<div>
    <!--[if BLOCK]><![endif]--><?php if($plans->count()): ?>
        <section id="signup" class="signup">

            <div class="container">

                <header class="section-header">
                    <h2>Sign Up</h2>
                    <p>Create new Account in less than 20 seconds</p>
                </header>

                <form class="php-email-form" wire:submit="signup">
                    <div class="row gy-4">
                        <div class="col-md-6 offset-md-3">

                            <div class="d-grid gap-3">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control"
                                        wire:model.live.debounce.500ms="name" placeholder="Name">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control"
                                        wire:model.live.debounce.500ms="email" placeholder="Email">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="form-group">
                                    <input type="text" name="contactNumber" class="form-control"
                                        wire:model.live.debounce.500ms="contactNumber" placeholder="Contact Number">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contactNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">https://</span>
                                        <input type="text" name="domain" class="form-control"
                                            wire:model.live.debounce.500ms="domain" placeholder="Domain">
                                        <span class="input-group-text">.<?php echo e($tld); ?></span>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="form-group">
                                    <select class="form-select" wire:model.live="planId" aria-label="Select Plan">
                                        <option selected>Select Plan</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($plan->id); ?>"><?php echo e($plan->name); ?>

                                                <?php echo e($plan->getPriceWithDuration($currency, $frequency)); ?> </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['planId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="terms"
                                            wire:model.live.debounce.500ms="terms" name="terms">
                                        <label class="custom-control-label" for="terms">I agree to the <a
                                                href="<?php echo e(route('site.terms')); ?>" target="_blank">terms </a> & <a
                                                href="<?php echo e(route('site.privacy')); ?>" target="_blank">privacy
                                                policy</a></label>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-danger helper-message"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 text-center">

                                <!--[if BLOCK]><![endif]--><?php if($response): ?>
                                    <div class="<?php echo e($error ? 'text-danger' : 'text-success'); ?> helper-message mb-2">
                                        <?php echo e($response); ?></div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <button type="submit">Sign Up</button>
                            </div>
                        </div>

                    </div>
                </form>

                <!--[if BLOCK]><![endif]--><?php if($this->showResendLink): ?>
                    <div class="mt-4">
                        <div class="row gy-4">
                            <div class="col-md-6 offset-md-3">
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('resend-activation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3789336884-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="mt-4 text-center" style="cursor: pointer;" wire:click="showResendOption">Didn't
                        get
                        activation
                        token? Resend again!</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

        </section><!-- End Signup Section -->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /Users/abdiwakb/Herd/IK_admin/resources/views/livewire/signup.blade.php ENDPATH**/ ?>