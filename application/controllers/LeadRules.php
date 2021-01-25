<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LeadRules extends CI_Controller {
    public function __construct() {
        parent::__construct(); 
        $this->load->model('rule_model');
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
    public function index() {                     
        $data['leaddata'] = 
        $this->db->select('leadrules.*,concat_ws(" ",tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,concat_ws(" ",tbl_admin1.s_display_name,tbl_admin1.last_name) as updated_by_name')->from("leadrules")->where("leadrules.comp_id",$this->session->companey_id)->join('tbl_admin',"tbl_admin.pk_i_admin_id=leadrules.created_by",'inner')->join('tbl_admin as tbl_admin1',"tbl_admin1.pk_i_admin_id=leadrules.updated_by",'left')->get()->result_array();                          
        $data['content'] = $this->load->view('rules/rules_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }  
    
    /**
     * Parse a group of conditions */
    function parseGroup($rule, &$param) {
        $parseResult = "(";
        $bool_operator = $rule['condition'];
        // counters to avoid boolean operator at the end of the cycle 
        // if there are no more conditions
        $counter = 0;
        $total = count($rule['rules']);
        foreach($rule['rules'] as $i => $r) {
            if(array_key_exists('condition', $r)) {
                $parseResult .= "\n".$this->parseGroup($r, $param);
            } else {
                $parseResult .= $this->parseRule($r, $param);
                $total--;
                if($counter < $total)
                    $parseResult .= " ".$bool_operator." ";
            }
        }
        return $parseResult.")";
    }
    /**
     * Parsing of a single condition */
    function parseRule($rule, &$param) {
        $operators = array('equal' => "=", 
                   'not_equal' => "!=",
                   'in' => "IN (?)",
                   'not_in' => "NOT IN (_REP_)", 
                   'less' => "<", 
                   'less_or_equal' => "<=", 
                   'greater' => ">", 
                   'greater_or_equal' => ">=",
                   'begins_with' => "ILIKE",
                   'not_begins_with' => "NOT ILIKE",
                   'contains' => "ILIKE",
                   'not_contains' => "NOT ILIKE",
                   'ends_with' => "ILIKE",
                   'not_ends_with' => "NOT ILIKE",
                   'is_empty' => "=''",
                   'is_not_empty' => "!=''", 
                   'is_null' => "IS NULL", 
                   'is_not_null' => "IS NOT NULL"
               );
        $parseResult = "";
        $parseResult .= $rule['id']." ";
        $param[] = array($rule['type'][0] => $rule['value']);
        $parseResult .= $operators[$rule['operator']]." ".$rule['value'];
        return $parseResult;
    }
    public function save_rule($id=0){                
        $type           =   $this->input->post('type');
        $rule_json      =   $this->input->post('rule_json');
        $rule_action    =   $this->input->post('rule_action');
        $title          =   $this->input->post('rule_title');
        $rule_status    =   $this->input->post('rule_status');
        $this->form_validation->set_rules('type','Rule For','required');        
        $this->form_validation->set_rules('rule_action','Rule Action','required');        
        $this->form_validation->set_rules('rule_title','Rule Title','required');                
        $status = 0;        
        if ($this->form_validation->run() == true) {
            if (!empty($rule_json) && !empty($rule_action)) {  
                $jsonResult = array("data" => array());
                $getAllResults = false;
                $conditions = null;
                $result = "";
                $params = array();
                $conditions = $rule_json;
                if(!array_key_exists('condition', $conditions)) {
                    $getAllResults = true;
                } else {
                    $global_bool_operator = $conditions['condition'];
                    $counter = 0;
                    $total = count($conditions['rules']);
                    foreach($conditions['rules'] as $index => $rule) {
                        if(array_key_exists('condition', $rule)) {
                            $result .= $this->parseGroup($rule, $params);
                            $total--;
                            if($counter < $total)
                               $result .= " $global_bool_operator ";
                        } else {
                            $result .= $this->parseRule($rule, $params);
                            $total--;
                            if($counter < $total)
                               $result .= " $global_bool_operator ";
                        }
                    }
                }
                if ($id>0) {
                    $ins_data = array(
                            'type'       => $type,
                            'rule_sql'   => $result,
                            'status'     => $rule_status,
                            'rule_json'  => json_encode($rule_json),
                            'updated_by' => $this->session->user_id,
                            'rule_action'=> $rule_action,
                            'title'      => $title                            
                        );
                    $this->db->where('id',$id);
                    $this->db->where('comp_id',$this->session->companey_id);
                    $res    =   $this->db->update('leadrules',$ins_data);
                    if($type == 11){
                        
                        $this->db->where('id',$id);
                        $rule_row    =   $this->db->get('leadrules')->row_array();
                        $cron_id    =   $rule_row['cron_id'];

                        $cron_id = $this->create_cron_for_aging_rule($rule_action,$cron_id);                            
                    }
                    $msg = 'Rule Updated Successfully';                    
                }else{
                    $ins_data = array(
                                'comp_id'    => $this->session->companey_id,
                                'type'       => $type,
                                'rule_sql'   => $result,
                                'status'     => $rule_status,
                                'rule_json'  => json_encode($rule_json),
                                'created_by' => $this->session->user_id,
                                'rule_action'=> $rule_action,
                                'title'     => $title
                            );
                    $res    =   $this->db->insert('leadrules',$ins_data);
                    if($type == 11){
                        $cron_id = $this->create_cron_for_aging_rule($rule_action);                            
                        $this->db->where('id',$res);
                        $this->db->where('comp_id',$this->session->companey_id);
                        $this->db->set('cron_id',$cron_id);
                        $this->db->update('leadrules');                          
                    }                  
                }
                if($res){                                        
                    $status = 1;
                }else{
                    $msg = 'Something went wrong!';
                }
            }else{
                $msg = 'Something went wrong!';
            }
        }else{
            $msg = validation_errors();
        }
        echo json_encode(array('status'=>$status,'msg'=>$msg));
    }    

    public function create_cron_for_aging_rule($rule_action,$cron_id=0){
        $time_arr = explode(":",$rule_action);                        
        $hr  = (int)$time_arr[0];
        $min = (int)$time_arr[1];
        require_once FCPATH.'third_party/vendor/autoload.php';
        date_default_timezone_set("Asia/kolkata");
        if($hr && $min){
            $minute     =   $min;
            $hour       =   $hr;
            $command = $min.' '.$hour.' * * *' ;            
            $cron = Cron\CronExpression::factory($command);                                        
            $running_time= $cron->getNextRunDate()->format('Y-m-d H:i');            
            $url    =   base_url().'enquiry/lead_aging_rule_exec/'.$this->session->companey_id;
            $data=  [
                        'minute'        =>  $minute,
                        'hour'          =>  $hour,
                        'day'           =>  '*',
                        'month'         =>  '*',
                        'weekday'       =>  '*',
                        'command'       =>  $command,
                        'comp_id'       =>  $this->session->companey_id,
                        'status'        =>  0,
                        'created_by'    =>  $this->session->user_id,
                        'running_time'  =>  $running_time,
                        'url'           => $url
                    ];                            
            if($cron_id){
                $this->db->where('id', $cron_id);
                $cron_id = $this->db->update('cronjobs',$data);                
            }else{
                $cron_id = $this->db->insert('cronjobs',$data);
            }
        }
        return $cron_id;
    }
    public function create_rule($id=0){
        if (user_role('100') == true) {}
        $data['title'] = display('leadrules');
        $data['id'] = $id;
        $this->load->model('Leads_Model');
        $this->load->model('location_model');
        $this->load->model('user_model');
        $this->load->model('dash_model');
        $this->load->model('Datasource_model');
        $this->load->model('enquiry_model');
        if ($id) { 
            $data['rule_data']    =   $this->rule_model->get_rule($id);            
        }
        // print_r($data);
        // die();
        $source    =   $this->Leads_Model->get_leadsource_list();
        $country   =   $this->location_model->country();
        $state     =   $this->location_model->estate_list();
        $city      =   $this->location_model->ecity_list();
		$lead_score = $this->Leads_Model->get_leadscore_list();
        
        $process_list = $this->dash_model->get_user_product_list();        
        $sub_source = $this->Datasource_model->subsourcelist();             
        $products = $this->enquiry_model->get_user_productcntry_list();
        
        $this->load->model('ticket_Model');
        $ticket_status = $this->ticket_Model->get_ticket_status();
        $rule_source = array();
        if (!empty($source)) {             
            foreach ($source as $key => $value) {
              $rule_source[$value->lsid]  = $value->lead_name;
            }
        }
        $rule_subsource = array();
        if (!empty($sub_source)) {            
            foreach ($sub_source as $key => $value) {
              $rule_subsource[$value->subsource_id]  = $value->subsource_name;
            }
        }
        $rule_product = array();
        if (!empty($products)) {            
            foreach ($products as $key => $value) {
              $rule_product[$value->id]  = $value->country_name;
            }
        }
        $rule_country = array();
        if (!empty($country)) {
            foreach ($country as $key => $value) {
                $rule_country[$value->id_c]  = $value->country_name;
            }
        }
        $rule_state = array();
        if (!empty($state)) {
            foreach ($state as $key => $value) {
                $rule_state[$value->id]  = $value->state;
            }
        }
        $rule_city = array();
        if (!empty($city)) {
            foreach ($city as $key => $value) {
                $rule_city[$value->id]  = $value->city;
            }
        }
        $rule_lead_stage = array();
        if (!empty($lead_stages)) {
            foreach ($lead_stages as $key => $value) {
                $rule_lead_stage[$value->stg_id]  = $value->lead_stage_name;
            }
        }
        // $rule_lead_description = array();
        // if (!empty($all_description)) {
        //     foreach ($all_description as $key => $value) {
        //         $rule_lead_description[$value->id]  = $value->description;
        //     }
        // }
        // $rule_ticket_description = array();
        // if (!empty($ticket_description)) {
        //     foreach ($ticket_description as $key => $value) {
        //         $rule_ticket_description[$value->id]  = $value->description;
        //     }
        // }
        $rule_process = array();
        if (!empty($process_list)) {
            foreach ($process_list as $key => $value) {
                $rule_process[$value->sb_id]  = $value->product_name;
            }
        }
        $rule_ticket_status = array();
        if (!empty($ticket_status)) {
            foreach ($ticket_status as $key => $value) {
                $rule_ticket_status[$value->id] = $value->status_name;
            }
        }  

        $rule_lead_score = array();
        if (!empty($lead_score)) {
            foreach ($lead_score as $key => $value) {
                $rule_lead_score[$value->sc_id] = $value->score_name. ' '.$value->probability;
            }
        }  

        $data['lead_stages'] = $this->Leads_Model->getStageJson(array(1,2,3));
        $data['ticket_stages'] = $this->Leads_Model->getStageJson(array(4));
        $data['lead_description'] = $this->Leads_Model->getSubStageJson(array(1,2,3));
        $data['ticket_description'] = $this->Leads_Model->getSubStageJson(array(4));
        $data['rule_ticket_status'] = json_encode($rule_ticket_status);
        $data['rule_enquiry_status'] = json_encode(array(1=>'Enquiry',2=>'Lead',3=>'Client'));
        
        $data['rule_lead_score'] = json_encode($rule_lead_score);
        $data['lead_source'] = json_encode($rule_source);
        $data['country']     = json_encode($rule_country);
        $data['state']       = json_encode($rule_state);
        $data['city']        = json_encode($rule_city);
        // $data['lead_stages'] = json_encode($rule_lead_stage);
        

        $all_description  = $this->Leads_Model->getSubStageJson(array(1,2,3));  
        $ticket_description  = $this->Leads_Model->getSubStageJson(array(4)); 
        $data['rule_process']   = json_encode($rule_process);
        $data['user_list']   = $this->user_model->companey_users();
        $data['products']   = json_encode($rule_product);
        $data['sub_source']   = json_encode($rule_subsource);
       
        $data['content'] = $this->load->view('rules/create_rule', $data, true);
        $this->load->view('layout/main_wrapper', $data);       
    }
    public function execute_rule($id,$enquiry_code=0){ // for single rule execution
        $res  =  $this->rule_model->execute_rule($id,$enquiry_code);
        $this->session->set_flashdata('message', 'Rule Executed Successfully. '.$res.' data affected.');
        redirect('leadRules');
    }    
    public function auto_followup_rule(){
        $res    =   $this->rule_model->get_rule_by_type(4);
        echo json_encode($res);        
    }
    public function data_time_add_hr($hr){
        $new_time = date("Y-m-d H:i", strtotime("+$hr hours"));
        $new_time    =   explode(' ', $new_time);
        echo json_encode($new_time);
    }
}