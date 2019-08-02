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
                    <li><a>Edit Profile</a></li>
                </ul>
            </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
            <div class="col-sm-12 col-md-12">
                <!--<h4 class="section-subtitle">Change Password</h4>-->
                <?php if($this->session->flashdata('success_msg')){ ?>
                <div class="alert alert-success fade in">
                    <a href="#" class="close" data-dismiss="alert">×</a>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    <?=$this->session->flashdata('success_msg')?>
                </div>
                <?php } ?>
                <div class="panel">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?=base_url('admin/profile')?>" class="form-horizontal form-validation" method="post">
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Name *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control requiredCheck" value="<?php if(isset($edit_data)){ echo $edit_data['name'];} ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Email *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="email" class="form-control requiredCheck" value="<?php if(isset($edit_data)){ echo $edit_data['email'];} ?>" data-check="email">
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