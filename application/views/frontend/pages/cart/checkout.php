<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//App::dd($this->session->all_userdata());
?>
		
        <div class="check-out">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="wizard-container">
							<span id="paymentSuccess"></span>
		                <div class="card wizard-card from-sec" data-color="orange" id="wizardProfile">
		                    <form action="<?php echo base_url();?>paypal/pay/credit-card" method="post" id="checkoutForm" class="checkoutForm">
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
							
									<?php 
									if(count($this->cart->contents()) > 0) {
										foreach($this->cart->contents() as $item) { 
											if(is_array($item['seat_id']) && count($item['seat_id']) > 0) {
												foreach($item['seat_id'] as $price_id => $seat_id) {
													echo "<input class='seat_id' type='hidden' name='seat_id[$price_id][$seat_id]' value='1'>\n";
												}
											}
										}
									}
									?>
									
									
									<div class="tab-content">
										<div class="tab-pane" id="about">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_firstName]" type="text" id="c_firstName" placeholder="First name" value="<?php echo ($user['first_name']) ? $user['first_name'] : '';?>" required>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_lastName]" type="text" id="c_lastName" placeholder="Last name" value="<?php echo ($user['last_name']) ? $user['last_name'] : '';?>" required>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_email]" type="text" id="c_email" placeholder="Email" value="<?php echo ($user['email']) ? $user['email'] : '';?>" required>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_phone]" type="text" id="c_phone" placeholder="Phone" required>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<select name="billingAddress[c_country]" id="c_country" required>
														<option value="" data-country_id="">select country</option>
														<?php if(count($countries) > 0) {
															foreach($countries as $country) {
																?>
																<option value="<?php echo $country['value'];?>" data-country_id="<?php echo $country['id'];?>"><?php echo $country['text'];?></option>
																<?php
															}
														}
														?>
														
													</select>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<select name="billingAddress[c_state]" id="c_state" required>
														<option value="" data-country_id="" data-state_id="">select state</option>
													</select>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<select name="billingAddress[c_city]" id="c_city" required>
														<option value="" data-country_id="" data-state_id="">select city</option>
													</select>
												</div>
											
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_address]" type="text" id="c_address" placeholder="Address" required>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-12 input-field">
													<input name="billingAddress[c_zip]" type="text" id="c_zip" placeholder="Postalcode" required>
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
												<?php 
												/*
												<div class="login-inner col-md-8 col-md-offset-2">
													<ul class="nav nav-tabs">
														<li class="active left-radius"><a data-toggle="tab" href="#card">Credit Card</a></li>
														<li class="right-radius"><a data-toggle="tab" href="#paypal">PayPal</a></li>
													</ul>
													<div class="tab-content">
														<div id="card" class="tab-pane fade in active">
															<span id="paymentFailed"></span>
															<div class="from-sec">
																	
																	<input name="creditCardInfo[cc_num]" id="card_number" type="text" class="card-no creditCardInfo" placeholder="Card number" required>
																	<input name="creditCardInfo[cc_exp_month]" id="cc_exp_month" type="text" class="card-date grid-3 creditCardInfo" placeholder="DD" required>
																	<input name="creditCardInfo[cc_exp_year]" id="cc_exp_year" type="text" class="card-date grid-3 ml creditCardInfo" placeholder="YYYY" required>
																	<input name="creditCardInfo[cc_code]" id="cc_code" type="text" class="cvv grid-3 ml creditCardInfo" placeholder="CVV" required>
																	<input name="creditCardInfo[cc_type]" id="cc_type" type="hidden" class="cvv grid-3 ml">
																	<input type='submit' class='btn btn-default' name='finish' id="finish" value='Payment' />
																	
															</div>
														</div>
								
														<div id="paypal" class="tab-pane fade in">
															<div class="from-sec">
																<a class="paypal-back" href="#">Back</a>
																<a class="paypal-pay" href="#"><i class="fa fa-paypal" aria-hidden="true"></i> Paypal checkout</a>
															</div>
														</div> 
														<!---->
														<?php /*
														<div id="card" class="tab-pane fade in active creditCardForm">
           
															<div class="payment">
																	
																	<div class="form-group" id="card-number-field">
																		<label for="cardNumber">Card Number</label>
																		<input type="text" class="form-control" id="card_number" name="creditCardInfo[cc_num]">
																		<input name="creditCardInfo[cc_type]" id="cc_type" type="hidden" class="cvv grid-3 ml">
																	</div>
																	<div class="form-group" id="expiration-date">
																		<label>Expiration Date</label>
																		<select name="creditCardInfo[cc_exp_month]">
																			<option value="01">January</option>
																			<option value="02">February </option>
																			<option value="03">March</option>
																			<option value="04">April</option>
																			<option value="05">May</option>
																			<option value="06">June</option>
																			<option value="07">July</option>
																			<option value="08">August</option>
																			<option value="09">September</option>
																			<option value="10">October</option>
																			<option value="11">November</option>
																			<option value="12">December</option>
																		</select>
																		<select name="creditCardInfo[cc_exp_year]">
																			<option value="2019"> 2019</option>
																			<option value="2020"> 2020</option>
																			<option value="2021"> 2021</option>
																			<option value="2022"> 2022</option>
																			<option value="2023"> 2023</option>
																			<option value="2024"> 2024</option>
																			<option value="2025"> 2025</option>
																			<option value="2026"> 2026</option>
																			<option value="2027"> 2027</option>
																			<option value="2028"> 2028</option>
																			<option value="2029"> 2029</option>
																			<option value="2030"> 2030</option>
																			<option value="2031"> 2031</option>
																		</select>
																	</div>
																	<div class="form-group CVV">
																		<label for="cvv">CVV</label>
																		<input type="text" class="form-control" id="cc_code" name="creditCardInfo[cc_code]">
																	</div>
																	<div class="form-group" id="credit_cards">
																		<img src="<?php echo base_url();?>images/visa.jpg" id="visa">
																		<img src="<?php echo base_url();?>images/mastercard.jpg" id="mastercard">
																		<img src="<?php echo base_url();?>images/amex.jpg" id="amex">
																	</div>
																	<div class="form-group" id="pay-now">
																		<!-- <button type="submit" class="btn btn-default" id="finish" name='finish'>Confirm</button> -->
																		<input type='submit' class='btn btn-default' name='finish' id="finish" value='Payment' />
																	</div>
															</div>
        												</div>
														<!---->
														
													</div>
												</div>
												*/?>
												<div class="col-md-8 col-md-offset-2">
													<div class="col-md-8 col-sm-6 col-xs-12 input-field" id="card-number-field">
														<input class="paymentInput" type="text" id="card_number" name="creditCardInfo[cc_num]" placeholder="•••• •••• •••• ••••" autocompletetype="cc-number" required="required">
														<input name="creditCardInfo[cc_type]" id="cc_type" type="hidden">
													</div>
													
													<div class="col-md-2 col-sm-6 col-xs-12 input-field" id="card-cvv-field">
														<input class="paymentInput" name="creditCardInfo[cc_code]" type="text" id="cc_code" placeholder="CVC" autocompletetype="cc-cvc" required="required">
													</div>


													<!-- <div class="col-md-2 col-sm-6 col-xs-12 input-field" id="card-year-field">
														<input name="creditCardInfo[cc_exp_year]" type="text" id="cc_exp_year" placeholder="yy" required>
													</div> -->
													<div class="col-md-4 col-sm-6 col-xs-12 input-field" id="formatCardExpiryField">
														
														<input class="paymentInput" name="creditCardInfo[formatCardExpiry]" type="text" id="formatCardExpiry" placeholder="MM / YY" autocompletetype="cc-exp" required="required">
													</div>
													<div class="col-md-4 col-sm-6 col-xs-12 input-field">
														<div class="form-group" id="credit_cards">
															<img src="<?php echo base_url();?>images/visa.jpg" id="visa">
															<img src="<?php echo base_url();?>images/mastercard.jpg" id="mastercard">
															<img src="<?php echo base_url();?>images/amex.jpg" id="amex">
														</div>
													</div>
													<div class="col-md-8 col-sm-6 col-xs-12 input-field">
														<input type='submit' class='btn btn-default' name='finish' id="finish" value='Payment' disabled />
													</div>
										
												</div>
											</div>
										</div>
									</div>

								
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd' name='next' value='Next' />
		                                <!-- <input type='submit' class='btn btn-finish btn-fill btn-warning btn-wd' name='finish' id="finish" value='Payment' /> -->
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

		