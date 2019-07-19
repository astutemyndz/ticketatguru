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
                    <li><a>Manage CMS</a></li>
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
                                <form action="<?=base_url('admin/settings/cms')?>" class="form-horizontal form-validation" method="post">
                                    <input type="hidden" name="mode" value="<?php if(isset($post_attr) && $post_attr['mode']){ echo $post_attr['mode']; } ?>">
                                    <input type="hidden" name="id" value="<?php if(isset($post_attr) && $post_attr['id']){ echo $post_attr['id']; } ?>">
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Site Name *</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control requiredCheck" value="<?php if(isset($edit_data) && $edit_data['page_name']){ echo $edit_data['page_name']; } ?>" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Site Title *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="page_title" value="<?php if(isset($edit_data) && $edit_data['page_title']){ echo stripslashes($edit_data['page_title']); } ?>" class="form-control requiredCheck">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-3 control-label">Page Content *</label>
                                        <div class="col-sm-8">
                                            <textarea id="editor1" class="form-control" name="page_content" rows="4"><?php if(isset($edit_data) && $edit_data['page_content']){ echo stripslashes($edit_data['page_content']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-3 control-label">Meta Description</label>
                                        <div class="col-sm-8">
                                            <textarea id="editor2" class="form-control" name="meta_description" rows="4"><?php if(isset($edit_data) && $edit_data['page_meta_content']){ echo stripslashes($edit_data['page_meta_content']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-8">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                            <button type="button" onclick="location.href='<?=base_url('admin/settings/cms')?>';" class="btn btn-danger">Cancel</button>
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
<script src="<?=base_url()?>backend/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')
      CKEDITOR.replace('editor2')
  })
</script>