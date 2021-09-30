<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Dashboard extends REST_Controller {
    function __construct(){
        parent::__construct();
    }
    public function dashboard_post()
    {   
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process_id =  $this->input->post('process_id');//can be multiple

        $process = 0;
        if(!empty($process_id))
        {
            if(is_array($process_id))
                $process = implode(',',$process_id);
            else 
                $process = $process_id;
        }

        $this->load->model('enquiry_model');
        $funneldata = $this->enquiry_model->all_enqueries_api($user_id,$company_id,$process);
        $this->set_response([
            'status' => TRUE,            
            'funneldata' => $funneldata
        ], REST_Controller::HTTP_OK);        
    }
	
	public function forgot_password_post() {
             
            $email = $this->input->post('femail');
			$ecode = $this->input->post('fecode');
            $email_row = array();
			$this->load->model('dashboard_model','Message_models');
            if(is_numeric($email) == 1)
            {
              $data = $this->dashboard_model->getUserDataByPhone($email,$ecode);
              //$this->load->library('email');
              if(!empty($data))
              {
                $this->db->where('comp_id',$data->companey_id);
                $this->db->where('api_key','message');
                $email_row  =   $this->db->get('api_integration')->row_array();
              }else{
				$this->set_response([
                'status' => FALSE,            
                'message' => 'Invalid Credentials!'
                ], REST_Controller::HTTP_OK); 
			  }
              
            }
            else
            {
              $data = $this->dashboard_model->change_pass($email,$ecode);
              $this->load->library('email');
			  if(!empty($data))
              {
              $this->db->where('comp_id',$data->companey_id);
              $this->db->where('status',1);
              $email_row  =   $this->db->get('email_integration')->row_array();
			  }else{
				$this->set_response([
                'status' => FALSE,            
                'message' => 'Invalid Credentials!'
                ], REST_Controller::HTTP_OK);  
			  }
            }
            if(!empty($data))
              {
            //print_r($data);exit;
            if(empty($email_row) && $data->companey_id != 81){ 
                $this->set_response([
                'status' => FALSE,            
                'message' => 'Email is not configured'
                ], REST_Controller::HTTP_OK);                
            }else{
                if(is_numeric($email) == 1)
                {
                  expirePreviousOTP($data->pk_i_admin_id);
                  $phone= '91'.$this->input->post('femail');
                  $otp = mt_rand(100000, 999999);
                  //$otp = 123456;
                  $otpAry = array(
                    'otp'     => $otp,
                    'user_id' => $data->pk_i_admin_id,
                    'status'  => 1
                  );
                  $this->db->insert('tbl_otp',$otpAry);
                  $message = "Your OTP is $otp";
                  $this->Message_models->smssend($phone,$message,$data->companey_id,$data->pk_i_admin_id);
                $this->set_response([
                'status' => TRUE,            
                'message' => 'Please Enter otp received on phone'
                ], REST_Controller::HTTP_OK);
                }
                else
                {
                    $config['smtp_auth']    = true;
                    $config['protocol']     = $email_row['protocol'];
                    $config['smtp_host']    = $email_row['smtp_host'];
                    $config['smtp_port']    = $email_row['smtp_port'];
                    $config['smtp_timeout'] = '7';
                    $config['smtp_user']    = $email_row['smtp_user'];
                    $config['smtp_pass']    = $email_row['smtp_pass'];
                    $config['charset']      = 'utf-8';
                    $config['mailtype']     = 'html'; // or html
                    $config['newline']      = "\r\n";                  
                    $config['validation']   = TRUE; // bool whether to validate email or not 
                   $this->email->initialize($config);
                   $email_data['url'] = $this->config->base_url()."change-password/" . base64_encode($data->pk_i_admin_id);
                   //$this->load->library('email');
                   $this->email->from($email_row['smtp_user'], 'thecrm360');
                   $this->email->to($email);
                   $this->email->subject('Change password');
                   $msg = $this->load->view('templates/forgot_password_email',$email_data,true);
                   $this->email->message($msg);
                }
                
        
        //var_dump($this->email->send());exit();
        
        if ($data->reset_password === 1) {
            $this->set_response([
                'status' => TRUE,            
                'message' => 'Password reset link is already sent to your email id'
            ], REST_Controller::HTTP_OK);
        } else {          
            if(is_numeric($email) != 1)
            {
              if ($this->email->send()) {
                  $this->set_response([
                'status' => TRUE,            
                'message' => 'Your password reset link is sent on your email id'
            ], REST_Controller::HTTP_OK);
                  //echo $this->email->print_debugger();
              }else{
                $this->set_response([
                'status' => FALSE,            
                'message' => 'Somthing Went Wrong!'
            ], REST_Controller::HTTP_OK);
              }
            }   
        }
      }
	}else{
		$this->set_response([
        'status' => FALSE,            
        'message' => 'Invalid Credentials!'
        ], REST_Controller::HTTP_OK);
    }
	}
    public function ticket_dashboard_post()
    {
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $date_from = $this->input->post('date_from')??0;
        $date_to = $this->input->post('date_to')??0;
        
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Ticket_Model');
            //print_r($_POST); exit();    
          $res =  $this->Ticket_Model->TicketDashboardAPI($user_id,$company_id,$process,$date_from,$date_to);
            //$res=0;
           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Unable to Find'
            ], REST_Controller::HTTP_OK); 
           }

        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getSubSourceList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_subsource')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }


    public function getDataSourceList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_datasource')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getProbabilityList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('lead_score')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getIssueList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_nature_of_complaint')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }
 
    public function unique_company_list_post()
    {
        $user_id = $this->input->post('user_id')??0;
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $key = $this->input->post('key')??0;

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Client_Model');
          
            $res = $this->Client_Model->getCompanyList($key,array(),$company_id,$user_id,$process)->result();
            $ary = array();
            foreach ($res as $key => $value)
            {
                unset($value->enq_ids);
                unset($value->created_at);
                unset($value->updated_at);
                unset($value->comp_id); 
                $ary[] = $value;
            }
            $res = $ary;
            //echo $this->db->last_query();
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function filter_unique_company_list_post()
    {
        $user_id = $this->input->post('user_id')??0;
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $key = $this->input->post('key')??0;
		$keyword = $this->input->post('keyword');

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Client_Model');
          
            $res1 = $this->Client_Model->filter_getCompanyList($key,array(),$company_id,$user_id,$process,$keyword)->result();
			$res2 = $this->Client_Model->common_filter_getCompanyList($key,array(),$company_id,$user_id,$process,$keyword)->result();

			if(!empty($res1 && $res2)){
			$res = array_merge($res1,$res2);
			}else if(empty($res1) && !empty($res2)){
				$res = $res2; 
			}else{
				$res = $res1;
			}
			
            $ary = array();
            foreach ($res as $key => $value)
            {
                unset($value->enq_ids);
                unset($value->created_at);
                unset($value->updated_at);
                unset($value->comp_id); 
                $ary[] = $value;
            }
            $res = $ary;
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }	

    public function account_by_vcompany_post()
    {
        $comp_id = $this->input->post('vcompany_id');
        $user_id = $this->input->post('user_id');
        $this->form_validation->set_rules('vcompany_id','vcompany_id','required|trim');
        $this->form_validation->set_rules('user_id','user_id','required|trim');
        if($this->form_validation->run()==true)
        {
            $all_reporting_ids  = $this->common_model->get_categories($user_id);

            $this->db->select('enquiry.enquiry_id,enquiry.Enquery_id as enq_code,enquiry.client_name');
            $this->db->from('enquiry');
            $this->db->where("enquiry.company",$comp_id);

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
    }

    public function unique_company_list_page_post()
    {
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $key = $this->input->post('key')??'';
        $limit = $this->input->post('limit')??10;
        $offset = $this->input->post('offset')??0;
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Client_Model');
    
            $total = $this->Client_Model->getCompanyList($key,$company_id,$user_id,$process,'count');
            $data = $this->Client_Model->getCompanyList($key,$company_id,$user_id,$process,'data',$limit,$offset)->result();

            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = $data;
            
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }

    }   
    public function other_stages_post()
    {
        $base_url='https://v-trans.thecrm360.com/assets/images/icons/';
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $data=[];
        if($this->form_validation->run()==true)
        {
            $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING',$company_id);
            //featch 
            $dydata[]=['key'=>1,'title'=>'enquiry','icon'=>$base_url.'enquiry.jpeg' ];                        
            $dydata[]=['key'=>2,'title'=>'lead','icon'=>$base_url.'lead.jpeg' ];                        
            $dydata[]=['key'=>3,'title'=>'client','icon'=>$base_url.'client.jpeg' ];                        
            if (!empty($enquiry_separation)) {
                $enquiry_separation = json_decode($enquiry_separation, true);
                    foreach ($enquiry_separation as $key => $value) {
                       if($key==4){ $img='orders.jpeg'; }else{
                        $img='fit.jpeg';
                       }
                    $data[]=['key'=>$key,'title'=>$value['title'],'icon'=>$base_url.$img ];                        
                    }
                }              
            $res['basic_menu'] =$dydata;
            $res['dynamic_menu'] =$data;
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }

    }

public function getbranch_post()
    {       
	        $user_id = $this->input->post('user_id');
            $company_id = $this->input->post('company_id');
			if(!empty($user_id && $company_id)){
				$this->db->select('*');
				$this->db->from('branch');
				$this->db->where('comp_id',$company_id);
			$res =	$this->db->get()->result();
            }
           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
    }
	
}