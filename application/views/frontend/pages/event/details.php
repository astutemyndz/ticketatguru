<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
// $week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
// $jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
// $months = __('months', true);
// $short_months = __('short_months', true);
// ksort($months);
// ksort($short_months);
// $days = __('days', true);
// $short_days = __('short_days', true);
// $STORE = @$_SESSION[$controller->defaultStore];
?>
<!-- <section class="section-featured-header order-tickets-without-seat">
			<div class="container">
				<div class="section-content">
					<p>Neal S Blaisdell Arena <strong>MAROON 5 LIVE</strong> <span>Friday, November 4 2016 | 8:00 PM</span></p>
					<div class="tickets-left">
						<i class="fa fa-info-circle" aria-hidden="true"></i> 82 tickets left
					</div>
				</div>
			</div>
		</section> -->
<section class="section-full-events-schedule">
			<div class="container">
				<div class="row">
					
					<div class="section-content">
						<div class="tab-content">
						    <div role="tabpanel" class="tab-pane active" id="tab1">
								<div class="row">
									
									<div class="col-sm-12 col-md-12">
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane active" id="tab1-hr1">
												
												
												<?php
												$src = 'https://placehold.it/220x320';
												if(!empty($tpl['arr']['event_img']) && is_file(PJ_INSTALL_PATH . $tpl['arr']['event_img']))
												{
													$src = PJ_INSTALL_URL . $tpl['arr']['event_img'];
												} 
												?>
												<img src="<?php echo $src;?>" class="img-responsive" alt="Responsive image">
												<div class="full-event-info">
													<div class="full-event-info-header">
														<h2><?php echo pjSanitize::html($tpl['arr']['title']);?></h2>
														<!-- <span class="ticket-left-info">18 Tickets Left</span> -->
														<div class="clearfix"></div>
														<span class="event-date-info"><?php echo $tpl['selected_date_format'];?> 
														<?php
												/*
												foreach($tpl['time_arr'] as $v)
												{
													?>
													<?php echo date($tpl['option_arr']['o_time_format'], strtotime($tpl['selected_date'] . ' ' . $v)); ?>
													
													<?php
												} */
												?> | <?php echo $tpl['arr']['duration']?></span>
														<span class="event-venue-info">220 Morrissey Blvd. Boston, MA 02125</span>
													</div>
													<div class="full-event-info-content">
														<p><?php echo nl2br(stripslashes($tpl['arr']['description']));?></p>
														<!-- <a class="book-ticket" href="#">Book Ticket</a> -->
														
													</div>
													<?php
												
											foreach($tpl['show_date_arr'] as $v)
											{
												?>
												<a href="javascript:void(0);" class="ticket-left-info pjCbDaysNav" data-date="<?php echo $v;?>" data-event_id="<?php echo $tpl['arr']['id'];?>"><?php echo $v; ?></a>
												
												<?php
											} 
											?>
											<div class="timesSection"></div>
											
													
													
													
													
												</div>
											</div>
											
										</div>
										
									</div>									
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>
		