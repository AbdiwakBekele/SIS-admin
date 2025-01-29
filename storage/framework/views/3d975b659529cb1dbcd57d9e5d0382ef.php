<div class="footer-newsletter">
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
            <h4>Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
        </div>
        <div class="col-lg-6">
            <form wire:submit="subscribe">
                <input type="email" name="email" wire:model.live.debounce.500ms="email"><input type="submit" value="Subscribe">
            </form>
            <!--[if BLOCK]><![endif]--><?php if($message): ?>
                <p class="<?php echo e($error ? 'text-danger' : 'text-success'); ?> helper-message"><?php echo e($message); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger helper-message"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/abdiwakb/Herd/IK_admin/resources/views/livewire/newsletter.blade.php ENDPATH**/ ?>