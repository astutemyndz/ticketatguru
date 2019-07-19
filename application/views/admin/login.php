<!doctype html>
<html lang="en" class="fixed accounts sign-in">


<!-- Mirrored from myiideveloper.com/helsinki/last-version/helsinki_green-dark/src/pages_sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2019 13:51:33 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Admin | Login</title>
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?=base_url()?>backend/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url()?>backend/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>backend/favicon/favicon-16x16.png">
    <!--BASIC css-->
    <!-- ========================================================= -->
    <link rel="stylesheet" href="<?=base_url()?>backend/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?=base_url()?>backend/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?=base_url()?>backend/vendor/animate.css/animate.css">
    <!--SECTION css-->
    <!-- ========================================================= -->
    <!--TEMPLATE css-->
    <!-- ========================================================= -->
    <link rel="stylesheet" href="<?=base_url()?>backend/stylesheets/css/style.css">
</head>

<body>
    <div class="wrap">
        <!-- page BODY -->
        <!-- ========================================================= -->
        <div class="page-body animated slideInDown">
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
            <!--LOGO-->
            <div class="logo">
                <img alt="logo" src="<?=base_url()?>assets/images/problem.png" />
            </div>
            <div class="box">
                <!--SIGN IN FORM-->
                <div class="panel mb-none">
                    <div class="panel-content bg-scale-0">

                        <?php if(validation_errors()){ ?>
                        <div class="alert alert-danger fade in">
                            <a href="#" class="close" data-dismiss="alert">×</a>
                            <?php echo validation_errors(); ?>
                        </div>
                        <?php } ?>
                        <?php if($this->session->flashdata('success_msg')){ ?>
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert">×</a>
                            <?=$this->session->flashdata('success_msg')?>
                        </div>
                        <?php } if($this->session->flashdata('error_msg')){ ?>
                        <div class="alert alert-danger fade in">
                            <a href="#" class="close" data-dismiss="alert">×</a>
                            <?=$this->session->flashdata('error_msg')?>
                        </div>
                        <?php } ?>

                        <form action="<?=base_url('admin/login')?>" id="AL_loginForm" method="post">
                            <div class="form-group mt-md">
                                <span class="input-with-icon">
                                    <input type="email" name="logUsername" class="form-control requiredCheck" id="email" placeholder="Email" value="<?php if(isset($active_username)){ echo $active_username;} ?>">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <span class="input-with-icon">
                                    <input type="password" name="logPassword" class="form-control requiredCheck" id="password" placeholder="Password">
                                    <i class="fa fa-key"></i>
                                </span>
                                <span class="help-block"></span>
                            </div>
                            <!--<div class="form-group">
                            <div class="checkbox-custom checkbox-primary">
                                <input type="checkbox" id="remember-me" value="option1" checked>
                                <label class="check" for="remember-me">Remember me</label>
                            </div>
                        </div>-->
                            <div class="form-group">
                                <input type="submit" value="Sign in" class="btn btn-primary btn-block">
                            </div>
                            <div class="form-group text-center">
                                <a href="<?=base_url('admin/forgot/password')?>">Forgot password?</a>
                                <!--<hr/>
                             <span>Don't have an account?</span>
                            <a href="pages_register.html" class="btn btn-block mt-sm">Register</a>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        </div>
    </div>
    <!--BASIC scripts-->
    <!-- ========================================================= -->
    <script src="<?=base_url()?>backend/vendor/jquery/jquery-1.12.3.min.js"></script>
    <script src="<?=base_url()?>backend/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>backend/vendor/nano-scroller/nano-scroller.js"></script>
    <!--TEMPLATE scripts-->
    <!-- ========================================================= -->
    <script src="<?=base_url()?>backend/javascripts/template-script.min.js"></script>
    <script src="<?=base_url()?>backend/javascripts/template-init.min.js"></script>
    <!-- SECTION script and examples-->
    <!-- ========================================================= -->
    <?php if ($this->session->flashdata('success_msg')) { ?>
    <script>
        $('.alert-success').delay(2000).fadeOut(300);

    </script>
    <?php } ?>
    <script>
        $(document).ready(function() {

            $("#AL_loginForm").submit(function(e) {

                //check requst product blank field
                var Status = 0;
                $('.requiredCheck').each(function() {

                    var blank_value = $.trim($(this).val());
                    var blank_attr = $(this).attr('name');

                    if (!blank_value) {

                        Status = 1;
                        $(this).parents('.form-group').addClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('The ' + blank_attr + ' field is required.');
                    } else {
                        $(this).parents('.form-group').removeClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('');
                    }
                });

                if (Status == 1) {
                    //prevent Default functionality
                    e.preventDefault();
                    return false;
                } else {
                    return true;
                }
            });

        });

    </script>
</body>


<!-- Mirrored from myiideveloper.com/helsinki/last-version/helsinki_green-dark/src/pages_sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2019 13:51:39 GMT -->

</html>
