<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mobile extends CI_Controller
{
    /**
     * Send to a single device
     */
    public function sendNotification()
    {

    $query  = $this->db->query("select tbl_admin.mobile_token,query_response.subject,query_response.task_time,query_response.task_date,query_response.task_remark,query_response.query_id,query_response.create_by,enquiry.status,enquiry.enquiry_id as enqid,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as enq_name from query_response INNER JOIN enquiry ON query_response.query_id = enquiry.Enquery_id INNER JOIN tbl_admin ON query_response.create_by = tbl_admin.pk_i_admin_id where STR_TO_DATE(query_response.task_date,'%d-%m-%Y') = CURDATE() AND tbl_admin.mobile_token!=''");
    
    $result = $query->result_array();             
    // echo $this->db->last_query();
    // exit;
    /*
    echo "<pre>";
    print_r($result);
    echo "<pre>";*/
    
    if(!empty($result)){    
        foreach ($result as $key => $value) {
            $t_time = date('H:i',strtotime($value['task_time']));
            $curr_time    =   date("H:i");            

            if($t_time == $curr_time){
                echo $t_time.'-'.$curr_time.'<br>';
                $message = $value['task_remark'];      
                if(empty($message)){
                  $message = 'N/A';      
                }
                $title = $value['subject'];
                $d= [
                    "to" =>$value['mobile_token'],
                    "notification" => [
                    "body" => $message,
                    "title" => $title,
                    ]
                ];
                $data = json_encode($d);
                $url = 'https://fcm.googleapis.com/fcm/send';
                $server_key = 'AAAAT7RVphg:APA91bESMxfXW_6zPa_IyLepfh3ACK3D6pRoHB45-bvIge0J19TBV81LSsHnUBkpiVNPm8999YLeZn-Rwpr2RDLx4l-9YawqbPhmAVVKrEfz6w0Fj3jU_z0HBPbJj5o2nKlhXFT1PY_T	';
                $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
                );        
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                echo "<pre>";
                print($result);
                echo "</pre>";
            }
        }
    }   
    }
    /**
     * Send to multiple devices
     */
    public function sendToMultiple()
    {
        $token = array('Registratin_id1', 'Registratin_id2'); // array of push tokens
        $message = "Test notification message";
        $this->load->library('fcm');
        $this->fcm->setTitle('Test FCM Notification');
        $this->fcm->setMessage($message);
        $this->fcm->setIsBackground(false);
        // set payload as null
        $payload = array('notification' => '');
        $this->fcm->setPayload($payload);
        $this->fcm->setImage('https://firebase.google.com/_static/9f55fd91be/images/firebase/lockup.png');
        $json = $this->fcm->getPush();
        /** 
         * Send to multiple
         * 
         * @param array  $token     array of firebase registration ids (push tokens)
         * @param array  $json      return data from getPush() method
         */
        $result = $this->fcm->sendMultiple($token, $json);
    }
}
