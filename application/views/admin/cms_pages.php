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
                <!--<h4 class="section-subtitle">Manage CMS</h4>-->
                <?php if($this->session->flashdata('success_msg')){ ?>
                <div class="alert alert-success fade in">
                    <a href="#" class="close" data-dismiss="alert">×</a>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    <?=$this->session->flashdata('success_msg')?>
                </div>
                <?php } ?>
                <div class="panel">
                    <div class="panel-content">
                        <div class="table-responsive">
                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Page name</th>
                                        <th>Page title</th>
                                        <th>Last updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php
                                    if ($data_list) {
                                        $i = 1;
                                        foreach ($data_list as $dis_list) {
                                        ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=stripslashes($dis_list['page_name'])?></td>
                                        <td><?=stripslashes($dis_list['page_title'])?></td>
                                        <td><?=date('d M, Y H:i', strtotime($dis_list['updated_at']))?></td>
                                        <td><a href="<?=base_url('admin/settings/edit_cms?mode=edit&id='.$dis_list['id'])?>" title="edit"><i class="fa fa-fw fa-edit"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->

        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    </div>
    

<?php $this->load->view('admin/layout/footer'); ?>
<script src="<?=base_url()?>backend/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>backend/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
<!--Examples-->
<script src="<?=base_url()?>backend/javascripts/examples/tables/data-tables.js"></script>