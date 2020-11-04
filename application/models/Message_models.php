<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message_models extends CI_Model {

 public function smssend($phone,$message,$companey_id='',$user_id=''){
    /* $search = array(
		'/\n/',			// replace end of line by a space
		'/\>[^\S ]+/s',		// strip whitespaces after tags, except space
		'/[^\S ]+\</s',		// strip whitespaces before tags, except space
	 	'/(\s)+/s'		// shorten multiple whitespace sequences
	  );
 
	 $replace = array(
		' ',
		'>',
	 	'<',
	 	'\\1'
	  );*/
 
	$meg = urlencode($message);
  $companey_id = ($companey_id == '') ? $this->session->companey_id : $companey_id;
 //$url="https://api.msg91.com/api/sendhttp.php?authkey=308172AuZ3VC9dU5df325a4&route=1&sender=LALANT&mobiles=".$phone."&message=".$meg."&country=91";
 
$this->db->where('comp_id',$companey_id);
$this->db->where('api_for',2);
$api_conf  = $this->db->get('api_integration')->row_array();

if(empty($api_conf)){
  echo "SMS API details not found";
  exit();
}

if (strlen($phone) >= 12 && ($companey_id==83 || $companey_id==81)) {
    $phone = substr($phone, 2, 10);
}
if($companey_id == 65 && strlen($phone) < 12){
  $phone = "91".$phone;
}

 $url=$api_conf['api_url']."&".$api_conf['key_moblie']."=".$phone."&".$api_conf['api_key']."=".$meg."&country=91";

 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
));



      $response = curl_exec($curl);
       $err = curl_error($curl);
      curl_close($curl);

      if ($err) {

      } else {
        $response;
      }
          $insert_array = array(
            'mobile_no'     => $phone,
            'created_by'    =>  ($this->session->user_id != '') ? $this->session->user_id : $user_id,
            'msg'           => $message,
            'response'      => $response,
            'comp_id'       => $companey_id,
            'url'           => $url
          );
          $this->db->insert('sms_send_log',$insert_array);
      
    }
   public function total_whatsaap(){
      return   $this->db->select('*')
      ->from('whatsapp_send_log')
      ->get()
      ->num_rows();
   }
   public function today_whatsapp(){
      $date=date('Y-m-d');
      return   $this->db->select('*')
      ->from('whatsapp_send_log')
      ->like("created_at",$date)
      ->get()
      ->num_rows();
   }
   public function total_msg(){
       return   $this->db->select('*')
      ->from('sms_send_log')
      ->get()
      ->num_rows();
   }
   public function today_tody_msg(){
        $date=date('Y-m-d');
      return   $this->db->select('*')
      ->from('sms_send_log')
      ->like("created_at",$date)
      ->get()
      ->num_rows(); 
   }
 
     public function sendwhatsapp($number,$message,$companey_id='',$user_id=''){
       $this->load->model('user_model');
      $usermeta = $this->user_model->get_user_meta( $this->session->user_id,array('api_name','api_url'));
      $destination =$number;
      // die();
        if (!empty($usermeta['api_url'])) {
          if ($usermeta['api_url']!='') {
            $api_url = $usermeta['api_url'];
          }else{
            echo "Api URL is not configured";
            exit();
          }
         
        }else{
          $companey_id = ($companey_id == '') ? $this->session->companey_id : $companey_id;
          $this->db->where('comp_id',$companey_id);          
          $this->db->where('api_for',1);          
          $email_row  = $this->db->get('api_integration')->row_array();
           //$my_apikey = "CW9FFHPDJGC5RXUWSIC6";
          // $message = "MESSAGE TO SEND";
           $api_url = $email_row['api_url'];
           }
           //$api_url = "https://panel.apiwha.com/send_message.php";
           //$api_url .= "?apikey=". urlencode ($my_apikey);

           $api_url .= "&number=". urlencode ($destination);
           $api_url .= "&text=". urlencode ($message);
           $curl = curl_init();
           curl_setopt_array($curl, array(
           CURLOPT_URL => "$api_url",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        $response = curl_exec($curl);
        
        $response = json_decode($response,true);
        if (empty($response)) {
          echo "Api URL is not configured";
            exit();
        }
        //print_r($response);
        $wp_mob_num = $number;
        if (strlen($number) == 12 && substr($number, 0, 2) == "91")
            $wp_mob_num = substr($number, 2, 10);
        
        $this->db->where('mobile_no',$wp_mob_num);
        if( $this->db->get('whatsapp_send_log')->num_rows() == 0){
          $insert_array = array(
                                  'mobile_no'     => $wp_mob_num,
                                  'status'        =>  $response['result_code'],
                                  'created_by'    =>  ($this->session->user_id != '') ? $this->session->user_id : $user_id,
                                  'msg'           => $message,
                                  'response'      => json_encode($response)
                              );
          $this->db->insert('whatsapp_send_log',$insert_array);

      }
        $err = curl_error($curl);
        //echo $err;
        curl_close($curl);
    }

public function get_chat($number){
           $my_apikey = "CW9FFHPDJGC5RXUWSIC6";
           $destination =$number;
          // $message = "MESSAGE TO SEND";
           $api_url = "http://panel.apiwha.com/get_messages.php";
           $api_url .= "?apikey=". urlencode ($my_apikey);
           $api_url .= "&number=". urlencode ($destination);
          /// $api_url .= "&text=". urlencode ($message);
           $curl = curl_init();
           curl_setopt_array($curl, array(
           CURLOPT_URL => "$api_url",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        return $response = curl_exec($curl);
        
       }

       public function send_email($to,$subject,$message,$companey_id=''){
          $companey_id = ($companey_id == '') ? $this->session->companey_id : $companey_id;

          $this->db->where('comp_id',$companey_id);
          $this->db->where('status',1);
          $email_row  = $this->db->get('email_integration')->row_array();
          
          if (!empty($email_row)) {            
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

            $this->email->initialize($config);          
            $this->email->from($email_row['smtp_user']);          
            $this->email->to($to);
            $this->email->subject($subject); 
            $this->email->message($message);                           
            if($this->email->send()){
              return true;
            }else{
              return false;
            }
          }else{
            return false;
          }
       }
		
}