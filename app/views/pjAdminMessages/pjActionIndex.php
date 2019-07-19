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
	
	pjUtil::printNotice(__('infoMessagesTitle', true), __('infoMessagesDesc', true)); 
	?>
	<div class="b10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
			<input type="hidden" name="controller" value="pjAdminMessages" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<input type="submit" class="pj-button" value="<?php __('btnCreateMessage'); ?>" />
		</form>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
		<?php
		$filter = __('filter', true);
		?>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all">All</a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $filter['active']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $filter['inactive']; ?></a>
		</div>
		<br class="clear_both" />
	</div>

	<div id="grid"></div>
	
	<div id="dialogSend" title="<?php __('lblSendTestTitle'); ?>" style="display:none">
		<form id="frmSendTest" action="" method="post" class="form pj-form">
			<p><?php __('lblPromptEnterEmail');?></p>
			<p>
				<label class="title120"><?php __('lblEmail'); ?></label>
				<span class="pj-form-field-custom pj-form-field-custom-before">
					<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
					<input type="text" name="email" id="email" class="pj-form-field w280 email required" placeholder="info@domain.com" />
				</span>
			</p>
		</form>
	</div>
	<div id="dialogSendResult" title="<?php __('lblSendTestTitle'); ?>" style="display:none">
		<div class="form pj-form">
			<p id="result_message"></p>
		</div>
	</div>
	<div id="dialogDuplicate" title="<?php __('lblDuplicateTitle'); ?>" style="display:none">
		
		<p><?php __('lblDuplicateConfirm');?></p>
		
	</div>
	
	<script type="text/javascript">
		var pjGrid = pjGrid || {};
		pjGrid.roleId = <?php echo (int) $_SESSION[$controller->defaultUser]['role_id']; ?>;
		var myLabel = myLabel || {};
		myLabel.id = "<?php __('lblID'); ?>";
		myLabel.subject = "<?php __('lblSubject'); ?>";
		myLabel.total_sent = "<?php __('lblTotalSent'); ?>";
		myLabel.last_sent = "<?php __('lblLastSent'); ?>";
		myLabel.active = "<?php __('lblActive'); ?>";
		myLabel.inactive = "<?php __('lblInactive'); ?>";
		myLabel.revert_status = "<?php __('revert_status'); ?>";
		myLabel.exported = "<?php __('lblExport'); ?>";
		myLabel.delete_selected = "<?php __('pj_delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('gridActionMessage'); ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";

		myLabel.send_test = "<?php __('lblSendTest'); ?>";
		myLabel.preview = "<?php __('lblPreview'); ?>";
		myLabel.duplicate = "<?php __('lblDuplicate'); ?>";
		myLabel.send_progress = "<?php __('lblSendProgress'); ?>";
		myLabel.sent_ok = "<?php __('lblSentOK'); ?>";
		myLabel.sent_error = "<?php __('lblSentError'); ?>";
		myLabel.allowed_ext = "<?php __('lblAllowedExt'); ?>";
	</script>
	<?php
}
?>