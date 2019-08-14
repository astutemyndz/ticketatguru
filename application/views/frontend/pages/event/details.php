<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo $selected_date ."==". $today;
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
<section class="section-full-events-schedule" id="eventDetails">
			<div class="container">
				<div class="row">
					
					<div class="section-content">
						<div class="tab-content">
						    <div role="tabpanel" class="tab-pane active" id="tab1">
								<div class="row" >
									<div class="col-md-4">
										<div class="event-img">
											<?php
												$src = 'https://placehold.it/220x320';
											if(!empty($arr['event_img']) && is_file(PJ_INSTALL_PATH . $arr['event_img']))
												{
													$src = PJ_INSTALL_URL . $arr['event_img'];
												} 
												?>
												<img src="<?php echo $src;?>" class="img-responsive" alt="Responsive image">
										</div>
									</div>
									<div class="col-md-8">
										<div class="event-title">
											<h3><?php echo pjSanitize::html($arr['title']);?></h3>
											<span><img src="<?php echo base_url(); ?>/images/ticket.png" alt="">20 Tickets Left</span>
										</div>
										<div class="clearfix"></div>
										<div class="event-info">
											<span><img src="<?php echo base_url();?>images/time-calendar.png" alt=""><?php echo $selected_date_format;?> | <?php echo $arr['duration']?></span>
											<span><img src="<?php echo base_url();?>images/placeholder.png" alt="">220 Morrissey Blvd. Boston, MA 02125</span>
										</div>
										<div class="clearfix"></div>
										<div class="about-event">
											<p><?php echo nl2br(stripslashes($arr['description']));?></p>
										</div>
										
									</div>
									<div class="col-md-12">
										<div class="event-date">
											<ul class="date-picker">
											<?php
												
												foreach($show_date_arr as $v)
												{
													?>
												<li>
												<a href="javascript:void(0);" class="ticket-left-info pjCbDaysNav <?php echo ($selected_date == $v) ? 'active' : '';?>" data-date="<?php echo $v;?>" data-event_id="<?php echo $arr['id'];?>">
												<?php 
													
												?>
														<span class="day">
															<?php echo date("l",strtotime($v));;?>
														</span>
														<span class="date">
														<?php echo date("jS",strtotime($v));;?>
														</span>
														<span class="mmyy">
														<?php echo date("M, Y",strtotime($v));;?>
														</span>
													</a>
												</li>
												<?php
												} 
												?>
											</ul>
											<?php /*?><?php
												
											foreach($show_date_arr as $v)
											{
												?>
												<a href="javascript:void(0);" class="ticket-left-info pjCbDaysNav <?php echo ($selected_date == $v) ? 'active' : '';?>" data-date="<?php echo $v;?>" data-event_id="<?php echo $arr['id'];?>"><?php echo $v; ?></a>
												
												<?php
											} 
											?><?php */?>
											<div class="timesSection"></div>
										</div>
									</div>
									
									
									<!----------------------------------------------------------------------------------------------->
																	
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>
		<script>
			$("document").ready(function() {
				$('.date-picker').slick({
				  slidesToShow: 7,
				  slidesToScroll: 1,
				  autoplay: false,
				  autoplaySpeed: 2000,
				});

			});
		</script>