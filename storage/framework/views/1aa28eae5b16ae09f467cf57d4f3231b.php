<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo e(config('config.general.meta_description')); ?>">
    <meta name="keywords" content="<?php echo e(config('config.general.meta_keywords')); ?>">
    <meta name="author" content="<?php echo e(config('config.general.meta_author')); ?>">
    <title><?php echo e(config('config.general.app_name', config('app.name', 'ScriptMint'))); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <link rel="icon" href="<?php echo e(config('config.assets.favicon')); ?>" type="image/png">

    <!-- Favicons -->
    <link href="<?php echo e(config('config.assets.favicon')); ?>" rel="icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/site/vendor/aos/aos.css" rel="stylesheet">
    <link href="/site/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/site/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/site/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/site/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/site/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/site/css/style.css" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/site.js'], 'site/build'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <!-- =======================================================
  * Template Name: FlexStart - v1.9.0
  * Template URL: https://bootstrapmade.com/flexstart-bootstrap-startup-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="theme-<?php echo e(config('config.system.theme')); ?>">

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('cookie-consent');

$__html = app('livewire')->mount($__name, $__params, 'lw-4032635318-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <?php if (isset($component)) { $__componentOriginale7973a0cd111432859375f720ac31db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7973a0cd111432859375f720ac31db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7973a0cd111432859375f720ac31db5)): ?>
<?php $attributes = $__attributesOriginale7973a0cd111432859375f720ac31db5; ?>
<?php unset($__attributesOriginale7973a0cd111432859375f720ac31db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7973a0cd111432859375f720ac31db5)): ?>
<?php $component = $__componentOriginale7973a0cd111432859375f720ac31db5; ?>
<?php unset($__componentOriginale7973a0cd111432859375f720ac31db5); ?>
<?php endif; ?>

    <?php echo e($slot); ?>


    <?php if (isset($component)) { $__componentOriginal21120ef38d90a9d572330a5268a23b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21120ef38d90a9d572330a5268a23b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21120ef38d90a9d572330a5268a23b04)): ?>
<?php $attributes = $__attributesOriginal21120ef38d90a9d572330a5268a23b04; ?>
<?php unset($__attributesOriginal21120ef38d90a9d572330a5268a23b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21120ef38d90a9d572330a5268a23b04)): ?>
<?php $component = $__componentOriginal21120ef38d90a9d572330a5268a23b04; ?>
<?php unset($__componentOriginal21120ef38d90a9d572330a5268a23b04); ?>
<?php endif; ?>

    <!-- Vendor JS Files -->
    <script src="/site/vendor/purecounter/purecounter.js"></script>
    <script src="/site/vendor/aos/aos.js"></script>
    <script src="/site/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/site/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/site/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/site/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="/site/js/main.js"></script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scriptConfig(); ?>

</body>

</html>
<?php /**PATH /Users/abdiwakb/Herd/IK_admin/resources/views/components/site/layout.blade.php ENDPATH**/ ?>