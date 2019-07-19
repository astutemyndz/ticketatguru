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
                    <li><a>Site Settings</a></li>
                </ul>
            </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
            <div class="col-sm-12 col-md-12">
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
                                <form action="<?=base_url('admin/settings')?>" class="form-horizontal form-validation" method="post">
                                    <input type="hidden" name="id" value="<?php if(isset($edit_data) && $edit_data['id']){ echo $edit_data['id']; } ?>">
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Site Name *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="site_name" class="form-control requiredCheck" value="<?php if(isset($edit_data) && $edit_data['site_name']){ echo $edit_data['site_name']; } ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-4 control-label">Age Restriction *</label>
                                        <div class="col-sm-4">
                                            <select name="age_restriction" class="form-control requiredCheck">
                                                <?php for($a=1; $a<=50; $a++){ ?>
                                                <option <?php if(isset($edit_data) && $edit_data['age_restriction'] == $a){ echo 'selected'; } ?> value="<?=$a?>"><?=$a.'+'?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Sender Name *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="site_sender_name" class="form-control requiredCheck" value="<?php if(isset($edit_data) && $edit_data['site_sender_name']){ echo $edit_data['site_sender_name']; } ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Sender Email *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="sender_email" class="form-control requiredCheck" data-check="email" value="<?php if(isset($edit_data) && $edit_data['site_sender_email']){ echo $edit_data['site_sender_email']; } ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Receiver Email *</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="site_reciver_email" class="form-control requiredCheck" data-check="email" value="<?php if(isset($edit_data) && $edit_data['site_receiver_email']){ echo $edit_data['site_receiver_email']; } ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Address</label>
                                        <div class="col-sm-5">
                                            <textarea class="form-control" name="site_address" rows="3" id="textareaMaxLength" maxlength="100"><?php if(isset($edit_data) && $edit_data['site_address']){ echo stripslashes($edit_data['site_address']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Phone 1</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="site_phone1" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_phone_1']){ echo $edit_data['site_phone_1']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Phone 2</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="site_phone2" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_phone_2']){ echo $edit_data['site_phone_2']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Google plus Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="gplus_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_gplus_link']){ echo $edit_data['site_gplus_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Facebook Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="facebook_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_facebook_link']){ echo $edit_data['site_facebook_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Twitter Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="twitter_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_twitter_link']){ echo $edit_data['site_twitter_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Linkedin Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="linkedin_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_linkedin_link']){ echo $edit_data['site_linkedin_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Instagram Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instagram_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_instagram_link']){ echo $edit_data['site_instagram_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Youtube Link</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="youtube_link" class="form-control" value="<?php if(isset($edit_data) && $edit_data['site_youtube_link']){ echo $edit_data['site_youtube_link']; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Site Title *</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="meta_title" class="form-control requiredCheck" value="<?php if(isset($edit_data) && $edit_data['site_title']){ echo stripslashes($edit_data['site_title']); } ?>">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-4 control-label">Meta Description</label>
                                        <div class="col-sm-5">
                                            <textarea class="form-control" name="meta_description" rows="4"><?php if(isset($edit_data) && $edit_data['site_meta_description']){ echo stripslashes($edit_data['site_meta_description']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
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