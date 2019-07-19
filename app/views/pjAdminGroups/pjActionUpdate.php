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
	
	pjUtil::printNotice(__('infoUpdateGroupTitle', true), __('infoUpdateGroupBody', true)); 
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminGroups&amp;action=pjActionUpdate" method="post" id="frmUpdateGroup" class="form pj-form">
		<input type="hidden" name="group_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php __('lblGroupDetails'); ?></a></li>
				<li><a href="#tabs-2"><?php __('lblSubscriberData'); ?></a></li>
				<li><a href="#tabs-3"><?php __('lblConfirmation'); ?></a></li>
			</ul>
			<div id="tabs-1">
				<p>
					<label class="title"><?php __('lblDashTotalSubscribers'); ?></label>
					<label class="content float_left r30">
						<?php
						if($tpl['arr']['total'] > 0)
						{ 
							?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSubscribers&amp;action=pjActionIndex&group_id=<?php echo $tpl['arr']['id'];?>"><?php echo $tpl['arr']['total'];?></a><?php
						}else{
							echo $tpl['arr']['total'];
						} 
						?>
					</label>
					<label class="content float_left">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSubscribers&action=pjActionImport&group_id=<?php echo $tpl['arr']['id'];?>"><?php __('lblDashImportSubscribers');?></a> / <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&action=pjActionForm&group_id=<?php echo $tpl['arr']['id'];?>"><?php __('lblSubscriptionForm');?></a>
					</label>
				</p>
				<p>
					<label class="title"><?php __('lblDashSubscribed'); ?></label>
					<label class="content">
						<?php
						if($tpl['arr']['subscribed'] > 0)
						{ 
							?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSubscribers&amp;action=pjActionIndex&group_id=<?php echo $tpl['arr']['id'];?>&subscribed=T"><?php echo $tpl['arr']['subscribed'];?></a><?php
						}else{
							echo $tpl['arr']['subscribed'];
						} 
						?>
					</label>
				</p>
				<p>
					<label class="title"><?php __('lblDashUnsubscribed'); ?></label>
					<label class="content">
						<?php
						if($tpl['arr']['unsubscribed'] > 0)
						{ 
							?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSubscribers&amp;action=pjActionIndex&group_id=<?php echo $tpl['arr']['id'];?>&subscribed=F"><?php echo $tpl['arr']['unsubscribed'];?></a><?php
						}else{
							echo $tpl['arr']['unsubscribed'];
						} 
						?>
					</label>
				</p>
				<p>
					<label class="title"><?php __('lblGroup'); ?></label>
					<span class="inline_block">
						<input type="text" name="group_title" id="group_title" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['group_title'])); ?>" class="pj-form-field w300 required" />
					</span>
				</p>
				
				<p>
					<label class="title"><?php __('lblStatus'); ?></label>
					<span class="inline_block">
						<select name="status" id="status" class="pj-form-field required">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach (__('u_statarr', true) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['status'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>admin.php?controller=pjAdminGroups&action=pjActionIndex';" />
				</p>
			</div><!-- tabs-1 -->
			<div id="tabs-2" class="subscriber-data-container">
				<?php
				pjUtil::printNotice(__('infoSubscriberDataTitle', true), __('infoSubscriberDataBody', true)); 
				$subscriber_fields = explode(",", $tpl['arr']['subscribed_fields']); 
				?>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('first_name', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					<input type="checkbox" name="data[]" id="first_name" value="first_name" <?php echo in_array('first_name', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="first_name" class="data-label"><?php __('lblFirstName');?></label>
				</div>
				
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('last_name', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="last_name" value="last_name" <?php echo in_array('last_name', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="last_name" class="data-label"><?php __('lblLastName');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('phone', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="phone" value="phone" <?php echo in_array('phone', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="phone" class="data-label"><?php __('lblPhone');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('website', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="website" value="website" <?php echo in_array('website', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="website" class="data-label"><?php __('lblWebsite');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('gender', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="gender" value="gender" <?php echo in_array('gender', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="gender" class="data-label"><?php __('lblGender');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('age', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="age" value="age" <?php echo in_array('age', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="age" class="data-label"><?php __('lblAge');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('birthday', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="birthday" value="birthday" <?php echo in_array('birthday', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="birthday" class="data-label"><?php __('lblBirthday');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('address', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="address" value="address" <?php echo in_array('address', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="address" class="data-label"><?php __('lblAddress');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('city', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="city" value="city" <?php echo in_array('city', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="city" class="data-label"><?php __('lblCity');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('state', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="state" value="state" <?php echo in_array('state', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="state" class="data-label"><?php __('lblState');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('country', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="country" value="country" <?php echo in_array('country', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="country" class="data-label"><?php __('lblCountry');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('zip', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="zip" value="zip" <?php echo in_array('zip', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="zip" class="data-label"><?php __('lblZip');?></label>
					
				</div>
				<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array('company_name', $subscriber_fields) ? ' pj-checkbox-checked' : NULL; ?>">
					
					<input type="checkbox" name="data[]" id="company_name" value="company_name" <?php echo in_array('company_name', $subscriber_fields) ? 'checked="checked"' : null;?> class="subscriber-data"/>
					<label for="company_name" class="data-label"><?php __('lblCompanyName');?></label>
					
				</div>
				<div class="clear_both"></div>
				<div class="overflow">
					<input type="hidden" id="subscribed_fields" name="subscribed_fields" value="<?php echo $tpl['arr']['subscribed_fields'];?>" class="required"/>
				</div>
				<p>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>admin.php?controller=pjAdminGroups&action=pjActionIndex';" />
				</p>
			</div><!-- tabs-2 -->
			<div id="tabs-3">
				<?php
				pjUtil::printNotice(__('infoSendConfirmTitle', true), __('infoSendConfirmBody', true) . '<br/><br/>' . __('lblMessageTokens', true), false); 
				?>
				<fieldset class="overflow b10">
					<p>
						<label class="title"><?php __('lblSendConfirmMessage'); ?></label>
						<span class="inline_block">
							<label class="content"><input type="checkbox" name="send_confirm" id="send_confirm" value="T" <?php echo $tpl['arr']['send_confirm'] == 'T' ? 'checked="checked"' : null;?> /></label>
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblSubject'); ?></label>
						<span class="inline_block">
							<input type="text" name="confirm_subject" id="confirm_subject" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['confirm_subject'])); ?>" class="pj-form-field w400" />
						</span>
					</p>
					<p>
						<label class="title">
							<?php __('lblMessage'); ?>
						</label>
						<span class="inline_block">
							<textarea id="confirm_message" name="confirm_message" class="pj-form-field textarea h300 mceEditor"><?php echo stripslashes($tpl['arr']['confirm_message']); ?></textarea>
						</span>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
						<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>admin.php?controller=pjAdminGroups&action=pjActionIndex';" />
					</p>
				</fieldset>
				<?php
				pjUtil::printNotice(__('infoSendResponseTitle', true), __('infoSendResponseBody', true)); 
				?>
				<fieldset class="overflow b10">
					<p>
						<label class="title"><?php __('lblSendAutoResponse'); ?></label>
						<span class="inline_block">
							<label class="content"><input type="checkbox" name="send_response" id="send_response" value="T" <?php echo $tpl['arr']['send_response'] == 'T' ? 'checked="checked"' : null;?>/></label>
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblSubject'); ?></label>
						<span class="inline_block">
							<input type="text" name="response_subject" id="response_subject" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['response_subject'])); ?>" class="pj-form-field w400" />
						</span>
					</p>
					<p>
						<label class="title">
							<?php __('lblMessage'); ?>
						</label>
						<span class="inline_block">
							<textarea id="response_message" name="response_message" class="pj-form-field textarea h300 mceEditor"><?php echo stripslashes($tpl['arr']['response_message']); ?></textarea>
						</span>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
						<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>admin.php?controller=pjAdminGroups&action=pjActionIndex';" />
					</p>
				</fieldset>
			</div><!-- tabs-3 -->
		</div>
		
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.same_group = "<?php echo __('pj_same_group', true); ?>";
	myLabel.select_field = "<?php echo __('lblSelectField', true); ?>";
	myLabel.install_url = "<?php echo PJ_INSTALL_URL; ?>";
	</script>
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{
		$tab_id = explode("-", $_GET['tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "selected", <?php echo $tab_id; ?>);
			});
		})(jQuery_1_8_2);
		</script>
		<?php
	}
}
?>