<?php
//date_default_timezone_set("America/New_York");
header("Content-Type: text/event-stream");

$counter = rand(1, 10); // a random counter
$user_id  = $this->session->user_id;
while (0) {
// 1 is always true, so repeat the while loop forever (aka event-loop)

    //$query  = $this->db->query("select *,NOW() from query_response where assign_to=$user_id OR (create_by=$user_id AND assign_to=0) AND STR_TO_DATE(nxt_date,'%d-%m-%Y %H:%i') >= NOW()");  
    
    //$query  = $this->db->query("select query_response.task_time,query_response.task_date,query_response.task_remark,query_response.query_id,enquiry.status,enquiry.enquiry_id as enqid,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as enq_name from query_response INNER JOIN enquiry ON query_response.query_id = enquiry.Enquery_id where query_response.create_by=$user_id AND STR_TO_DATE(query_response.task_date,'%d-%m-%Y') = CURDATE() ");

    $query  = $this->db->query("select query_response.task_time,query_response.task_date,query_response.task_remark,query_response.query_id,enquiry.status,enquiry.enquiry_id as enqid,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as enq_name from query_response INNER JOIN enquiry ON query_response.query_id = enquiry.Enquery_id where query_response.create_by=$user_id AND STR_TO_DATE(query_response.task_date,'%d-%m-%Y') = CURDATE() AND query_response.task_time = TIME_FORMAT(NOW(), '%H:%i:%s')");
    $result = $query->result_array();             
    
    $t_time = date('H:i:s',strtotime($result[0]['task_time']));    
    if ($result[0]['status'] == 1) {
      $url  = base_url().'enquiry/view/'.$result[0]['enqid'];
    }else if($result[0]['status'] == 2) {
      $url  = base_url().'lead/lead_details/'.$result[0]['enqid'];
    }else if($result[0]['status'] == 3) {
      $url  = base_url().'client/view/'.$result[0]['query_id'];
    }else{
      $url  = 'javascript:void(0)';
    }

    if($t_time == date("H:i:s")){                
      echo 'data: <b>Task Time:</b> '.$t_time.' '.$result[0]['task_date'].'<br>'.' <b>Enquery : </b><a href="'.$url.'">'.$result[0]['enq_name'].'</a><br><b>Subject:</b>'.$result[0]['task_remark']."\n\n";      

    }
      //echo 'data: <b>Task Time:</b> '.$t_time.' '.$result[0]['task_date'].'<br>'.' <b>Enquery : </b><a href="'.$url.'">'.$result[0]['enq_name'].'</a><br><b>Subject:</b>'.$result[0]['task_remark']."\n\n";      
    
    

  // flush the output buffer and send echoed messages to the browser

  while (ob_get_level() > 0) {
    ob_end_flush();
  }
  flush();

  // break the loop if the client aborted the connection (closed the page)
  
  if ( connection_aborted() ) break;

  // sleep for 1 second before running the loop again
  
  //sleep(1);
  session_write_close();
}
