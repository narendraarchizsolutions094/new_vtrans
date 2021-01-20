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
			<div class="form-group">
				<label>Metric Type: <?=($goal->metric_type=='deal')?'Deal Value':'Won Deals'?></label>
			</div>
			<div class="form-group">
				<label>Goal For: <?=($goal->goal_type=='user')?'User':'Role'?></label>
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
				<label>Prodcuts : '.implode(' , ',$products).'</label>
				</div>';
			}
			?>

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
							$this->load->model('Target_Model');

							if($goal->goal_type=='team')
							{
								$custom_target = (array)json_decode($goal->custom_target);
							}
							else if($goal->goal_type=='user')
							{
								$target= $goal->target_value;
							}

							foreach (explode(',', $goal->goal_for) as $user_id)
							{
								$user_forecast = $this->Target_Model->getUserWiseForecast($goal->goal_id,$user_id);
								$user_achieved= $this->Target_Model->getUserWiseAchieved($goal->goal_id,$user_id);

								if($goal->goal_type=='team')
								{
									$target = $custom_target[$user_id];
								}
								

								$foracast_value =(int) ($goal->metric_type=='deal'?$user_forecast->p_amnt:$user_forecast->num_value);

								$achieved_value =(int) ($goal->metric_type=='deal'?$user_achieved->p_amnt:$user_achieved->num_value);
								

								if($target)
									$percent = round(($achieved_value/$target)*100,2);

								if($percent<30)
									$barcolor='danger';
								else if($percent>=30 && $percent<60)
									$barcolor='warning';
								else
									$barcolor='success';

								echo'<tr>
									<td>'.$user_forecast->s_display_name.'</td>
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
</script>
<style type="text/css">
	.content{
		min-height: 650px;
	}
</style>