<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo "<pre>";
// print_r($this->session->userdata('defaultStore'));
// exit;
mt_srand();
$index = mt_rand(1, 9999);
$validate = str_replace(array('"', "'"), array('\"', "\'"), __('validate', true, true));


$defaultStore = ($this->session->userdata('pjTicketBooking_Store')) ? $this->session->userdata('pjTicketBooking_Store') : [];
// echo "<pre>";
// print_r($defaultStore);
// exit;
$option_arr = ($this->session->userdata('option_arr')) ? $this->session->userdata('option_arr') : [];
$layout = ($this->input->get('layout')) ? $this->input->get('layout') : $option_arr['o_theme'];

$class = 'tbAssignedNoMap';
if(isset($defaultStore['venue_arr']))
{
	if (is_file($defaultStore['venue_arr']['map_path']))
	{
		$class = 'tbAssignedSeats';
	}
} 
$ticket_name_arr = array();
$ticket_tooltip_arr = array();
if($defaultStore['ticket_arr'] && count($defaultStore['ticket_arr']) > 0)
{
	foreach($defaultStore['ticket_arr'] as $v)
	{
		$ticket_name_arr[$v['price_id']] = pjSanitize::html($v['ticket']);
		$ticket_tooltip_arr[$v['price_id']] = pjSanitize::html($v['ticket']) . ', ' .  pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);
	}
}
?>
<section class="section-select-seat-page-content">
	<div id="pjWrapperTicketBooking_theme1">
		<div id="tbContainer_<?php echo $index;?>" class="tbContainer pjCbContainer">
			<div class="container ">
				<div class="row pjCbBody">
				
					<div id="primary" class="col-md-8">
						<div class="stage-name">
							<h3><?php echo pjSanitize::html($defaultStore['arr']['title']);?></h3>
							<p><?php __('front_date')?>: <?php echo date($option_arr['o_date_format'], strtotime($defaultStore['selected_date'])); ?></p>
							<p><?php __('front_time')?>: <?php echo date($option_arr['o_time_format'], strtotime($defaultStore['selected_date'] . ' ' .$defaultStore['selected_time'])); ?></p>
							<p><?php __('front_running_time')?>: <?php echo $defaultStore['arr']['duration']?></p>
						</div>
						<?php
							if(isset($defaultStore['venue_arr'])) {
								$map = PJ_INSTALL_PATH . $defaultStore['venue_arr']['map_path'];
									if (is_file($map)) { 
										$size = getimagesize($map);
										?>
										<div class="wrapper-image">
											
												<div id="tbMapHolder_<?php echo $index;?>" class="tbMapHolder pjCbCinema">
													<div style="height: 100%;width:100%" class="panzoom">
														<!-- <img id="stadium-seat-plan"  src="<?php echo base_url();?>images/stadium2-bg.jpg" alt="stadium" usemap="#map" /> -->
														<img usemap="#map" id="tbMap_1" src="<?php echo PJ_INSTALL_URL . $defaultStore['venue_arr']['map_path']; ?>" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500;" />
														
														<map name="map" class="seatmap">
														<?php
															foreach ($defaultStore['seat_arr'] as $seat)
															{
																$is_selected = false;
																$is_available = true;
																$_arr = explode("~:~", $seat['price_id']);
																
																
																$tooltip = array();
																foreach($_arr as $pid)
																{
																	
																	if(isset($defaultStore['seat_id'][$pid][$seat['id']]))
																	{
																		//echo "is_selected= true";
																		$is_selected = true;
																		if($seat['seats'] == $defaultStore['seat_id'][$pid][$seat['id']])
																		{
																			$is_available = false;
																		}
																	} else {
																		//echo "is_selected = false";
																	}
																	$tooltip[] = $ticket_tooltip_arr[$pid];
																}
																// echo "<pre>";
																// print_r($tooltip);
																$avail_seats = $seat['seats'] - $seat['cnt_booked'];
																?>
															
															<span class="tbSeatRect<?php echo $avail_seats <= 0 ? ' tbSeatBlocked' : ($is_available == true ? ' tbSeatAvailable' : null); ?><?php echo $is_selected == true ? ' tbSeatSelected' : null;?>" data-id="<?php echo $seat['id']; ?>" data-price-id="<?php echo $seat['price_id']; ?>" data-name="<?php echo $seat['name']; ?>" data-count="<?php echo $avail_seats; ?>" style="width: <?php echo $seat['width']; ?>px; height: <?php echo $seat['height']; ?>px; left: <?php echo $seat['left']; ?>px; top: <?php echo $seat['top']; ?>px; line-height: <?php echo $seat['height']; ?>px" data-toggle="tooltip" data-placement="top" data-html="true" title="<?php echo join('<br/>', $tooltip);?>"><?php echo stripslashes($seat['name']); ?></span>
															<?php } ?>
														</map>
													</div>
												</div>
												
											
											<div id="PLKZOOMBTNWRAPPER" style="clear:both;" class="show-for-large zoom-buttons-wrapper">
													<!-- <button class="button print" alt="Print Map" title="Print Map" onclick="printGalaMap('72', '64')"><i class="fa fa-print" alt="Print Map"></i></button> -->
													<button class="button reset" alt="Reset" title="Reset"><i class="fa fa-times-circle" alt="Reset"></i></button>
													<button class="button zoom-out" alt="Zoom Out" title="Zoom Out"><i class="fa fa-minus-circle" alt="Zoom Out"></i></button>
													<button class="button zoom-in" alt="Zoom In" title="Zoom In"><i class="fa fa-plus-circle" alt="Zoom In"></i></button>
											</div>
										</div>
										
										
								<?php } else { ?>
								<?php } ?>
						<?php }?>
						<?php
			if($defaultStore['seats_available'] == true)
			{ 
				?>
				<div class="panel-footer text-center pjCbFoot">
					<form id="tbSeatsForm_<?php echo $index;?>" action="#" method="post" class="form-inline" style="display: none;">
						<?php
						if(isset($defaultStore['seat_id']))
						{
							$seat_label_arr = $defaultStore['seat_id'];
							foreach($seat_label_arr as $price_id => $seat_arr)
							{
								foreach($seat_arr as $seat_id => $cnt)
								{
									?><input class="tbHiddenSeat_<?php echo $price_id;?>" type="hidden" name="seat_id[<?php echo $price_id;?>][<?php echo $seat_id;?>]" data_seat_id="<?php echo $seat_id;?>" value="<?php echo $cnt;?>"><?php
								}
							}
						} 
						?>
					</form>
					
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<br />
							<div class="col-xs-12 tbErrorMessage pjCbSeatsMessage"></div>
					
							
							
							<button class="btn btn-default pull-right tbSelectorButton tbContinueButton pjCbBtn pjCbBtnPrimary" data-date="<?php echo date($option_arr['o_date_format'], strtotime($defaultStore['hash_date'])); ?>"><?php __('front_button_continue')?></button>
		
						</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
					</div>
					
						
				</div><!-- /.panel-footer text-center pjCbFoot -->
				<?php
			} 
			?>
						<div class="seat-label">
							<ul>
								<li><img src="<?php echo base_url();?>images/available.png" alt="available"> Available</li>
								<li><img src="<?php echo base_url();?>images/sold.png" alt="sold"> Sold Out</li>
								<li><img src="<?php echo base_url();?>images/selected.png" alt="selected"> Selected</li>
								
								
							</ul>
							



						</div>
						
						
					</div>

					<div id="secondary" class="col-md-4">
						<div class="ticket-price">
							<div class="tickets">
							<!--alert alert-info-->
							
									<div class="tbAskToSelectTickets alert alert-info" role="alert" style="display: <?php echo isset($defaultStore['tickets']) ? 'none': 'block';?>"><?php $defaultStore['seats_available'] == true ? __('front_select_ticket_types_above') : __('front_no_seats_available');?></div>
										<div style="display: <?php echo isset($defaultStore['tickets']) ? 'block': 'none';?>">
											<div class="tbSelectSeatGuide alert alert-info" role="alert"></div>
												<label for="" class="tbSelectedSeatsLabel"><?php __('front_selected_seats');?>:</label>
												<?php
												if($class == 'tbAssignedSeats')
												{ 
													?>
													<div class="tbAskToSelectSeats pjCbSeatsMessage" style="display: <?php echo isset($defaultStore['seat_id']) ? 'none': 'block';?>"><?php __('front_select_available_seats');?></div>
													<?php
												} 
												?>
												<div id="tbSelectedSeats_<?php echo $index;?>">
													<?php
													if(isset($defaultStore['seat_id']))
													{
														$seat_label_arr = $defaultStore['seat_id'];
														foreach($seat_label_arr as $price_id => $seat_arr)
														{
															foreach($seat_arr as $seat_id => $cnt)
															{
																for($i = 1; $i <= $cnt; $i++)
																{
																	?><span class="<?php echo $class;?> tbAssignedSeats_<?php echo $price_id;?>" data_seat_id="<?php echo $seat_id;?>" data_price_id="<?php echo $price_id;?>"><?php echo $ticket_name_arr[$price_id]; ?> #<?php echo $defaultStore['seat_name_arr'][$seat_id];?></span><?php
																}	
															}
														}
													} 
													?>
												</div>
												<?php
												if($class == 'tbAssignedSeats')
												{ 
													?>
													<div class="tbTipToRemoveSeats pjCbSeatsMessage" style="display: <?php echo isset($defaultStore['seat_id']) ? 'block': 'none';?>"><?php __('front_how_to_remove_seats');?><br/></div>
													<?php
												} 
												?>
										</div>
								
								<!--alert alert-info-->	
							<label>Available seats: <?php echo $defaultStore['total_remaining_avaliable_seats'];?></label>
							<?php 
								if($defaultStore['ticket_arr'] && count($defaultStore['ticket_arr']) > 0)
								{
									foreach($defaultStore['ticket_arr'] as $v)
									{
										if($v['cnt_tickets'] > 0 && $defaultStore['seats_available'] == true)
										{
											?>
												<label for=""><?php echo pjSanitize::html($v['ticket']);?>:</label>
															<?php echo pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);?>
															<select id="tbTicket_<?php echo $v['price_id'];?>" name="tickets[<?php echo $v['id'];?>][<?php echo $v['price_id'];?>]" class="selectpicker dropdown tbTicketSelector" data-id="<?php echo $v['price_id'];?>" data-ticket="<?php echo pjSanitize::html($v['ticket']);?>" data-price="<?php echo pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);?>">
																<?php
																for($i = 0; $i <= $v['cnt_tickets']; $i++)
																{
																	?><option value="<?php echo $i;?>"<?php echo isset($defaultStore['tickets'][$v['id']][$v['price_id']]) ? ($defaultStore['tickets'][$v['id']][$v['price_id']] == $i ? ' selected="selected"' : null) : null;?>><?php echo $i?></option><?php
																} 
																?>
															</select>
											<?php
										}else{
											?>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<p class="text-muted"><?php echo pjSanitize::html($v['ticket']);?>:</p>
												<p class="lead"><strong><?php __('front_na');?></strong></p>
											</div>
											<?php
										}
									}
								} 
								?>
								<!-- Choose a hall form list of hall arr -->
								<?php if(count($defaultStore['hall_arr']) > 1) {?>
									<label>Choose Hall</label>
											<select class="selectpicker dropdown pjCbSeatVenue" id="venue_id_<?php echo $defaultStore['index'];?>" name="venue_id">
											<option>choose hall</option>
												<?php
												foreach($defaultStore['hall_arr'] as $hall)
												{
													?><option value="<?php echo $hall['venue_id'];?>"<?php echo ($defaultStore['venue_id']) ? ($defaultStore['venue_id'] == $hall['venue_id'] ? ' selected="selected"' : NULL) : NULL;?>><?php echo pjSanitize::html($hall['venue_name']);?></option><?php
												} 
												?>
											</select>
								<?php  } ?>
								<!-- Choose a hall form list of hall arr -->

								
								
								
								<!-- <label>Price Range</label>
								<input id="price-range" type="text" class="span2" value="" data-slider-min="10" data-slider-max="200" data-slider-step="5" data-slider-value="[50,150]"/>  -->
							</div>
							
						</div>
					</div>

				
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
var TicketBooking_<?php echo $index; ?>;

	"use strict";
	var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),

	
	getSessionId = function () {
		return sessionStorage.getItem("session_id") == null ? "" : sessionStorage.getItem("session_id");
	},
	createSessionId = function () {
		if(getSessionId()=="") {
			sessionStorage.setItem("session_id", "<?php echo session_id(); ?>");
		}
	},
	options = {
		server: "<?php echo PJ_INSTALL_URL; ?>",
		folder: "<?php echo PJ_INSTALL_URL; ?>",
		layout: "<?php echo $layout;?>",
		index: <?php echo $index; ?>,
		hide: 0,
		locale: 1,
		week_start: "<?php echo (int) $option_arr['o_week_start']; ?>",
		date_format: "<?php echo $option_arr['o_date_format']; ?>",
		guide_msg: <?php echo pjAppController::jsonEncode(__('front_guide', true)); ?>,
		error_msg: <?php echo pjAppController::jsonEncode(__('front_err', true)); ?>,
		validate: <?php echo pjAppController::jsonEncode($validate); ?>
	};
	

</script>
