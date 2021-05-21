<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Visits extends REST_Controller {
  function __construct() 
  {
      parent::__construct();
      $this->load->library('form_validation');
	  $this->load->model(array('enquiry_model','common_model','user_model'));
  }
  
  public function abs_diff($v1, $v2) {
        $diff = $v1 - $v2;
        return $diff < 0 ? (-1) * $diff : $diff;
    }

  	public function visit_list_page_post()
    {
	  $visit_id= $this->input->post('visit_id')??0;
      $user_id= $this->input->post('user_id');
      $process_id= $this->input->post('process_id');
      $company_id = $this->input->post('company_id');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;

      if(strpos(',',$process_id) !== false) 
      {
        $process = implode(',',$process_id);
      }
      else
      {
        $process = $process_id;
      }


       $res= array();
    
        $total = $this->enquiry_model->visit_list_api($company_id,$user_id,$process,$visit_id)->num_rows();
        $minus=0;
$result =array();
        $data['result'] = $this->enquiry_model->visit_list_api($company_id,$user_id,$process,$visit_id,$limit,$offset);
         //echo "<pre>";
      //   print_r($_POST);
      //   print_r($data['result']->result_array());
       //echo $this->db->last_query();
       //exit;
		foreach($data['result']->result() as $key=> $value)
    {
			$ades = $value->actualDistance;
			$ides = $value->idealDistance;
		
		//print_r($data['result']->result());exit;
//add code for color coding in table		
		        $percentChange=0;
            $km_rate = $this->user_model->get_user_meta($user_id,array('km_rate'));
            if(!empty($km_rate['km_rate'])){$rate= $km_rate['km_rate'];}else{
              $rate=10;
              }
            $totalpay=($ades)*$rate;
            $idealamt=($ides)*$rate;
         if($idealamt > 0 && $totalpay > 0){
         $dif= $this->abs_diff($idealamt,$totalpay);
             $percentChange = (($totalpay - $idealamt) / $idealamt)*100;
                }
 //add code for color coding in table
                //checing for min max difference filter

        if(!empty($_POST['filters']['min_diff']) || !empty($_POST['filters']['max_diff']))
        {
            if(!empty($_POST['filters']['min_diff']))
            {
                $min = $_POST['filters']['min_diff'];
                if($percentChange < $min)
                {
                  $minus++;
                  continue;
                }
            }
            
            if(!empty($_POST['filters']['max_diff']))
            {
                $max = $_POST['filters']['max_diff'];
                if($max>$percentChange)
                  { $minus++;
                  continue;
                }
            }
        }

        


        
        $visit_totalexp= $this->db->where(array('tbl_expense.visit_id'=> $value->id))->count_all_results('tbl_expense');
        $visit_reject= $this->db->where(array('tbl_expense.visit_id'=> $value->id,'approve_status' => 1))->count_all_results('tbl_expense');
        $visit_approve= $this->db->where(array('tbl_expense.visit_id'=> $value->id,'approve_status' => 2))->count_all_results('tbl_expense');
        $visit_pending= $this->db->where(array('tbl_expense.visit_id'=> $value->id,'approve_status' => 0))->count_all_results('tbl_expense');
        $expstatus='N/A';
        if($visit_totalexp!=0)
        {
            if($visit_reject==$visit_totalexp){
               $expstatus='Rejected ';
            }elseif($visit_approve==$visit_totalexp){
               $expstatus='Approved';
            }elseif($visit_pending==$visit_totalexp){
               // $expstatus='Pending';
               $expstatus='Pending';
            }elseif($visit_reject!=0 AND $visit_approve!=0 OR $visit_pending!=0){
              $expstatus='Partial';
            }         
        }
       // echo $expstatus.'<br>';
        //continue;

         $type = 0;               
         if(!empty($_POST['filters']['expence'])){ //expence status filter
            $type = $_POST['filters']['expence'];
            if($type=='1' && $expstatus!='Approved'){
               $minus++;
               continue;
            }
            if($type=='2' and $expstatus!='Pending'){
               $minus++;
               continue;
            }
            if($type=='3' and $expstatus!='Rejected'){
               $minus++;
               continue;
            }
            if($type=='4' and $expstatus!='Partial'){
               $minus++;
               continue;
            }
         }
         //echo $expstatus.' '.$type;
                  //For total_expence And	visit_status  
                  //$visit_status= $this->db->select('visit_status')->where('visit_id', $value->id)->get('visit_details')->row();
                  //$total_expence= $this->db->select('amount')->where('visit_id', $value->id)->get('tbl_expense')->row();
	//$ttl_exp = $total_expence->amount;
	//$vst_sts = $visit_status->visit_status;
	//End
   $result[$key]=(array)$value;
   $result[$key]['diff']=$percentChange;
   $result[$key]['status'] = $expstatus;
   // $result[$key]['meeting_status'] = $meeting_status;
   //$result[$key]['total_expence'] = $value->total_expence;//round(abs($value->visit_expSum+$value->visit_otexpSum));
   $result[$key]['visit_status'] = $value->visit_status;
} 

if(!empty($result))
{
   $res= array();
   
   $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total-$minus;
            $res['list'] = $result;

            $this->set_response([
                'status' => TRUE,
                'data' =>$res
                 ], REST_Controller::HTTP_OK);
          }   
		    else
         {
	    
	        $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
	      }
    }


    public function visit_details_post()
    {
    	$id = $this->input->post('visit_id');
       $value_d = $this->db->select('enquiry.company,comp.company_name,enquiry.address,tbl_visit.*  ,CONCAT(COALESCE(enquiry.name,"")," ",COALESCE(enquiry.lastname,"")) as name');
       $value_d =     $this->db->where('tbl_visit.id',$id);
       $value_d =      $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
                       $this->db->join('tbl_company comp','enquiry.company=comp.id','left');

       $value_d =     $this->db->order_by('created_at','DESC');
       $value_d =       $this->db->get('tbl_visit')->row();

    	$tvalue = $this->db->where('visit_id',$id)->limit('1')->order_by('id','DESC')->get('visit_details')->row();
		//$tvalue = $this->db->where('visit_id',$id)->order_by('id','DESC')->get('visit_details')->result();
       $expenselist=$this->db->select('tbl_expense.*,tbl_expense.id as expense_id,tbl_expenseMaster.id,tbl_expenseMaster.title')->where(array('tbl_expense.visit_id'=>$id,'tbl_expense.type'=>2))->join('tbl_expenseMaster','tbl_expenseMaster.id=tbl_expense.expense')->get('tbl_expense')->result();
       $list=[];
	  // print_r($tvalue);exit;
        foreach ($expenselist as $key => $value) {
          $file='';
           if($value->file){
             $file= base_url('assets/images/user/'.$value->file);
           }
            $list[]=[
               "expense_id"=>$value->expense_id,
              "title"=>$value->title,
              "approved_by"=>$value->uid,
              "visit_id"=>$value->visit_id,
              "created_by"=>$value->created_by,
              "created_at"=>$value->created_at,
              "amount"=>$value->amount,
              "file"=>$file,
              "remarks"=>$value->remarks,
              "approve_status"=>$value->approve_status,
            ];
        }
      $data=['visit'=>$value_d,'travelData'=>$tvalue,'expenceData'=>$list];
    	if(!empty($value_d))
    	{
    		 $this->set_response([
                'status' => TRUE,
                'data' =>$data
                 ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		 $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
    	}
    }

    public function delete_visit_post()
    {
    	$visit_id = $this->input->post('visit_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_code = $this->input->post('enquiry_code');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('visit_id','visit_id','required|trim');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_code','enquiry_code','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->db->where(array('id'=>$visit_id,'comp_id'=>$comp_id));
    		$this->db->delete('tbl_visit');

    		if($this->db->affected_rows())
    		{
	    		$this->load->model('Leads_Model');
	        	$this->Leads_Model->add_comment_for_events('Visit Deleted.',$enquiry_code,0,$user_id);

    			$this->set_response([
                  'status' => true,
                  'message' =>'Deleted Successfully.',
               ], REST_Controller::HTTP_OK);
    		}
    		else
    		{
    			$this->set_response([
                  'status' => false,
                  'message' =>'Visit Not found',
               ], REST_Controller::HTTP_OK);
    		}
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }
    public function save_visit_post()
    {
    	$visit_id = $this->input->post('visit_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_id = $this->input->post('enquiry_id');
    	$user_id = $this->input->post('user_id');
      $contact_id  = $this->input->post('contact_id')??'';
      $visit_date = $this->input->post('visit_date');
      $visit_time = $this->input->post('visit_time');
      $m_purpose = $this->input->post('m_purpose');

    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','required|trim');
      $this->form_validation->set_rules('contact_id','contact_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');
      $this->form_validation->set_rules('m_purpose','m_purpose','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->load->model(array('Client_Model','Enquiry_model','Leads_Model'));

    		$data = array(
                            'contact_id'=>$contact_id,
                            'enquiry_id'=>$enquiry_id,
                            'visit_date'=>$this->input->post('visit_date'),
                            'visit_time'=>$this->input->post('visit_time'),
							'm_purpose'=>$this->input->post('m_purpose'),
                            'comp_id'=>$comp_id,
                            'user_id'=>$user_id,
                        );
    		$done = 0;
            $res = $this->db->where(array('enquiry_id'=>$enquiry_id))->get('enquiry')->row();

            if(!empty($res))
            {	
            	if(!empty($visit_id))
	            {
	            	$this->db->where('id',$visit_id)->update('tbl_visit',$data);
	            	$this->Leads_Model->add_comment_for_events('Visit Updated',$res->Enquery_id,0,$user_id);
	            }
	            else
	            {	$data['enquiry_id'] = $enquiry_id;
	            	$this->Client_Model->add_visit($data);
	            	$this->Leads_Model->add_comment_for_events('Visit Added',$res->Enquery_id,0,$user_id);
      
                  $this->Leads_Model->add_comment_for_events_popup('Visit',$visit_date, '', '', '', '', $visit_time, $res->Enquery_id, '', 'Visit -'.$m_purpose,1,3,$user_id,$comp_id);

	            }
	            $done = 1;
            }	

            if($done)
            {
            	
            	$this->set_response([
                  'status' => true,
                  'message' =>'Saved Successfully.',
               ], REST_Controller::HTTP_OK);
			     }
           else
           {
				    $this->set_response([
                  'status' => FALSE,
                  'message' =>'Unable to Save.',
               ], REST_Controller::HTTP_OK);
            }
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }
    public function visit_start_post()
    {
      $user_id= $this->input->post('user_id');
      $company_id = $this->input->post('company_id');
      $visit_id = $this->input->post('visit_id');
      $vd_id = $this->input->post('vd_id');
      $status = $this->input->post('status');
      $res= array();
        $total = $this->db->where(array('id'=>$visit_id,'user_id'=>$user_id))->get('tbl_visit');
         //insert status
           if($total->num_rows()==1){
              if($status==1){
              $latitude   = (float)$this->input->post('latitude');
              $longitude  = (float)$this->input->post('longitude');
                 //only waypoints
              $new_waypoint = array($latitude,$longitude);
              if($latitude!=0 AND $longitude!=0){

              //check any travelled is started or not
            //  $checkexistvisit=$this->db->where(array('comp_id'=>$company_id,'visit_id'=>$visit_id,'created_by'=>$user_id))
            //            ->count_all_results('visit_details');
            //    if($checkexistvisit==0){
                  $checkvisit=$this->db->where(array('comp_id'=>$company_id,'created_by'=>$user_id,'visit_status'=>1))
                  ->get('visit_details');
                  if($checkvisit->num_rows()==0){
                  $data=['comp_id'=>$company_id,'visit_id'=>$visit_id,'visit_status'=>1,'visit_start'=>date('Y-m-d H:i:s'),'created_by'=>$user_id,'way_points'=>json_encode(array($new_waypoint))];
                  $this->db->insert('visit_details',$data);
                  $insertid=$this->db->insert_id();

                  $this->db->where('id',$visit_id);
                  $this->db->set('start_time',date('Y-m-d H:i:s'));
                  $this->db->update('tbl_visit');

                  $res=['message'=>'Travel Started','vd_id'=>$insertid];
                  $this->set_response([
                     'status' => true,
                     'data' =>$res,
                   ], REST_Controller::HTTP_OK);
                     }else{
                        $vd=$checkvisit->row();
                        $res=['message'=>'Visit already Started','vd_id'=>$vd->id,'visit_id'=>$vd->visit_id];
                        $this->set_response([
                           'status' => false,
                           'data' =>$res,
                        ], REST_Controller::HTTP_OK);
                     }
                  
              
                  }else{
                     $this->set_response([
                        'status' => false,
                        'data' =>'Not Supported waypoints',
                           ], REST_Controller::HTTP_OK);
                        }
              }elseif($status==2 ){

               $visit_details = $this->db->where(array('id'=>$vd_id))->get('visit_details')->row();
               $latitude   = (float)$this->input->post('latitude');
               $longitude  = (float)$this->input->post('longitude');
               if($latitude!=0 AND $longitude!=0){

                 //only waypoints
                 $new_waypoint = array($latitude,$longitude);

                 if(!empty($visit_details)){
                   $waypoints  = json_decode($visit_details->way_points);   
                   array_push($waypoints, $new_waypoint);
                   $this->calculate_distance_post($visit_id,$waypoints,$company_id,$user_id);

                   $data=['visit_status'=>$status,'visit_end'=>date('Y-m-d H:i:s'),'way_points'=>json_encode($waypoints)];
                  $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);

                  //finalizeing start and end location

                $start = $this->db->where(array('visit_id'=>$visit_id,'visit_status'=>2))->order_by('id','ASC')->limit(1)->get('visit_details')->row();

                $end = $this->db->where(array('visit_id'=>$visit_id,'visit_status'=>2))->order_by('id','DESC')->limit(1)->get('visit_details')->row();

                if(!empty($start) && !empty($end))
                { $sname='NA';$ename='NA';

                      if(!empty($start->way_points))
                      {
                          $sjson = (array)json_decode($start->way_points);
                          $s_latlong = $sjson[0];
                      }
                      if(!empty($end->way_points))
                      {
                          $ejson = (array)json_decode($end->way_points);
                          $e_latlong = end($ejson);
                      }
                      if(!empty($s_latlong) && !empty($e_latlong))
                      {
                          $start_name = location_name_by_longlat($s_latlong[1],$s_latlong[0]);
                          $end_name = location_name_by_longlat($e_latlong[1],$e_latlong[0]);

                          $loc = array(
                                'start_location'=>$start_name,
                                'end_location'=>$end_name,
                          );
                          $this->db->where('id',$visit_id)->update('tbl_visit',$loc);
                      }
                }

                  $res=['message'=>'Travel Stoped'];
                  $this->set_response([
                     'status' => true,
                     'data' =>$res,
                  ], REST_Controller::HTTP_OK);
                 }
               }else{
                  $res=['message'=>'waypoints updated'];

                  $this->set_response([
                    'status' => true,
                    'data' =>$res,
                 ], REST_Controller::HTTP_OK);
               }
              }elseif($status==3){
               $data=['meeting_status'=>1,'start_time'=>date('Y-m-d H:i:s')];
               $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);
               $res=['message'=>'Meeting Started'];
               $this->set_response([
                  'status' => true,
                  'data' =>$res,
               ], REST_Controller::HTTP_OK);
              }elseif($status==4){
               $data=['meeting_status'=>2,'end_time'=>date('Y-m-d H:i:s')];
               $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);
               $res=['message'=>'Meeting Ended'];

               
               $this->db->where('id',$visit_id);
               $this->db->set('end_time',date('Y-m-d H:i:s'));
               $this->db->update('tbl_visit');

               $this->set_response([
                  'status' => true,
                  'data' =>$res,
               ], REST_Controller::HTTP_OK);
              }elseif($status==5){
               $visit_details = $this->db->where(array('id'=>$vd_id))->get('visit_details')->row();
               $latitude   = (float)$this->input->post('latitude');
               $longitude  = (float)$this->input->post('longitude');
               if($latitude!=0 AND $longitude!=0){
                 //only waypoints
                 $new_waypoint = array($latitude,$longitude);
                 if(!empty($visit_details)){
                   $waypoints  = json_decode($visit_details->way_points);        
                    array_push($waypoints, $new_waypoint);
                   $data=['way_points'=>json_encode($waypoints)];
               $res=['message'=>'waypoints updated'];

                   $this->db->where('id',$vd_id);
                   $this->db->update('visit_details',$data);
                   $this->set_response([
                     'status' => true,
                     'data' =>$res,
                  ], REST_Controller::HTTP_OK);
                 } 
               }else{
               $res=['message'=>'Not Supported waypoints'];

                  $this->set_response([
                     'status' => false,
                     'data' =>$res,
                  ], REST_Controller::HTTP_OK);
               }
            }elseif($status==7){
               $res=['message'=>'Status Updated'];

               $visit_details = $this->db->where(array('id'=>$vd_id))->get('visit_details')->row();
               if(!empty($visit_details)){
                  $data=['visit_status'=>7];
                  $this->db->where('id',$vd_id);
                  $this->db->update('visit_details',$data);
                  $this->set_response([
                    'status' => true,
                    'data' =>$res,
                 ], REST_Controller::HTTP_OK);
                } 
            }
           }else{
            $res=['message'=>'Not Supported waypoints'];

            $this->set_response([
               'status' => true,
               'data' =>$res,
            ], REST_Controller::HTTP_OK);
           }   
   }


  

    public function for_data_list_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');
        if($this->form_validation->run()==true)
        {

        	$all_reporting_ids  = $this->common_model->get_categories($user_id);

	    	$this->db->select('tbl_visit.enquiry_id,CONCAT(COALESCE(enquiry.name,"")," ",COALESCE(enquiry.lastname,"")) as name,client_name');
	        $this->db->from('tbl_visit');
	        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
	        $this->db->where("tbl_visit.comp_id",$comp_id);

	        $where="";
	        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
	        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
	        $this->db->where($where);
	        $res = $this->db->get()->result();

         	if(!empty($res))
            {
            	
            	$this->set_response([
                  'status' => true,
                  'data' =>$res,
               ], REST_Controller::HTTP_OK);
			       }
            else
            {
				$this->set_response([
                  'status' => FALSE,
                  'message' =>'No data.',
               ], REST_Controller::HTTP_OK);
            }
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		}
    }

    public function rating_list_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	if($this->form_validation->run()==true)
    	{
    		$data = array('1 Star','2 Star','3 Star','4 Star','5 Star');
    		$this->set_response([
                  'status' => true,
                  'data' =>$data,
               ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
    	}
    }
    public function expense_master_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	if($this->form_validation->run()==true)
    	{
         $expenselist=$this->db->select('id,title')->where(array('comp_id'=>$comp_id))->get('tbl_expenseMaster')->result();
    		$this->set_response([
                  'status' => true,
                  'data' =>$expenselist,
               ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
    	}
    }

    public function expense_table_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$visit_id = $this->input->post('visit_id');
    	$this->form_validation->set_rules('company_id','company id','required|trim');
    	$this->form_validation->set_rules('visit_id','visit id','required|trim');
    	if($this->form_validation->run()==true)
    	{
         $expenselist=$this->db->select('tbl_expense.*,tbl_expense.id as expense_id,tbl_expenseMaster.id,tbl_expenseMaster.title')->where(array('tbl_expense.comp_id'=>$comp_id,'tbl_expense.visit_id'=>$visit_id,'tbl_expense.type'=>2))->join('tbl_expenseMaster','tbl_expenseMaster.id=tbl_expense.expense')->get('tbl_expense')->result();
        $list=[];
         foreach ($expenselist as $key => $value) {
           $file='';
            if($value->file){
              $file= base_url('assets/images/user/'.$value->file);
            }
             $list[]=[
                "expense_id"=>$value->expense_id,
               "title"=>$value->title,
               "approved_by"=>$value->uid,
               "visit_id"=>$value->visit_id,
               "created_by"=>$value->created_by,
               "created_at"=>$value->created_at,
               "amount"=>$value->amount,
               "file"=>$file,
               "remarks"=>$value->remarks,
               "approve_status"=>$value->approve_status,
             ];
         }
         $this->set_response([
                  'status' => true,
                  'data' =>$list,
               ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
    	}
    }
    public function add_remarks_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$enquiry_id = $this->input->post('enquiry_id');
    	$user_id = $this->input->post('user_id');
    	$visit_id = $this->input->post('visit_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->load->model(array('Client_Model','Enquiry_model','Leads_Model'));
    		$update_visit_data = array( 'remarks'=>$this->input->post('remarks'),
                            'rating'=>$this->input->post('rating'),  );
							
         $res = $this->db->where(array('comp_id'=>$comp_id,'user_id'=>$user_id,'id'=>$visit_id))->update('tbl_visit',$update_visit_data);

         $done = 0;
            $res = $this->db->where(array('enquiry_id'=>$enquiry_id))->get('enquiry')->row();
            $done = 1;

            if(!empty($res) AND !empty($this->input->post('visit_date')) AND !empty($this->input->post('visit_time')) AND !empty($this->input->post('m_purpose')))
            {	
                 	$data = array(
                            'visit_date'=>$this->input->post('visit_date'),
                            'visit_time'=>$this->input->post('visit_time'),
                            'm_purpose'=>$this->input->post('m_purpose'),
                            'comp_id'=>$comp_id,
                            'user_id'=>$user_id,   
                            'enquiry_id'=>$enquiry_id,
                        );


	            	if(!empty($enquiry_id)){
                    $this->Client_Model->add_visit($data);
	                $this->Leads_Model->add_comment_for_events('Visit Added',$res->Enquery_id,0,$user_id);
                    }
	            $done = 1;
            }	
               //add expense start
              if(!empty($_POST['expense'])){
               foreach ($_POST['expense'] as $key =>$value ) 
               {
                  $exp_data = array();
                  $finalfilename='';
                  $expense = $_POST['expense'][$key];
                  $amount = $_POST['amount'][$key];
                  if(!empty($_FILES['imagefile']['name'][$key]))
                  {
                      $file_name =$_FILES['imagefile']['name'][$key];
                      $file_size =$_FILES['imagefile']['size'][$key];
                      $file_tmp  =$_FILES['imagefile']['tmp_name'][$key];
                      $file_type =$_FILES['imagefile']['type'][$key];  
                      $upload_path    =   "assets/images/user/";
                      $finalfilename='expense_'.time().$file_name;
                      move_uploaded_file($file_tmp,$upload_path.$finalfilename);

                  $exp_data['file'] = $finalfilename;

                  }

                  $exp_data['amount']=$amount;
                  $exp_data['expense']=$expense;
                
             
                    if(empty($_POST['ids'][$key]))
                    {
                      $exp_data['type'] = 2;
                      $exp_data['visit_id']=$visit_id;
                      $exp_data['created_by']=$user_id;
                      $exp_data['comp_id']=$comp_id;
                      $this->db->insert('tbl_expense',$exp_data);
                    }
                    else
                    {
                      $this->db->where('id',$_POST['ids'][$key]);
                      $this->db->update('tbl_expense',$exp_data);
                    }
                  }
               }

               //add expence end 
            if($done)
            {
            	
            	$this->set_response([
                  'status' => true,
                  'message' =>'Saved Successfully.',
               ], REST_Controller::HTTP_OK);
			}
            else
            {
				$this->set_response([
                  'status' => FALSE,
                  'message' =>'Unable to Save.',
               ], REST_Controller::HTTP_OK);
            }
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }
     
    }


    public function calculate_distance_post($visit_id,$way_points,$comp_id,$user_id)
     {
        $get_dis=$this->db->where('id',$visit_id)->get('tbl_visit')->row();
      // print_r();
      $ideald=$get_dis->idealDistance;
      $actiuald=$get_dis->actualDistance;
      // print_r();
      // die();
      // $way_points=json_decode($way_points);      
      $totalpoints=count($way_points);
      $newpoints=array();
      // print_r($totalpoints);
      $cuts=$totalpoints/23;
      for ($i=0; $i < $totalpoints; $i+=$cuts) { 
        array_push($newpoints,$way_points[$i]);
      }
      // $newpoints2 = array_reverse($newpoints);
      // print_r($way_points);
       $lastKey = key(array_slice($newpoints, -1, 1, true));
      $origins=$newpoints[0];
      $destinations=$newpoints[$lastKey];
      $origins=implode(',',$origins);
      $destinations=implode(',',$destinations);
      // $fd=implode('|',$newpoints);
      // $alldesinations=implode('|',$fd);
      foreach ($newpoints as $key => $value_d) {
         // print_r(implode(',',$value_d));
         $fdata[]=implode(',',$value_d);
      }
      $finalwaypoints=implode('%7C',$fdata);
      // $km_rate = $this->user_model->get_user_meta($user_id,array('km_rate'));
      // $km_rate['km_rate'];
      // $rate=1;
      // if(!empty($km_rate['km_rate'])){
      //    $rate=$km_rate['km_rate'];
      // }

// die();

$url='https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins='.$origins.'&destinations='.$destinations.'&key=AIzaSyAaoGdhDoXMMBy1fC_HeEiT7GXPiCC0p1s';
 $actualurl='https://maps.googleapis.com/maps/api/directions/json?origin='.$origins.'&destination='.$destinations.'&waypoints='.$finalwaypoints.'&key=AIzaSyAaoGdhDoXMMBy1fC_HeEiT7GXPiCC0p1s';
    //actual distance

     
    /* eCurl */
    $curl = curl_init($url);

    /* Set JSON data to POST */
   //  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
    /* Define content type */
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        
    /* Return json */
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
    /* make request */
    $result = curl_exec($curl);
         $sum=0;
    $dresult=json_decode($result);
    $dsresult=$dresult->rows;
   //  print_r($dresult['elements']);
    foreach ($dresult->rows as $key => $value1) {
      //  print_r($value);
    foreach ($value1->elements as $key => $values) {
       $distance=$values->distance->value;
   // print_r($distance);
   $sum +=$distance;
    }

    }

    //actual distance



   //  print_r($sum);
   //  print_r($dsresult['elements']);
    /* close curl */
    curl_close($curl);  

    $sum = $sum/1000;
$sum = round($sum, 2, PHP_ROUND_HALF_UP);
$fdistance=$this->distance_actual($actualurl);
// die();
   $data_up=['idealDistance'=>$ideald+$sum,'actualDistance'=>$actiuald+$fdistance];
    $this->db->where('id',$visit_id)->update('tbl_visit',$data_up);
    //fetch user km rate
    $km_rate = $this->user_model->get_user_meta($user_id,array('km_rate'));
    if(!empty($km_rate['km_rate'])){$rate= $km_rate['km_rate'];}else{
      $rate=10;;
  }
    //add and update expense here
    $get_dis=$this->db->where(array('visit_id'=>$visit_id,'type'=>1,'created_by'=>$user_id,'comp_id'=>$comp_id))->get('tbl_expense');
      if($get_dis->num_rows()==0){
         $exp_data=['visit_id'=>$visit_id,'type'=>1,'amount'=>($fdistance)*$rate,'expense'=>0,'comp_id'=>$comp_id,'created_by'=>$user_id];
        $this->db->insert('tbl_expense',$exp_data);
      }else{
         $expfinal=$get_dis->row()->amount;
         $exp_data=['amount'=>$expfinal+($fdistance *$rate),'expense'=>0,'comp_id'=>$comp_id];
         $this->db->where(array('visit_id'=>$visit_id,'type'=>1,'created_by'=>$user_id,'comp_id'=>$comp_id))->update('tbl_expense',$exp_data);
      }

   }
   public function distance_actual($actualurl)
   {
      
    /* eCurl */
    $curl = curl_init($actualurl);

    /* Set JSON data to POST */
   //  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
    /* Define content type */
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        
    /* Return json */
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
    /* make request */
    $result = curl_exec($curl);
    curl_close($curl);  
         $sum=0;
    $dresult=json_decode($result);
    foreach ($dresult->routes as $key => $valuet) {
      // print_r($value->legs);
    foreach ($valuet->legs as $key => $values) {
      // print_r($values->steps);
      foreach ($values->steps as $key => $valuess) {
                  $sum +=$valuess->distance->value;
      }
      }
      }
   if($sum!=0){
   $sum = $sum/1000;
   $sum = round($sum, 2, PHP_ROUND_HALF_UP);
   }else{
      $sum=0;
   }
return $sum;
   }

   public function delete_expense_post()
   {
      $this->form_validation->set_rules('expense_id','expense_id','required');
      $this->form_validation->set_rules('visit_id','visit_id','required');
      
      if($this->form_validation->run())
      {
        $exp_id = $this->input->post('expense_id');
        $visit_id = $this->input->post('visit_id');
        
          $this->db->where('id',$exp_id)
                    ->where('visit_id',$visit_id)
                    ->delete('tbl_expense');
          if($this->db->affected_rows())
          {
            $this->set_response([
                  'status' => true,
                  'message' =>'Deleted Successfully!',
               ], REST_Controller::HTTP_OK);
          }
          else
          {
             $this->set_response([
                  'status' => true,
                  'message' =>'Unable to delete',
               ], REST_Controller::HTTP_OK);
          }
          
      }
      else
      {
          $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
      }
   }

   public function update_expense_post()
   {
      $this->form_validation->set_rules('expense_id','expense_id','required');
      $this->form_validation->set_rules('amount','amount','required');
      $this->form_validation->set_rules('remarks','remarks','required');
      $this->form_validation->set_rules('expense','expense','required');
      if($this->form_validation->run())
      {
        $exp_id = $this->input->post('expense_id');
        $amount = $this->input->post('amount');
        $remarks = $this->input->post('remarks');
        $expense = $this->input->post('expense');
          $data =array(
                      'expense'=>$expense,
                      'amount'=>$amount,
                      'remarks'=>$remarks
          );
          $filename='';
          if(!empty($_FILES['file']))
          {
              $filename='expense_'.time().$_FILES['file']['name'];
              $upload_path    =   "assets/images/user/".$filename;

              if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path))
              {
                $data['file'] = $filename;
              }
          }

          $this->db->where('id',$exp_id)
                    ->update('tbl_expense',$data);
          if($this->db->affected_rows())
          {
            $this->set_response([
                  'status' => true,
                  'message' =>'Updated Successfully!',
               ], REST_Controller::HTTP_OK);
          }
          else
          {
             $this->set_response([
                  'status' => true,
                  'message' =>'Unable to Update',
               ], REST_Controller::HTTP_OK);
          }
          
      }
      else
      {
          $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
      }
   }

   public function visit_action_post()
   {
      $this->form_validation->set_rules('visit_list','visit_list (comma seperated)','required|trim');
      $this->form_validation->set_rules('remarks','remarks','required|trim');
      $this->form_validation->set_rules('status','status [1-Reject,2-Approve]','required|trim');
      $this->form_validation->set_rules('user_id','user_id','required|trim');
      $this->form_validation->set_rules('comp_id','comp_id','required|trim');
      if($this->form_validation->run()==true)
      {
        $this->load->model('Leads_Model');
        $comp_id=$this->input->post('comp_id');
        $user_id=$this->input->post('user_id');
        $status = $this->input->post('status');
        $remarks = $this->input->post('remarks');
       // print_r($_POST);exit;
        $list = explode(',', $this->input->post('visit_list'));
        foreach ($list as $key => $value) 
        {
        $visit_row = $this->db->select("enquiry.Enquery_id as comment_id")
                            ->from("tbl_visit")
                ->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left')
                            ->where('tbl_visit.id', $value)
                            ->get();
          $comment_id = $visit_row->row()->comment_id;
            if($status=='1'){
                  $subject = 'Visit Reject'; 
            }else{
            $subject = 'Visit Approve'; 
            }       

            $data=['uid'=>$user_id,'remarks'=>$remarks,'approve_status'=>$status];
            $this->db->where(array('comp_id'=>$comp_id,'visit_id'=>$value))->update('tbl_expense',$data);
        //timeline code here
            $this->db->set('remark',$remarks);
        $this->Leads_Model->add_comment_for_events($subject,$comment_id,0,$user_id);
        //Bell botification code here
        // $assign_data_noti[]=array('create_by'=> $user_id,
        //                     'subject'=>$subject,
        //                     'task_remark'=>$remarks,
        //                     'query_id'=>$comment_id,
        //                     'task_date'=>date('d-m-Y'),
        //                     'task_time'=>date('H:i:s')
        //                     );
        }
        //$this->db->insert_batch('query_response',$assign_data_noti);

           $this->set_response([
                  'status' => true,
                  'message' =>'Visit Updated.',
               ], REST_Controller::HTTP_OK);
      }
      else
      {
           $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
      }
   }
}
