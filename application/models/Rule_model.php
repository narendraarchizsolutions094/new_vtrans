<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rule_model extends CI_Model {
	
	public function get_rule($id,$comp_id=0){
        if ($comp_id == 0) {
            $comp_id    =   $this->session->companey_id;
        }
		$this->db->where('id',$id);
		$this->db->where('comp_id',$comp_id);
		return $this->db->get('leadrules')->row_array(); 
	}

    public function get_rules($type=array(),$comp_id=0){        
        if ($comp_id == 0) {
            $comp_id    =   $this->session->companey_id;
        }
        $this->db->where('comp_id',$comp_id);
        $this->db->where('status',1);
		$this->db->where('process_id',$this->session->process[0]);
        if (!empty($type)) {
            $this->db->where_in('type',$type);
        }
        return $this->db->get('leadrules')->result_array();
    }
    
    public function get_probability_by_score($score,$comp_id){        
        return $this->db->query("select * from lead_score where comp_id=$comp_id AND $score between score_from and score_to")->row_array();
    }

	public function execute_rule($id,$enquiry_code=0,$comp_id=0,$user_id=0){
        if ($comp_id == 0) {
            $comp_id    =   $this->session->companey_id;
        }
        if ($user_id == 0) {
            $user_id = $this->session->user_id;
        }
		//$this->load->model('rule_model');
		$rule_data    =   $this->get_rule($id,$comp_id);        
        $affected = 0;
        if (!empty($rule_data)) {
            if (!empty($rule_data['rule_sql']) && $rule_data['status'] == 1) {
                if ($rule_data['type'] == 1) {                    
                    
                    $score_name    =   $this->get_probability_by_score($rule_data['rule_action'],$comp_id);                    
                    $sid = 0;
                    if(!empty($score_name['score_name'])){
                        $sid = $score_name['sc_id'];
                    }

                    $this->db->where('('.$rule_data['rule_sql'].')');                    
                    if ($enquiry_code) {
                        $this->db->where('Enquery_id',$enquiry_code);                
                    }
                    $this->db->where('comp_id',$comp_id);                
                    if(!empty($sid)){
                        $this->db->set('lead_score',$sid);
                    }
                    $this->db->set('score',$rule_data['rule_action']);
                    $this->db->update('enquiry');                    
                    $affected = $this->db->affected_rows();
                }else if ($rule_data['type'] == 2) {
                    if (!empty($rule_data['rule_action'])) {
                        $assign_to = explode(',', $rule_data['rule_action']);
                        if (!empty($assign_to[0])) {
                            $this->db->where('('.$rule_data['rule_sql'].')');
                            if ($enquiry_code) {
                                $this->db->where('Enquery_id',$enquiry_code);                
                            }
                            $this->db->where('comp_id',$comp_id);                
                            $this->db->where('aasign_to is null'); 
                            $this->db->set('aasign_to',$assign_to[0]);
                            $this->db->update('enquiry');                            
                            $affected = $this->db->affected_rows();
                            //echo $affected.'<br>'.$assign_to[0].$this->db->last_query();
                            if ($affected) { 
                                //$this->Leads_Model->add_comment_for_events('Converted to client', $enquiry_code);
                                //$this->Leads_Model->add_comment_for_events_stage(, ,'','',$rule_data['title'].' '.'Rule Applied','');

                                $this->Leads_Model->add_comment_for_events_stage_api('Enquiry Assigned', $enquiry_code,'','',$rule_data['title'].' '.'Rule Applied',$user_id);
                                array_push($assign_to, array_shift($assign_to));
                                $assign_to = implode(',', $assign_to);
                                $this->db->where('id',$id);
                                $this->db->update('leadrules',array('rule_action'=>$assign_to));       
                            }                                   
                        }
                    } 
                }else if ($rule_data['type'] == 3) {

                    $this->db->where('('.$rule_data['rule_sql'].')');

                    $rule_for = (substr($enquiry_code,0,3)=='TCK')?'ticket':'enquiry';

                    
                    
                    if ($enquiry_code)
                    {
                        if($rule_for == 'ticket'){
                            $this->db->select('tbl_ticket.*');
                            $this->db->join('enquiry','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                            $this->db->where('tbl_ticket.ticketno',$enquiry_code); 
                            $this->db->where('tbl_ticket.company',$comp_id);
                        }
                        else{
                            $this->db->select('enquiry.*');
                            $this->db->join('tbl_ticket','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                            $this->db->where('enquiry.Enquery_id',$enquiry_code);                
                            $this->db->where('enquiry.comp_id',$comp_id);
                        }
                    }                                    
                    //$this->db->where('rule_executed!=',$id);   
                    if($rule_for=='ticket')
                        $enq_row = $this->db->get('tbl_ticket')->row_array();
                    else                                 
                        $enq_row = $this->db->get('enquiry')->row_array();

                    if (!empty($enq_row['email']) && !empty($rule_data['rule_action'])) {
                        
                        $this->db->where('pk_i_admin_id',$user_id);
                        $user_row  = $this->db->get('tbl_admin')->row_array();
                        
                        $row   =   $this->db->select('*')
                                    ->from('api_templates')
                                    ->join('mail_template_attachments', 'mail_template_attachments.templt_id=api_templates.temp_id', 'left')                    
                                    ->where('temp_id',$rule_data['rule_action'])                        
                                    ->get()
                                    ->row_array();
                        
                        if (!empty($row)) {

                            $this->db->where('comp_id',$comp_id);
                            $this->db->where('sys_para','usermail_in_cc');
                            $this->db->where('type','COMPANY_SETTING');
                            $cc_row = $this->db->get('sys_parameters')->row_array(); 
                            $cc = '';
                            if(!empty($cc_row))
                            {
                                $this->db->where('pk_i_admin_id',$user_id);
                               $cc_user =  $this->db->get('tbl_admin')->row_array();
                               if(!empty($cc_user))
                                    $cc = $cc_user['s_user_email'];
                            }
                           
                            $this->load->model('Message_models');
                            $subject = $row['mail_subject'];
                            $message = $row['template_content'];

                            if($rule_for=='ticket')
                            {


                                $find = array('@name',
                                                '@phone',
                                                '@username',
                                                '@userphone',
                                                '@designation',
                                                  '@ticket_no',
                                                    '@tracking_no'
                                            );
                                $replace = array(
                                    $enq_row['name'],
                                    $user_row['contact_phone'],
                                    $user_row['s_username'],
                                    $enq_row['phone'],
                                    $user_row['designation'],
                                    $enquiry_code,
                                    $enq_row['tracking_no'],
                                    );
                                $message  =str_replace($find, $replace, $message);

                                $subject  = str_replace($find, $replace, $subject);
                            }
                            else
                            {
                                $name1 = $enq_row['name_prefix'].' '.$enq_row['name'].' '.$enq_row['lastname'];

                                $message = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message))))));

                                $subject = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$subject))))));
                            } 

                            if($this->Message_models->send_email($enq_row['email'],$subject,$message,$comp_id,$cc)){
                                //$this->db->where('Enquery_id',$enquiry_code);
                                //$this->db->update('enquiry',array('rule_executed'=>$id));
                            }
                        }

                    }
                }else if($rule_data['type'] == 6){
                    $this->db->where('('.$rule_data['rule_sql'].')');
                     $rule_for = (substr($enquiry_code,0,3)=='TCK')?'ticket':'enquiry';
                    
                    
                     if ($enquiry_code)
                     {
                         if($rule_for == 'ticket'){
                             $this->db->select('tbl_ticket.*');
                             $this->db->join('enquiry','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                             $this->db->where('tbl_ticket.ticketno',$enquiry_code); 
                             $this->db->where('tbl_ticket.company',$comp_id);
                         }
                         else{
                            $this->db->select('enquiry.*');
                             $this->db->join('tbl_ticket','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                             $this->db->where('enquiry.Enquery_id',$enquiry_code);                
                             $this->db->where('enquiry.comp_id',$comp_id);
                         }
                     }                    

                    //$this->db->where('rule_executed!=',$id);                                    
                     if($rule_for=='ticket')
                        $enq_row = $this->db->get('tbl_ticket')->row_array();
                    else                                 
                        $enq_row = $this->db->get('enquiry')->row_array();


                    if (!empty($enq_row['phone']) && !empty($rule_data['rule_action'])) {

                        $this->db->where('pk_i_admin_id',$user_id);
                        $user_row  = $this->db->get('tbl_admin')->row_array();

                        $phone    =   $enq_row['phone'];

                        $row   =   $this->db->select('*')
                                    ->from('api_templates')                                    
                                    ->where('temp_id',$rule_data['rule_action'])                        
                                    ->get()
                                    ->row_array();
                        
                        if (!empty($row)) { 
                           
                            $this->load->model('Message_models');                            
                            $message = $row['template_content'];


                            if($rule_for=='ticket')
                            {


                                $find = array('@name',
                                                '@phone',
                                                '@username',
                                                '@userphone',
                                                '@designation',
                                                 '@ticket_no',
                                                    '@tracking_no'
                                            );
                                $replace = array(
                                    $enq_row['name'],
                                    $user_row['contact_phone'],
                                    $user_row['s_username'],
                                    $enq_row['phone'],
                                    $user_row['designation'],
                                    $enquiry_code,
                                    $enq_row['tracking_no'],
                                    );
                                $message  =str_replace($find, $replace, $message);
                            }
                            else
                            {
                                $name1 = $enq_row['name_prefix'].' '.$enq_row['name'].' '.$enq_row['lastname'];

                                $message = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message))))));
                            } 

                            if($this->Message_models->smssend($phone,$message,$comp_id,$user_id)){
                                //$this->db->where('Enquery_id',$enquiry_code);
                                //$this->db->update('enquiry',array('rule_executed'=>$id));
                            }
                        }

                    }
                    
                }else if($rule_data['type'] == 7){
                    $this->db->where('('.$rule_data['rule_sql'].')');


                    $rule_for = (substr($enquiry_code,0,3)=='TCK')?'ticket':'enquiry';
                    
                    if ($enquiry_code)
                    {
                        if($rule_for == 'ticket'){
                            $this->db->select('tbl_ticket.*');
                            $this->db->join('enquiry','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                            $this->db->where('tbl_ticket.ticketno',$enquiry_code); 
                            $this->db->where('tbl_ticket.company',$comp_id);
                        }
                        else{
                            $this->db->select('enquiry.*');
                            $this->db->join('tbl_ticket','enquiry.enquiry_id=tbl_ticket.ticketno','left');
                            $this->db->where('enquiry.Enquery_id',$enquiry_code);                
                            $this->db->where('enquiry.comp_id',$comp_id);
                        }
                    }
                    //$this->db->where('rule_executed!=',$id);                                    
                    
                     if($rule_for=='ticket')
                        $enq_row = $this->db->get('tbl_ticket')->row_array();
                    else                                 
                        $enq_row = $this->db->get('enquiry')->row_array();

                    if (!empty($enq_row['phone']) && !empty($rule_data['rule_action'])) {

                         $this->db->where('pk_i_admin_id',$user_id);
                        $user_row  = $this->db->get('tbl_admin')->row_array();

                        $phone    =   $enq_row['phone'];
                        $row   =   $this->db->select('*')
                                    ->from('api_templates')                                    
                                    ->where('temp_id',$rule_data['rule_action'])                        
                                    ->get()
                                    ->row_array();
                        
                        if (!empty($row)) { 
                            $this->load->model('Message_models');                            
                            $message = $row['template_content'];

                            if($rule_for=='ticket')
                            {


                                $find = array('@name',
                                                '@phone',
                                                '@username',
                                                '@userphone',
                                                '@designation',
                                                  '@ticket_no',
                                                    '@tracking_no'
                                            );
                                $replace = array(
                                    $enq_row['name'],
                                    $user_row['contact_phone'],
                                    $user_row['s_username'],
                                    $enq_row['phone'],
                                    $user_row['designation'],
                                    $enquiry_code,
                                    $enq_row['tracking_no'],
                                    );
                                $message  =str_replace($find, $replace, $message);
                            }
                            else
                            {
                                $name1 = $enq_row['name_prefix'].' '.$enq_row['name'].' '.$enq_row['lastname'];

                                $message = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message))))));
                            } 

                            if($this->Message_models->sendwhatsapp($phone,$message,$comp_id,$user_id))
                            {
                                //$this->db->where('Enquery_id',$enquiry_code);
                                //$this->db->update('enquiry',array('rule_executed'=>$id));
                            }
                        }

                    }
                }else if($rule_data['type'] == 8){ // ticket auto priority
                    $this->db->where('('.$rule_data['rule_sql'].')');
                    if ($enquiry_code) {
                        $this->db->where('ticketno',$enquiry_code);                
                    }
                    $this->db->where('company',$comp_id);
                    //$this->db->where('rule_executed!=',$id);                                    
                    $enq_row = $this->db->get('tbl_ticket')->row_array();                    
                    if (!empty($rule_data['rule_action']) && !empty($enq_row)) {
                        $this->db->where('tbl_ticket.ticketno',$enquiry_code);
                        $this->db->where('tbl_ticket.company',$comp_id);
                        $this->db->update('tbl_ticket',array('tbl_ticket.priority'=>$rule_data['rule_action']));
                    }
                }else if($rule_data['type'] == 9){                                        
                    if(!empty($rule_data['rule_action'])){
                        $action = json_decode($rule_data['rule_action'],true);                        
                    }
                    if(!empty($action)){

                        $this->db->where('('.$rule_data['rule_sql'].')');
                        $this->db->where('ticketno',$enquiry_code);

                        $enq_row = $this->db->get('tbl_ticket')->row_array();  

                        if (!empty($rule_data['rule_action']) && !empty($enq_row)) {                                                     
                            $this->load->model('Ticket_Model');
                            $this->Ticket_Model->saveconv($enq_row['id'],'Stage Updated',$rule_data['title']. ' Rule Applied','',$user_id,$action['stage'],$action['sub_stage'],$action['ticket_status'],$comp_id);
                           // echo'Rule done'; exit();
                        }
                    }
                }else if($rule_data['type'] == 10){                                        
                    if(!empty($rule_data['rule_action'])){
                        $action = json_decode($rule_data['rule_action'],true);                        
                    }
                    if(!empty($action)){
                        $this->db->where('('.$rule_data['rule_sql'].')');
                        $this->db->where('ticketno',$enquiry_code);                                        
                        $this->db->where('company',$comp_id);                                           
                        $enq_row = $this->db->get('tbl_ticket')->row_array();                    
                        if (!empty($rule_data['rule_action']) && !empty($enq_row)) {  
                            if(empty($action['source'])){
                                $action['source']=0;
                            } 
                            $this->load->model('Ticket_Model');
                            $this->Ticket_Model->moveTicketToEnq($enq_row['id'],'Stage Updated',$rule_data['title'],'Rule Applied',$user_id,$action['stage'],$action['assignto'],$action['defaultProcess'],$action['source']);
                        
                        }
                    }
                }
            }
        }
        return $affected;
	}
    public function execute_rules($enquiry_code,$type,$comp_id=0,$user_id=0){ // for multiple rule execution
        if ($comp_id == 0) {
            $comp_id    =   $this->session->companey_id;
        }
        if ($user_id == 0) {
            $user_id = $this->session->user_id;
        }
        //echo $comp_id.'<br>'.$user_id; 
        $results    =   $this->get_rules($type,$comp_id);
        if (!empty($results)) {
            foreach ($results as $key => $value) {
                $this->execute_rule($value['id'],$enquiry_code,$comp_id,$user_id);                
            }
        }
    }
    public function get_rule_by_type($type){
        $comp_id = $this->session->companey_id;
        $this->db->where('type',$type);
        $this->db->where('comp_id',$comp_id);
        $this->db->where('status',1);
        return $this->db->get('leadrules')->result_array();
    }
}
