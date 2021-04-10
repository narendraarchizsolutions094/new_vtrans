<?php
class Target extends CI_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Target_Model');
	}

	public function index()
	{	
		$this->load->model('common_model');
		//$this->session->process = array(197);
		$this->load->model('location_model');
		$data['title'] = display('all_goals');

		$roles = $this->db->where('comp_id',$this->session->companey_id)->get('tbl_user_role')->result();

		$r = array();
		foreach ($roles as $role)
		{
			$r[] =array('id'=>$role->use_id,
						'role_name'=>$role->user_role);
		}
		$data['roles'] = json_encode($r);

		$users  = $this->common_model->getUsers($this->session->user_id,$this->session->companey_id);
		
		$u = array();
		foreach ($users as $user)
		{
			$u[] =array('id'=>$user->pk_i_admin_id,
						'user_name'=>$user->s_display_name);
		}
		$data['users'] = json_encode($u);

		$data['product_list'] = $this->location_model->productcountry();
		
		$data['content'] = $this->load->view('target/goal_list',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function chk()
	{
				$_GLOBALS['ary'] = array();
				
				$a = $this->make_tree($this->session->user_id);
			
				// foreach ($a as $key => $value)
				// {
				// 	if(is_array($value))
				// 		fetch_ary($value);
				// 	else
				// 	{
				// 		$ar[] = $value;
				// 	}
				// }
			$z = $this->reshape_ary($a);
			print_r($z);
	}

	function reshape_ary($ary)
	{	$x =array();
		foreach ($ary as $key => $value)
		{
			if(is_array($value))
			{
				$x[]  = $this->reshape_ary($value);
			}
			else if($value==$key)
			{
				return $key;
			}
		}
		return $x;
	}

	function make_tree($uid)
	{

		$main = $this->db->select('pk_i_admin_id')->where('report_to',$uid)
					->where('companey_id',$this->session->companey_id)
					->get('tbl_admin')->result();
		
		if(!empty($main))
		{	$ab =array();
			foreach ($main as $key => $value) 
			{	$x = $this->make_tree($value->pk_i_admin_id);
				$ab[$value->pk_i_admin_id] = $x;
			}
			return $ab;
		}
		else
			return $uid;
	}

	public function save_goal()
	{
		$this->load->model('Target_Model');

		if($this->input->post())
		{
			$process = $this->session->process;
			if(!empty($process))
			{
				if(is_array($process))
				{
					if(count($process)==1)
						$process_id = $process[0];
					else
					{
						$this->session->set_flashdata('message','Select Only one Process.');
					redirect($_SERVER['HTTP_REFERER']);
					}
				}
				else
				{
					$process_id = $process;
				}
			}
			else 
			{
				$this->session->set_flashdata('message','No process is Selected.');
				redirect($_SERVER['HTTP_REFERER']);
			}


			$target_list = implode(',', $this->input->post('target_list[]'));

			$prod = $this->input->post('products')??array();
			$deal_type = $this->input->post('deal_type')??array();
			$business_type = $this->input->post('business_type')??array();
			$data = array(	'goal_name'=>$this->input->post('goal_name'),
							'goal_period'=>$this->input->post('goal_period'),
							'time_range' =>$this->input->post('time_range'),
							'date_from' =>$this->input->post('period_from'),
							'date_to' =>$this->input->post('period_to'),
							'goal_type' =>$this->input->post('goal_type'),
							'goal_for' =>$target_list,
							'metric_type' =>$this->input->post('metric_type'),
							'target_value' =>$this->input->post('target_value'),
							'products' => implode(',',$prod),
							'deal_type'=>implode(',',$deal_type),
							'business_type'=>implode(',',$business_type),
							'comp_id' =>$this->session->companey_id,
							'process_id' =>$process_id,
							'created_by'=>$this->session->user_id,
						);
			

			// $chk = $this->db->from('tbl_goals')->where('( (date_from <="'.$data['date_from'].'" and date_to >="'.$data['date_to'].'" ) or ( (date_from BETWEEN "'.$data['date_from'].'" and "'.$data['date_to'].'") OR (date_to between "'.$data['date_from'].'" and  "'.$data['date_to'].'"))) and comp_id='.$data['comp_id'].' and  process_id='.$data['process_id'].' ')->count_all_results();

			// if($chk>0)
			// {
			// 	$this->session->set_flashdata('exception','Unable to create goal ! Goal date clashes with already created goal.');
			// 	redirect($_SERVER['HTTP_REFERER']);
			// }

			if($data['goal_type']=='team')
			{	
				$data['custom_target'] = json_encode($this->input->post('user_target_value'));
				$keys = array();
				foreach ($this->input->post('user_target_value') as $key => $value)
				{
					if($value>0)
						$keys[] = $key;
				}
				//exit();
				$data['team_id'] =$target_list;
				$data['goal_for'] = implode(',', $keys);
			}

			$this->Target_Model->save_goal($data);
			redirect(base_url('Target/index'));
		}	
	}

	public function load_goal_table()
	{
		//exit();
		$start_date_from = $this->input->post('start_date_from')??0;
		$start_date_to = $this->input->post('start_date_to')??0;

		$end_date_from = $this->input->post('end_date_from')??0;
		$end_date_to = $this->input->post('end_date_to')??0;

		$role  = $this->input->post('role_for')??0;
		$user = $this->input->post('user_for')==''?0:$this->input->post('user_for');
		//echo $user;exit();
		if(empty($user))
			$user = $this->session->user_id;


		$metric_type = $this->input->post('metric_type')??0;
		$fetch_type = $this->input->post('fetch_type')?2:1;

		$keyword = $this->input->post('keyword');

		$graph = $this->input->post('graph')??0;



		$where =array();
		if($start_date_from)
		{
			$where[] = " (goals.date_from >= '".$start_date_from."')";
 		}

 		if($start_date_to)
 		{
 			$where[] = " (goals.date_from <= '".$start_date_to."')";
 		}

 		if($end_date_from)
		{
			$where[] = " (goals.date_to >= '".$end_date_from."')";
 		}

 		if($end_date_to)
 		{
 			$where[] = " (goals.date_to <= '".$end_date_to."')";
 		}

 		if($role)
 		{
 			$where[] = " goals.team_id = '".$role."'";
 		}
 		
 		if($metric_type)
 		{
 			$where[] = " goals.metric_type = '".$metric_type."'";
 		}

 		if(!empty($keyword))
 		{
 			$where[] = " goals.goal_name LIKE '%".$keyword."%'";
 		}

 		//print_r($where); 
		$all_goals = $this->Target_Model->getGoals(implode(' AND ',$where),$fetch_type,$user);
		//print_r($all_goals); exit();
		// echo count($all_goals);
		// echo $this->db->last_query(); exit();
		$gval =array();
		if($graph=='1')
		{
			$gval =$this->target_graph(implode(' AND ',$where),$fetch_type,$user);
		}
		$code = '';
			$code.='<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th rowspan="2">#</th>
						<th rowspan="2">Goal Name</th>
						<th rowspan="2">GOAL PERIOD</th>
						<th rowspan="2">METRIC</th>
						<th rowspan="2">For</th>
						<th colspan="3">ATTAINMENT</th>
						<th rowspan="2" align="center">Status</th>
						<th rowspan="2">Created By</th>
						<th rowspan="2">Created At</th>
						<th rowspan="2">Action</th>
					</tr>
					<tr><th>T</th><th>F</th><th>A</th></tr>
				</thead>
				<tbody>';

				if(!empty($all_goals))
				{	$num=1;
					foreach ($all_goals as $goal)
					{
						$target = $goal->target_value;

						$for_list = $this->get_userdata($goal->goal_for);
						$html = "<ul class='list-group'>";
						foreach ($for_list as $r)
						{
							$html.="
							<li class='list-group-itme'>".$r->s_display_name."</li>
							";
						}
						$html.="</ul>";

						if($goal->goal_type=='team'){

							$goal_for_list = '<span data-toggle="tooltip" data-html="true" title="'.$html.'">
							<center>'.$goal->user_role.'</center>
							</span>';
						}
						else
						{
							$goal_for_list = '<span data-toggle="tooltip" data-html="true" title="'.$html.'">
								<center>Custom Users</center>
								</span>';
						}

						$url = base_url('target/goal_details/'.$goal->goal_id);
						if($goal->goal_type=='user')
						{
							$target *=count(explode(',', $goal->goal_for));
							$url = base_url('target/goal_details_user/'.$goal->goal_id);
						}
						$ci = &get_instance();
						$ci->load->model('Target_Model');
						//echo $fetch_type.' '.$user.'|201';exit();
						$Forecast = $ci->Target_Model->getForecast($goal->goal_id,$fetch_type,$user);
						
						$Achieved = $ci->Target_Model->getAchieved($goal->goal_id,$fetch_type,$user);
						// echo $this->db->last_query();
						// print_r($Achieved); exit();

						$forecast_value =(int)($goal->metric_type=='freight'?$Forecast->e_amnt:$Forecast->e_amnt);//$Forecast->num_value);
						$achieved_value =(int)($goal->metric_type=='freight'?$Achieved->e_amnt:$Achieved->e_amnt);

						$percent=0;
						if($target)
								$percent = round(($achieved_value/$target)*100,2);
						if($percent<30)
								$barcolor='danger';
							else if($percent>=30 && $percent<60)
								$barcolor='warning';
							else
								$barcolor='success';


						$prd = array();
		
						if(!empty($goal->products))
						{
							foreach (explode(',',$goal->products) as $p)
							{								
								$prd[] = '<label class="label label-success">'.ucfirst($p).'</label>';
							}
						}

						$code.='<tr>
							<td>'.$num++.'</td>
							<td>'.$goal->goal_name.'</td>

							<td><big>'.ucwords($goal->goal_period).'</big> <br>
							<a href="'.$url.'"><b>'.date('d-M-Y',strtotime($goal->date_from)).' - '.date('d-M-Y',strtotime($goal->date_to)).'</b></a><br>
							'.implode(' ',$prd).'
							</td>
							<td>'.ucfirst($goal->metric_type).'</td>
							<td style="cursor:pointer;">'.$goal_for_list.'</td>
							<td>'.$target.'</td>
							<td> <span data-ids="'.$Forecast->info_ids.'" onclick="view_source(this)" style="cursor:pointer">'.$forecast_value.'</span></td>
							<td> <span data-ids="'.$Achieved->info_ids.'" onclick="view_source(this)" data-toggle="tooltip" data-title="'.((float)$Achieved->info_count).'" style="cursor:pointer;">'.$achieved_value.'</span></td>
							<td style="text-align:center">
								'.$achieved_value.'/'.$target.'<br>
								<div class="progress" style="border:1px solid #cccccc;">
									  <div class="progress-bar progress-bar-'.$barcolor.' progress-bar-striped" role="progressbar"
									  aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%; max-width:100%;">
									  </div>
								</div>

								</td>
							<td>'.$goal->added_by.'</td>
							<td>'.(date('d-M-Y',strtotime($goal->created_at)).'<br>'.date('H:i A',strtotime($goal->created_at))).'</td>
							<td>
							<div class="btn-group">
							';
							if(user_access('263'))
							{
							$code.='<a href="'.base_url('target/edit_goal/'.$goal->goal_id).'"><span class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> </span></a>';
							}
							if(user_access('264'))
							{
							$code.='<a onclick="return confirm(\'Are you sure?\')" href="'.base_url('target/delete_goal/'.$goal->goal_id).'"><span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> </span></a>';
							}
							$code.='</td>
							</tr>';
					}
					
				}	
		$code.='</tbody>
		</table>';

		echo json_encode(array('code'=>$code,'graph'=>$graph,'gval'=>$gval));
	}

	public function divide_target()
	{
		$role_id = $this->input->post('role_id');
		$target_value = $this->input->post('target_value');
		$ignore = $this->input->post('ignore')!=''?explode(',',$this->input->post('ignore')):array();
		
		$this->load->model('User_model');

		$all_users = $this->User_model->get_users_by_role($role_id);

		if(!empty($all_users))
		{
			$divisor = count($all_users)-count($ignore);
			if($divisor==0)
			{
				echo'<center> <font color="red">Can not Remove All.</font></center>';
				unset($ignore[count($ignore)-1]);
				$divisor = count($all_users)-count($ignore);
			}

			$per_user = round((int)$target_value / $divisor,2);

			echo'<ul class="list-group">';

				foreach ($all_users as $res)
				{
				echo'<li class="list-group-item">
						<input type="checkbox" data-toggle="toggle" data-size="mini" value="'.$res->pk_i_admin_id.'" '.(in_array($res->pk_i_admin_id,$ignore)?'':'checked').'>
						&nbsp;
					'.$res->s_display_name.'
					<input type="number" class="form-control pull-right target_value_input" style="width:130px; height:25px;" name="user_target_value['.$res->pk_i_admin_id.']" value="'.(in_array($res->pk_i_admin_id,$ignore)?0:$per_user).'">
					</li>';
				}

			echo'</ul>';

		}
		else
		{
			echo'<center>Empty Team</center>';
		}

	}

	public function goal_details($goal_id,$user_id=0)
	{
		$this->load->model('Target_Model');

		$data['title'] = 'Goal Details';

		$goal  = $data['goal'] = $this->Target_Model->getGoals(array('goals.goal_id'=>$goal_id))[0];
		
		$prd = array();
		
		if(!empty($goal->products))
		{
			foreach (explode(',',$goal->products) as $p)
			{				
				$prd[] = ucfirst($p);
			}
		}
		$option_list = $this->common_model->get_categories($this->session->user_id);
		$op = $this->db->select('role.use_id,GROUP_CONCAT(ad.pk_i_admin_id) as ids,GROUP_CONCAT(ad.s_display_name) as names,role.user_role')
				->from('tbl_admin ad')
				->join('tbl_user_role role','ad.user_type=role.use_id','left')
				->where('ad.pk_i_admin_id IN ('.implode(',', $option_list).')')
				->group_by('role.use_id')
				->get()->result();

		$data['option_list'] = $op;
		$data['products'] = $prd;
		$data['user_id'] = empty($user_id)?$this->session->user_id:$user_id;
		$data['content'] = $this->load->view('target/goal_details',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function goal_details_user($goal_id,$user_id=0)
	{
		$this->load->model('Target_Model');

		$data['title'] = 'Goal Details';

		$goal  = $data['goal'] = $this->Target_Model->getGoals(array('goals.goal_id'=>$goal_id))[0];
		
		$prd = array();
		
		if(!empty($goal->products))
		{
			foreach (explode(',',$goal->products) as $p)
			{				
				$prd[] = ucfirst($p);
			}
		}
		$option_list = $this->common_model->get_categories($this->session->user_id);
		$op = $this->db->select('role.use_id,GROUP_CONCAT(ad.pk_i_admin_id) as ids,GROUP_CONCAT(ad.s_display_name) as names,role.user_role')
				->from('tbl_admin ad')
				->join('tbl_user_role role','ad.user_type=role.use_id','left')
				->where('ad.pk_i_admin_id IN ('.implode(',', $option_list).')')
				->group_by('role.use_id')
				->get()->result();

		$data['option_list'] = $op;
		$data['products'] = $prd;
		$data['user_id'] = $user_id;
		$data['content'] = $this->load->view('target/backup_goal_details',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function get_userdata($list)
	{
		$this->load->model('User_model');
		$data =array();
		foreach (explode(',', $list) as $user_id)
		{
			$data[] = $this->User_model->get_user_by_id($user_id);
		}
		return $data;
	}


	public function edit_goal($goal_id)
	{
		if(user_role('263')){

		}
		$this->load->model(array('location_model'));

		$data['title'] = display('edit_goal');

		$roles = $this->db->where('comp_id',$this->session->companey_id)->get('tbl_user_role')->result();
		$r = array();
		foreach ($roles as $role)
		{
			$r[] =array('id'=>$role->use_id,
						'role_name'=>$role->user_role);
		}
		$data['roles'] = json_encode($r);

		$users  = $this->db->where('companey_id',$this->session->companey_id)->get('tbl_admin')->result();
		
		$u = array();
		foreach ($users as $user)
		{
			$u[] =array('id'=>$user->pk_i_admin_id,
						'user_name'=>$user->s_display_name);
		}
		$data['users'] = json_encode($u);

		$data['product_list'] = $this->location_model->productcountry();
		
		$data['goal'] = $this->Target_Model->getGoals(array('goals.goal_id'=>$goal_id))[0];

		$data['content'] = $this->load->view('target/edit_goal',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function update_goal()
	{
		if(user_role('263')){}
		$this->load->model('Target_Model');

		if($this->input->post())
		{	
			$goal_id = $this->input->post('goal_id');

			$target_list = implode(',', $this->input->post('target_list[]'));
			$deal_type = $this->input->post('deal_type')??array();
			$business_type = $this->input->post('business_type')??array();

			$prod = $this->input->post('products')??array();
			$data = array('goal_period'=>$this->input->post('goal_period'),
							'time_range' =>$this->input->post('time_range'),
							'date_from' =>$this->input->post('period_from'),
							'date_to' =>$this->input->post('period_to'),
							'goal_type' =>$this->input->post('goal_type'),
							'goal_for' =>$target_list,
							'metric_type' =>$this->input->post('metric_type'),
							'deal_type'=>implode(',',$deal_type),
							'business_type'=>implode(',',$business_type),
							'target_value' =>$this->input->post('target_value'),
							'products' => implode(',',$prod),
						);
			
			if($data['goal_type']=='team')
			{	
				$data['custom_target'] = json_encode($this->input->post('user_target_value'));
				$keys = array();
				foreach ($this->input->post('user_target_value') as $key => $value)
				{
					if($value>0)
						$keys[] = $key;
				}
				//exit();
				$data['team_id'] =$target_list;
				$data['goal_for'] = implode(',', $keys);
			}

			$this->Target_Model->update_goal(array('goal_id'=>$goal_id),$data);
			$this->session->set_flashdata('SUCCESSMSG','Goal Successfully Updated.');
			redirect(base_url('Target/index'));
		}	
	}
	public function delete_goal($goal_id)
	{
		if(user_role('264')){}
		$this->db->where('goal_id',$goal_id)->delete('tbl_goals');
		$this->session->set_flashdata('message','Deleted Successfully');
		redirect(site_url('target'));
	}
	public function graphs($chk=0)
	{
		$this->load->model('common_model');
		// print_r($this->common_model->get_categories($chk));

		// exit();
		$data['title'] = 'Target & Forecasting';

		$users  =$this->common_model->getUsers($this->session->user_id,$this->session->companey_id);
		
		$u = array();
		foreach ($users as $user)
		{
			$u[] =array('id'=>$user->pk_i_admin_id,
						'user_name'=>$user->s_display_name);
		}
		$data['users'] = json_encode($u);
		$data['content'] = $this->load->view('target/target_graphs',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function target_graph($where,$fetch_type,$user)
	{
		// $fetch_type = $this->input->post('fetch_type');
		// $user = $this->input->post('user_for')==''?0:$this->input->post('user_for');
		//echo $user; exit();
		$all_goals = $this->Target_Model->getGoals($where,$fetch_type,$user);
		//print_r($all_goals); exit();
		$total_target['deal'] = 0;
		$total_forecast['deal'] = 0;
		$total_achieved['deal'] = 0;
		$total_info_count_forecast['deal'] = 0;
		$total_enq_count_forecast['deal'] = 0;
		$total_info_count_achieved['deal'] = 0;
		$total_enq_count_achieved['deal'] = 0;

		$total_target['won'] = 0;
		$total_forecast['won'] = 0;
		$total_achieved['won'] = 0;
		$total_info_count_forecast['won'] = 0;
		$total_enq_count_forecast['won'] = 0;
		$total_info_count_achieved['won'] = 0;
		$total_enq_count_achieved['won'] = 0;

		foreach ($all_goals as $goal)
		{
			$count_goals[] = $goal->goal_id;
 			$target = $goal->target_value;

			if($goal->goal_type=='user')
				$target *=count(explode(',', $goal->goal_for));
			$ci = &get_instance();
			$ci->load->model('Target_Model');
			
			$Forecast = $ci->Target_Model->getForecast($goal->goal_id,$fetch_type,$user);
			$Achieved = $ci->Target_Model->getAchieved($goal->goal_id,$fetch_type,$user);

			$forecast_value =(int)($goal->metric_type=='deal'?$Forecast->p_amnt:$Forecast->num_value);
			$achieved_value =(int)($goal->metric_type=='deal'?$Achieved->p_amnt:$Achieved->num_value);

			//print_r($Forecast);exit();
				$total_target[$goal->metric_type] 	+= $target;
				$total_forecast[$goal->metric_type] += $forecast_value;
				$total_achieved[$goal->metric_type] += $achieved_value; 

				$total_info_count_forecast[$goal->metric_type]+=$Forecast->info_count;
				$total_enq_count_forecast[$goal->metric_type]+= $Forecast->enq_count;

				$total_info_count_achieved[$goal->metric_type]+=$Achieved->info_count;
				$total_enq_count_achieved[$goal->metric_type]+= $Achieved->enq_count;

		}

		$final = array('deal'=>array('target'=>$total_target['deal'],
										'forecast'=>array(
															'value'=>$total_forecast['deal'],
															'info_count'=>$total_info_count_forecast['deal'],
															'enq_count'=>$total_enq_count_forecast['deal'],
													),
										'achieved'=>array(
													'value'=>$total_achieved['deal'],
													'info_count'=>$total_info_count_achieved['deal'],
													'enq_count'=>$total_enq_count_achieved['deal'],
										),

									),
						'won'=>array('target'=>$total_target['won'],
										'forecast'=>array(
															'value'=>$total_forecast['won'],
															'info_count'=>$total_info_count_forecast['won'],
															'enq_count'=>$total_enq_count_forecast['won'],
													),
										'achieved'=>array(
													'value'=>$total_achieved['won'],
													'info_count'=>$total_info_count_achieved['won'],
													'enq_count'=>$total_enq_count_achieved['won'],
												),

									),
					
					);

		return $final;

	}

	public function current_year_target_forecast()
	{
		$this->load->model('common_model');

		$post = array();
		if(!empty($_POST['datas']))
			$post  =(array) json_decode($_POST['datas']);


		if(!empty($post['users']))
		{
		$all_reporting_ids = $this->common_model->get_categories($post['users']);
		}
		else
		{	
		$all_reporting_ids = $this->common_model->get_categories($this->session->user_id);
		}


		$data = array();
		for($i=1; $i<=12;$i++)
		{
			if($i<10)
			{
				$date = date('Y').'-0'.$i;
				$prev  = ((int)date('Y')-1).'-0'.$i;
			}
			else
			{
				$date = date('Y').'-'.$i;
				$prev  = ((int)date('Y')-1).'-'.$i;
			}

			//this year forecast
			$this->db->select('sum(info.expected_amount) as e_amnt,sum(info.potential_amount) as p_amnt,COUNT(DISTINCT(info.id)) as info_count,GROUP_CONCAT(info.id) as info_ids,COUNT(DISTINCT(enquiry.enquiry_id)) as enq_count,GROUP_CONCAT(DISTINCT(enquiry.enquiry_id)) as enq_ids');

			$this->db->from('enquiry')
				->join('commercial_info info','info.enquiry_id = enquiry.enquiry_id and info.status IN (0,1)','inner');

			$this->db->where('enquiry.lead_expected_date LIKE "%'.$date.'%"');

			if(!empty($post['from_date']))
				$this->db->where('enquiry.lead_expected_date >= "'.$post['from_date'].'"');

			if(!empty($post['to_date']))
				$this->db->where('enquiry.lead_expected_date <= "'.$post['to_date'].'"');

			$this->db->where_in('info.createdby ',$all_reporting_ids);
			$this->db->where_in('enquiry.product_id',$this->session->process);
			//$this->db->where('enquiry.status=2');
			$res = $this->db->get()->row();
			//echo $this->db->last_query(); exit();
			$data[0]['forecast'][]  = (float)$res->p_amnt;

			//prev year forecast

			$this->db->select('sum(info.expected_amount) as e_amnt,sum(info.potential_amount) as p_amnt,COUNT(DISTINCT(info.id)) as info_count,GROUP_CONCAT(info.id) as info_ids,COUNT(DISTINCT(enquiry.enquiry_id)) as enq_count,GROUP_CONCAT(DISTINCT(enquiry.enquiry_id)) as enq_ids');

			$this->db->from('enquiry')
				->join('commercial_info info','info.enquiry_id = enquiry.enquiry_id and info.status IN (0,1)','inner');
			$this->db->where('enquiry.lead_expected_date LIKE "%'.$prev.'%"');	
			
			if(!empty($post['from_date']))
				$this->db->where('enquiry.lead_expected_date >= "'.$post['from_date'].'"');

			if(!empty($post['to_date']))
				$this->db->where('enquiry.lead_expected_date <= "'.$post['to_date'].'"');

			$this->db->where_in('info.createdby',$all_reporting_ids);
			$this->db->where_in('enquiry.product_id',$this->session->process);

			$res = $this->db->get()->row();

			$data[1]['forecast'][]  = (float)$res->p_amnt;


			// this year achieved

			$this->db->select('sum(info.expected_amount) as e_amnt,sum(info.potential_amount) as p_amnt,COUNT(DISTINCT(info.id)) as info_count,GROUP_CONCAT(info.id) as info_ids,COUNT(DISTINCT(enquiry.enquiry_id)) as enq_count,GROUP_CONCAT(DISTINCT(enquiry.enquiry_id)) as enq_ids');
			
		
			$this->db->from('enquiry')
				->join('commercial_info info','info.enquiry_id=enquiry.enquiry_id  and info.status=1','inner');


			$this->db->where('enquiry.client_created_date LIKE "%'.$date.'%"');
			

			if(!empty($post['from_date']))
				$this->db->where('enquiry.client_created_date >= "'.$post['from_date'].'"');

			if(!empty($post['to_date']))
				$this->db->where('enquiry.client_created_date <= "'.$post['to_date'].'"');

			$this->db->where_in('info.createdby',$all_reporting_ids);
			$this->db->where_in('enquiry.product_id',$this->session->process);	

			$res = $this->db->get()->row();

			$data[0]['achieved'][]  = (float)$res->p_amnt;

			//prev year achieved

			$this->db->select('sum(info.expected_amount) as e_amnt,sum(info.potential_amount) as p_amnt,COUNT(DISTINCT(info.id)) as info_count,GROUP_CONCAT(info.id) as info_ids,COUNT(DISTINCT(enquiry.enquiry_id)) as enq_count,GROUP_CONCAT(DISTINCT(enquiry.enquiry_id)) as enq_ids');
			
		
			$this->db->from('enquiry')
				->join('commercial_info info','info.enquiry_id=enquiry.enquiry_id  and info.status=1','inner');
				
			$this->db->where('enquiry.client_created_date LIKE "%'.$prev.'%"');

			if(!empty($post['from_date']))
				$this->db->where('enquiry.client_created_date >= "'.$post['from_date'].'"');

			if(!empty($post['to_date']))
				$this->db->where('enquiry.client_created_date <= "'.$post['to_date'].'"');

			$this->db->where_in('info.createdby',$all_reporting_ids);
			$this->db->where_in('enquiry.product_id',$this->session->process);

			$res = $this->db->get()->row();

			$data[1]['achieved'][]  = (float)$res->p_amnt;

		}
		echo json_encode($data);
	}
}
?>