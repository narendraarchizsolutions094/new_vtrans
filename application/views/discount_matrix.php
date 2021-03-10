<div class="row">
	
	<div class="col-md-6" style="padding: 15px;">
		<form method="post">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Add
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Allowed Discount (%)</label>
					<input type="number" name="discount" class="form-control" required onkeyup="{
						if(this.value>100 || this.value <0)
							this.value=0;
						}">
				</div>
				<div class="form-group">
					<label>Rate/Km </label>
					<input type="number" name="rate_km" class="form-control" required onkeyup="{
						if(this.value <0)
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
					<th>Name</th>
					<th>Discount(%)</th>
					<th>Rate/Km</th>
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
						<td>'.$value->name.'</td>
						<td>'.$value->discount.'</td>
						<td>'.$value->rate_km.'</td>
						<td><div class="btn btn-group">
						<a class="btn btn-xs btn-primary" onclick="update_d('.$value->id.')">Edit</a>

						<a href="'.base_url('setting/delete_grade/'.$value->id).'" onclick="return confirm(\'Are you sure?\')" class="btn btn-xs btn-danger">Delete</a>
						
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
			url:'<?=base_url('setting/edit_discount')?>',
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