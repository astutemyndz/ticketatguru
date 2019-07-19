<?php
defined('BASEPATH') OR exit('No direct script access allowed');

mt_srand();
$index = mt_rand(1, 9999);
$validate = str_replace(array('"', "'"), array('\"', "\'"), __('validate', true, true));


$pjActionSeatsAjaxResponse = ($this->session->userdata('pjActionSeatsAjaxResponse')) ? $this->session->userdata('pjActionSeatsAjaxResponse') : [];
$option_arr = ($pjActionSeatsAjaxResponse['tpl']['option_arr']) ? $pjActionSeatsAjaxResponse['tpl']['option_arr'] : [];
$layout = ($this->input->get('layout')) ? $this->input->get('layout') : $option_arr['o_theme'];

$class = 'tbAssignedNoMap';
if(isset($pjActionSeatsAjaxResponse['venue_arr']))
{
	if (is_file($pjActionSeatsAjaxResponse['venue_arr']['map_path']))
	{
		$class = 'tbAssignedSeats';
	}
} 
$ticket_name_arr = array();
$ticket_tooltip_arr = array();
if($pjActionSeatsAjaxResponse['ticket_arr'] && count($pjActionSeatsAjaxResponse['ticket_arr']) > 0)
{
	foreach($pjActionSeatsAjaxResponse['ticket_arr'] as $v)
	{
		$ticket_name_arr[$v['price_id']] = pjSanitize::html($v['ticket']);
		$ticket_tooltip_arr[$v['price_id']] = pjSanitize::html($v['ticket']) . ', ' .  pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);
	}
}
?>
<section class="section-select-seat-page-content">
	<div id="pjWrapperTicketBooking_theme1">
		<div id="tbContainer_1" class="tbContainer pjCbContainer">
			<div class="container ">
				<div class="row">
				
					<div id="primary" class="col-md-8">
						<div class="stage-name">
							<h3><?php echo pjSanitize::html($pjActionSeatsAjaxResponse['arr']['title']);?></h3>
							<p><?php __('front_date')?>: <?php echo date($option_arr['o_date_format'], strtotime($pjActionSeatsAjaxResponse['selected_date'])); ?></p>
							<p>Time: <?php echo date($option_arr['o_time_format'], strtotime($pjActionSeatsAjaxResponse['selected_date'] . ' ' .$pjActionSeatsAjaxResponse['selected_time'])); ?></p>
							<p>Event Duration: <?php echo $pjActionSeatsAjaxResponse['arr']['duration']?></p>
						</div>
						<?php
				if(isset($pjActionSeatsAjaxResponse['venue_arr']))
				{
					$map = PJ_INSTALL_PATH . $pjActionSeatsAjaxResponse['venue_arr']['map_path'];
					if (is_file($map))
					{ 
						$size = getimagesize($map);
						?>
						<div class="wrapper-image">
							<div id="tbMapHolder_1" class="tbMapHolder pjCbCinema" style="height: <?php echo $size[1];?>px;">
								<div style="height: <?php echo $size[1];?>px;width:<?php echo $size[0];?>px;margin-left: 0px;margin:0 auto;position: relative;">
									<!-- <img id="stadium-seat-plan"  src="<?php echo base_url();?>images/stadium2-bg.jpg" alt="stadium" usemap="#map" /> -->
									<img usemap="#map" id="tbMap_1" src="<?php echo PJ_INSTALL_URL . $pjActionSeatsAjaxResponse['venue_arr']['map_path']; ?>" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500;" />
									
									<map name="map" class="seatmap">
									<?php
										foreach ($pjActionSeatsAjaxResponse['seat_arr'] as $seat)
										{
											$is_selected = false;
											$is_available = true;
											$_arr = explode("~:~", $seat['price_id']);
											
											
											$tooltip = array();
											foreach($_arr as $pid)
											{
												
												if(isset($pjActionSeatsAjaxResponse['seat_id'][$pid][$seat['id']]))
												{
													//echo "is_selected= true";
													$is_selected = true;
													if($seat['seats'] == $pjActionSeatsAjaxResponse['seat_id'][$pid][$seat['id']])
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
						</div>
					<?php } else { ?>
					<?php }?>
					<?php }?>
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
							<label>Available seats: <?php echo $pjActionSeatsAjaxResponse['total_remaining_avaliable_seats'];?></label>
							<?php 
								if($pjActionSeatsAjaxResponse['ticket_arr'] && count($pjActionSeatsAjaxResponse['ticket_arr']) > 0)
								{
									foreach($pjActionSeatsAjaxResponse['ticket_arr'] as $v)
									{
										if($v['cnt_tickets'] > 0 && $pjActionSeatsAjaxResponse['seats_available'] == true)
										{
											?>
												<label for=""><?php echo pjSanitize::html($v['ticket']);?>:</label>
															<?php echo pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);?>
															<select id="tbTicket_<?php echo $v['price_id'];?>" name="tickets[<?php echo $v['id'];?>][<?php echo $v['price_id'];?>]" class="selectpicker dropdown tbTicketSelector" data-id="<?php echo $v['price_id'];?>" data-ticket="<?php echo pjSanitize::html($v['ticket']);?>" data-price="<?php echo pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);?>">
																<?php
																for($i = 0; $i <= $v['cnt_tickets']; $i++)
																{
																	?><option value="<?php echo $i;?>"<?php echo isset($pjActionSeatsAjaxResponse['tickets'][$v['id']][$v['price_id']]) ? ($pjActionSeatsAjaxResponse['tickets'][$v['id']][$v['price_id']] == $i ? ' selected="selected"' : null) : null;?>><?php echo $i?></option><?php
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
								<?php if(count($pjActionSeatsAjaxResponse['hall_arr']) > 1) {?>
									<label>Choose Hall</label>
											<select class="selectpicker dropdown pjCbSeatVenue" id="venue_id_<?php echo $pjActionSeatsAjaxResponse['index'];?>" name="venue_id">
											<option>choose hall</option>
												<?php
												foreach($pjActionSeatsAjaxResponse['hall_arr'] as $hall)
												{
													?><option value="<?php echo $hall['venue_id'];?>"<?php echo ($pjActionSeatsAjaxResponse['venue_id']) ? ($pjActionSeatsAjaxResponse['venue_id'] == $hall['venue_id'] ? ' selected="selected"' : NULL) : NULL;?>><?php echo pjSanitize::html($hall['venue_name']);?></option><?php
												} 
												?>
											</select>
								<?php  } ?>
								<!-- Choose a hall form list of hall arr -->

								
								<!--alert alert-info-->
								<div class="col-xs-12">
									<div class="tbAskToSelectTickets alert alert-info" role="alert" style="display: <?php echo isset($pjActionSeatsAjaxResponse['tickets']) ? 'none': 'block';?>"><?php $pjActionSeatsAjaxResponse['seats_available'] == true ? __('front_select_ticket_types_above') : __('front_no_seats_available');?></div>
										<div style="display: <?php echo isset($pjActionSeatsAjaxResponse['tickets']) ? 'block': 'none';?>">
											<div class="tbSelectSeatGuide alert alert-info" role="alert"></div>
												<label for="" class="tbSelectedSeatsLabel"><?php __('front_selected_seats');?>:</label>
												<?php
												if($class == 'tbAssignedSeats')
												{ 
													?>
													<div class="tbAskToSelectSeats pjCbSeatsMessage" style="display: <?php echo isset($pjActionSeatsAjaxResponse['seat_id']) ? 'none': 'block';?>"><?php __('front_select_available_seats');?></div>
													<?php
												} 
												?>
												<div id="tbSelectedSeats_<?php echo $_GET['index'];?>">
													<?php
													if(isset($pjActionSeatsAjaxResponse['seat_id']))
													{
														$seat_label_arr = $pjActionSeatsAjaxResponse['seat_id'];
														foreach($seat_label_arr as $price_id => $seat_arr)
														{
															foreach($seat_arr as $seat_id => $cnt)
															{
																for($i = 1; $i <= $cnt; $i++)
																{
																	?><span class="<?php echo $class;?> tbAssignedSeats_<?php echo $price_id;?>" data_seat_id="<?php echo $seat_id;?>" data_price_id="<?php echo $price_id;?>"><?php echo $ticket_name_arr[$price_id]; ?> #<?php echo $pjActionSeatsAjaxResponse['seat_name_arr'][$seat_id];?></span><?php
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
													<div class="tbTipToRemoveSeats pjCbSeatsMessage" style="display: <?php echo isset($pjActionSeatsAjaxResponse['seat_id']) ? 'block': 'none';?>"><?php __('front_how_to_remove_seats');?><br/></div>
													<?php
												} 
												?>
										</div>
								</div>
								<!--alert alert-info-->
								
								<!-- <label>Price Range</label>
								<input id="price-range" type="text" class="span2" value="" data-slider-min="10" data-slider-max="200" data-slider-step="5" data-slider-value="[50,150]"/>  -->
							</div>
							<!-- <table class="table table-hover">
								<thead>
									<tr>
										<th>Section</th>
										<th>Row</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									<tr class="select-seat">
										<td>A3-Middle <span>2 Tickets left</span></td>
										<td>5</td>
										<td>$65 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>C1-Left <span>4 Tickets left</span></td>
										<td>4</td>
										<td>$67 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>C2-Left <span>14 Tickets left</span></td>
										<td>2</td>
										<td>$76 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>C5-Right <span>1 Ticket left</span></td>
										<td>5</td>
										<td>$58 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>C6-Right <span>1 Ticket left</span></td>
										<td>5</td>
										<td>$59 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>B1-Left <span>10 Ticket left</span></td>
										<td>1</td>
										<td>$58 <span>Per seat</span></td>
									</tr>
									<tr class="select-seat">
										<td>B6-Right <span>12 Ticket left</span></td>
										<td>5</td>
										<td>$70 <span>Per seat</span></td>
									</tr>
								</tbody>
							</table> -->
						</div>
					</div>

				
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
var TicketBooking_<?php echo $index; ?>;
(function () {
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
	
})();
</script>