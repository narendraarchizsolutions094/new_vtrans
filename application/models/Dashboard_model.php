<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Dashboard_model extends CI_Model {



	private $table = "tbl_admin"; 

	public function check_userByID($user_id)
	{
		$user=$this->db->where(array('user_id'=>$user_id,'user_role'=>2))->get('user');
		return $user;
	}
	public function check_userByMAIL($email)
	{
		$check_user=  $this->db->select("*")
         ->from('tbl_admin')
         ->join('user','user.user_id = tbl_admin.companey_id','left')			
         ->where(array('s_user_email'=>$email))
		 ->get();
		 return $check_user;
	}
 

	public function check_user($data = []){

		$where = "(tbl_admin.s_user_email='".$data['email']."' OR tbl_admin.s_phoneno='".$data['email']."') AND tbl_admin.s_password='".$data['password']."' AND tbl_admin.b_status = 1 AND user.status = 1";
		
		return $this->db->select("*")
			->from($this->table)
			->join('user','user.user_id = tbl_admin.companey_id','left')			
			->where($where)
			->get();

	} 

	public function check_user_by_mail_phone($data = []){
		if(!empty($data['email']) || !empty($data['phone'])){
			$where = '';
			if(!empty($data['email'])){
				$where = "tbl_admin.s_user_email='".$data['email'].'\'';
			}
			if(!empty($data['phone'])){
				if(!empty($where)){
					$where 	.=	" OR tbl_admin.s_phoneno='".$data['phone'].'\'';
				}else{
					$where 	.=	"tbl_admin.s_phoneno='".$data['phone'].'\'';
				}
			}
			
			return $this->db->select("*")
			->from($this->table)
			->join('user','user.user_id = tbl_admin.companey_id','left')			
			->where($where)
			->get()->row();
		}else{
			return false;
		}

	} 

	public function find_user_by_email($email){
		return $this->db->select("*")
			->from($this->table)
			->join('user','user.user_id = tbl_admin.companey_id','left')
			->where('tbl_admin.s_user_email',$email)			
			->where('tbl_admin.b_status',1)
			->get()
			->row();
	} 

  
	public function check_user_enquiry($user_id)

	{

		return $this->db->select("*")

			->from($this->table)

			->join('user','user.user_id = tbl_admin.companey_id','left')
			->where('tbl_admin.pk_i_admin_id',$user_id)

			->where('tbl_admin.b_status',1)

			->get();

	}
 


	public function check_patient($data = [])

	{

		return $this->db->select("*")

			->from("patient")

			->where('email',$data['email'])

			->where('password',$data['password'])

			->where('status',1)

			->get();

	}  



	public function read_by_id($user_id = null)

	{

		return $this->db->select("user.*, department.name AS department")

			->from('user')

			->join('department', 'department.dprt_id = user.department_id', 'left')

			->where('user.user_id',$user_id)

			->get()

			->row();

	} 



	public function profile($user_id = null)

	{

		return $this->db->select("*")

			->from("user") 

			->where('user_id', $user_id)

			->get()

			->row();

	} 

	public function enquiry()

	{

		return $this->db->select('enquiry_id, name, email, enquiry')

			->from('enquiry')

			->limit(4)

			->order_by('checked','asc')

			->order_by('created_date','desc')

			->order_by('enquiry_id','desc')

			->get()

			->result();

	}



 

	public function update($data = [])
	{
		return $this->db->where('user_id',$data['user_id'])
			->update("user" ,$data); 
	}
	//Get city,state,teritory,Region according to city...

	public function location_base_country($country){

	    

         $tbl_region = $this->db->select('*')->from('tbl_region')->where('country_id',$country)->get()->result();

         

         $tbl_territory = $this->db->select('*')->from('tbl_territory')->where('country_id',$country)->get()->result();

         

         $state = $this->db->select('*')->from('state')->where('country_id',$country)->get()->result();

         

         $city = $this->db->select('*')->from('city')->where('country_id',$country)->get()->result();

         

         return array('region'=>$tbl_region,'territory'=>$tbl_territory,'state'=>$state,'city'=>$city);

	    

	}

	

	public function all_locations(){

	    

	     $tbl_region = $this->db->select('*')->from('tbl_region')->get()->result();

         

         $tbl_territory = $this->db->select('*')->from('tbl_territory')->get()->result();

         

         $state = $this->db->select('*')->from('state')->get()->result();

         

         $city = $this->db->select('*')->from('city')->get()->result();

         

         return array('region'=>$tbl_region,'territory'=>$tbl_territory,'state'=>$state,'city'=>$city);

	}

    

    //change password...

    public function change_pass($email){

        

        $datas = array('reset_password'=>1);

        

            $data = $this->db->select('pk_i_admin_id,companey_id,s_user_email,s_password,reset_password')

                        ->from('tbl_admin')

                        ->where('s_user_email',$email)

                        ->get()

                        ->row();

                        

                if($data==true){

                    

                     $this->db->where('s_user_email',$email)->update('tbl_admin',$datas);

                     

                     return $data;

                }

                        

            

    }

    public function getUserDataByPhone($mob)
    {
    	if(!empty($mob))
    	{
    		return $this->db->select("*")->from('tbl_admin')->where('s_phoneno',$mob)->get()->row();
    	}
    	else
    	{
    		return "";
    	}

    }

    

    //change password

    public function set_new_pass($user,$data){

        

        $this->db->where('pk_i_admin_id',$user)

                        ->update('tbl_admin',$data);

                        

            if($this->db->affected_rows() >=0){

                

              return true; 

              

            }else{

                

              return false; //add your your code here

              

            }

    }

    

    //check link is dissabled or not

    public function disabl_reset_link($user){

        

        return $this->db->select('reset_password')

                        ->from('tbl_admin')

                        ->where('pk_i_admin_id',$user)

                        ->get()

                        ->row();

	}
	public function countLead_api($type,$comp_id=0,$user_id=0){
	
		$comp_id = $this->session->userdata('companey_id')??$comp_id;

		return $this->db->where(array('comp_id' => $comp_id,'type'=>$type))->count_all_results('tbl_followupAvgtime');
    }

	public function dataLead_api($type,$comp_id=0,$user_id=0){
		$comp_id = $this->session->userdata('companey_id')??$comp_id;

		return $this->db->select_sum('time')->where(array('comp_id' =>$comp_id,'type'=>$type))->get('tbl_followupAvgtime');
	}
    public function countLead($type,$comp_id=0,$user_id=0,$process=0){

		$userid= !empty($user_id)?$user_id:$this->session->user_id;

    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
		
		$comp_id = $this->session->userdata('companey_id')??$comp_id;
        $where=" enquiry.comp_id=$comp_id";

        if(!empty($_POST))
        {
            $filter=json_encode(array(
                'from_date'=>$_POST['from_date']??'',
                'to_date'=>$_POST['to_date']??'',
                'users'=>$_POST['users']??'',
                'state_id'=>$_POST['state_id']??'',
                'city_id'=>$_POST['city_id']??'',
                              ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                // $where.=" AND enquiry.created_date >='$from_date'";
                // $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $arr = !empty($process)?$process:$this->session->process; 

        if(is_array($arr)){
           $arr = implode(',', $arr);
	        $where.=" AND enquiry.product_id IN (".$arr.')';
        } 

		return $this->db->join('enquiry','enquiry.enquiry_id=tbl_followupAvgtime.enq_id')->where(array('tbl_followupAvgtime.type'=>$type))->where($where)->count_all_results('tbl_followupAvgtime');

    }
	public function dataLead($type,$comp_id=0,$user_id=0,$process=0){
		$userid=!empty($user_id)?$user_id:$this->session->user_id;
		$all_reporting_ids    =   $this->common_model->get_categories($userid);
		
		$comp_id = $this->session->userdata('companey_id')??$comp_id;
        $where=" enquiry.comp_id=$comp_id";

        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
		}
		// if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
		// 	$from_date=$_POST['from_date'];
		// 	$to_date=$_POST['to_date'];
		// 	$where.=" AND `enquiry.created_date` >=$from_date";
		// 	$where.=" AND enquiry.created_date <=$to_date";
			
		// }
        $arr = !empty($process)?$process:$this->session->process;           
       if(is_array($arr)){
           $arr = implode(',', $arr);
        } 
        $where.=" AND enquiry.product_id IN (".$arr.')';     

		$comp_id = $this->session->userdata('companey_id')??$comp_id;
		return $this->db->select_sum('tbl_followupAvgtime.time')->where(array('tbl_followupAvgtime.type'=>$type))->where($where)->join('enquiry','enquiry.enquiry_id=tbl_followupAvgtime.enq_id')->get('tbl_followupAvgtime');

	}
	public function getfistMonth($type,$msgType)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where ='';
		$where .= "( created_by IN (" . implode(',', $all_reporting_ids) . '))';
		$data= $this->db->where($where)->where(array('comp_id'=> $this->session->companey_id,'type'=>$type,'msg_type'=>$msgType))
						->limit(1)->get('msg_logs');
		if($data->num_rows()==1){
			return $data->row()->created_at;
		}else{
			return false;
		}
	}
	public function getMsgLogUsers($type)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where = '';
		$where .= "( msg_logs.created_by IN (" . implode(',', $all_reporting_ids) . '))';

		$users= $this->db->select('tbl_admin.picture,msg_logs.created_by,msg_logs.comp_id,msg_logs.type,msg_logs.created_by,msg_logs.msg_type,tbl_admin.pk_i_admin_id,tbl_admin.last_name,tbl_admin.s_display_name')->distinct('created_by')
		->where($where)
		->where(array('msg_logs.comp_id'=> $this->session->companey_id,'msg_logs.type'=>$type))
		->join('tbl_admin','tbl_admin.pk_i_admin_id=msg_logs.created_by')
		->get('msg_logs');
		return $users;	
	}
	public function getdataFromdate($idate,$type,$msgType)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where ='';
		$where .= "( created_by IN (" . implode(',', $all_reporting_ids) . '))';
		$count = $this->db->where($where)->where(array('comp_id'=>$this->session->companey_id,'type'=>$type,'msg_type'=>$msgType))->like('created_at', $idate)->count_all_results('msg_logs');
		return $count;
	}

	public function secsToStr_api($minutes) 
	{
	    $d = floor ($minutes / 1440);
	    $h = floor (($minutes - $d * 1440) / 60);
	    $m = $minutes - ($d * 1440) - ($h * 60);
	    $m = round($m);
	    if($d!=0){
	        return  $d.' days '.$h.' hours '.$m.' minutes ';
	    }elseif($h!=0){
	        return $h.' hours '.$m.' minutes ';
	    }else{
	        return  $m.' minutes ';
	    }
    
	}

}