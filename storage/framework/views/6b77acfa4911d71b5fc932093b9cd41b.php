<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Connexion Farm App</title>
    <link rel="apple-touch-icon" href="<?php echo e(asset('app-assets/images/avatars/chevre.webp')); ?>>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('app-assets/images/avatars/chevre.webp')); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet ?>">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/vendors/css/vendors.min.css') ?>">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/bootstrap-extended.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/colors.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/components.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/themes/dark-layout.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/themes/bordered-layout.css') ?>">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/core/menu/menu-types/vertical-menu.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/plugins/forms/form-validation.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset('app-assets/css/pages/page-auth.css') ?>">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= asset('assets/css/style.css') ?>">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="px-2 auth-wrapper auth-v1">
                    <div class="py-2 auth-inner">
                        <!-- Login v1 -->
                        <div class="mb-0 card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <img src="<?php echo e(logo()); ?>" alt="Logo" class="mx-2" style="width: 98px !important;max-width: 120px !important; height: 80px;" >
                                </a>
                                <h4 class="mb-1 text-center card-title" style="color:black;">Bienvenue Sur Farm app 👋</h4>
                                <p class="mb-2 text-center card-text" style="color:black;">Connectez vous pour accèder à l'application</p>

                                <form class="mt-2 auth-login-form" method="POST" action="<?php echo e(route('login')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="login-email" class="text-black form-label" style="color:black;">E-mail</label>
                                        <input id="login-email" aria-describedby="login-email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" placeholder="farmapp@example.com" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between" >
                                            <label for="login-password" class="text-black" style="color:black;">Mot de Passe</label>
                                            <?php if(Route::has('password.request')): ?>
                                                <a class="text-black btn btn-link" href="<?php echo e(route('password.request')); ?>">
                                                    <small><?php echo e(__('Mot de passe Oublié?')); ?></small>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle">

                                            <input id="login-password" type="password" tabindex="2" class="form-control-merge form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password">

                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo e($message); ?></strong>
                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="input-group-append">
                                                <span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>

                                        <label class="text-black form-check-label" for="remember" style="color:black;">
                                            <?php echo e(__('Se souvenir')); ?>

                                        </label>
                                            </div>
                                    </div>
                                    <button class="text-black btn btn-secondary btn-gradient-secondary btn-block" tabindex="4"><?php echo e(__('Se Connecter')); ?></button>
                                </form>
                            </div>
                        </div>
                        <!-- /Login v1 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?= asset('app-assets/vendors/js/vendors.min.js') ?>"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') ?>"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= asset('app-assets/js/core/app-menu.js') ?>"></script>
    <script src="<?= asset('app-assets/js/core/app.js') ?>"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?= asset('app-assets/js/scripts/pages/page-auth-login.js') ?>"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
<?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/auth/login.blade.php ENDPATH**/ ?>