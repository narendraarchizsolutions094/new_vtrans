<div class="row" style="padding-top: 30px;">
	<div class="col-md-5" style="padding: 15px;">
		<form method="post" autocomplete="off">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Add HDFC Bank Details
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Branch Name</label>
					<input type="text" name="bank_branch" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Collection Account No.</label>
					<input type="text" name="ac_no" class="form-control" required>
				</div>
				<div class="form-group">
					<label>IFSC Code</label>
					<input type="text" name="ifsc" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Zone</label>
					<select name="zone_id" class="form-control" required>
						<?php
						if(!empty($zone_list))
						{

							foreach ($zone_list as $key => $zone)
							{
								echo'<option value="'.$zone->zone_id.'">'.$zone->name.'</option>';
							}
						}?>
					</select>
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
	<div class="col-md-7">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Zone</th>
					<th>Branch</th>
					<th>Account No</th>
					<th>IFSC</th>
				<!-- 	<th>Created At</th>
					<th>Updated At</th> -->
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($bank_list))
				{	$i=1;
					foreach ($bank_list as $key => $res)
					{
						echo'<tr>
							<td>'.$i++.'</td>
							<td>'.$res->zone_name.'</td>
							<td>'.$res->bank_branch.'</td>
							<td>'.$res->account_no.'</td>
							<td>'.$res->ifsc.'</td>
							<td>
								<div class="btn btn-group">
								<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#edit_modal" onclick="update_d('.$res->id.')">Edit</a>

								<a class="btn btn-xs btn-danger">Delete</a>
								
								</div>
							</td>
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
			url:'<?=base_url('setting/edit_bank')?>',
			type:'post',
			data:{id:id,task:'view'},
			success:function(res){
				$('#edit_box').html(res);
				$('#edit_box').find('select').select2();
			}
		});
	}
</script>
<!-- Modal -->
<div class="modal fade" id="edit_modal"  role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Bank Details</h4>
         </div>
         <div class="modal-body" id="edit_box">
         </div>
      </div>
   </div>
</div>