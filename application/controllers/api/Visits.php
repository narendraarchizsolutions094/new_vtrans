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
	  $this->load->model(array('enquiry_model','common_model'));
  }

  	public function visit_list_page_post()
    {
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
    
        $total = $this->enquiry_model->visit_list_api($company_id,$user_id,$process)->num_rows();

        $data['result'] = $this->enquiry_model->visit_list_api($company_id,$user_id,$process,$limit,$offset);
                  
          if(!empty($data['result']->result()))
          {
            $res= array();
            
            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = $data['result']->result();

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

    	$value = $this->db->where('id',$id)->get('tbl_visit')->row();
    	$tvalue = $this->db->select('')->where('visit_id',$id)->get('visit_details')->row();
       $expenselist=$this->db->select('tbl_expense.*,tbl_expense.id as expense_id,tbl_expenseMaster.id,tbl_expenseMaster.title')->where(array('tbl_expense.visit_id'=>$id,'tbl_expense.type'=>2))->join('tbl_expenseMaster','tbl_expenseMaster.id=tbl_expense.expense')->get('tbl_expense')->result();
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
      $data=['visit'=>$value,'travelData'=>$tvalue,'expenceData'=>$list];
    	if(!empty($value))
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

    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->load->model(array('Client_Model','Enquiry_model','Leads_Model'));

    		$data = array(
                            'visit_date'=>$this->input->post('visit_date'),
                            'visit_time'=>$this->input->post('visit_time'),
                            'travelled'=>$this->input->post('travelled'),
                            'travelled_type'=>$this->input->post('travelled_type'),
                            'rating'=>$this->input->post('rating'),
                            'next_date'=>$this->input->post('next_visit_date'),
                            'next_location'=>$this->input->post('next_location'),
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
             $checkexistvisit=$this->db->where(array('comp_id'=>$company_id,'visit_id'=>$visit_id,'created_by'=>$user_id))
                       ->count_all_results('visit_details');
               if($checkexistvisit==0){
                  $checkvisit=$this->db->where(array('comp_id'=>$company_id,'created_by'=>$user_id,'visit_status'=>1))
                  ->get('visit_details');
                  if($checkvisit->num_rows()==0){
                  $data=['comp_id'=>$company_id,'visit_id'=>$visit_id,'visit_status'=>1,'visit_start'=>date('Y-m-d H:i:s'),'created_by'=>$user_id,'way_points'=>json_encode(array($new_waypoint))];
                  $this->db->insert('visit_details',$data);
                  $insertid=$this->db->insert_id();
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
                  $res=['message'=>'Visit Travel History Already Present','vd_id'=>''];
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
              }elseif($status==2){

               $visit_details = $this->db->where(array('id'=>$vd_id))->get('visit_details')->row();
               $latitude   = (float)$this->input->post('latitude');
               $longitude  = (float)$this->input->post('longitude');
               if($latitude!=0 AND $longitude!=0){

                 //only waypoints
                 $new_waypoint = array($latitude,$longitude);
                 if(!empty($visit_details)){
                   $waypoints  = json_decode($visit_details->way_points);   
                   array_push($waypoints, $new_waypoint);
                   $data=['visit_status'=>2,'visit_end'=>date('Y-m-d H:i:s'),'way_points'=>json_encode($waypoints)];
                  $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);
                  $res=['message'=>'Travel Stoped'];
                  $this->set_response([
                     'status' => true,
                     'data' =>$res,
                  ], REST_Controller::HTTP_OK);
                 }
               }else{
                  $this->set_response([
                     'status' => false,
                     'data' =>'Not Supported waypoints',
                  ], REST_Controller::HTTP_OK);
               }
              }elseif($status==3){
               $data=['visit_status'=>3,'start_time'=>date('Y-m-d H:i:s')];
               $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);
               $res=['message'=>'Meeting Started'];
               $this->set_response([
                  'status' => true,
                  'data' =>$res,
               ], REST_Controller::HTTP_OK);
              }elseif($status==4){
               $data=['visit_status'=>4,'end_time'=>date('Y-m-d H:i:s')];
               $this->db->where(array('id'=>$vd_id))->update('visit_details',$data);
               $res=['message'=>'Meeting Ended'];
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
                   $this->db->where('id',$vd_id);
                   $this->db->update('visit_details',$data);
                   $this->set_response([
                     'status' => true,
                     'data' =>'waypoints updated',
                  ], REST_Controller::HTTP_OK);
                 } 
               }else{
                  $this->set_response([
                     'status' => false,
                     'data' =>'Not Supported waypoints',
                  ], REST_Controller::HTTP_OK);
               }
            }
           }else{
            $this->set_response([
               'status' => false,
               'msg' =>'Visit not Found'
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

	    	$this->db->select('tbl_visit.enquiry_id,CONCAT(COALESCE(enquiry.name,"")," ",COALESCE(enquiry.lastname,"")) as name');
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

            if(!empty($res))
            {	
                 	$data = array(
                            'visit_date'=>$this->input->post('visit_date'),
                            'visit_time'=>$this->input->post('visit_time'),
                            'comp_id'=>$comp_id,
                            'user_id'=>$user_id,   
                            'enquiry_id'=>$enquiry_id
                        );

	            	if(!empty($enquiry_id)){
                  $this->Client_Model->add_visit($data);
	            	$this->Leads_Model->add_comment_for_events('Visit Added',$res->Enquery_id,0,$user_id);
                  }
	            $done = 1;
            }	
               //add expense start
                if(!empty($_POST['expense'])){
               foreach ($_POST['expense'] as $key =>$value ) {
                  $finalfilename='';
                  $expense = $_POST['expense'][$key];
                  $amount = $_POST['amount'][$key];
                  if(!empty($_FILES['imagefile']['name'][$key])){
                  $file_name =$_FILES['imagefile']['name'][$key];
                  $file_size =$_FILES['imagefile']['size'][$key];
                  $file_tmp  =$_FILES['imagefile']['tmp_name'][$key];
                  $file_type =$_FILES['imagefile']['type'][$key];  
                  $upload_path    =   "assets/images/user/";
                  $finalfilename='expense_'.time().$file_name;
                  move_uploaded_file($file_tmp,$upload_path.$finalfilename);
                  }
                  // visit type =2
                  $exp_data=['type'=>2,
                         'amount'=>$amount,
                         'visit_id'=>$visit_id,
                         'created_by'=>$user_id,
                         'expense'=>$expense,
                         'file'=>$finalfilename,
                         'comp_id'=>$comp_id,
                         ];
              $this->db->insert('tbl_expense',$exp_data);
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
}
