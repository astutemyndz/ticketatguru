<footer id="colophon" class="site-footer">
			
			<div class="main-footer">
				<div class="container">
					<div class="row">
						<div class="footer-1 col-md-4 col-sm-4 col-xs-12">
							<div class="about clearfix">
								<h3>Quick links</h3>
								<ul>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url();?>">Home</a></li>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url('about-us');?>">About Us</a></li>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url('event/list');?>">Events</a></li>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url('gallery');?>">Gallery</a></li>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url('partners');?>">Partners</a></li>
										<li><i class="fa fa-angle-double-right" aria-hidden="true"></i><a href="<?php echo base_url('contact-us');?>">Contact</a></li>
									</ul>
							</div>
						</div>
						<div class="footer-1 col-md-4 col-sm-4 col-xs-12">
							<div class="about clearfix">
								<h3>Contact Us</h3>
								<span class="foo-adrs">
									<img src="<?php echo base_url() ?>/images/location.png" alt="">
									3 Park Road, Crouch End, London N8 8TE</span>
								<span class="foo-call">
									<img src="<?php echo base_url() ?>/images/united-kingdom.png" alt="">
									<a href="tel:+447718286666">+44 771 828 6666</a>
								</span>
								<span class="foo-adrs">
									<img src="<?php echo base_url() ?>/images/location.png" alt="">
									34 El-Hassan Street, Ad Doqi Giza Governorate
								</span>
								<span class="foo-call">
									<img src="<?php echo base_url() ?>/images/united-kingdom.png" alt="">
									<a href="tel:+447718286666">+44 771 828 6666</a>
								</span>
								<span class="foo-call">
									<img src="<?php echo base_url() ?>/images/foo-mail.png" alt="">
									<a class="foo-mail" href="mailto:info@ticketatguru.co.uk">info@ticketatguru.co.uk</a>
								</span>
								
							</div>
						</div>
						<div class="footer-2 col-md-4 col-sm-4 col-xs-12">
							<div class="about clearfix">
								<ul class="foo-social">
									<span>Find us on :</span>
									<li>
										<a href="https://www.facebook.com/globalgalashow" target="_blank">
											<img src="<?php echo base_url(); ?>/images/foo-facebook.png" alt="Facebook">
										</a>
									</li>
									<li>
										<a href="https://www.instagram.com/globalgalashow/" target="_blank">
											<img src="<?php echo base_url(); ?>/images/foo-insta.png" alt="Instagram">
										</a>
									</li>
									<li>
										<a href="https://www.instagram.com/globalgalashow/" target="_blank">
											<img src="<?php echo base_url(); ?>/images/foo-twitter.png" alt="Twitter">
										</a>
									</li>
								</ul>
								<div><img src="<?php echo base_url(); ?>/images/sage_logos2.png" alt="payment"></div>
								<ul class="foo-social">
									<li><a href="<?php echo base_url('terms-conditions');?>">Terms & Conditions</a></li>
									<li><a href="<?php echo base_url('privacy-policy');?>">Cookies</a></li>
									<li><a href="<?php echo base_url('contact-us');?>">Company Information</a></li>
								</ul>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="top-footer">
				<div class="container">
					<div class="row">
						
						<div class="col-md-6">
							<a href="#"><img src="<?php echo base_url();?>/images/ticketGuruLogo.png" alt="logo"></a>
						</div>
						<div class="col-md-6">
						<p>Copyright &copy; <?php echo date('Y');?>  Ticketatguru.com. Design And Developed By <a href="https://astutemynzd.com/" target="_blank" rel="nofollow" class="MSI_ext_nofollow">Astutemynzd</a></p>
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
<script src="<?php echo base_url();?>js/payment/CreditCard.js"></script>
<script src="<?php echo base_url();?>js/jquery.creditCardValidator.js"></script>
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
  responsive: [
  {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
		slidesToScroll: 1
      }
    },
    {
      breakpoint: 800,
      settings: {
        slidesToShow: 2,
		slidesToScroll: 1
      }
    },
	{
      breakpoint: 667,
      settings: {
        slidesToShow: 1,
		slidesToScroll: 1,
		variableWidth: true
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
		slidesToScroll: 1
      }
    }
  ]
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
<script>
$(document).ready(function(){
  $("#selectLang").click(function(){
    $("#chooseLang").toggle(100);
  });
});
</script>


<script>
$(document).ready(function(){
  $(".navBar ul li a").on('click', function(event) {

    if (this.hash !== "") {
      event.preventDefault();

      var hash = this.hash;
	  var padTop = 90;
		
      $('html, body').animate({
        scrollTop: $( $(this).attr('href') ).offset().top - padTop
      }, 500, function(){
   
        window.location.hash = hash;
      });
    } 
  });
  var $cc_type = $('#cc_type');
  $('#card_number').validateCreditCard(function(result)
    {

		console.log(result.card_type.name);
		$cc_type.val(result.card_type.name);
    });
});
</script>	  
	<script src="<?php echo base_url();?>js/jquery.bootstrap.wizard.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.min.js" type="text/javascript"></script>
	<!--  Plugin for the Wizard -->
	<script src="<?php echo base_url();?>js/checkoutFormWizard.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/paper-bootstrap-wizard.js" type="text/javascript"></script>
</body>
</html>