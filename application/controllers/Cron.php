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
}