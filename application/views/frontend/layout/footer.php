<footer id="colophon" class="site-footer">
			
			<div class="main-footer">
				<div class="container">
					<div class="row">
						<div class="footer-1 col-md-4">
							<div class="about clearfix">
								<h3>About</h3>
								<p>
									Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
								</p>
							</div>
						</div>
						<div class="footer-1 col-md-4">
							<div class="about clearfix">
								<h3>Site Map</h3>
								<ul>
										<li><a href="#">Home</a></li>
										<li><a href="#">Events</a></li>
										<li><a href="#">Tickets Cart</a></li>
										<li><a href="#">Parties</a></li>
										<li><a href="#">Gallery</a></li>
										<li><a href="#">Partners</a></li>
										<li><a href="#">Contact</a></li>
									</ul>
							</div>
						</div>
						<div class="footer-2 col-md-4">
							<div class="about clearfix">
								<h3>News Letter</h3>
								<form class="foo-email" action="mailto:youremail@youremail.com">
									<label>
										<input type="email" placeholder="Email">
										<button type="button"><img src="<?php echo base_url() ?>/images/sent-mail.png" alt="submit"></button></span>
									</label>
								</form>
								<ul class="foo-social">
									<span>Find us on :</span>
									<li>
										<a href="https://www.facebook.com/globalgalashow" target="_blank">
											<img src="<?php echo base_url(); ?>/images/facebook.png" alt="Facebook">
										</a>
									</li>
									<li>
										<a href="https://www.instagram.com/globalgalashow/" target="_blank">
											<img src="<?php echo base_url(); ?>/images/instagram.png" alt="Instagram">
										</a>
									</li>
									<li>
										<a href="https://www.instagram.com/globalgalashow/" target="_blank">
											<img src="<?php echo base_url(); ?>/images/twitter.png" alt="Twitter">
										</a>
									</li>
								</ul>
							</div>
						</div>
					<!-- 	<div class="footer-2 col-md-3">
							<div class="footer-dashboard">
								<h3>globalgala Dashboard</h3>
								<ul>
									<li><a href="#">Professional</a></li>
									<li><a href="#">Subscriber Login</a></li>
								</ul>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<div class="top-footer">
				<div class="container">
					<div class="row">
						
						<div class="col-md-8">
							<a href="#"><img src="<?php echo base_url();?>/images/ticketGuruLogo.png" alt="logo"></a>
						</div>
						<div class="col-md-4">
						
						<p>&copy; 2016 globalgala.COM. ALL RIGHTS RESEVED</p>
						</div>
					</div>
				</div>
			</div>
		</footer>
<?php
 
 if(get_cookie('set_country_id')!=''){
 	$set_country_id = get_cookie('set_country_id');
 }else{
 	$set_country_id = '';
 }
 
 if(get_cookie('set_city_id')!=''){
 	$set_city_id = get_cookie('set_city_id');
 }else{
 	$set_city_id = '';
 }
 echo $set_country_id = get_cookie('set_country_id');
?>

		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Please choose your location.</h4>
		      </div>
		      <div class="modal-body">
		      	
		      	<form method="post" action="<?php echo base_url()?>location" id="location_set">
		      	<div class="form-group">
		      		<label class="control-label col-sm-2">Country:</label>
    				<div class="col-sm-10">
				        <?php $country=get_country(); ?>
				        <select id="country_list" name="country_list" class="form-control" autocomplete="off">
				        	<option value=""> Select Country</option>
				        	<?php 
				        	if(count($country)>0) { 
				        		foreach ($country as $country_key => $country_value) {
				        			?>
				        			<option value="<?php echo $country_value['countryID'];?>" <?php if($set_country_id == $country_value['countryID']){?> selected="selected" <?php }?>><?php echo $country_value['countryName'];?></option>
				        			<?php 
				        		}
				        	}
				        	?>
				        </select>
			    	</div>
			    </div>
			    
			    <?php if($set_city_id !='') { ?>
					<div class="form-group"  id="city_id1">
			      		<label class="control-label col-sm-2">City:</label>
	    				<div class="col-sm-10">
					        <?php $city=get_city(); ?>
					        <select id="country_list" name="country_list" class="form-control" autocomplete="off">
					        	<option value=""> Select City</option>
					        	<?php 
					        	if(count($city)>0) { 
					        		foreach ($city as $city_key => $city_value) {
					        			?>
					        			<option value="<?php echo $city_value['cityID'];?>" <?php if($set_city_id == $city_value['cityID']){?> selected="selected" <?php }?>><?php echo $city_value['cityName'];?></option>
					        			<?php 
					        		}
					        	}
					        	?>
					        </select>
				    	</div>
				    </div>
				<?php }else{ ?>
					<div class="form-group"  id="city_id1">
					</div>
				<?php } ?>
			    <div class="btn-wrap">
					<button type="submit" id="submit1" class="btn site-btn-red" name="login">Set Location</button>
				</div>
				</form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
		</div>
		
        <script src="<?php echo base_url();?>js/bootstrap-slider.min.js"></script>
        <script src="<?php echo base_url();?>js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url();?>js/jquery.scrolling-tabs.min.js"></script>
        <script src="<?php echo base_url();?>js/jquery.countdown.min.js"></script>
        <script src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>
        <script src="<?php echo base_url();?>js/jquery.imagemapster.min.js"></script>
        <script src="<?php echo base_url();?>js/tooltip.js"></script>
        <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>js/featherlight.min.js"></script>
        <script src="<?php echo base_url();?>js/featherlight.gallery.min.js"></script>
        <script src="<?php echo base_url();?>js/bootstrap.offcanvas.min.js"></script>
        <script src="<?php echo base_url();?>js/main.js"></script>
        <script src="<?php echo base_url();?>js/datepicker.js"></script>
        
        <script src="//jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
		<script type="text/javascript">
		var API_URL = '<?php echo base_url();?>';
		
			
			
			$("document").ready(function() {
				
				
				
				<?php if($set_country_id =='' && $set_city_id ==''){ ?>
				///let modal = document.querySelector('#myModal');
				///$(modal).modal({show:true});
				<?php } ?>
				$('#country_list').on('change',function(){
					var country_id = $(this).val();
					console.log(country_id);

					$.ajax({
						type: "POST",
						url: "<?php echo base_url('ajaxCity'); ?>",
						data : {'country_id':country_id },
						success: function(data){
							$("#city_id1").html(data);
						}

					});
				});
				$("#location_set").validate({
					rules:{
						country_list:{
							required:true
						},
						city:{
							required:true
						}						
					}
				});
				$('[data-toggle="datepicker"]').datepicker();
			});
		</script>		
<script src="<?php echo base_url();?>js/custom.js"></script>

<script src="<?php echo base_url();?>js/jquery.loading.js"></script>
<script src="<?php echo base_url();?>js/event/Event.js"></script>
<script src="<?php echo base_url();?>js/auth/auth.js"></script>
<script src="<?php echo base_url();?>js/cart/Cart.js"></script>
<script>
	(function() {
		var $section = $('.wrapper-image');
		$section.find('.panzoom').panzoom({
		$zoomIn: $section.find(".zoom-in"),
		$zoomOut: $section.find(".zoom-out"),
		$zoomRange: $section.find(".zoom-range"),
		$reset: $section.find(".reset")
		});

		
	})();
</script>

<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
<script>
$('.autoplay').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
});
</script>
<script>
			$(document).ready(function(){
				$('#contact').hover(function(){
					$('#selectNo').show(100);
				},function(){
					$('#selectNo').hide(100);
				});
			});
		</script>
	  
