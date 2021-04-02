<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Client extends REST_Controller {
    function __construct()
    {
        parent::__construct();
           $this->load->database();
           $this->load->library('form_validation');
           	
		$this->load->model(array(
			'enquiry_model','Leads_Model','location_model','Task_Model','User_model','Message_models','Client_Model'
		));
		
		$this->load->library('email'); 
    $this->load->model('api/sync_model');
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
    public function active_clients_post(){   
       $user_id= $this->input->post('user_id');
       $process_id= $this->input->post('process_id');

       //===========for others = 
      if(empty($_POST['other_id']))
        $data_type_id = 3;
      else
        $data_type_id = $_POST['other_id'];


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
             $data['active_enquiry'] = $this->enquiry_model->active_enqueries_api($user_id,$data_type_id,$user_role,$process);
    
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
                    array_push($res,array('enquery_id'=>$value->enquiry_id,'enquery_code'=>$value->Enquery_id,'org_name'=>$value->org_name,'customer_name'=>$value->name_prefix.' '.$value->name.' '.$value->lastname,'email'=>$value->email,'phone'=>$value->phone,'state'=>'','source'=>'test',
                    'tag'=>$tags_row,
                    'type'=>$customer,'process_id'=>$value->product_id));  
                 } 
               }
               
               if(empty($res)){array_push($res,array('error'=>'enquiry not find'));}
            }else{array_push($res,array('error'=>'user not exist'));}
             
          $this->set_response([
                'status' => TRUE,
                'client' =>$res
                 ], REST_Controller::HTTP_OK);
        
        }else{
	
	   $this->set_response([
                 'status' => false,
                'message' => array('error'=>'not found!') 
                 ], REST_Controller::HTTP_OK);
        }
    }
    public function view_post(){
      $this->form_validation->set_rules('client_id','Client Id','required');    
      $this->form_validation->set_message('required', 'Invalid %s');
      
      if ($this->form_validation->run() == true) {
        
        $client_id  = $this->input->post('client_id');  
        
        $this->load->model('Client_Model');
        $client_row = $this->Client_Model->clientdetail_by_id($client_id);
        
        $this->db->select('s_display_name,last_name');
        $this->db->where('pk_i_admin_id',$client_row->created_by);
        $created_by_row  = $this->db->get('tbl_admin')->row();
        $created_by_name = $created_by_row->s_display_name.' '.$created_by_row->last_name;
            $customer='';
              if(!empty($client_row->customer_type)>0){
                    $customer_type_val =$client_row->customer_type;
                    $type='Customer';               
              }else if(!empty($client_row->channel_partnr_type)>0){
                $customer_type_val =$client_row->customer_type;
                  $type='Channel Partner';                 
              }
        
        if (!empty($client_row)) {
            $client_array = array(
                'client_id'         =>  $client_row->cli_id,
                'client_code'       =>  $client_row->customer_code,
                'name'              =>  $client_row->name_prefix." ".$client_row->cl_name." ".$client_row->lastname,
                'email'             =>  $client_row->cl_email,
                'other_phone'       =>  $client_row->other_no,
                'other_email'       =>  $client_row->other_email,
                'phone'             =>  $client_row->phone,
                'enquiry_cust_type' =>  $type,
                'customer_type'     =>  $customer_type_val,
                'org_name'          =>  $client_row->org_name,
                'source'            =>  $client_row->lead_name,
                'referred_by'       =>  $client_row->referdby,
                'country'           =>  $client_row->country_name,
                'region'            =>  $client_row->region_name,
                'state'             =>  $client_row->state,
                'territory'         =>  $client_row->territory_name,
                'city'              =>  $client_row->city,
                'pincode'           =>  $client_row->pin_code,
                'address'           =>  $client_row->address,
                'requirement'       =>  $client_row->enquiry,                
                'created_date'      =>  $client_row->created_date,
                'created_by'        =>  $created_by_name,
            );
        }else{
          $client_array = array();
        }
         if($client_row->enquiry_cust_type == 1){
            $this->db->select('customer_type');
            $this->db->where('cus_id',$client_row->customer_type);
            $customer_type_name   = $this->db->get('tbl_customer_type')->row();
            $client_array['customer_type'] = $customer_type_name->customer_type;
          }else if ($client_row->enquiry_cust_type == 11) {
            $this->db->select('channel_partner_type');
            $this->db->where('ch_id',$client_row->channel_partnr_type);
            $channel_type_name   = $this->db->get('tbl_channel_partner')->row();
            
            $client_array['customer_type'] = $channel_type_name->channel_partner_type;            
          
          }
        $this->set_response([
                        'status' => true,
                        'client' =>$client_array
                         ], REST_Controller::HTTP_OK);     
      }else{
        $this->set_response([
            'status' => false,
            'client' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))  
             ], REST_Controller::HTTP_OK);
      }
  }
  
     public function update_post()
     {
         $client_code = $this->form_validation->set_rules('client_code');
         $user_id = $this->form_validation->set_rules('user_id');
         
         if($this->form_validation->run() == true)
         {
      
          $name = $this->input->post('name');
          $email = $this->input->post('email');
          $mobile = $this->input->post('mobile');
          $address = $this->input->post('address');
          $status = $this->input->post('status');
          $updateDate = date('d-m-Y');
          
          $this->db->set('cl_mobile',$mobile);
          $this->db->set('cl_email',$email);
          $this->db->set('cl_name',$name);
          $this->db->set('address',$address);
          $this->db->set('cl_status',$status);
          $this->db->set('updated_date',$updateDate);
          
          $this->db->where('customer_code',$client_code);
          $this->db->update('clients');
          $this->Leads_Model->add_comment_for_events_api('Information Updated',$client_code,$user_id);
          
          $this->set_response([
              'status' => false,
              'message' => 'Informations Updated Successfully'  
             ], REST_Controller::HTTP_OK);
      
      }else{ 
         $this->set_response([
                'status' => false,
                'message' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))  
                 ], REST_Controller::HTTP_OK);
      }
    }

      public function assign_client_post(){
          
          $this->form_validation->set_rules('user_id','User ID' ,'required');
          $this->form_validation->set_rules('assign_user_id','Assign ID' ,'required');
          $this->form_validation->set_rules('client_code[]','Client Code' ,'required');
          if($this->form_validation->run() == true){
            
            $move_enquiry=$this->input->post('client_code[]');
            
            $assign_employee=$this->input->post('assign_user_id');
            $user_id=$this->input->post('user_id');
            
            $user =$this->User_model->read_by_id($assign_employee);
            
            if(!empty($move_enquiry)){

                $assignee_phone = '91'.$user->s_phoneno;
                $assign_to_name = $user->s_display_name.' '.$user->last_name;
                $assigner_user = $this->User_model->read_by_id($user_id);            
                $assigner_name  = $assigner_user->s_display_name.' '.$assigner_user->s_display_name;
                $customer_name  = '';
              foreach($move_enquiry as $key){
                $data['enquiry'] = $this->Client_Model->clientdetail_by_client_code($key);             
                $enquiry_code = $data['enquiry']->Enquery_id;            
                $enquiry_id = $data['enquiry']->enquiry_id;            
                $this->Client_Model->assign_enquery_api($enquiry_id,$assign_employee,$enquiry_code,$user_id);
                $customer_name  .= $data['enquiry']->name_prefix.''.$data['enquiry']->name.' '.$data['enquiry']->lastname.',';
                $customer_name1  = $data['enquiry']->name_prefix.''.$data['enquiry']->name.' '.$data['enquiry']->lastname;
                $notification_msg = sprintf(display('client_assigned_to'),trim($customer_name1),trim($assign_to_name),trim($assigner_name));
                $this->Leads_Model->add_comment_for_events_api($notification_msg,$enquiry_code,$user_id);
              }
              $notification_msg = sprintf(display('client_assigned_to'),trim($customer_name),trim($assign_to_name),trim($assigner_name));
              $this->Message_models->sendwhatsapp($assignee_phone,$notification_msg); 
            
              $this->set_response([
              'status' => true,
              'message' => array(array('error'=>display('client').' Assigned successfully to Sales'))  
               ], REST_Controller::HTTP_OK);
            
            }else{
            
               $this->set_response([
              'status' => false,
              'message' => array(array('error'=>'No '.display('client').' found to assign.'))  
               ], REST_Controller::HTTP_OK);
            
            }
          
          }else{
            $this->set_response([
                'status' => false,
                'message' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))  
                 ], REST_Controller::HTTP_OK);
          }  
      }

       public function assign_installation_post(){  // assign to service
          $this->form_validation->set_rules('client_code[]',display('client').' Code','required');
          $this->form_validation->set_rules('user_id','User Id','required');
          $this->form_validation->set_rules('assign_employee','Assigned to user id','required');
          
            if($this->form_validation->run() == true){
                
                $move_enquiry=$this->input->post('client_code[]');
                $assign_employee=$this->input->post('assign_employee');
                $user_id=$this->input->post('user_id');
                
                $user =$this->User_model->read_by_id($assign_employee);
                
                if(!empty($move_enquiry)){
                  
                  $assignee_phone = '91'.$user->s_phoneno;
  
                  $assign_to_name = $user->s_display_name.' '.$user->last_name;
                  $assigner_user = $this->User_model->read_by_id($user_id);            
                  $assigner_name  = $assigner_user->s_display_name.' '.$assigner_user->s_display_name;
                  $customer_name  = '';
                  //echo $assigner_name;

                  foreach($move_enquiry as $key){
                  //echo "string";
                    if($this->Client_Model->assign_installer_api($key,$assign_employee)){
                   // echo "string";  
                      //$this->Leads_Model->add_comment_for_events('Client Assigned',$key);
                    
                      $data['enquiry'] = $this->Client_Model->clientdetail_by_client_code($key);             
                    /*  echo "<pre>dty";
                      print_r($data['enquiry']);
                      exit();
*/
                      
                      $customer_name  .= $data['enquiry']->name_prefix.''.$data['enquiry']->name.' '.$data['enquiry']->lastname.', ';
                      
                      $customer_name1  = $data['enquiry']->name_prefix.''.$data['enquiry']->name.' '.$data['enquiry']->lastname;
                      
                      $notification_msg = sprintf(display('client_assign_for_installation'),trim($customer_name1),trim($assign_to_name),trim($assigner_name));
                      $this->Leads_Model->add_comment_for_events_api($notification_msg,$key,$user_id);

                    }
                  }
                  $notification_msg = sprintf(display('client_assign_for_installation'),trim($customer_name),trim($assign_to_name),trim($assigner_name));
                  $this->Message_models->sendwhatsapp($assignee_phone,$notification_msg);

                       $this->set_response([
                  'status' => true,
                  'message' => array(array('error'=>display('client').' Assigned successfully for Installation'))  
                   ], REST_Controller::HTTP_OK);
                      
                }else{
            
                 $this->set_response([
                'status' => false,
                'message' => array(array('error'=>'No '.display('client').' found to assign.'))  
                 ], REST_Controller::HTTP_OK);
            
              }
            }else{
              $this->set_response([
                'status' => false,
                'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
                 ], REST_Controller::HTTP_OK);
            }  
      }
      public function assignee_list_post(){ // assign client as service
        $user_list =  $this->User_model->read();
        $user_list_array = array();
        foreach ($user_list as $user) { 
          if (!empty($user->user_permissions)) {
            $module=explode(',',$user->user_permissions);
          }
          if(in_array(100,$module)==true||in_array(101,$module)==true||in_array(102,$module)==true){
            $user_list_array[] = array('user_id'=>$user->pk_i_admin_id,'name'=>$user->s_display_name.' '.$user->last_name);           
          } 
        }
        $this->set_response([
                'status' => true,
                'message' => array($user_list_array)  
                 ], REST_Controller::HTTP_OK);
      
      }
public function get_client_list_post(){
      $dfields = $this->sync_model->getformfield();       
        
      $dacolarr = array();

      if(empty($_POST['other_id']))
        $data_type_id = 3;
      else
        $data_type_id = $_POST['other_id'];

$data_type = $data_type_id;

$compid = $this->input->post('comp_id');
 $fieldval =  $this->sync_model->getfieldvalue($compid);  
if(!empty($compid)){
    $arr_basic = $arr_dyn = array();
  $res = $this->sync_model->get_enquiry_list($data_type,$compid);
    foreach ($res as $key => $value) {
        $arr_basic = array('nameprefix'=>$value->name_prefix,'firstname'=>$value->name,'lastname'=>$value->lastname,'phone'=>$value->phone,'address'=>$value->address,'process'=>$value->product_name,'lead_stage'=>$value->lead_stage_name,'lead_description'=>$value->lead_discription,'reference_name'=>$value->reference_name,'created_date'=>$value->created_date,'created_by'=>$value->created_by_name,'assign_to'=>$value->assign_to_name,'datasource_name'=>$value->datasource_name,'country_name'=>$value->country_name,'bank_name'=>$value->bank_name);
            $enqid = $value->enquiry_id;      
      if(!empty($dacolarr) and !empty($dfields)){
        foreach($dfields as $ind => $flds){         
          if(in_array($flds->input_id, $dacolarr )){            
            $arr_dyn = $fieldval[$enqid][$flds->input_id]->fvalue;  
          }         
        }       
      }
      
    }
    $this->set_response([
      'status' => TRUE,
      'basic' =>$arr_basic,
      'dyn'   =>$arr_dyn,
      ], REST_Controller::HTTP_OK); 
}
}

public function active_clients_page_post(){   
       $user_id= $this->input->post('user_id');
       $process_id= $this->input->post('process_id');
        $offset = $this->input->post('offset')??0;
        $limit = $this->input->post('limit')??10;


        if(empty($_POST['other_id']))
        $data_type_id = 3;
      else
        $data_type_id = $_POST['other_id'];

        if(is_array($process_id))
             $process = implode(",", $process_id);
        else
          $process = $process_id;

            $res= array();
            if(!empty($user_id))
            {
                $user_role1 = $this->User_model->read_by_id($user_id); 
                if(!empty($user_role1))
                {
                  $user_role=$user_role1->user_roles;


            $total = $this->enquiry_model->active_enqueries_api($user_id,$data_type_id,$user_role,$process)->num_rows();
           
             $data['active_enquiry'] = $this->enquiry_model->active_enqueries_api($user_id,$data_type_id,$user_role,$process,$offset,$limit);
    
               if(!empty($data['active_enquiry']->result()))
               {

                    $res['offset'] = $offset;
                    $res['limit'] = $limit;
                    $res['total'] = $total;
                    $res['list'] = array();

                  foreach($data['active_enquiry']->result() as $value){
                      $customer='';
                      $tags_row = array();
                    if(!empty($value->tag_ids)){
                      $this->db->select('title,color,id');
                      $this->db->where("id IN(".$value->tag_ids.")");
                      $tags = $this->db->get('tags')->result_array();
                      if(!empty($tags)){
                        foreach ($tags as $k => $v) {
                          $tags_row[] = array('color'=>$v['color'],'name'=>$v['title'],'id'=>$v['id']);
                        }
                      }
                    }
                    array_push($res['list'],array('enquery_id'=>$value->enquiry_id,'enquery_code'=>$value->Enquery_id,'org_name'=>$value->company_name,'client_name'=>$value->client_name,'customer_name'=>$value->name_prefix.' '.$value->name.' '.$value->lastname,'email'=>$value->email,'phone'=>$value->phone,'state'=>'','source'=>'test','type'=>$customer,'process_id'=>$value->product_id,
                    'tags'=>$tags_row,
                    'lead_stage'=>$value->lead_stage,'lead_description'=>$value->lead_discription));  
                 } 

                  $this->set_response([
                'status' => true,
                'client' => $res, 
                 ], REST_Controller::HTTP_OK);
               }
               else
               {
                   $this->set_response([
                    'status' => false,
                    'message' => array('error'=>'No data found!') 
                     ], REST_Controller::HTTP_OK);
               }
            }
            else
            {
               $this->set_response([
                'status' => false,
                'message' => array('error'=>'user not exist!') 
                 ], REST_Controller::HTTP_OK);
            }
        }else{
  
           $this->set_response([
                'status' => false,
                'message' => array('error'=>'not found!') 
                 ], REST_Controller::HTTP_OK);
        }
    } 
  
}