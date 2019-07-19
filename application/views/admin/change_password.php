<?php $this->load->view('admin/layout/header'); ?>

    <!-- side bar -->
    <?php $this->load->view('admin/layout/sidemenu'); ?>

    <!-- CONTENT -->
    <!-- ========================================================= -->
    <div class="content">
        <!-- content HEADER -->
        <!-- ========================================================= -->
        <div class="content-header">
            <div class="leftside-content-header">
                <ul class="breadcrumbs">
                    <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                    <li><a>Change Password</a></li>
                </ul>
            </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
            <div class="col-sm-12 col-md-12">
                <!--<h4 class="section-subtitle">Change Password</h4>-->
                <div class="panel">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?=base_url('admin/admin/doChangePassword')?>" return_to="" class="form-horizontal commonFormSubmitByAjax" method="post">
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Current Password *</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="old_password" class="form-control commonRequired">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">New Password *</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="new_password" class="form-control commonRequired">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Confirm New Password *</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="con_new_password" class="form-control commonRequired">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="submit" class="btn btn-wide btn-primary form-submit-button">Save Changes</button>
                                        <button type="button" onclick="location.href='<?=base_url('admin/dashboard')?>';" class="btn btn-danger">Cancel</button>
                                        <button type="button" class="status-message btn btn-wide btn-o btn-danger" style="display:none;"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->

        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    </div>

<?php $this->load->view('admin/layout/footer'); ?>