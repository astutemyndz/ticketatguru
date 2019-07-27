<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo "<pre>";
print_r($this->cart->contents());
?>
<section class="section-page-header">
			<div class="container">
				<h1 class="entry-title">Order Details</h1>
			</div>
		</section>
		<div class="cart-list">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table">
								<thead class="table-head">
									<tr>
										<th>Event</th>
										<th>Time</th>
										<th>Section</th>
										<th class="text-center">Seats</th>
										<th class="text-right">Price</th>
										<th class="text-right">Delete</th>
									</tr>
								</thead>
								<tbody  class="table-list">
									<tr>
										<td><img src="https://dummyimage.com/50x50/55595c/fff"> </td>
										<td>02:30 PM</td>
										<td>A-5, A-6, A-7</td>
										<td><input type="text" value="3"></td>
										<td class="text-right">124,90 €</td>
										<td class="text-right"><button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button> </td>
									</tr>
									<tr>
										<td><img src="https://dummyimage.com/50x50/55595c/fff"> </td>
										<td>2:45 PM</td>
										<td>C-5, F-2, D-7</td>
										<td><input type="text" value="3"></td>
										<td class="text-right">33,90 €</td>
										<td class="text-right"><button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button> </td>
									</tr>
									<tr>
										<td><img src="https://dummyimage.com/50x50/55595c/fff"> </td>
										<td>7:30 PM</td>
										<td>B-2, B-5, B-6, B-8</td>
										<td><input type="text" value="4"></td>
										<td class="text-right">70,00 €</td>
										<td class="text-right"><button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button> </td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<td class="text-right">Number of tickets</td>
										<td class="text-right">Total Price</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<td class="text-right">5</td>
										<td class="text-right">6,90 €</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-sm-12  col-md-6">
								<a class="secondary-link" href="#">Continue Booking</a>
							</div>
							<div class="col-sm-12 col-md-6 text-right">
								<a class="primary-link" href="#">Check Out</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>