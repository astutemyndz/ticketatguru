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
                    <li><a><?php if($post_attr['mode'] == 'add'){ echo 'Add'; }else{ echo 'Edit'; }?> Service</a></li>
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
                                <form action="<?=base_url('admin/services/save_service_data')?>" return_to="<?=base_url('admin/services')?>" class="form-horizontal commonFormSubmitByAjax" method="post">
                                    <input type="hidden" name="mode" value="<?php if($post_attr['mode']){ echo $post_attr['mode']; }?>"/> 
                                    <input type="hidden" name="id" value="<?php if($post_attr['id']){ echo $post_attr['id']; }else{ echo '';}?>"/>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Service Name *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="servicename" class="form-control commonRequired" value="<?php if(isset($edit_data)){ echo $edit_data['name'];} ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="submit" class="btn btn-wide btn-primary form-submit-button"><?php if($post_attr['mode'] == 'add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?> </button>
                                        <button type="button" onclick="location.href='<?=base_url('admin/services')?>';" class="btn btn-danger">Cancel</button>
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