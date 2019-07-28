<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
										<th>Description</th>
										<th class="text-right">Price</th>
										<th class="text-right">Sub Total</th>
										<th class="text-right">Action</th>
									</tr>
								</thead>
								<tbody  class="table-list">
									
									<?php $i = 1; ?>
<?php if(count($this->cart->contents()) > 0){?>
<?php foreach ($this->cart->contents() as $items): ?>

        <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>

        <tr>
               
                <td>
                        <?php echo $items['name']; ?>

						<?php 
						/*
						if ($this->cart->has_options($items['rowid']) == TRUE): ?>

                                <p>
                                        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

                                                <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />

                                        <?php endforeach; ?>
                                </p>

                        <?php endif; */?>

                </td>
                <td class="text-right"><?php echo $this->cart->format_number($items['price']); ?></td>
				<td class="text-right">$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
				<td class="text-right"><button id="removeCartButton" data-id="<?php echo $items['rowid'];?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button> </td>
        </tr>

<?php $i++; ?>

<?php endforeach; ?>
<?php } else { ?>
<span> Oops! Cart is empty </span>
<?php } ?>
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