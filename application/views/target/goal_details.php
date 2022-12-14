<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>        
<!--           <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#add_goal" title="Add Goal"></a>   -->       
        </div>
</div>
<style type="text/css">
	.monthtab
	{
		font-size: 14px;
		margin:10px;
	}	
</style>
<div class="row" style="margin: 17px 5px;">
<?php
// $start    = new DateTime($goal->date_from);
// $start->modify('first day of this month');
// $end      = new DateTime($goal->date_to);
// $end->modify('first day of next month');
// $interval = DateInterval::createFromDateString('1 month');
// $period   = new DatePeriod($start, $interval, $end);

// foreach ($period as $dt)
// {
//     echo "<div class='label label-".(true?'success':'default')." monthtab'>".$dt->format("Y-m"). "</div>";
// }

?>
</div>

<div class="row" style="padding: 15px;">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?=ucwords($goal->goal_period)?><br>
			<small><b><?=$goal->date_from.' - '.$goal->date_to?></b></small>
		</div>
		<div class="panel-body">
			<div style='border:1px solid gray'>
			<div class="form-group">
				<label>Goal Name : <?=$goal->goal_name;?></label>
			</div>
			
			<div class="form-group">
				<label>Metric Type : <?=ucfirst($goal->metric_type)?></label>
			</div>
			<div class="form-group">
				<label>Deal Type : <?=ucwords($goal->deal_type)?></label>
			</div>
			<div class="form-group">
				<label>Business Type : <?=ucwords($goal->business_type)?></label>
			</div>
			<div class="form-group">
				<label>Goal For : <?=($goal->goal_type=='user')?'User':'Role'?></label>
			</div>
			
			<?php

			if($goal->goal_type=='team')
			{
				echo'<div class="form-group">
				<label>Role : '.ucwords($goal->user_role).'</label>
				</div>';
			}
			//print_r($products);
			if(!empty($goal->products))
			{
				echo'<div class="form-group">
				<label>Booking Type : '.implode(' , ',$products).'</label>
				</div>';
			}			

			?>
			</div>
		<div class="row">
			<?php

			foreach ($option_list as $key => $opt)
			{
			
			echo'<div class="col-md-4">
				<label>'.$opt->user_role.'</label>
				<select class="form-control" onchange="makeTable(this.value)">';
				$id_list = explode(',', $opt->ids);
				$name_list = explode(',', $opt->names);
				echo'<option value="">Select</option>';
				foreach ($id_list as $key => $op)
				{
					
					echo'<option value="'.$op.'" '.($op==$user_id?'selected':'').'>'.$name_list[$key].'</option>';
				}

				echo'
					</select>
				</div>';

			}

			?>
		</div>
			<br>
		<table class="table  table-bordered table-striped">
				<thead>
					<tr>
						<th>User</th>
						<th>Target</th>
						<th>Forecast</th>
						<th>Achieved</th>
						<th>Status</th>
						<th>Created By</th>
						<th>Created At</th>
					</tr>
				</thead>
				<?php
						if(!empty($goal->goal_for))
						{
							$target = 0;
							$this->load->model('Target_Model');

							// if($goal->goal_type=='team')
							// {
							// 	$custom_target = (array)json_decode($goal->custom_target);
							// }
							// else if($goal->goal_type=='user')
							// {
							// 	$target= $goal->target_value;
							// }
							
							$user_list = $this->common_model->get_categories($user_id);
						
							foreach ($user_list as $user_id)
							{
								$user_forecast= $this->Target_Model->getForecast($goal->goal_id,2,$user_id);
								$user_achieved= $this->Target_Model->getAchieved($goal->goal_id,2,$user_id);
								// echo "<pre>";
								// print_r($user_forecast);
								// print_r($user_achieved);
								//exit();
								//$use_target = $this->Target_Model->ge
								$user_target = $this->Target_Model->getTarget($goal->goal_id,2,$user_id);
								if(!empty($user_target[0])){
									$user_target = $user_target[0];
								}
								//continue;
								// echo "<pre>";
								// print_r($user_target);
								
								if(!($user_forecast || $user_achieved))
									continue;

								if($goal->goal_type=='team' && !empty($user_target->target_value))
								{
									$target = $user_target->target_value;
								}
								//echo $target;
								$userdata = $this->db->select('user.s_display_name,user.last_name,role.user_role')
										->from('tbl_admin user')
										->join('tbl_user_role role','user.user_type=role.use_id','left')
										->where('user.pk_i_admin_id',$user_id)
										->get()->row();

								$foracast_value =(int)$user_forecast->e_amnt;

								$achieved_value =(int)$user_achieved->e_amnt;
								
								$percent = 0;
								if(!empty($target))
									$percent = round(($achieved_value/$target)*100,2);

								if($percent<30)
									$barcolor='danger';
								else if($percent>=30 && $percent<60)
									$barcolor='warning';
								else
									$barcolor='success';

								echo'<tr>
									<td>'.$userdata->s_display_name.' ['.$userdata->user_role.']</td>
									<td>'.$target.'</td>
									<td> <span data-ids="'.$user_forecast->info_ids.'" onclick="view_source(this)" style="cursor:pointer">'.$foracast_value.'</span></td>
									<td>  <span data-ids="'.$user_achieved->info_ids.'" onclick="view_source(this)" style="cursor:pointer">'.$achieved_value.'</span>
										'.($goal->metric_type=='won'?'<br>Deal Value: '.(float)$user_achieved->p_amnt:'').'
									</td>
									<td style="text-align:center">
									'.$achieved_value.'/'.$target.'<br>
									<div class="progress" style="border:1px solid #cccccc;">
										  <div class="progress-bar progress-bar-'.$barcolor.' progress-bar-striped"  role="progressbar"
										  aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%; max-width:100%;">
										  </div>
									</div>
									</td>
									<td>'.$goal->added_by.'</td>
									<td>'.(date('d-M-Y',strtotime($goal->created_at)).'<br>'.date('H:i A',strtotime($goal->created_at))).'</td>
								</tr>';
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
function view_source(t)
{
	var list = $(t).data('ids');
	if(list!='')
	{
		var url ="<?=base_url('client/deals/')?>"+btoa(list);
		window.open(url,'_blank');
	}
}

function makeTable(v)
{
	//alert(v);
	var link ="<?=base_url('target/goal_details/'.$goal->goal_id)?>"+"/"+v;
	location.href=link;
}

</script>
<style type="text/css">
	.content{
		min-height: 650px;
	}
</style>