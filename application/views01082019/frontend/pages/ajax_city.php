	<label class="control-label col-sm-2">City</label> 
	<div class="col-sm-10">
	<select class="form-control" name="city" id="city" required="required" >
		<option value="">Select Any City</option>
		<?php foreach($ajax_city as $city) {?>
		<option value="<?php echo $city->cityID;?>" ><?php echo $city->cityName;?></option>
		<?php }?>
	</select>
	</div>