<?php
// echo "<pre>";
// print_r($tpl);
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	
	pjUtil::printNotice(__('infoAddUserTitle', true), __('infoAddUserDesc', true));
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRoleAcl&amp;action=pjActionCreate" method="post" id="frmCreateRoleAcl" class="form pj-form" autocomplete="off">
		<input type="hidden" name="role_acl" value="1" />
		<p>
			<label class="title"><?php __('lblRole'); ?></label>
			<span class="inline_block">
				<select name="role_id" id="role_id" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach ($tpl['role_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['role']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<div id="privileges_configuration" class="form-group">
                        <label>Privileges Configuration</label>
                                                <table class="table table-striped table-hover table-bordered">
                            <thead>
                            <tr class="active">
                                <th width="3%">No.</th>
                                <th width="60%">Module's Name</th>
                                <th>&nbsp;</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Read</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            <tr class="info">
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <td align="center"><input title="Check all vertical" type="checkbox" id="is_visible"></td>
                                <td align="center"><input title="Check all vertical" type="checkbox" id="is_create"></td>
                                <td align="center"><input title="Check all vertical" type="checkbox" id="is_read"></td>
                                <td align="center"><input title="Check all vertical" type="checkbox" id="is_edit"></td>
                                <td align="center"><input title="Check all vertical" type="checkbox" id="is_delete"></td>
                            </tr>
                            </thead>
                            <tbody>
								<?php 
								$no = 1; 
								foreach($tpl['modules'] as $module) {
								?>
									<?php
									if(isset($_GET['id'])) {
										$acls = pjRoleAclModel::factory()
												->where('id_tk_cbs_modules', $module['id'])->where('id_tk_cbs_roles', $_GET['id'])->getData();
									}
									?>
									
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $module['name'];?></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="info" align="center"><input type="checkbox" title="Check All Horizontal" class="select_horizontal" ></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="active" align="center"><input type="checkbox" class="is_visible" name="privileges[<?php echo $module['id'];?>][is_visible]" value="1" ></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="warning" align="center"><input type="checkbox" class="is_create" name="privileges[<?php echo $module['id'];?>][is_create]" value="1"></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="info" align="center"><input type="checkbox" class="is_read" name="privileges[<?php echo $module['id'];?>][is_read]" value="1"></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="success" align="center"><input type="checkbox" class="is_edit" name="privileges[<?php echo $module['id'];?>][is_edit]" value="1"></td>
                                    <td <?php ($acls['is_create'] && $acls['is_read'] && $acls['is_edit'] && $acls['is_delete']) ? "checked" : "" ?> class="danger" align="center"><input type="checkbox" class="is_delete" name="privileges[<?php echo $module['id'];?>][is_delete]" value="1"></td>
								</tr>
								<?php $no++; } ?>
                                                                                            
                                                        </tbody>
                        </table>


                    </div>
		
		
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
			<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>admin.php?controller=pjAdminRoleAcl&action=pjActionIndex';" />
		</p>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	//myLabel.email_taken = "<?php __('pj_email_taken', false, true); ?>";
	
	</script>
	<script>
                                $(function () {
                                    $("#is_visible").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_visible").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_create").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_create").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_read").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_read").prop("checked", is_ch);
                                    })
                                    $("#is_edit").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_edit").prop("checked", is_ch);
                                    })
                                    $("#is_delete").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_delete").prop("checked", is_ch);
                                    })
                                    $(".select_horizontal").click(function () {
                                        var p = $(this).parents('tr');
                                        var is_ch = $(this).is(':checked');
                                        p.find("input[type=checkbox]").prop("checked", is_ch);
                                    })
                                })
                            </script>
	<?php
}
?>