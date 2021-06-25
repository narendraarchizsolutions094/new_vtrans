<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Telephony extends CI_Controller {
    public function __construct() {
        parent::__construct();      
      
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
    
    public function save_log(){
        $log    =   $this->input->post('log');
        $log_arr = explode('|', $log);
        $insert_arr = array(
                        'comp_id'=>$this->session->companey_id,
                        'mobile_no'=>$log_arr[1],
                        'agent_id'=>$this->session->telephony_agent_id,
                        'session_id'=>$log_arr[2],
                        'call_by'=>$this->session->user_id
                    );
        $this->db->insert('telephony_log',$insert_arr);
    }
    public function forword_to($phone=''){
        if(!empty($_GET) && $this->session->companey_id == 82){
            $this->session->set_userdata('call_parameters',$_GET);
        }
        if(!empty($_GET['phone'])){
            $phone=$_GET['phone'];
        }else{
            $phone=$phone;
        }
        if($this->session->companey_id == 65 || $this->session->companey_id == 82){
            $url = 'ticket/add?phone='.$phone;
            redirect($url);
        }
        $this->db->select('enq.enquiry_id,enq.status');
        $this->db->where(array('enq.phone' => $phone,"usr.companey_id" => $this->session->companey_id ) );
        $this->db->from('enquiry enq');
        $this->db->join('tbl_admin usr', 'usr.pk_i_admin_id = enq.created_by', 'left');
        $row_array    =   $this->db->get()->row_array();        
      
        if(empty($row_array)){
            $url = 'enquiry/create?phone='.$phone;            
        }else{
            if($row_array['status'] == 1){
                $url= 'enquiry/view/'.$row_array['enquiry_id'];
            }else if($row_array['status'] == 2){
                $url= 'lead/lead_details/'.$row_array['enquiry_id'];             
            }else if($row_array['status'] == 3){
                $url= 'client/view/'.$row_array['enquiry_id'];
            }
        }
        
        
        redirect($url,'refresh');
        
    }
    
    public function mark_abilibality(){
        
        $atID   =   !empty($_POST['callbreakstatus'])?$_POST['callbreakstatus']:'';
        
        $user_id    =   $this->session->user_id;        
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://developers.myoperator.co/user",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => "token=".$this->session->telephony_token."&receive_calls=".$atID."&uuid=".$this->session->telephony_agent_id,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "postman-token: 3d348a84-e8b7-0c9c-c602-d7b6dd625bca"
              ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
            $user_id    =   $this->session->user_id;
            $this->db->set('availability',$atID);
            $this->db->where('pk_i_admin_id',$user_id);
            $this->db->update('tbl_admin');         
        
            unset($this->session->availability);
            $_SESSION['availability'] =  $atID;
            redirect('enq/index'); 
            // if($atID == 0){     
        //     echo json_encode(array('id'=>0,'status'=>$atID));
        // }else{
        //     echo json_encode(array('id'=>0,'status'=>$atID));           
        // }
    }
   public function get_call_status($uid=''){
        $newdata = array( 
        'uid_call'  =>str_replace('_','.',base64_decode($uid)), 
        );
        $where=' uid="'.str_replace('_','.',base64_decode($uid)).'" AND users!="" AND status=0 AND (cll_state=5 OR cll_state=3)';
        $this->db->select('users');
        $this->db->from('tbl_col_log');
        $this->db->where($where);
        $this->db->order_by('id','DESC');
        $res=$this->db->get()->row();
        if(!empty($res)){
         $array_users= json_decode($res->users);
         $user_id='91'.$this->session->phone;
        if(in_array($user_id,$array_users)){
        echo '1';
        $this->session->set_userdata($newdata);   
        $this->db->set('status',1);
        $this->db->set('enq_id',$this->session->enq_id);
        $this->db->where('uid',str_replace('_','.',base64_decode($uid)));
        $this->db->update('tbl_col_log');
        }else{echo '2';  
        $this->session->unset_userdata($newdata);
       }
        }else{  
        $this->session->unset_userdata($newdata);
            echo '2';
        }
    }
    
    public function get_in_status($uid=''){
        if($this->session->uuid != $uid){
            echo 1;
            $this->session->set_userdata('uuid',$uid);
        }else{
            echo 0;
        }

    //     $newdata = array(   
    //     'uid_call'  =>str_replace('_','.',base64_decode($uid)), 
    //     );
    //     $where=' uid="'.str_replace('_','.',base64_decode($uid)).'" AND users!="" AND status=0 AND (cll_state=5 OR cll_state=3)';
    //     $this->db->select('users,json_data');
    //     $this->db->from('tbl_col_log');
    //     $this->db->where($where);
    //     $this->db->order_by('id','DESC');  
    //     $res=$this->db->get()->row();
    //     if(!empty($res)){
    //      $array_users= json_decode($res->users);
    //      $user_id='91'.$this->session->phone;
    //     $call_json = !empty($res->json_data)?json_decode($res->json_data,true):array();
    //     $inbound = 0;
    //     if(!empty($call_json['event']) && $call_json['event'] ==1){
    //         $inbound = 1;
    //     }
    //     if(in_array($user_id,$array_users)){
    //      echo $inbound;
    //     $this->session->set_userdata($newdata);   
    //     $this->db->set('status',1);
    //     //$this->db->set('enq_id',$this->session->enq_id);
    //     $this->db->where('uid',str_replace('_','.',base64_decode($uid)));
    //     $this->db->update('tbl_col_log');
    //     }else{echo '2';  
    //     $this->session->unset_userdata($newdata);
    //    }
    //     }else{  
    //     $this->session->unset_userdata($newdata);
    //         echo '2';
    //     }
    }
    
    
    
    
    public function click_to_dial($phone='')
    {
    $phone           = '+91'.$phone;
    $token           = $this->input->post("token");
    $support_user_id = $this->session->telephony_agent_id;
    $this->db->where('telephony_agent_id',$support_user_id);
    $res=$this->db->get('tbl_admin')->row();
    if(!empty($res)){
    if(!empty($res->public_ivr_id)){    
    $curl = curl_init();
    curl_setopt_array($curl, array(  CURLOPT_URL => "https://obd-api.myoperator.co/obd-api-v1",
    CURLOPT_RETURNTRANSFER => true,  CURLOPT_CUSTOMREQUEST => "POST", 
    CURLOPT_POSTFIELDS =>'{  "company_id": "'.$res->telephony_compid.'",
    "secret_token": "'.$res->telephony_comp_token.'", 
    "type": "1", 
    "user_id": "'.$support_user_id.'",
    "number": "'.$phone.'",   
    "public_ivr_id":"'.$res->public_ivr_id.'", 
    "reference_id": "",  
    "region": "",
    "caller_id": "",  
    "group": ""   }', 
    CURLOPT_HTTPHEADER => array(    "x-api-key:oomfKA3I2K6TCJYistHyb7sDf0l0F6c8AZro5DJh", 
    "Content-Type: application/json"  ),));
    $response = curl_exec($curl);
    print_r($response);
     }else{
     $url = "https://developers.myoperator.co/clickOcall";
        $data = array(
        'token'=>$this->session->telephony_token,
        'customer_number'=>$phone,
        'customer_cc'=>91,
        'support_user_id'=>$this->session->telephony_agent_id
        );
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close( $curl );
        print_r($response);
        }
    }
    }
    
    public function one_way_dial($phone='',$enq=''){
        $url = "http://developers.myoperator.co/call/one-way";
        $data = array(
        'token'=>$this->session->telephony_token,
        'customer_number'=>$phone,
        'customer_cc'=>91,
        'uuid'=>$this->session->telephony_agent_id
        );
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close( $curl );
        print_r($response);
        if(!empty($enq)){
         $res=$this->enquiry_model->getenq_by_id($enq);
         if(!empty($res)){
             $newdata = array('enq_id'=>$enq);
             $this->session->set_userdata($newdata); 
             $this->db->set('call_count',($res->call_count+1));
             $this->db->where('enquiry_id',$enq);
             $this->db->update('enquiry');
             
         }
        }
        
    }
    
     public function hangup($uid='',$uri){
        $uid1=str_replace('_','.',$this->session->uid_call);
        //$this->load->library('user_agent');
        $url = "https://developers.myoperator.co/call/action";
        $data = array(
        'token'=>$this->session->telephony_token,
        'uid'=>$uid1,
        'action'=>'hangup',
         );
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close( $curl );
        $this->session->unset_userdata('uid_call');
        $this->session->unset_userdata('enq_id');
        
        /*$agent = $this->request->getUserAgent();                
        if ($agent->isBrowser()){
            redirect($this->agent->referrer());
        }elseif ($agent->isMobile()){
            redirect('','refresh')
        }else{
        }*/
        $uri = base64_decode($uri);
        
        redirect($uri);
    }
    
    public function add_user($phone=''){
        $url = "http://developers.myoperator.co/call/one-way";
        $data = array(
        'token'=>$this->session->telephony_token,
        'customer_number'=>$phone,
        'customer_cc'=>91,
        'uuid'=>$this->session->telephony_agent_id
        );
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close( $curl );
        print_r($response);
    }
    
    
    public function user_list(){
        $all_user=array();
        $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=23d43096026d5982b7ec873f920abe8a&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        } 
         $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=74107b67bb340dbc8c6a566c4a717f6d&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        }
        $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=a8974b7b3c644333c022e8e2211a8745&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        }
        
        $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=2305577d659cbbde8331c3231f94fb35&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        }
        $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=0bfe070ff5847ce58b4fc0567859dc7d&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        } 
                
        $data['all_user']=$all_user;
        $data['content'] = $this->load->view('telephony/myoprater_user_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);        
    }
    public function inbound_list(){
        $all_user=array();
        $a=array('1','2','3','4','5');
        foreach($a as $v){
        $url = "https://developers.myoperator.co/user?token=f69449b6baf515f7312b0e489d67397c&&page-size=100&&page=".$v;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close($curl);
        array_push($all_user,array('user_data'=>json_decode($response)->data));
        }           
        $data['all_user']=$all_user;
        $data['content'] = $this->load->view('telephony/myoprater_user_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);        
    }
    
    
    public function test(){
    $FIREBASE = "https://advance-crm-441ee.firebaseio.com/users.json";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $FIREBASE );
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
      //  curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec($curl);
        curl_close( $curl );
        print_r($response);
        
        $FIREBASE = "https://advance-crm-441ee.firebaseio.com/reminders.json";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $FIREBASE );
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
      //  curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec($curl);
        curl_close( $curl );
        print_r($response);
        
        $FIREBASE = "https://advance-crm-441ee.firebaseio.com/us.json";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $FIREBASE );
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
      //  curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec($curl);
        curl_close( $curl );
        print_r($response);
    }
    /*public function ameyo_api(){
        $ameyo_url    =   $this->input->post('ameyo_url');
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $ameyo_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Cookie: JSESSIONID=451E64BF7E8FFC80ACA818B68F8BE2DF; __METADATA__=772eb950-8910-456b-b35d-eee95f57dc1d"
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        
    }*/
    
    public function ameyo_api(){
        $user_id = $this->session->email;        
        $campaign_id = $this->input->post('campaignId');
        $phone = $this->input->post('phone');
        
        if($this->session->companey_id == 82){
            $user_id = $this->session->phone;        
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://emergems.ameyo.net:8443/ameyowebaccess/command/?command=clickToDialWithToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //CURLOPT_POSTFIELDS => "data=%7B%22userId%22%3A%22".$user_id."%22%2C%22campaignId%22%3A%22".$campaign_id."%22%2C%22phone%22%3A%22".$phone."%22%2C%22shouldAddCustomer%22%3Afalse%7D",
            CURLOPT_POSTFIELDS => "data=%7B%22userId%22%3A%22".$user_id."%22%2C%22campaignId%22%3A%22".$campaign_id."%22%2C%22phone%22%3A%22".$phone."%22%2C%22shouldAddCustomer%22%3A%22false%22%7D",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "hash-key:  fecace70bf6ea0c450c5b2071cbabc9",
                "host:  climber-click-to-dial",
                "policy-name:  token-based-authorization-policy",
                "requesting-host:  imz-click-to-dial",
                "Cookie: __METADATA__=772eb950-8910-456b-b35d-eee95f57dc1d"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            //print_r($_SESSION);
            echo $response;
        }else{        
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://app.ameyoemerge.in:8887/ameyowebaccess/command/?command=clickToDialWithToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "data=%7B%22userId%22%3A%22".$user_id."%22%2C%22campaignId%22%3A%22".$campaign_id."%22%2C%22phone%22%3A%22".$phone."%22%2C%22shouldAddCustomer%22%3Afalse%7D",
            CURLOPT_HTTPHEADER => array(
                "hash-key: e1b672e444bc90e3ef6b7ea9d7c9eb7d",
                "policy-name: token-based-authorization-policy",
                "requesting-host: religare-clickToDialWithToken",
                "Content-Type: application/x-www-form-urlencoded",
                "Cookie: JSESSIONID=CD6B2C8814410AC04966D61D885E4B61; __METADATA__=a16e9790-cd73-42b8-863e-77764c6b33e1"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;
        }
    }
}
