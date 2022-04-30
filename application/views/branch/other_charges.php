<div class="row" style="padding-top: 20px;">
	<div class="col-md-5">
		<form action="" method="post">
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="form-group">
					<label>GC Charges</label>
					<input type="text" name="gc" class="form-control" value="<?=$oc->gc?>">
				</div>
				<div class="form-group">
					<label>Minimum Chargeable Wt</label>
					<input type="text" name="mcwt" class="form-control" value="<?=$oc->mcwt?>">
				</div>
				<div class="form-group">
					<label>Minimum Freight Value</label>
					<input type="text" name="mfv" class="form-control" value="<?=$oc->mfv?>">
				</div>
				<div class="form-group">
					<label>CFT factor</label>
					<input type="text" name="cft" class="form-control" value="<?=$oc->cft?>">
				</div>
				<div class="form-group">
					<label>Hamali Charges</label>
					<input type="text" name="hamali" class="form-control" value="<?=$oc->hamali?>">
				</div>
				<div class="form-group">
					<label>FOV Charges (owner risk)</label>
					<input type="text" name="fovowner" class="form-control" value="<?=$oc->fovowner?>">
				</div>

				<div class="form-group">
					<label>FOV Charges (Carrier risk)</label>
					<input type="text" name="fovcarrier" class="form-control" value="<?=$oc->fovcarrier?>">
				</div>
				<div class="form-group">
					<label>AOC Charges</label>
					<input type="text" name="aoc"  class="form-control" value="<?=$oc->aoc?>">
				</div>
				<div class="form-group">
					<label>COD/DOD Charges</label>
					<input type="text" name="cod" class="form-control" value="<?=$oc->cod?>">
				</div>
				<div class="form-group">
					<label>DACC Charges</label>
					<input type="text" name="dcc" class="form-control" value="<?=$oc->dcc?>">
				</div>
				<div class="form-group">
					<label>Other (Please Specify)</label>
					<input type="text" name="other" class="form-control" value="<?=$oc->other?>">
				</div>
				<div class="form-group">
					<label>CR Charges to be Paid By</label><br>
					<input type="radio" name="cr" value="Consignor" <?=$oc->cr=='Consignor'?'checked':''?>> Consignor &nbsp; &nbsp; 
					<input type="radio" name="cr" value="Consignee" <?=$oc->cr=='Consignee'?'checked':''?>> Consignee
				</div>
				<div class="form-group">
					<label>Demurrage charges </label>
					<input type="text" name="dccharge" class="form-control" value="<?=$oc->dccharge?>">
				</div>
				<div class="form-group">
					<label>Demurrage Charges to be Paid By </label><br>
					<input type="radio" name="dcby" value="Consignor" <?=$oc->dcby=='Consignor'?'checked':''?>> Consignor &nbsp; &nbsp; 
					<input type="radio" name="dcby" value="Consignee" <?=$oc->dcby=='Consignee'?'checked':''?>> Consignee
					
				</div>
				<div class="form-group">
					<label>Loading/Unloading Charges/Union Charges </label>
					<input type="text" name="loading" class="form-control" value="<?=$oc->loading?>">
				</div>
				<div class="form-group">
					<label>GI Charges </label>
					<input type="text" name="gi" class="form-control" value="<?=$oc->gi?>">
				</div>
				<div class="form-group">
					<label>Dynamic Fuel Surcharge in %</label>
					<input type="text" name="dfs" class="form-control" value="<?=$oc->dfs?>">
				</div>
				<!--<div class="form-group">
					<label>Levy- in %</label>
					<input type="text" name="l_in" class="form-control" value="<?=$oc->l_in?>">
				</div>-->
				<div class="form-group">
					<label>E-way bill charge</label>
					<input type="text" name="eway" class="form-control" value="<?=$oc->eway?>">
				</div>
				<div class="form-group">
					<label>Door Collection Charges</label>
					<input type="text" name="door" class="form-control" value="<?=$oc->door?>">
				</div>
				<div class="form-group">
					<label>Last Mile  Delivery charges</label>
					<input type="text" name="lmdc" class="form-control"  value="<?=$oc->lmdc?>">
				</div>
				<div class="form-group">
					<label>Average fuel price at the time of signing the contract</label>
					<input type="text" name="avg_fuel" class="form-control" value="<?=$oc->avg_fuel?>">
				</div>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Save</button>
			</div>
		</div>
		</form>
	</div> 
</div>