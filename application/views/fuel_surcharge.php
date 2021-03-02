<div class="row">
	
	<div class="col-md-6" style="padding: 15px;">
		<form method="post">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Add Fuel Surcharge
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Greater Than or Equal To (Rs.)</label>
					<input type="number" name="greater_than" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Less Than Rs.</label>
					<input type="number" name="less_than" class="form-control" required>
				</div>
				<div class="form-group">
					<label>FSC Applicable (%)</label>
					<input type="number" name="fsc" class="form-control" required>
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
					<th>Greater Than or <br>Equal To (Rs.)</th>
					<th>Less Than</th>
					<th>FSC</th>
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
						<td>'.$value->greater_than.'</td>
						<td>'.$value->less_than.'</td>
						<td>'.$value->fsc.'</td>
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
			url:'<?=base_url('setting/edit_surcharge')?>',
			type:'post',
			data:{id:id,task:'view'},
			success:function(res){
			
				Swal.fire({
					title:'Edit',
					html:res,
					showConfirmButton:false
				});
			}
		});
	}
</script>