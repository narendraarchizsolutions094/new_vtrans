<?php

use Sabberworm\CSS\Value\Value;

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
			'Client_Model','Apiintegration_Model','Message_models','enquiry_model'
		));
       
        }
 
    public function index()
    { 
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
        $data['title'] = 'Cron Jobs';
        $data['crons']=$this->db->get('cronjobs')->result();

        $data['content'] = $this->load->view('cron/cron-list',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    } 


    public function add()
    { 
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
        $data['title'] = 'Add New Cron';
        $data['content'] = $this->load->view('cron/add-cron',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    }
    public function insertCron()
    {
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
require_once FCPATH.'third_party/vendor/autoload.php';
    date_default_timezone_set("Asia/kolkata");
    if($_POST){
        $minute=$this->input->post('minute');
        $hour=$this->input->post('hour');
        $day=$this->input->post('day');
        $month=$this->input->post('month');
        $weekday=$this->input->post('weekday');
        $command=$this->input->post('command');
        $url=$this->input->post('url');
        $status=$this->input->post('status');
        // Works with complex expressions
        $cron = Cron\CronExpression::factory($command);
        // print_r($cron);
        $running_time= $cron->getNextRunDate()->format('Y-m-d H:i');
        $data=[ 'minute'=>$minute,
                'hour'=>$hour,
                'day'=>$day,
                'month'=>$month,
                'weekday'=>$weekday,
                'command'=>$command,
                'comp_id'=>$this->session->companey_id,
                'status'=>$status,
                'created_by'=>$this->session->user_id,
                'running_time'=>$running_time,'url'=>$url];
$this->db->insert('cronjobs',$data);
$this->session->set_flashdata('message','Cron Added');

redirect('cron');

}
    
}
public function updateCron()
{
    if ($this->session->userdata('isLogIn') == false) 
    redirect('login'); 
require_once FCPATH.'third_party/vendor/autoload.php';
date_default_timezone_set("Asia/kolkata");
if($_POST){
    $cid=$this->input->post('cid');
    $minute=$this->input->post('minute');
    $hour=$this->input->post('hour');
    $day=$this->input->post('day');
    $month=$this->input->post('month');
    $weekday=$this->input->post('weekday');
    $command=$this->input->post('command');
    $url=$this->input->post('url');
    $status=$this->input->post('status');
    // Works with complex expressions
    $cron = Cron\CronExpression::factory($command);
    // print_r($cron);
    $running_time= $cron->getNextRunDate()->format('Y-m-d H:i');
    $data=[ 'minute'=>$minute,
            'hour'=>$hour,
            'day'=>$day,
            'month'=>$month,
            'weekday'=>$weekday,
            'command'=>$command,
            'comp_id'=>$this->session->companey_id,
            'status'=>$status,
            'created_by'=>$this->session->user_id,
            'running_time'=>$running_time,
            'url'=>$url];
$this->db->where('id',$cid)->update('cronjobs',$data);
$this->session->set_flashdata('message','Cron updated');
redirect('cron');
}else{
    $data['title'] = 'Update Cron';
    $data['cron']=$this->db->where('id',$cid)->get('cronjobs')->result();
    $data['content'] = $this->load->view('cron/add-cron',$data,true);
    $this->load->view('layout/main_wrapper',$data);
}
}
public function delete_cron()
{
  $id=$this->uri->segment('3');
 $delete= $this->db->where('id',$id)->delete('cronjobs');
 if($delete){
    $this->session->set_flashdata('message','Cron Deleted');
redirect('cron');

 }

}

public function msgsend_app()
{
      $currentTime=date('Y-m-d H:i');
    // if()
    // die();
    $schedule=$this->db->where('status',0)->get('scheduledata')->result();
    foreach ($schedule as $key => $value) {
        if(date("Y-m-d H:i", strtotime($value->schedule_time))==$currentTime){

           $type= $value->message_type;
           $to=$value->send_to;
           $comp_id=$value->comp_id;
           $message=$value->message_data;
           $id=$value->id;
           if(!empty($message) AND !empty($to)){
           $jsonmsg=json_decode($message);
           if($type==1){
            $response= $this->Message_models->sendwhatsapp($to,$message,$comp_id);
         echo "Whatsapp sent successfully";
         $data=['status'=>1,'response'=>$response];
         $this->db->where('id',$id)->update('scheduledata',$data);
                }elseif($type==2){
                    //sms send
				  $response=  $this->Message_models->smssend($to,$message,$comp_id);
                  echo "Message sent successfully";
                  $data=['status'=>1,'response'=>$response];
         $this->db->where('id',$id)->update('scheduledata',$data);
                }elseif($type==3){
                    //whatsapp send
            //    print_r($jsonmsg->message);
            $message=$jsonmsg->message;
            $cc=$jsonmsg->cc;
            $media=$jsonmsg->media;
            $email_subject=$jsonmsg->subject;
    // $message = $this->input->post('message_name');
    //$email_subject = $this->input->post('email_subject');
    $this->db->where('comp_id',$this->session->companey_id);
    $this->db->where('status',1);
    $email_row	=	$this->db->get('email_integration')->row_array();
    if(empty($email_row)){
        $$response= "Email is not configured";
          $data=['status'=>1,'response'=>$response];
          $this->db->where('id',$id)->update('scheduledata',$data);
          exit();
    }else{
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
        //$config['validation']   = TRUE; // bool whether to validate email or not    
    }
    // $this->email->initialize($config);
    $this->email->from($email_row['smtp_user']);
    $this->email->to($to);
    if($cc!='')$this->email->cc($cc);
    $this->email->subject($email_subject); 
    $this->email->message($message); 
    //echo $message.'<br>'.$email_subject.'<br>'.$cc;
    //$this->email->set_mailtype('html');
    if($media!=null || !empty($media==null))
    {
        $this->email->attach($media);
    }
    if($this->email->send()){
        $response= "Mail sent successfully";
    }else{
        echo $this->email->print_debugger();
        $response= "Something went wrong";	
    }
    $data=['status'=>1,'response'=>$response];
    $this->db->where('id',$id)->update('scheduledata',$data);
                }
              

        }
    }
}
}


public function run()
{
    date_default_timezone_set("Asia/kolkata");
    $currentTime=date('Y-m-d H:i');
    $cron=$this->db->where('status',0)->get('cronjobs')->result();
    foreach ($cron as $key => $value) {
        $id=$value->id;
        $command=$value->command;
        if(date("Y-m-d H:i", strtotime($value->running_time))==$currentTime){
            $run=$value->url;
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL,$run);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // grab URL and pass it to the browser
            $result = curl_exec($ch);
            // close cURL resource, and free up system resources
            require_once FCPATH.'third_party/vendor/autoload.php';
            $cron = Cron\CronExpression::factory($command);
            $running_time= $cron->getNextRunDate()->format('Y-m-d H:i');
            $data=['running_time'=>$running_time];
                        $this->db->where('id',$id)->update('cronjobs',$data);
                        curl_close($ch);
            
        }

    }

}

    public function generate_ticket_by_mail()
    {   
            $list = $this->db->select('config.*')
                            ->from('ticket_email_config config')
                            ->join('user','user.user_id=config.comp_id','inner')
                            ->where('FIND_IN_SET(318,user.company_rights) > 0')
                            ->where('config.status','1')
                            ->like('config.next_hit',date('Y-m-d H:i'))
                        ->get('ticket_email_config')->result();

          foreach ($list as $key => $imap_config)
          {
            $company_id = $imap_config->comp_id;
            $process_id = $imap_config->process_id;

            //smtp config
            $this->db->where('comp_id', $company_id);
                $this->db->where('status', 1);
            $smtp_config  = $this->db->get('email_integration')->row_array();

            if(empty($smtp_config))
                continue;
            if (!empty($smtp_config))
            {
                  $config['smtp_auth']    = true;
                  $config['protocol']     = $smtp_config['protocol'];
                  $config['smtp_host']    = $smtp_config['smtp_host'];
                  $config['smtp_port']    = $smtp_config['smtp_port'];
                  $config['smtp_timeout'] = '7';
                  $config['smtp_user']    = $smtp_config['smtp_user'];
                  $config['smtp_pass']    = $smtp_config['smtp_pass'];
                  $config['charset']      = 'utf-8';
                  $config['mailtype']     = 'html'; // or html
                  $config['newline']      = "\r\n";
                  $send_from = $smtp_config['smtp_user'];
                  $this->email->initialize($config);
                  $this->email->from($send_from);
            }
            // imap config
            $hostname =  $imap_config->hostname;
            $username = $imap_config->username;
            $password = $imap_config->password;
            $user = $imap_config->belongs_to;
            $process  = $imap_config->process_id;

            //template fetch 
            $template = 'No Template';
            $tmp = $this->db->where('temp_id',$imap_config->template)
                                ->where('comp_id',$company_id)
                                ->where('FIND_IN_SET(3,temp_for)>0')
                                ->get('api_templates')->row();
            if(!empty($tmp))
                $template= $tmp->template_content;


            $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail: ' . imap_last_error());

            /* grab emails */
            $emails = imap_search($inbox, 'UNANSWERED UNSEEN');//

            if ($emails) {
                /* begin output var */
                $output = '';
                /* put the newest emails on top */
                rsort($emails);
                /* for every email... */
                foreach ($emails as $ind => $email_number) {

                    if ($ind > 50) break;
                    
                    /* get information specific to this email */
                    $overview = imap_fetch_overview($inbox, $email_number, 0);
                    $message  = imap_fetchbody($inbox, $email_number, 1);
                    $headers = imap_header($inbox, $email_number, 1);

                    $message= quoted_printable_decode($message);

                    $fromMail = $overview[0]->from ;
                    $message_id = $overview[0]->message_id;
                    $subject = $overview[0]->subject;
                    $y = explode('<',$fromMail);
                    $name = $y[0]??'';
                    $mailid = str_replace('>', '', $y[1]??'');

                    // echo 'Subject- '.$subject.'<br>
                    //         Mail- '.$mailid.'<br>
                    //         name- '.$name.'<br>
                    //         Seen: '.$overview[0]->seen.'<br>
                    //         Message
                    //         msgid:'.htmlentities($message_id).'
                    //         <br><hr>
                    // ';
                    $flag =1;
                    if(!empty($overview[0]->references))
                    {   
                        $ref = explode(' ',$overview[0]->references)[0];
                        $this->db->where('email_message_id',$ref);
                        $match = $this->db->where('email',$mailid)->get('tbl_ticket');
                        $flag = !$match->num_rows();
                    }

                    if($flag)
                    {
                        if(empty($name)){$name='';}
                        $data=['name'=>$name,'email'=>$mailid,'email_message_id'=>$message_id,'message'=>$subject,'send_date'=>date("Y-m-d H:i:s"),'added_by'=>$user,'process_id'=>$process,'company'=>$company_id];
                        $this->db->insert("tbl_ticket", $data);
                        $insid = $this->db->insert_id();
                        $tckno = "TCK" . $insid . strtotime(date("y-m-d h:i:s"));
                        $updarr = array("ticketno" => $tckno);
                        $this->db->where("id", $insid);
                        $this->db->update("tbl_ticket", $updarr);
                        //insert conv
                        $insarr = array(
                            "tck_id" => $insid,
                            "comp_id" => $company_id,
                            "parent" => 0,
                            "subj"   => 'Ticket Created by Mail',
                            "msg"    => $subject,
                            "attacment" => "",
                            "status"  => 0,
                            "ticket_status" =>0,
                            "stage"  => 0,
                            "sub_stage"  => 0,
                            "added_by" => $user,
                        );
                        $ret = $this->db->insert("tbl_ticket_conv", $insarr);
                        
                        if(!empty($smtp_config))
                        {

                            $mail_subject= ticket_subject($tckno,$subject);

                            $mail_msg = $template;
                            $search  = array('@ticketno'=>$tckno,
                                            '@sender'=>$name,
                                            '@subject'=>$subject,
                                        );
                            
                            foreach ($search as $key => $value) {
                            $mail_msg = str_replace($key, $value, $mail_msg);
                             } 


                            $this->email->to($mailid);
                            // $this->email->set_header('In-Reply-To:', $message_id);
                            // $this->email->set_header('References:', $message_id);
                            $this->email->subject($mail_subject);
                            $this->email->message($mail_msg);
                            if($this->email->send())
                            {
                                echo'Reverted :'.$mailid;
                            } 
                            else
                            {
                               echo $this->email->print_debugger();
                            }
                        }
                       // echo'<hr>';
                    }
                    else
                    {
                        $tck = $match->row();

                        if(!empty($smtp_config))
                        {
                            
                            $mail_msg = "Thanks For Your Response. We have recorded your response";
                            $this->email->to($tck->email);
                            // $this->email->set_header('In-Reply-To:', $tck->email_message_id);
                            // $this->email->set_header('References:', $overview[0]->references);
                            $this->email->subject(ticket_subject($tck->ticketno,$message));
                            $this->email->message($mail_msg);
                            if($this->email->send())
                            {
                                echo'
                                Mail-ID:'.htmlentities($tck->email_message_id).'<br>
                                Reverted :'.$mailid;
                            $message = htmlentities($message);
                           $insarr = array(
                            "tck_id" => $tck->id,
                            "comp_id" => $company_id,
                            "parent" => 0,
                            "subj"   => 'Reponse Received by mail.',
                            "msg"    => $message,
                            "attacment" => "",
                            "status"  => 0,
                            "ticket_status" =>0,
                            "stage"  => 0,
                            "sub_stage"  => 0,
                            "added_by" => $user,
                        );
                        $ret = $this->db->insert("tbl_ticket_conv", $insarr);
                            } 
                        }
                        // echo'<hr>';


                        // print_r($overview[0]);
                        // echo'<hr>';
                        // print_r($tck);
                    }
        
                }
                //echo $output;
                print_r($imap_config);
            }

            //when done for one
            $next_hit = date('Y-m-d H:i:s',(time()+(60*$imap_config->fetch_time)));
            $this->db->where('id',$imap_config->id)
                        ->set('next_hit',$next_hit)
                        ->update('ticket_email_config');

        }

    }

}

