<?php
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
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	pjUtil::printNotice(__('infoGroupTitle', true), __('infoGroupBody', true));
	$subscribed_arr = __('subscribed_arr', true);
	?>
	<div class="b10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
			<input type="hidden" name="controller" value="pjAdminGroups" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<input type="submit" class="pj-button" value="<?php __('btnCreateList'); ?>" />
		</form>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
		<?php
		$filter = __('filter', true);
		?>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all"><?php __('lblAll');?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $filter['active']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $filter['inactive']; ?></a>
		</div>
		<br class="clear_both" />
	</div>

	<div id="grid"></div>
	
	<script type="text/javascript">
		var pjGrid = pjGrid || {};
		pjGrid.roleId = <?php echo (int) $_SESSION[$controller->defaultUser]['role_id']; ?>;
		var myLabel = myLabel || {};
		myLabel.group = "<?php __('lblGroup'); ?>";
		myLabel.subscribers = "<?php __('lblSubscribers'); ?>";
		myLabel.total = "<?php __('lblTotal'); ?>";
		myLabel.subscribed = "<?php echo $subscribed_arr['T']; ?>";
		myLabel.unsubscribed = "<?php echo $subscribed_arr['F']; ?>";
		myLabel.active = "<?php __('lblActive'); ?>";
		myLabel.inactive = "<?php __('lblInactive'); ?>";
		myLabel.revert_status = "<?php __('revert_status'); ?>";
		myLabel.exported = "<?php __('lblExport'); ?>";
		myLabel.delete_selected = "<?php __('pj_delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('gridActionList'); ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";
		myLabel.same_group = "<?php __('pj_same_group'); ?>";
	</script>
	<?php
}
?>