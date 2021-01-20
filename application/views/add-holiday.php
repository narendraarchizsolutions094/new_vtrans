<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>    
<div class="row">
	<div class="col-lg-12 ">
		<?php
		if($suc = $this->session->flashdata('SUCCESSMSG'))
			echo '<div class="alert alert-success">'.$suc.'</div>';
		?>
		
		<form action="" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">Add Holiday</div>
			<div class="panel-body">
				<div class="row">
				<div class="form-group col-lg-4">
					<label>Festival Name</label>
					<select name="festival" class="form-control" required>
						<?php
						echo "<option value=''>--- Select Festival---</option>";
							if(!empty($festivals))
							{	$i=1;
								foreach ($festivals->result_array() as $res)
								{
								echo'<option value="'.$res['id'].'">'.$res['festival_name'].'</option>';
								}
							}
						?>
					</select>
				</div>
				<div class="form-group col-lg-4">
					<label>Date From</label>
					<input   name="datefrom" class="form-control form-date" value="<?=date('Y-m-d')?>" required>
				</div>

				<div class="form-group col-lg-4">
					<label>Date To</label>
					<input name="dateto" class="form-control form-date" value="<?=date('Y-m-d')?>" required>
				</div>

				
				<div class="form-group col-lg-3">
					<label>State</label>
					<select name="state" class="form-control" onchange="find_city(this.value)" required>
						<?php
						echo "<option value=''>--- Select State---</option>";
							if(!empty($state))
							{	$i=1;
								foreach ($state as $res)
								{
								echo'<option value="'.$res->id.'">'.$res->state.'</option>';
								}
							}
						?>
					</select>
				</div>
			<!-- 	<div class="form-group col-lg-3">
					<label>Territory</label>
					<select name="territory" class="form-control" onchange="find_city(this.value)">
					</select>
				</div> -->
				<div class="form-group col-lg-3">
					<label>City</label>
					<select name="city[]" class="form-control multiple" multiple required>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-lg-3">
					<label>Status</label><br>
					<input type="radio" name="status" value="1" checked> Active &nbsp; &nbsp;
					<input type="radio" name="status" value="0" required> Inactive<br>
				</div>
			</div>
			</div>
			<div class="panel-footer">
				<button class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
		</form>
	</div>

	<div class="col-lg-12">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SR.</th>
					<th>Festival Name</th>
					<th>Date</th>
					<th>Day</th>
					<th>State</th>
					<th>City</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($holiday_table))
			{	$i=1;
				foreach ($holiday_table as $row)
				{
					echo'<tr>
							<td>'.$i++.'</td>
							<td>'.$row['festival_name'].'</td>
							<td>'.$row['datefrom'].' TO '.$row['dateto'].'</td>
							<td>'.(date('l',strtotime($row['datefrom']))).'</td>
							<td>'.$row['state_name'].'</td>
							<td>'.$row['city_name'].'</td>
							<td style="text-align:center">'.($row['status']?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>').'</td>
							<td><a href="'.base_url('holiday/edit-holiday/').$row['id'].'"><i class="fa fa-edit text-primary"></i></a> &nbsp; <i class="fa fa-trash text-danger" onclick="delete_holiday('.$row['id'].')"></i></td>
						</tr>';
				}
			}
			?>
			</tbody>
			
		</table>
	</div>
</div>
<script type="text/javascript">
	 $('.multiple').select2({});  

	 function delete_holiday(id) 
	 {
	 	if(confirm("Delete?"))
	 	{
	 		location.href="<?=base_url('Holiday/delete-holiday/')?>"+id;
	 	}
	 }
           
function find_city() 
{
    
    var state = $("select[name=state]").val();
    
    $.ajax({
    type: 'POST',
    url: '<?= base_url('location/select_city_by_state');?>',
    data: {state_id:state},
    
    success:function(data){
        //alert(data);
        var html='';
        var obj = JSON.parse(data);
        
        html='';
        for(var i=0; i <(obj.length); i++){
            
            html +='<option value="'+(obj[i].id)+'">'+(obj[i].city)+'</option>';
        }
        
        $("select[name='city[]']").html(html);
        
    }
  });
}
         
        </script>