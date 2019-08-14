<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
		
        <div class="check-out">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="wizard-container">

		                <div class="card wizard-card from-sec" data-color="orange" id="wizardProfile">
		                    <form action="" method="">
								<div class="wizard-navigation">
									<div class="progress-with-circle">
									     <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
									</div>
									<ul>
			                            <li>
											<a href="#about" data-toggle="tab">
												<div class="icon-circle">
													<i class="fa fa-user icon" aria-hidden="true"></i>
												</div>
												Billing address
											</a>
										</li>
			                            <li>
											<a href="#account" data-toggle="tab">
												<div class="icon-circle">
													<i class="fa fa-cart-plus icon" aria-hidden="true"></i>
												</div>
												Cart summary
											</a>
										</li>
			                            <li>
											<a href="#address" data-toggle="tab">
												<div class="icon-circle">
													<i class="fa fa-credit-card icon" aria-hidden="true"></i>
												</div>
												Payment options
											</a>
										</li>
			                        </ul>
								</div>
								<form id="checkoutForm">
									<div class="tab-content">
										<div class="tab-pane" id="about">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="firstName" placeholder="First name">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="lastName" placeholder="Last name">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="email" placeholder="Email">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="phone" placeholder="Phone">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="countryCode" placeholder="Country">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="city" placeholder="City">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="_address" placeholder="Address">
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input type="text" id="postalCode" placeholder="Postalcode">
												</div>
											</div>
										</div>
										<div class="tab-pane" id="account">
											<div class="row" id="loadCartSummeryTable">
												<div class="col-md-12">
													<div class="product-info">
															<table>
																<thead>
																	<tr>
																		<th>TICKET(s)</th>
																		<th>PRICE(00.00)</th>
																	</tr>
																</thead>
																<tbody id="loadCartSummery">
																	
																	
																</tbody>
																
															</table>
														</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="address">
											<div class="row">
												<div class="login-inner col-md-8 col-md-offset-2">
													<ul class="nav nav-tabs">
														<li class="active left-radius"><a data-toggle="tab" href="#card">Credit Card</a></li>
														<li class="right-radius"><a data-toggle="tab" href="#paypal">PayPal</a></li>
													</ul>
													<div class="tab-content">
														<div id="card" class="tab-pane fade in active">
															<div class="from-sec">
																	<!--<input type="text" placeholder="Name on card">-->
																	<input id="number" type="text" class="card-no" placeholder="Card number">
																	<input id="expireMonth" type="text" class="card-date grid-3" placeholder="Month">
																	<input id="expireYear" type="text" class="card-date grid-3 ml" placeholder="Year">
																	<input id="cvv2" type="text" class="cvv grid-3 ml" placeholder="CVV">
																	<input id="type" type="hidden" class="cvv grid-3 ml">
															</div>
														</div>
														<div id="paypal" class="tab-pane fade in">
															<div class="from-sec">
																<a class="paypal-back" href="#">Back</a>
																<a class="paypal-pay" href="#"><i class="fa fa-paypal" aria-hidden="true"></i> Paypal checkout</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd' name='next' value='Next' />
		                                <input type='button' class='btn btn-finish btn-fill btn-warning btn-wd' name='finish' id="finish" value='Payment' />
		                            </div>

		                            <div class="pull-left">
		                                <input type='button' class='btn btn-previous btn-default btn-wd' name='previous' value='Previous' />
		                            </div>
		                            <div class="clearfix"></div>
		                        </div>
		                    </form>
		                </div>
		            </div>
					</div>	
				</div>
			</div>
		</div>

		