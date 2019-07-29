<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// exit;
mt_srand();
$index = mt_rand(1, 9999);
$validate = str_replace(array('"', "'"), array('\"', "\'"), __('validate', true, true));


$defaultStore = ($this->session->userdata('pjTicketBooking_Store')) ? $this->session->userdata('pjTicketBooking_Store') : [];
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
		$ticket_tooltip_arr['tooltip'][$v['price_id']] = pjSanitize::html($v['ticket']) . ', ' .  pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);
		$ticket_tooltip_arr['tooltip']['price'][$v['price_id']] = $v['price'];
		$ticket_tooltip_arr['tooltip']['price']['currency'][$v['price_id']] = $option_arr['o_currency'];
	}
}
// echo "<pre>";
// print_r($ticket_tooltip_arr );
?>
<section class="section-select-seat-page-content">
	<div id="pjWrapperTicketBooking_theme1">
		<div id="tbContainer_<?php echo $index;?>" class="tbContainer pjCbContainer">
			<div class="container ">
				<div class="row pjCbBody">
				
					<div id="primary" class="col-md-12">
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
														
														<map id="plk-map-seat-points-wrapper" name="map" class="seatmap">
														
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
					
				
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var pjQ = pjQ || {},
	TicketBooking_<?php echo $index; ?>;
	(function() {
		"use strict";
		var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),

	loadCssHack = function(url, callback){
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = url;

		document.getElementsByTagName('head')[0].appendChild(link);

		var img = document.createElement('img');
		img.onerror = function(){
			if (callback && typeof callback === "function") {
				callback();
			}
		};
		img.src = url;
	},
	loadRemote = function(url, type, callback) {
		if (type === "css" && isSafari) {
			loadCssHack(url, callback);
			return;
		}
		var _element, _type, _attr, scr, s, element;
		
		switch (type) {
		case 'css':
			_element = "link";
			_type = "text/css";
			_attr = "href";
			break;
		case 'js':
			_element = "script";
			_type = "text/javascript";
			_attr = "src";
			break;
		}
		
		scr = document.getElementsByTagName(_element);
		s = scr[scr.length - 1];
		element = document.createElement(_element);
		element.type = _type;
		if (type == "css") {
			element.rel = "stylesheet";
		}
		if (element.readyState) {
			element.onreadystatechange = function () {
				if (element.readyState == "loaded" || element.readyState == "complete") {
					element.onreadystatechange = null;
					if (callback && typeof callback === "function") {
						callback();
					}
				}
			};
		} else {
			element.onload = function () {
				if (callback && typeof callback === "function") {
					callback();
				}
			};
		}
		element[_attr] = url;
		s.parentNode.insertBefore(element, s.nextSibling);
	},
	loadScript = function (url, callback) {
		loadRemote(url, "js", callback);
	},
	loadCss = function (url, callback) {
		loadRemote(url, "css", callback);
	},
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
	// code goes here
	
	loadScript("<?php echo base_url();?>js/booking/TicketBooking.js", function () {
		//console.log(options);
		TicketBooking_<?php echo $index; ?> = new TicketBooking(options);
	});
})();
	


	
	

</script>
