<div class="row">
	
	<div class="col-md-8" style="padding: 15px;">
		<form method="post">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Add
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Category Name</label>
					<input type="text" name="name" class="form-control" value="<?php if(!empty($list_aa)){echo $list_aa[0]->name;} ?>" required>
				</div>
				<div class="col-md-12">
					<div class="col-md-12">
					<div class="col-md-3"><label>Max</label></div>
					<div class="col-md-2"><label></label></div>
					<div class="col-md-3"><label>Min</label></div>
					<div class="col-md-3"><label>Discount (%)</label></div>
					<div class="col-md-1"></div>
					</div>
					<?php //print_r($list_aa);
					if(!empty($list_aa)){
					 foreach($list_aa as $d){?>
					<div class="col-md-12" id="s">

					<div class="col-md-3">
					<input type="text" name="max[]" class="form-control" value="<?php echo $d->max_a; ?>" >
						</div>
						
						<div class="col-md-2" style="text-align:center">
						>=</div>
						<div class="col-md-3">
						<input type="text" name="min[]" class="form-control" value="<?php echo $d->min_a; ?>" >
						</div>
						<div class="col-md-3">
						<input type="text" name="dis[]" class="form-control" value="<?php echo $d->discount; ?>">
						</div>
						<div class="col-md-1"><i class="btn btn-danger fa fa-minus remove" onclick="remove(s)" aria-hidden="true"></i></div>
						<br><br><br></div>
						<input type="hidden" name="update_id[]" class="form-control" value="<?php echo $d->id; ?>">
						<input type="hidden" name="group_id[]" class="form-control" value="<?php echo $d->group_id; ?>">
					 <?php } } ?>
					<div class="col-md-12" id="s">
					<div class="col-md-3">
					<input type="text" name="max[]" class="form-control"  >
						</div>
						
						<div class="col-md-2" style="text-align:center">
						>=</div>
						<div class="col-md-3">
						<input type="text" name="min[]" class="form-control"  >
						</div>
						<div class="col-md-3">
						<input type="text" name="dis[]" class="form-control"  >
						</div>
						<div class="col-md-1"><i class="btn btn-danger fa fa-minus remove" onclick="remove(s)" aria-hidden="true"></i></div>
						</div>
						<div class="new_row">
						</div>
						<div class="row col-md-12"><br>
						<i class="btn btn-info add_more" aria-hidden="true"> Add More</i>
						</div>
						
						

				</div>
				<div class="form-group">
					<label>Rate/Km </label>
					<input type="text" name="rate_km" class="form-control" required value="<?php if(!empty($list_aa)){echo $list_aa[0]->rate_km;} ?>">
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
	<div class="col-md-4" style="padding: 15px;">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Category Name</th>
					<th>Min</th>
					<th>Max</th>
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
						<td>'.$value->min_a.'</td>
						<td>'.$value->max_a.'</td>
						<td>'.$value->discount.'</td>
						<td>'.$value->rate_km.'</td>
						<td><div class="btn btn-group">
						<a class="btn btn-xs btn-primary" href="'.base_url().'setting/catageory_matrix?data='.$value->group_id.'" >Edit</a>

						<a href="'.base_url('setting/delete_cat/'.$value->group_id).'" onclick="return confirm(\'Are you sure?\')" class="btn btn-xs btn-danger">Delete</a>
						
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
$('.add_more').click(function(){
	var d='<div class="row col-md-12" id="s"><br>'+'<div class="col-md-3">'
					+'<input type="text" name="max[]" class="form-control" required onkeyup="">'+
						'</div>'+'<div class="col-md-2" style="text-align:center">'
						+'>=</div>'
						+'<div class="col-md-3">'+
						'<input type="text" name="min[]" class="form-control" required onkeyup="">'+
						'</div>'+
						'<div class="col-md-3">'+
						'<input type="text" name="dis[]" class="form-control" required onkeyup="">'+
						'</div>'+
						'<div class="col-md-1"><i class="btn btn-danger fa fa-minus remove" onclick="remove(s)" aria-hidden="true"></i></div>'+
						'</div>';
      $(".new_row").append(d);
       }); 
      function remove(s){
		 // alert();
            $('#s').remove();
			
        }	   
    </script>
<!----<script type="text/javascript">

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
</script>----> 