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
                    <li><a><?php if($post_attr['mode'] == 'add'){ echo 'Add'; }else{ echo 'Edit'; }?> Credit Plan</a></li>
                </ul>
            </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
            <div class="col-sm-12 col-md-12">
                <?php if($this->session->flashdata('error_msg')){ ?>
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">×</a>
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    <?=$this->session->flashdata('error_msg')?>
                </div>
                <?php } ?>
                <div class="panel">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?=base_url('admin/credit/plans')?>" class="form-horizontal form-validation" method="post">
                                    <input type="hidden" name="mode" value="<?php if(isset($post_attr) && $post_attr['mode']){ echo $post_attr['mode']; } ?>">
                                    <input type="hidden" name="id" value="<?php if(isset($post_attr) && $post_attr['id']){ echo $post_attr['id']; } ?>">
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Title *</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="heading" value="<?php if(isset($edit_data) && $edit_data['title']){ echo stripslashes($edit_data['title']); } ?>" class="form-control requiredCheck">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-3 control-label">Description *</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control requiredCheck" name="description" rows="4"><?php if(isset($edit_data) && $edit_data['description']){ echo stripslashes($edit_data['description']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Credit Amount *</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="credit" value="<?php if(isset($edit_data) && $edit_data['credit']){ echo stripslashes($edit_data['credit']); } ?>" class="form-control requiredCheck" placeholder="Eg: 150" onkeypress="return isNumber(event)">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Sell Price *</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="sell_price" value="<?php if(isset($edit_data) && $edit_data['sell_price']){ echo stripslashes($edit_data['sell_price']); } ?>" class="form-control requiredCheck allowNumberDot" placeholder="Eg: 99.90">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Tag</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="taging" value="<?php if(isset($edit_data) && $edit_data['tag']){ echo stripslashes($edit_data['tag']); } ?>" class="form-control" placeholder="Eg: Best Deal">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-8">
                                            <button type="submit" class="btn btn-primary form-submit-button"><?php if($post_attr['mode'] == 'add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?></button>
                                            <button type="button" onclick="location.href='<?=base_url('admin/credit/plans')?>';" class="btn btn-danger">Cancel</button>
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
<script>
$(function () {
    
})
</script>