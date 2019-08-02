<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="section-page-header">
			<section class="page-banner">
			<img src="<?php echo base_url() ?>/images/banner.jpg" alt="">
			<div class="black-layer"></div>
			<div class="caption">
				<h3>Tickets for Camp Nou Experience</h3>
			</div>
		</section>
		</section>
		<div class="cart-list"  >
			<div class="container" >
				
				<div class="row"  id="cartTable">
					<div class="col-md-12" >
					<table class="table cart-table" >
						<thead class="table-head">
							<tr>
								<th>Title</th>
								<th>Quantity</th>
								<th>Price</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="table-list" id="loadCart">
						</tbody>
						<tbody class="subtotal">
							<tr>
								<td colspan="2">
									Subtotal
								</td>
								<td colspan="2">
									&euro; 2000
								</td>
							</tr>
						</tbody>
						
					</table>
					</div>
					<div class="col-md-12 cart-btn">
						<div class="row">
							<a id="continueLink" class="secondary-link" href="#">Continue Booking</a>
							<a id="checkoutLink" class="primary-link" href="javascript:void(0);">Check Out</a>							
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<h3 id="cartEmpty" class="cartEmpty"></h3>
				</div>
			</div>
			</div>
		</div>