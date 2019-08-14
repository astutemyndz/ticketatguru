<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
		
		<div class="cart-list">
			<div class="container">
				
				<div class="row"  id="cartTable">
					<div class="col-md-12" >
					<table class="table cart-table">
						<thead class="table-head">
							<tr>
								<th>TICKET(s)</th>
								<th>PRICE(00.00)</th>
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
								<td id="subtotal" colspan="2">
									
								</td>
							</tr>
						</tbody>
						
					</table>
					</div>
					<div class="col-md-12 cart-btn">
						<div class="row">
							<a id="continueLink" class="secondary-link" href="#">Continue Booking</a>
							<a id="checkoutLink" class="primary-link" href="javascript:void(0);">Place Order</a>							
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

		