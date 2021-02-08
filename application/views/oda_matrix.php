<!-- <div class="row" style="padding-top: 20px;">
	
	<div class="col-md-6">

		<div class="form-group">
			<label>Distance (In KM)</label>
			<div>
				<div style="width: 49%; display: inline-block;">	
					<input type="text" name="distance_from" class="form-control" placeholder="From" required>
				</div>
				<div style="width: 49%; display: inline-block;">
					<input type="text" name="distance_to" class="form-control" placeholder="To" required>
				</div>
				<div class="form-group">
					<button class="btn btn-success">Add</button>
				</div>
			</div>
		</div>
	</div>
</div> -->
<div class="row"  style="padding-top: 20px;">
	<div class="col-md-6" style="padding: 15px;">
		<form method="post">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Add
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Distance (In KM)</label>
					<div>
					<div style="width: 49%; display: inline-block;">	
						<input type="text" name="distance_from" class="form-control" placeholder="From" required>
					</div>
					<div style="width: 49%; display: inline-block;">
						<input type="text" name="distance_to" class="form-control" placeholder="To" required>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label>Weight (In KG)</label>
					<div>
					<div style="width: 49%; display: inline-block;">	
						<input type="text" name="weight_from" class="form-control" placeholder="From" required>
					</div>
					<div style="width: 49%; display: inline-block;">
						<input type="text" name="weight_to" class="form-control" placeholder="To" required>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label>Charge (Rs.)</label>
					<input type="number" name="charge" class="form-control" required onkeyup="{
						if(this.value!='' && this.value <0)
							this.value=0;
						}">
				</div>
			</div>
			<div class="panel-footer">
				<div class="form-group">
						<button class="btn btn-primary">Save</button>
					</div>
			</div>
		</div>
		</form>
	</div>
	<div class="col-md-6" style="padding: 15px;">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Distance</th>
					<th>Weight</th>
					<th>Charge</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($list))
				{	$i=1;
					foreach ($list as $key => $value) 
					{
					echo'<tr>
						<td>'.$i++.'</td>
						<td>'.$value->distance_from.' - '.$value->distance_to.' KM</td>
						<td>'.$value->weight_from.' - '.$value->weight_to.' KG</td>
						<td>'.$value->charge.' Rs.</td>
						
						<td><div class="btn btn-group">
						<a class="btn btn-xs btn-primary" onclick="update_d('.$value->id.')">Edit</a>

						<a class="btn btn-xs btn-danger">Delete</a>
						
						</div></td>
					</tr>';
					}
					
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	function update_d(id)
	{
		$.ajax({
			url:'<?=base_url('setting/edit_oda')?>',
			type:'post',
			data:{id:id,task:'view'},
			success:function(res){	
				Swal.fire({
					title:'Edit',
					html:res,
					showConfirmButton:false,
				});
			}
		});
	}
</script>