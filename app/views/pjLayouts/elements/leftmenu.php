<?php
if (pjObject::getPlugin('pjOneAdmin') !== NULL && $controller->isAdmin())
{
	$controller->requestAction(array('controller' => 'pjOneAdmin', 'action' => 'pjActionMenu'));
}
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionIndex' ? 'menu-focus' : NULL; ?>"><span class="menu-dashboard">&nbsp;</span><?php __('menuDashboard'); ?></a></li>
		<?php
		if ($controller->isAdmin())
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminSchedule' ? 'menu-focus' : NULL; ?>"><span class="menu-schedule">&nbsp;</span><?php __('menuSchedule'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminBookings' || ($_GET['controller'] == 'pjInvoice' && $_GET['action'] != 'pjActionIndex')) ? 'menu-focus' : NULL; ?>"><span class="menu-bookings">&nbsp;</span><?php __('menuBookings'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminArtists&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminArtists' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuArtist'); ?></a></li>			
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEvents&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminEvents' ? 'menu-focus' : NULL; ?>"><span class="menu-events">&nbsp;</span><?php __('menuEvents'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVenues&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminVenues' ? 'menu-focus' : NULL; ?>"><span class="menu-venues">&nbsp;</span><?php __('menuVenues'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminImageGallery&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminImageGallery' ? 'menu-focus' : NULL; ?>"><span class="menu-venues">&nbsp;</span>Image Gallery</a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSlider&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminSlider' ? 'menu-focus' : NULL; ?>"><span class="menu-venues">&nbsp;</span><?php __('menuSlider'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSponsors&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminSponsors' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuSponsor'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminGroups&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminGroups' ? 'menu-focus' : NULL; ?>"><span class="menu-groups">&nbsp;</span><?php __('menuGroups'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSubscribers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminSubscribers' ? 'menu-focus' : NULL; ?>"><span class="menu-subscribers">&nbsp;</span><?php __('menuSubscriber'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMessages&amp;action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminMessages' && $_GET['action'] != 'pjActionSend') ? 'menu-focus' : NULL; ?>"><span class="menu-messages">&nbsp;</span><?php __('menuMessages'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCms&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminCms' ? 'menu-focus' : NULL; ?>"><span class="menu-venues">&nbsp;</span><?php __('menuCms'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminAdvertisements&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminAdvertisements' ? 'menu-focus' : NULL; ?>"><span class="menu-venues">&nbsp;</span><?php __('menuAdvertisement'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionIndex', 'pjActionBooking', 'pjActionNotification', 'pjActionBookingForm', 'pjActionTicket', 'pjActionTerm'))) || in_array($_GET['controller'], array('pjAdminLocales', 'pjBackup', 'pjLocale', 'pjSms')) || ($_GET['controller'] == 'pjInvoice' && $_GET['action'] == 'pjActionIndex') ? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCustomers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminCustomers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuCustomers'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>
			
			
			
			<?php
		}
		if ($controller->isEditor())
		{
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionProfile' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuProfile'); ?></a></li><?php
		}
		?>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogout"><span class="menu-logout">&nbsp;</span><?php __('menuLogout'); ?></a></li>
	</ul>
</div>
<div class="leftmenu-bottom"></div>