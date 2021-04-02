<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Lead extends REST_Controller {
    function __construct() 
    {
        parent::__construct();
           $this->load->database();
           $this->load->library('form_validation');
           	
		$this->load->model(array(
			'User_model','Leads_Model','Message_models','enquiry_model','common_model'
		));
		$this->load->model('api/sync_model');
		$this->load->library('email'); 
   // $this->lang->load('notifications_lang', 'english');   
		
           $this->load->helper('url');
           $this->methods['users_get']['limit'] = 500; 
           $this->methods['users_post']['limit'] = 100;  
           $this->methods['users_delete']['limit'] = 50; 
        /*   header('Content-type: application/json');
        header('Access-Control-Allow-Origin', '*');
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header('Access-Control-Allow-Headers', 'Content-Type');*/
    }
    public function active_leads_post(){   
      $user_id= $this->input->post('user_id');
      $process_id= $this->input->post('process_id');
      //for multiprocess id
      if (strpos(',',$process_id) !== false) 
 			{
				$process = implode(',',$process_id);
			}
			else
			{
				$process = $process_id;
			}
            $res= array();
            if(!empty($user_id)){
                    $user_role1 = $this->User_model->read_by_id($user_id); 
                    if(!empty($user_role1)){
              $user_role=$user_role1->user_roles;
             $data['active_enquiry'] = $this->enquiry_model->active_enqueries_api($user_id,2,$user_role,$process);
               
               if(!empty($data['active_enquiry']->result())){
                  $res= array();
                  foreach($data['active_enquiry']->result() as $value){
                      $customer='';
                      $tags_row = array();
                      if(!empty($value->tag_ids)){
                        $this->db->select('title,color');
                        $this->db->where("id IN(".$value->tag_ids.")");
                        $tags = $this->db->get('tags')->result_array();
                        if(!empty($tags)){
                          foreach ($tags as $k => $v) {
                            $tags_row[] = array('color'=>$v['color'],'name'=>$v['title']);
                          }
                        }
                      }
                    array_push($res,array('enquery_id'=>$value->enquiry_id,'enquery_code'=>$value->Enquery_id,'org_name'=>$value->org_name,'client_name'=>$value->client_name,'customer_name'=>$value->name_prefix.' '.$value->name.' '.$value->lastname,'email'=>$value->email,'phone'=>$value->phone,'state'=>'','source'=>'test','type'=>$customer,'process_id'=>$value->product_id,
                    'tag'=>$tags_row,
                    'lead_stage'=>$value->lead_stage,'lead_description'=>$value->lead_discription));  
                 } 
               }
			}
			}			
          if(!empty($res)){   
          $this->set_response([
                'status' => TRUE,
                'leads' =>$res
                 ], REST_Controller::HTTP_OK);
        
        }else{
	
	   $this->set_response([
                 'status' => false,
                'message' => array('error'=>'not found!') 
                 ], REST_Controller::HTTP_OK);
        }
  }
  public function view_post(){
    $this->form_validation->set_rules('lead_id','Lead Id','required');    
    $this->form_validation->set_message('required', 'Invalid %s');
    
    if ($this->form_validation->run() == true) {
      
      $lead_id  = $this->input->post('lead_id');  
      $lead_row = $this->Leads_Model->get_leadListDetailsby_id_api($lead_id);
      
     /* echo "<pre>";
      print_r($lead_row);
      echo "</pre>";*/
      if (!empty($lead_row)) {
          
          $lead_array = array(
          'lead_id'           =>  $lead_row->lid,
          'lead_code'         =>  $lead_row->lead_code,
          'name'              =>  $lead_row->name_prefix." ".$lead_row->name." ".$lead_row->lastname,
          'email'             =>  $lead_row->email,
          'phone'             =>  $lead_row->phone,
          'other_phone'       =>  $lead_row->other_no,
          'other_email'       =>  $lead_row->other_email,
          'enquiry_cust_type' =>  ($lead_row->enquiry_cust_type == 1)?'Customer':'Channel Partner',
          'org_name'          =>  $lead_row->org_name,
          'source'            =>  $lead_row->lead_name,
          'referred_by'       =>  $lead_row->referdby,
          'country'           =>  $lead_row->country_name,
          'region'            =>  $lead_row->region_name,
          'state'             =>  $lead_row->state,
          'territory'         =>  $lead_row->territory_name,
          'city'              =>  $lead_row->city,
          'pincode'           =>  $lead_row->pin_code,
          'address'           =>  $lead_row->address,
          'requirement'       =>  $lead_row->enquiry,
          'stage'             =>  $lead_row->lead_stage_name,
          'created_date'      =>  $lead_row->created_date,
          'created_by'        =>  $lead_row->created_by_name,
          'assigned_to'        =>  $lead_row->assign_name
        );
          if($lead_row->enquiry_cust_type == 1){
            $this->db->select('customer_type');
            if (!empty($lead_row->customer_type)) {
              $this->db->where('cus_id',$lead_row->customer_type);
              $customer_type_name   = $this->db->get('tbl_customer_type')->row();
              $lead_array['customer_type'] = $customer_type_name->customer_type;
              
            }else{
              $lead_array['customer_type'] = '';              
            }

          }else if ($lead_row->enquiry_cust_type == 11) {
            $this->db->select('channel_partner_type');
            $this->db->where('ch_id',$lead_row->channel_partnr_type);
            $channel_type_name   = $this->db->get('tbl_channel_partner')->row();
            
            $lead_array['customer_type'] = $channel_type_name->channel_partner_type;            
          
          }
      }else{
        $lead_array = array();
      }
      $this->set_response([
                      'status' => true,
                      'lead' =>array($lead_array)
                       ], REST_Controller::HTTP_OK);     
    }else{
      $this->set_response([
          'status' => false,
          'lead' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))  
           ], REST_Controller::HTTP_OK);
    }
  }

  public function update_post(){
      $lead_code = $this->input->post('lead_code');      
      $this->form_validation->set_rules('lead_code','Lead Code','required');
      $this->form_validation->set_rules('enquiry_type','Enquiry Type','required');
      $this->form_validation->set_rules('customer_types','Customer Type','required');
      $this->form_validation->set_rules('org_name','Organisation Name','required');
      $this->form_validation->set_rules('mobileno','Mobile No','required');
      $this->form_validation->set_rules('name_prefix','Name Prefix','required');
      $this->form_validation->set_rules('enquirername','Organisation Name','required');
      $this->form_validation->set_rules('lastname','Last Name','required');
      $this->form_validation->set_rules('email','Email','required');
      $this->form_validation->set_rules('state_id','State','required');
      $this->form_validation->set_rules('fcity','City','required');
      $this->form_validation->set_rules('address','Address','required');
      $this->form_validation->set_rules('pincode','Pincode','required');
      $this->form_validation->set_rules('lead_source','Source','required');
      $this->form_validation->set_rules('enquiry','Requirement/Product','required');
      $this->form_validation->set_rules('lead_score','Conversion Probability','required');
      $this->form_validation->set_rules('lead_stage','Lead Stage','required');      
      if($this->form_validation->run() == true){      
        if($this->input->post('referredby')!=''){      
          $ref=$this->input->post('referredby');      
        }else{      
            $ref='';      
        }          
          $name             = $this->input->post('enquirername');      
          $email            = $this->input->post('email');
      
          $mobile           = $this->input->post('mobileno');
      
          $lead_source      = $this->input->post('lead_source');
      
          $address          = $this->input->post('address');
      
          $enquiry          = $this->input->post('enquiry');
        
          $enquiry_cust_type= $this->input->post('enquiry_type');
      
          $org_name         = $this->input->post('org_name');
      
          $en_comments      = $this->input->post('en_comments');
      
          $city_id  = $this->db->select("*")
          ->from("city")
          ->where('id',$this->input->post('fcity'))
          ->get();
      
          if($this->input->post('enquiry_type')==11){
      
              $partener=$this->input->post('customer_types');
      
          }else{
            $partener=0;
          }
      
          if($this->input->post('enquiry_type')==1){
      
            $customer_types=$this->input->post('customer_types');
      
          }else{
      
              $customer_types=0;
      
          }
      
          if(!empty($this->input->post('other_no'))){
      
            $other_no=implode(',',$this->input->post('other_no'));
          }else{
            $other_no=''; 
          }
          if(!empty($this->input->post('other_email'))){
            $other_email=implode(',',$this->input->post('other_email'));
          }else{
            $other_email=''; 
          }
          $this->db->set('city_id',$city_id->row()->id);
      
          $this->db->set('state_id',$city_id->row()->state_id);
      
          $this->db->set('country_id',$city_id->row()->country_id);
      
          $this->db->set('region_id',$city_id->row()->region_id);
      
          $this->db->set('territory_id',$city_id->row()->territory_id);
      
          //$this->db->set('other_phone', $other_no);
      
          //$this->db->set('other_email',$other_email);
      
          $this->db->set('phone',$mobile);
          
          $this->db->set('email',$email);
      
          $this->db->set('name',$name);
      
          $this->db->set('address',$address);
      
          $this->db->set('enquiry_source',$lead_source);
      
          //$this->db->set('referdby',$ref);
          
          $this->db->set('enquiry',$enquiry);
        
          //$this->db->set('enquiry_cust_type',$enquiry_cust_type);
      
          $this->db->set('org_name',$org_name);
      
          //$this->db->set('channel_partnr_type',$partener);
      
          //$this->db->set('customer_type',$customer_types);
      
          //$this->db->set('op_size',$this->input->post('opportunity_size'));
      
          $this->db->set('lastname',$this->input->post('lastname'));
      
          $this->db->set('name_prefix',$this->input->post('name_prefix'));
      
          $this->db->set('pin_code',$this->input->post('pincode'));
      
          $this->db->where('Enquery_id',$this->input->post('lead_code'));
      
          $this->db->update('enquiry');
          //echo $this->db->last_query();
        
          $lead_source = $this->input->post('lead_source');
      
          $lead_score = $this->input->post('lead_score');
      
          $lead_stage = $this->input->post('lead_stage');
      
          $this->db->set('lead_score',$lead_score);
      
          $this->db->set('lead_stage',$lead_stage);
      
          $this->db->where('lead_code',$lead_code);
      
          $this->db->update('allleads');             
      
          $ld_updt_by = $this->input->post('user_id');

          $adt = date("d-m-Y H:i:s");          
          $this->db->set('lead_id',$lead_code);
          $this->db->set('created_date',$adt);
          $this->db->set('comment_msg','Update Leads');
          $this->db->set('created_by',$ld_updt_by);
          $this->db->insert('tbl_comment');
          $this->set_response([
                      'status' => true,
                      'message' =>array(array('error'=>'Lead updated Successfully'))
                       ], REST_Controller::HTTP_OK); 
      
      
      }else{
        $this->set_response([
          'status' => false,
          'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);
      }
  }
  
  public function get_probability_stage_post(){
	  $comp=$this->input->post('company_id');	
      $leadsource = $this->enquiry_model->get_leadsource_list_api($comp);
      $lead_score = $this->enquiry_model->get_leadscore_list_api($comp);
      $leadsource_array = array();
      $lead_score_array = array();
      
      foreach ($leadsource as $key => $value) {
        $leadsource_array[] = array('id'=>$value->lsid,'lead_name'=>$value->lead_name);
      }
      foreach ($lead_score as $key => $value) {
        $lead_score_array[] = array('id'=>$value->sc_id,'score_name'=>$value->score_name.' '.$value->probability);
      }
      $this->set_response([
          'status' => true,
          'probability_stage' => array(array('lead_stage'=>$leadsource_array,'probability'=>$lead_score_array))  
           ], REST_Controller::HTTP_OK);
  }
          //////// Drop Enquiry API /////////
  public function drop_leads_post(){
      $this->form_validation->set_rules('reason','Reason','required');
      $this->form_validation->set_rules('drop_status','Drop Status','required');
      $this->form_validation->set_rules('lead_code[]','Lead Code','required');
      $this->form_validation->set_rules('user_id','User Id','required');
      if( $this->form_validation->run() == true){
        $reason = $this->input->post('reason');
        
        $drop_status = $this->input->post('drop_status');
        $user_id = $this->input->post('user_id');
        
        $leads = $this->input->post('lead_code[]');
          if(!empty($leads)){
            foreach($leads as $key){
          
              $this->db->set('drop_status',$drop_status);
          
              $this->db->set('drop_reason',$reason);
          
              $this->db->set('update_date',date('d-m-Y H:i:s'));
          
              $this->db->where('Enquery_id',$key);
          
              $this->db->update('enquiry');

              $comp_id =  $this->db->select('comp_id')->where('Enquery_id',$key)->get('enquiry')->row_array();
              if(!empty($comp_id))
                $comp_id = $comp_id['comp_id'];

              $this->Leads_Model->add_comment_for_events_api(display('lead',$comp_id).' Dropped.',$key,$user_id);
          
            } 
            $this->set_response([
                'status' => true,
                'message' => array(array('error'=>'Droped successfully'))  
                 ], REST_Controller::HTTP_OK);
          
          }else{
          
            $this->set_response([
              'status' => false,
              'message' => array(array('error'=>'No data found to drop.'))  
               ], REST_Controller::HTTP_OK);
        
        }
      }else{
        $this->set_response([
          'status' => false,
          'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);          
      }
    
  }
      public function assign_lead_post(){
        
        $this->form_validation->set_rules('user_id','User ID' ,'required');
        $this->form_validation->set_rules('assign_user_id','Assign ID' ,'required');
        $this->form_validation->set_rules('lead_code[]','Lead Code' ,'required');

        if($this->form_validation->run()==true){
          
          $move_enquiry = $this->input->post('lead_code[]');
          $assign_employee = $this->input->post('assign_user_id');
          
          $user_id = $this->input->post('user_id');
          
          $user =$this->User_model->read_by_id($assign_employee);
          
          if(!empty($move_enquiry)){
            $customer_name = '';
            
            foreach($move_enquiry as $key){
          
          
              $this->db->set('aasign_to',$assign_employee);
          
              $this->db->set('assign_by',$user_id);
          
              $this->db->where('Enquery_id',$key);
          
              $this->db->update('enquiry');
          
              $lid = $this->Leads_Model->get_leadListDetailsby_code($key)->lid;   
              $enquiry_row = $this->Leads_Model->get_leadListDetailsby_ledsonly($lid);   
                         
              
        /*      echo "<pre>";
              print_r($enquiry_row);
              exit();*/
              $customer_name  .= $enquiry_row->name_prefix.''.$enquiry_row->name.' '.$enquiry_row->lastname.', ';

              $comp_id =  $this->db->select('comp_id')->where('Enquery_id',$key)->get('enquiry')->row_array();
              if(!empty($comp_id))
                $comp_id = $comp_id['comp_id'];


              $this->Leads_Model->add_comment_for_events_api(display('lead',$comp_id).' Assigned successfully',$key,$user_id);
          
            }
            $assigner_user = $this->User_model->read_by_id($user_id); // assigner user row
            $assignee_phone = '91'.$user->s_phoneno; 
            $assign_to_name = $user->s_display_name.' '.$user->last_name;
            
            $assign_by_name = $assigner_user->s_display_name.' '.$assigner_user->last_name;
          $notification_msg = sprintf(display('lead_assigned_to'),trim($customer_name),trim($assign_to_name),trim($assign_by_name));
          $this->Message_models->sendwhatsapp($assignee_phone,$notification_msg);
            $this->set_response([
              'status' => true,
              'message' => array(array('error'=>'Lead Assigned successfully to Sales'))  
               ], REST_Controller::HTTP_OK);
          
          }else{
          
            $this->set_response([
              'status' => false,
              'message' => array(array('error'=>'No lead found to assign.'))  
               ], REST_Controller::HTTP_OK);
          }
        }else{
           $this->set_response([
          'status' => false,
          'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);
        } 
      }
       public function lead_stage_post()
        {
		 $comp=$this->input->post('company_id');
		 $pro_id=$this->input->post('process_id');
		 $stg_id=$this->input->post('stage_id');
		 
         $data['lead_stages'] = $this->Leads_Model->find_stage_api($comp,$pro_id,$stg_id);
         $stages=array();
         foreach($data['lead_stages']  as $value){
           array_push($stages,array('stg_id'=>$value->stg_id,'stg_name'=>$value->lead_stage_name));
             
         }
          $this->set_response([
                'status' => TRUE,
                'lead_stages' => $stages,
                 ], REST_Controller::HTTP_OK);
        
        }
		
		public function sub_stage_post()
        {
		 $diesc = $this->input->post('lead_stage');
        $data['lead_stages']=$this->Leads_Model->select_des_by_stage_api($diesc);
         $stages=array();
         foreach($data['lead_stages']  as $value){
           array_push($stages,array('sub_id'=>$value->id,'sub_name'=>$value->description));
             
         }
          $this->set_response([
                'status' => TRUE,
                'sub_stages' => $stages,
                 ], REST_Controller::HTTP_OK);
        
        }
        public function disposition_update_post(){
            
            $this->form_validation->set_rules('lead_stage', 'Lead Stage', 'trim|required');
            $this->form_validation->set_rules('lead_discription', 'Lead Description', 'trim|required');
        //    $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
            $this->form_validation->set_rules('enquiry_code', 'Enquiry Code', 'trim|required');
            $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
            if ($this->form_validation->run() == TRUE ) {              
              
              $stage_id         = $this->input->post('lead_stage');
              $stage_desc       = $this->input->post('lead_discription');
              $stage_remark     = $this->input->post('remark');
              $en_id            = $this->input->post('enquiry_code');
              $user_id          = $this->input->post('user_id');
          

        $stage_date = date("d-m-Y",strtotime(str_replace("/","-",$this->input->post('stage_date'))));
        $stage_time = date("H:i:s",strtotime($this->input->post('stage_time')));  
			  $rem_time = date("H:i",strtotime($this->input->post('stage_time')));	
			  
              $this->db->set('lead_stage', $stage_id);
              $this->db->set('lead_discription', $stage_desc);
              $this->db->set('lead_discription_reamrk', $stage_remark);
		     
              $this->db->where('Enquery_id', $en_id);
              $this->db->update('enquiry');
              $this->Leads_Model->add_comment_for_events_stage_api('Stage Updated',$en_id,$stage_id,$stage_desc,$stage_remark,$user_id); 

			  
        
    if(!empty($this->input->post('stage_date')) && !empty($this->input->post('stage_time')))
    {

      $tid = $this->Leads_Model->add_comment_for_events_popup_api($stage_remark,$stage_date,$stage_time,$en_id,$user_id);
        //echo 'chla';exit();
        $this->load->model('Notification_model');
        $this->db->select('CONCAT_WS(" ",name_prefix,name,lastname) as enq_name'); 
        $this->db->where('Enquery_id',$en_id);
        $enq_row  = $this->db->get('enquiry')->row_array();
        $this->db->select('lead_stage_name');
        $this->db->where('stg_id',$stage_id);
        $lead_stage_row  = $this->db->get('lead_stage')->row_array();
        $reminder_txt = $lead_stage_row['lead_stage_name'].' :'.$enq_row['enq_name'];
        
        $res  = $this->Notification_model->add_task_reminder($user_id,$en_id,$stage_date,$rem_time,$reminder_txt);            
        $res = json_decode($res,true);
        $nid = $res['name']; // notification id
        $this->db->where('resp_id',$tid);
        $this->db->update('query_response',array('notification_id'=>$nid,'subject'=>$reminder_txt));

    }
             $this->set_response([  
              'status' => true,
              'message' => array(array('error'=>'Successfully Status Changed'))  
               ], REST_Controller::HTTP_OK);
            } else {
              $this->set_response([
              'status' => false,
              'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
               ], REST_Controller::HTTP_OK);   
            }
            
        }
		
      public function move_to_client_post(){ 
        // $this->form_validation->set_rules('expected_date','Expected Date');
       // $this->form_validation->set_rules('conversion_probability','Conversion Probability','required');
       // $this->form_validation->set_rules('comment','Comment','required');
        $this->form_validation->set_rules('enquiry_code[]','Enquery Code' ,'required');
        $this->form_validation->set_rules('user_id','User Id' ,'required');
        $this->form_validation->set_rules('status','Status');
		$this->form_validation->set_rules('deals_id[]','Deal');

        if($this->form_validation->run() == true){
            $move_enquiry=$this->input->post('enquiry_code[]');
			$move_deals=$this->input->post('deals_id[]');
            $status=$this->input->post('status');
           if($status==''){
             $status=3;
           } 
            if(!is_array($move_enquiry))
            {
                $this->set_response([
                'status' => true,
                'message' => 'Enquiry Code should be array',  
                 ], REST_Controller::HTTP_OK);
              exit();
            }
           
            if(empty($comment)){
               $comment=''; 
            }
            if(!empty($move_enquiry)){
              $assigner_user_id =  $this->input->post('user_id');
              $assigner_user = $this->User_model->read_by_id($this->input->post('user_id'));          
              $convertor_phone = '91'.$assigner_user->s_phoneno;
              $comp_id = $assigner_user->companey_id;

              $enquiry_separation = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING',$comp_id);
              $other = array();
              if(!empty($enquiry_separation))
              {
                $enquiry_separation = json_decode($enquiry_separation, true);

                foreach ($enquiry_separation as $key => $value) 
                {
                    $other[$key] = $value['title'];
                }
              }
                
              foreach($move_enquiry as $key){
                $enq = $this->enquiry_model->enquiry_by_code($key);
                $this->db->set('status',$status);
                $this->db->where('Enquery_id',$key);
                $this->db->update('enquiry');

                $msg = '';
                if($status=='1')
                {
                  $msg = 'Moved to '.display('enquiry',$comp_id);
                }
                else if($status =='2')
                  $msg = 'Moved to '.display('lead',$comp_id);
                else if($status == '3')
                  $msg = 'Moved to '.display('client',$comp_id);
                else
                {
                    if(!empty($other[$status]))
                      $msg = 'Moved to '.$other[$status];
                }
				
				foreach($move_deals as $key=>$val){
				$this->db->set('stage_id',$status);
                $this->db->where('id',$val);
                $this->db->update('commercial_info');
				}

                $this->Leads_Model->add_comment_for_events_stage_api($msg,$enq->Enquery_id,'','','',$assigner_user_id);

                 /*

              $created_by_user_id =   $enq->created_by;
              
              
              $phone_no =$this->User_model->read_by_id($created_by_user_id)->s_phoneno;
              
              $creator_phone = '91'.$phone_no;          
              
              $enq_of_name = $enq->name_prefix.''.$enq->name.' '.$enq->lastname;
              $notification_msg = sprintf(display('enquiry_converted_to_lead'),trim($enq_of_name));
        
              $this->Message_models->sendwhatsapp($convertor_phone,$notification_msg);
              
              $this->Message_models->sendwhatsapp($creator_phone,$notification_msg); /*             
              $this->Leads_Model->add_comment_for_events_api($notification_msg,$enq->Enquery_id,$assigner_user_id);             
              
              $insert_id = $this->Leads_Model->LeadAdd($data);
                
             // }*/
              }
               $this->set_response([
              'status' => true,
              'message' => array(array('error'=>'Lead moved successfully'))  
               ], REST_Controller::HTTP_OK);
          }
          else{
            $this->set_response([
              'status' => false,
              'message' => array(array('error'=>'No enquiry found to move.'))  
               ], REST_Controller::HTTP_OK);
          }
        }else{
          $this->set_response([
          'status' => false,
          'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);          
        }
        
 
      }
      public function user_boq_post(){ // assign lead BOQ users
        $user_list =  $this->User_model->read();
        $user_list_array = array();
        
        foreach ($user_list as $user) { 
          if (!empty($user->user_permissions)) {
            $module=explode(',',$user->user_permissions);
          }
          if(in_array(150,$module)==true){
            $user_list_array[] = array('user_id'=>$user->pk_i_admin_id,'name'=>$user->s_display_name.' '.$user->last_name);           
          } 
        }
        $this->set_response([
                'status' => true,
                'boq_users' => $user_list_array  
                 ], REST_Controller::HTTP_OK);
      
      }
        
        
          public function convert_to_client_post(){
        
            $this->form_validation->set_rules('client_code','Client Code' ,'required');
            $this->form_validation->set_rules('user_id','User Id' ,'required');

            if($this->form_validation->run() == true){
            //////////////// Insert Lead Data Into Client Table ///////////////////////
              $key = $this->input->post('client_code');
              $key = $this->Leads_Model->get_leadListDetailsby_code($key)->lid;
              $lead = $this->Leads_Model->get_leadListDetailsby_ledsonly($key);
        
              if($lead->lead_stage>=8){
                $data = array(
                'cl_name' => $lead->ld_name,
                'customer_code'=>$lead->lead_code,
                'cl_email' => $lead->ld_email,
                'cl_mobile' => $lead->ld_mobile,
                'address' => $lead->address,         
                'created_date'=>date('d-m-Y H:i:s'),
                'ip_address' =>$_SERVER['REMOTE_ADDR'],
                'create_by' => $this->session->user_id,
                'cl_status' => '1'
                );
               
               $insert_id = $this->Leads_Model->ClientMove($data);
               $this->db->set('lead_stage','Account');
               $this->db->where('lid',$key);
               $this->db->update('allleads');
               $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_ledsonly($key);
               $lead_code = $data['enquiry']->lead_code;
               $this->Leads_Model->add_comment_for_events('Converted to '.display('client',$data['enquiry']->comp_id),$lead_code);
             
               $mail_access  = $this->enquiry_model->access_mail_temp(); //access mail template..
               
               $signature   = $this->enquiry_model->get_signature();
             
               $to = $lead->ld_email;
            
               $custype=  $this->enquiry_model->get_custemertype($lead_code);
               if($custype->num_rows()>0){
                $phone ='91'.$lead->ld_mobile;
                $message = "Congratulations and Welcome to Osum family. We appreciate your business and look forward to serve you well with our innovative products and world class services. For any query or complaints kindly reach out to us at +91-8010-600-200 or write to us at support@osum.in .Associating with you is an OSUM feeling. - Thank you";
                
                //Send Email to admin..
                $this->Message_models->smssend($phone,$message);
                $this->Message_models->sendwhatsapp($phone,$message);
      
                  //send welcome mail to client...
                  foreach($mail_access as $rows){                        
                    if(trim($rows->response_type)==2 && trim($rows->auto_mail_for)==2){                        
                        $img = "<img src='".base_url($signature->logo)."' width='100px' height='100px' onerror='this.style.display=".'none'."'>";                        
                        if(strpos($rows->template_content, '@') == true){
                            
                            $msg = str_replace('@name',$name1,str_replace('@org',$org,str_replace('@phone',$this->session->phone,str_replace('@desg',$this->session->designation,str_replace('@user',$this->session->fullname,$rows->template_content)))));                            
                        }else{                            
                            $msg = $rows->template_content;
                        }
                        $this->Message_models->send_mail($to,$msg,$rows->mail_subject,'');
                    }
                  }                  
              }else{
                  $phone ='91'.$lead->ld_mobile;
                  $message = "Congratulations and Welcome on board as Authorized Channel Partner. We highly value your association and look forward to a long lasting and profitable business relationship with you. We appreciate your  business and look forward to serve you well with our innovative products and world class services. For any query or complaints kindly reach out to us at +91-8010-600-200 or write to us at support@osum.in . Once again a very warm welcome to the OSUM family - Associating with you is an OSUM feeling.";
                  $this->Message_models->smssend($phone,$message); 
                  $this->Message_models->sendwhatsapp($phone,$message);
                  
                  //send welcome mail to channel partner...
                   
                  foreach($mail_access as $rows){                        
                      if(trim($rows->response_type)==2 && trim($rows->auto_mail_for)==3){                          
                           $img = "<img src='".base_url($signature->logo)."' width='100px' height='100px' onerror='this.style.display=".'none'."'>";                           
                           if(strpos($rows->template_content, '@') == true){                              
                              $msg = str_replace('@name',$name1,str_replace('@org',$org,str_replace('@phone',$this->session->phone,str_replace('@desg',$this->session->designation,str_replace('@user',$this->session->fullname,$rows->template_content)))));
                          }else{                              
                              $msg = $rows->template_content;
                          }                          
                          $this->Message_models->send_mail($to,$msg,$rows->mail_subject,'');                      
                      }
                  } 
              
              }           
            
              $this->session->set_flashdata('message','Lead converted successfully to Client');            
              $user = $this->User_model->read_by_id($lead->created_by);            
              $customer_name = $lead->name_prefix.''.$lead->name.' '.$lead->lastname;
              $notification_msg = sprintf(display('lead_convert_to_client'),trim($customer_name));      
              $creator_phone = $user->s_phoneno;      
              $this->Message_models->sendwhatsapp($creator_phone,$notification_msg);             
              redirect('lead');
            
            }else{
              $this->session->set_flashdata('exception','Please Complete all Stages');
              redirect('lead');  
            }
          }else{
            $this->set_response([
            'status' => false,
            'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);
  
          }          
        }

public function get_enq_post(){
$compid = $this->input->post('comp_id');
      if(!empty($compid)){
        $arr_basic = array();
    
        $result = $this->sync_model->all_enquiry1($compid);
        $res = $this->sync_model->all_fieldvalue($id);
        // $extra_result = $this->sync_model->all_enquiry_extra1($compid);
        // $res['extra'] = $extra_result->result_array();

    foreach ($result as $key => $value) {
      foreach ($res as $key => $value1) {
        
      
        array_push($arr_basic,array('nameprefix'=>$value->name_prefix,'firstname'=>$value->name,'Mobile No.'=>$value->phone,'Email Address'=>$value->email,'Enquiry Source'=>$value->lead_name,'state'=>$value->stname,'city'=>$value->ciname,'Adress'=>$value->address,'Pin Code'=>$value->fvalue ,'Sub Source'=>$value->sub_source,'DOB'=>$value->fvalue,'Occupation'=>$value->fvalue,'Credit Score Doc'=>$value->fvalue,'Score'=>$value->fvalue ));
      
    }
  }
        
        $this->set_response([
          'status' => TRUE,
          'enquiry' =>$arr_basic
          ], REST_Controller::HTTP_OK);
      
    }else{
      $this->set_response([
            'status' => false,
            'enquiry' =>'not found'
             ], REST_Controller::HTTP_OK);
    }
}

  public function active_leads_page_post(){   
      $user_id= $this->input->post('user_id');
      $process_id= $this->input->post('process_id');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;
      //for multiprocess id
      if(!empty($process_id))
      {
        $process = implode(',',$process_id);
      }
            $res= array();
            if(!empty($user_id)){
                    $user_role1 = $this->User_model->read_by_id($user_id); 
                    if(!empty($user_role1)){
              $user_role=$user_role1->user_roles;

              $total = $this->enquiry_model->active_enqueries_api($user_id,2,$user_role,$process)->num_rows();
                
             $data['active_enquiry'] = $this->enquiry_model->active_enqueries_api($user_id,2,$user_role,$process,$offset,$limit);
              
               if(!empty($data['active_enquiry']->result()))
               {
                    $res['offset'] = $offset;
                    $res['limit'] = $limit;
                    $res['total'] = $total;
                    $res['list'] = array();

                  foreach($data['active_enquiry']->result() as $value){
                      $customer='';
                      
                    array_push($res['list'],array('enquery_id'=>$value->enquiry_id,'enquery_code'=>$value->Enquery_id,'org_name'=>$value->company_name,'client_name'=>$value->client_name,'customer_name'=>$value->name_prefix.' '.$value->name.' '.$value->lastname,'email'=>$value->email,'phone'=>$value->phone,'state'=>'','source'=>'test','type'=>$customer,'process_id'=>$value->product_id,'lead_stage'=>$value->lead_stage,'lead_description'=>$value->lead_discription));  
                 } 
               }
      }
      }     
          if(!empty($res)){   
          $this->set_response([
                'status' => TRUE,
                'leads' =>$res
                 ], REST_Controller::HTTP_OK);
        
        }else{
  
     $this->set_response([
                 'status' => false,
                'message' => array('error'=>'not found!') 
                 ], REST_Controller::HTTP_OK);
        }
  }

}