<!doctype html>
<html lang="en" class="fixed accounts forgot-password">


<!-- Mirrored from myiideveloper.com/helsinki/last-version/helsinki_green-dark/src/pages_forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2019 13:52:18 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Admin | Forgot Password</title>
    <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>backend/favicon/apple-icon-120x120.png">
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
    <div class="page-body  animated slideInDown">
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <!--LOGO-->
        <div class="logo">
            <img alt="logo" src="<?=base_url()?>backend/images/header-logo.png" />
        </div>
        <div class="box">
            <!--FORGOT PASSWPRD FORM-->
            <div class="panel mb-none">
                <div class="panel-content bg-scale-0">
                    <form class="form-validation" method="post">
                        <h4>Forgot your password?</h4>
                        <?php if($this->session->flashdata('success_msg')){ ?>
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert">×</a>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            <?=$this->session->flashdata('success_msg')?>
                        </div>
                        <?php } ?>
                        <?php if($this->session->flashdata('error_msg')){ ?>
                        <div class="alert alert-danger fade in">
                            <a href="#" class="close" data-dismiss="alert">×</a>
                            <?=$this->session->flashdata('error_msg')?>
                        </div>
                        <?php } ?>
                        <div class="form-group mt-md">
                                <span class="input-with-icon">
                                        <input type="email" name="register_email" class="form-control requiredCheck" id="email" placeholder="Email" data-check="email">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block ">Send</button>
                        </div>
                        <div class="form-group text-center">
                            You remembered?, <a href="<?=base_url('admin')?>">Sign in!</a>
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
<script src="<?php echo base_url(); ?>backend/javascripts/common-script.js?t<?=time()?>"></script>
<script src="<?=base_url()?>backend/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>backend/vendor/nano-scroller/nano-scroller.js"></script>
<!--TEMPLATE scripts-->
<!-- ========================================================= -->
<script src="<?=base_url()?>backend/javascripts/template-script.min.js"></script>
<script src="<?=base_url()?>backend/javascripts/template-init.min.js"></script>
<!-- SECTION script and examples-->
<!-- ========================================================= -->
</body>


<!-- Mirrored from myiideveloper.com/helsinki/last-version/helsinki_green-dark/src/pages_forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2019 13:52:18 GMT -->
</html>
