<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
            $this->load->helper('url');
		
		$this->load->model(array(
			'Client_Model','Apiintegration_Model','Message_models','enquiry_model'
		));
		$this->password = "67";
		 $this->load->library('email');
	}
    
    	public function get_templates($for,$ins=''){
			$product_id=$this->uri->segment(3);
			 $stage_id=$this->uri->segment(4);
			 $for=$this->uri->segment(5);
    	    $this->db->where('temp_for',$for);
    	    $this->db->where('comp_id',$this->session->companey_id);
    	    $res=$this->db->get('api_templates');
			$q=$res->result();
    	    if(!empty($q)){
    	        echo '<option value="0" selected style="display:none">Select Templates</option>';
    	    foreach($q as $value){
				 $stage = explode(',', $value->stage);
				 $process = explode(',', $value->process); 
				 if ($product_id==0) {
				$extprocess=$this->session->userdata('process');
				 $count_array=count(array_intersect($extprocess,$process));
				if(in_array($stage_id,$stage) AND $count_array!=0){
					echo '<option value="'.$value->temp_id.'">'.$value->template_name.'</option>';
					}
				}else{
					$process = explode(',', $value->process); 
					if(in_array($stage_id,$stage) AND in_array($product_id,$process)){
						echo '<option value="'.$value->temp_id.'">'.$value->template_name.'</option>';
						}

				}
				
    	    		}
    	    
    	    }
		}
		
		public function get_templates_for_user($for){
    	    $this->db->where('temp_for',$for);
    	    $this->db->where('comp_id',$this->session->companey_id);
    	    $res=$this->db->get('api_templates');
			$q=$res->result();
    	    if(!empty($q)){
    	        echo '<option value="0" selected style="display:none">Select Templates</option>';
    	    foreach($q as $value){
					echo '<option value="'.$value->temp_id.'">'.$value->template_name.'</option>';
					}
    	    
    	    }
		}
		
		public function get_templates_without_process($for){			
			$this->db->where('temp_for',$for);			
    	    $this->db->where('comp_id',$this->session->companey_id);
    	    $res=$this->db->get('api_templates');
			$q=$res->result();
    	    if(!empty($q)){
				echo '<option value="0" selected style="display:none">Select Templates</option>';
				foreach($q as $value){
				   echo '<option value="'.$value->temp_id.'">'.$value->template_name.'</option>';
				}
    	    }
	    }
	    public function all_description()
	    {
	    	$this->load->model('Leads_Model');
	    	$list = $this->Leads_Model->find_description();
	    	//print_r($list);exit();
	    	foreach ($list as $res)
	    	{
	    		echo'<option value="'.$res->id.'">'.$res->description.'</option>';
	    	}
	    }
		public function getMessage($id){
		if((int)$id){
	    $this->db->where('temp_id',$id);
	    $res=$this->db->get('api_templates');
	    if(!empty($res->result())){
	    echo $q=$res->row()->template_content;}
		}else{
		   
		    
		}
	   
	}
	
	public function find_substage($id=0,$selected=0) 
	{
		$this->load->model('Leads_Model');
		
		if($id)
		{
			$res = $this->Leads_Model->all_description($id);
			//print_r($res); exit();
			echo'<option value="">Select Sub Stage</option>';
			foreach ($res as $row)
			{
				echo'<option value="'.$row->id.'" '.($selected==$row->id?'selected':'').'>'.$row->description.'</option>';
			}
		}
		else
		{
			echo'<option value=""></option>';
		}
		
	}
	public function all_stages($id,$selected=0)
	{
		$this->load->model('Leads_Model');
		if(sizeof($this->session->process)==1)
		{
			$res = $this->Leads_Model->find_estage($this->session->process[0],$id);
			echo'<option value="">Select Stage</option>';
			foreach ($res as $row)
			{
				echo'<option value="'.$row->stg_id.'" '.($selected==$row->stg_id?'selected':'').'>'.$row->lead_stage_name.'</option>';
			}
		}
		else
		{
			echo'<option value="">First Please Select only One Process</option>';
		}
	}
	public function send_sms_career_ex(){
		if ($this->input->post('mesge_type')== 3) {
			$temp_id = $this->input->post('templates');
	    	$rows	=	$this->db->select('*')
	                    ->from('api_templates')
	                    ->join('mail_template_attachments', 'mail_template_attachments.templt_id=api_templates.temp_id', 'left')                    
	                    ->where('temp_id',$temp_id)                        
	                    ->get()
	                    ->row();
	        $message = $this->input->post('message_name');
	        $email_subject = $this->input->post('email_subject');
	        $to_email = $this->input->post('mail');
	        $move_enquiry = $this->input->post('enquiry_id'); 
	        $curl_fields = array(
	        	'mail_datas'=>array(
	        		'message'=>array(
	        			'html_content'=>$message,
	        			'subject'=>$email_subject,
	        			'from_mail'=>'support@corefactors.in',
	        			'from_name'=>'CareerEx',
	        			'reply_to'=>'support@corefactors.in'
	        		)
	        	)
	        );
	        $to = array();
	        if(!empty($move_enquiry)){
	      	    foreach($move_enquiry as $key){
	      	        $enq = $this->enquiry_model->enquiry_by_id($key);
			        $to[]= array('email_id'=>$enq->email,'name'=>$enq->name.' '.$enq->lastname);	                
	  			}
	    	}else{					
		        $to[]= array('email_id'=>$to_email,'name'=>'');	                
			}
			$curl_fields['mail_datas']['message']['to_recipients'] = $to;
			$curl_fields = json_encode($curl_fields);
			/*echo $curl_fields;
			exit();*/
			if ($to) {
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://teleduce.in/send-email-json-otom/8c999fa1-e303-423d-a804-eb0e6210604d/1007/",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS =>$curl_fields,
				  CURLOPT_HTTPHEADER => array(
				    "Content-Type: application/json"
				  ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				/*echo $response;*/
				$res	=	json_decode($response,true);
				if (!empty($res['response']) && $res['response_type'] == 'success') {
					echo "Email Sent Successfully.";	
				}
			}
		}else if ($this->input->post('mesge_type')== 2) {
			$message = $this->input->post('message_name');
	        $move_enquiry = $this->input->post('enquiry_id');
	        $phone = '';
			if(!empty($this->input->post('mobile'))){	              	
				$phone= $this->input->post('mobile');
			}else{
				if(!empty($move_enquiry)){
				  $i = 0;
				  foreach($move_enquiry as $key){
				    $enq = $this->enquiry_model->enquiry_by_id($key);
				    if ($i==0) {
				    	$phone .= $enq->phone;
				    }else{
				    	$phone .= ','.$enq->phone;
				    }
				    $i++;
				  }
				}
			}
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://teleduce.corefactors.in/sendsms/?key=8c999fa1-e303-423d-a804-eb0e6210604d&text=$message&route=0&from=CORFCT&to=$phone",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			));
			$response = curl_exec($curl);
			echo "SMS Sent Successfully.";
			//echo $response;
		}
	}
   
	public function send_sms(){
	    
		
		// $usermeta = $this->user_model->get_user_meta( $this->session->user_id,array('api_name','api_url'));
		// print_r($usermeta);
		// die();
		  $signature   = $this->enquiry_model->get_signature();
		  //schedule 
		  $message_from=$this->input->post('message_from');
		  $schedule_time=$this->input->post('schedule_time');
		  $schedule=$this->input->post('schedule');
		//   $message_type=$this->input->post('message_type');
		  //schedule end
		  $msgType= $this->input->post('mesge_type');
		  $ticketId= $this->input->post('ticketId');
		  $msg_from=$this->input->post('msg_from');
		  $user_id = $this->session->user_id;
		  $move_enquiry = $this->input->post('enquiry_id');
		  $message=$this->input->post('message_name');
			$Enquiry_id=$this->input->post('Enquiry_id');
		  $replaceName='';
		  $replacePhone='';
		  $username=$this->session->userdata('fullname');
		  $userphone=$this->session->userdata('phone');
		  $designation=$this->session->userdata('designation');
		  //fetch visiting card
		  $media_url='';
		 $this->db->where('pk_i_admin_id',$this->session->user_id);
              $user_row  = $this->db->get('tbl_admin')->row_array();
		//   if($this->session->companey_id==65)
		//   {	
			$user_meta = $this->user_model->get_user_meta($user_id,array('visiting_card'));
			
			// print_r($message);
			// die();
		  	$email_subject = $this->input->post('email_subject')??'';
			 
			if(!empty($move_enquiry) && !is_array($move_enquiry)){
				
				$enq_row=(array)$this->Message_models->fetchenqById('enquiry',$move_enquiry);
				//print_r($enqData); exit();;
				// $replaceName=	$enqData->name_prefix.' '.$enqData->name;
				// $replacePhone=	$enqData->phone;
			    $name1 = $enq_row['name_prefix'].' '.$enq_row['name'].' '.$enq_row['lastname'];
                $message = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message))))));
                $email_subject = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$email_subject))))));
			   
			  }
			  else if($msg_from=='ticket')
			  {
					$enq_row=(array)$this->Message_models->fetchenqById('ticket',$ticketId);
				
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
			                $enq_row['ticketno'],
			                $enq_row['tracking_no'],
			                );
					$message  =str_replace($find, $replace, $message);

		            $email_subject  = str_replace($find, $replace, $email_subject);
			  }
			//  echo $message.'<br>'.$email_subject;exit();
		
        if($this->input->post('mesge_type')== 1){

	      	$templates_id	=	$this->input->post('templates');
	      	$this->db->where('temp_id',$templates_id);
			  $template_row	=	$this->db->get('api_templates')->row_array();
			  $template_name=$template_row['template_name'];
			
            $phone= '91'.$this->input->post('mobile');
            $move_enquiry = $this->input->post('enquiry_id');
        	if(!empty($move_enquiry) && is_array($move_enquiry)){
      	      foreach($move_enquiry as $key){	
      	        $enq = $this->enquiry_model->enquiry_by_id($key);
      	        $phone='91'.$enq->phone;
				  $this->Message_models->sendwhatsapp($phone,$message);
				  $media_url='';
				  
      	        if($template_row['media']){	      	        	
      	        	$media_url	=	$template_row['media'];
					  $this->Message_models->sendwhatsapp($phone,base_url().$media_url);
				  }
				  $save_message=['message'=>$message,'media'=>$media_url];
				  $saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($enq->Enquery_id,$save_message,$user_id,$template_name,'Send Whatsapp');

				  // if visiting card send
				}
				
      	       echo "Message sent successfully";
            }else{
				//check schedule type is or not
				if($schedule==1){
					
				  $this->Message_models->sendwhatsapp($phone,$message); 
				  $media_url='';
              	if($template_row['media']){	      	   
					  $media_url = $template_row['media'];    
					  if ($msg_from=='ticket') {
						//timeline add
						$saveMsgTimelineId=$this->Message_models->AddMsgtimline($msgType,$ticketId,$user_id,$templates_id,$template_name,'Send Whatsapp');
						//save logs
						$this->Message_models->saveMsgLogs($msgType,$ticketId,$user_id,$templates_id,$message,$phone,base_url().$media_url,$saveMsgTimelineId,0);
					   } 
					  $this->Message_models->sendwhatsapp($phone,base_url().$media_url);      	        		      	
				  }
				  //only for ticket
				  if ($msg_from=='ticket') {
					// print_r($message);
					// die();
					  //timeline add
					  $saveMsgTimelineId=$this->Message_models->AddMsgtimline($msgType,$ticketId,$user_id,$templates_id,$template_name,'Send Whatsapp');
					  //save logs
				      $this->Message_models->saveMsgLogs($msgType,$ticketId,$user_id,$templates_id,$message,$phone,base_url().$media_url,$saveMsgTimelineId,0);
				}else{
					$save_message=['message'=>$message,'media'=>$media_url];
					$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($Enquiry_id,$save_message,$user_id,$template_name,'Send Whatsapp');
				      }
			  echo "Message sent successfully";
			}else{
				if ($msg_from=='ticket') {
					$from_id=$ticketId;
				}else{
					$from_id=$Enquiry_id;
					
				}
				if(!empty($template_row['media'])){	      	        	
					$media_url	=	$template_row['media'];
					$media_url=     base_url().$media_url;
											}
				$scheduleData=['message'=>$message,'media'=>$media_url,'from_id'=>$from_id];
				$scheduledata=json_encode($scheduleData);
				$this->Message_models->saveSchedule($msgType,$message_from,$scheduledata,$phone,$user_id,$schedule_time);
				echo "Message Scheduled successfully";
			}
           }
        }else if($this->input->post('mesge_type')== 3){
	        $to = $this->input->post('mail');
			
			$temp_id = $this->input->post('templates');
        	$rows	=	$this->db->select('*')
                        ->from('api_templates')
                        ->join('mail_template_attachments', 'mail_template_attachments.templt_id=api_templates.temp_id', 'left')                    
                        ->where('temp_id',$temp_id)                        
                        ->get()
						->row();
						// print_r($rows);
						
			if($schedule==1){
			
        	
            // $message = $this->input->post('message_name');
            //$email_subject = $this->input->post('email_subject');
	        $move_enquiry = $this->input->post('enquiry_id');
        	$this->db->where('comp_id',$this->session->companey_id);
        	$this->db->where('status',1);
        	$email_row	=	$this->db->get('email_integration')->row_array();
        	if(empty($email_row)){
  				echo "Email is not configured";
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
        	//$this->load->library('email');
		}
        	$this->db->where('comp_id',$this->session->companey_id);
            $this->db->where('sys_para','usermail_in_cc');
            $this->db->where('type','COMPANY_SETTING');
            $cc_row = $this->db->get('sys_parameters')->row_array(); 
            $cc = '';
            if(!empty($cc_row))
            {
                $this->db->where('pk_i_admin_id',$this->session->user_id);
               $cc_user =  $this->db->get('tbl_admin')->row_array();
               if(!empty($cc_user))
                    $cc = $cc_user['s_user_email'];
            }
		
	
            if(!empty($move_enquiry)){
				
	            	if(is_array($move_enquiry))
	            	{
						
	            		foreach($move_enquiry as $key){
							
	            			if($this->session->companey_id==65)
	            			{

	            				$enq_row=(array)$this->Message_models->fetchenqById('enquiry',$key);
								//print_r($enqData); exit();;
								// $replaceName=	$enqData->name_prefix.' '.$enqData->name;
								// $replacePhone=	$enqData->phone;
						    $name1 = $enq_row['name_prefix'].' '.$enq_row['name'].' '.$enq_row['lastname'];
			                $message = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message))))));
			                $email_subject = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$email_subject))))));
								
						}
						$media_url='';
	            			//echo $to.'|'.$email_subject.'| '.$message.'|'.$cc; exit();	
			      	        $enq = $this->enquiry_model->enquiry_by_id($key);
			      	       
					        $this->email->initialize($config);
					        $this->email->from($email_row['smtp_user']);
			                $to=$enq->email;
			                $this->email->to($to);
			                if($cc!='')
			                	$this->email->cc($cc);
			                $this->email->subject($email_subject); 
			                $this->email->message($message); 
							//$this->email->set_mailtype('html');
			                if($rows->files!=null || !empty($rows->files==null))
			                {
								$this->email->attach($rows->files);
								$media_url=$rows->files;
			                }
			                if($this->email->send()){
									echo "Mail sent successfully";
									$save_message=['message'=>$message,'media'=>$media_url];
				                	$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($enq_row->Enquery_id,$save_message,$user_id,$email_subject,'Send Mail');
			                }else{
								echo $this->email->print_debugger();
								echo "Something went wrong";			                	
			                }
			  			}
	            	}
	      	    	else
	      	    	{
						// 	$enq = $this->enquiry_model->enquiry_by_id($move_enquiry);
						// $to=$enq->email;
						if(!empty($to)){

						if($schedule==1){
						
					        $this->email->initialize($config);
					        $this->email->from($email_row['smtp_user']);
			                $this->email->to($to);
			                if($cc!='')
			                	$this->email->cc($cc);
			                $this->email->subject($email_subject); 
			                $this->email->message($message); 
			                //echo $message.'<br>'.$email_subject.'<br>'.$cc;
							//$this->email->set_mailtype('html');
				  // if visiting card send
							if($rows->files!=null || !empty($rows->files==null))
							{ $this->email->attach($rows->files);
								$media_url=$rows->files;
							
							}
			                if($this->email->send()){
									$save_message=['message'=>$message,'media'=>$media_url];
				                	$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($Enquiry_id,$save_message,$user_id,$email_subject,'Send Mail');
									echo "Mail sent successfully";

								}else{
								echo $this->email->print_debugger();
								echo "Something went wrong";			                	
							}
						}else{
							$from_id=$Enquiry_id;
							$media_url='';
							if($rows->files!=null || !empty($rows->files==null))
							{ $media_url= $rows->files; }

				$scheduleData=['message'=>$message,'media'=>$media_url,'subject'=>$email_subject,'cc'=>$cc,'from_id'=>$from_id];
				$scheduledata=json_encode($scheduleData);
				$this->Message_models->saveSchedule($msgType,$message_from,$scheduledata,$to,$user_id,$schedule_time);
				echo "Email Scheduled successfully";
						}
					}else{
				echo "Email Id not Exist";
					      }
	      	    	}
        	}else{
				// $enq = $this->enquiry_model->enquiry_by_id($move_enquiry);
				// 		$to=$enq->email;
				if($schedule==1){

        	//echo $to.'|'.$email_subject.'| '.$message.'|'.$cc; exit();			
		        $this->email->initialize($config);
		        $this->email->from($email_row['smtp_user']);		                
	            $this->email->to($to);
	             
	                if($cc!='')
	                	$this->email->cc($cc);
	            $this->email->subject($email_subject); 
	            $this->email->message($message); 
	            //$this->email->set_mailtype('html');
	            //if($rows->files!=null || !empty($rows->files==null)){
	                //$this->email->attach($rows->files);
				//}
	            if($this->email->send()){
						echo "Mail sent successfully";
						if ($msg_from=='ticket') {
							//timeline add
							$saveMsgTimelineId=$this->Message_models->AddMsgtimline($msgType,$ticketId,$user_id,$temp_id,$email_subject,'Send Mail');
							//save logs
							$this->Message_models->saveMsgLogs($msgType,$ticketId,$user_id,$temp_id,$message,$to,base_url().$media_url,$saveMsgTimelineId,$email_subject);
					  }else{
						$save_message=['message'=>$message,'media'=>''];
						$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($Enquiry_id,$save_message,$user_id,$email_subject,'Send Mail');
						
					  }
	            }else{
	            	echo $this->email->print_debugger();
						echo "Something went wrong!";			                	
				}  
			}else{
				if ($msg_from=='ticket') {
					$from_id=$ticketId;
				}else{
					$from_id=$Enquiry_id;
					
				}
				$media_url='';
	$scheduleData=['message'=>$message,'media'=>$media_url,'subject'=>$email_subject,'cc'=>$cc,'from_id'=>$from_id];
	$scheduledata=json_encode($scheduleData);
	$this->Message_models->saveSchedule($msgType,$message_from,$scheduledata,$to,$user_id,$schedule_time);
	// echo $this->db->last_query();

	echo "Message Scheduled successfully";
			}              
    		}	        
    	}else if($this->input->post('mesge_type')== 2){
	        // $message = $this->input->post('message_name');
	        $move_enquiry = $this->input->post('enquiry_id');
	           
			if(!empty($this->input->post('mobile'))){
				$phone= '91'.$this->input->post('mobile');

				if($schedule==1){
				  // if visiting card send
							$message  =str_replace('@visiting_card',base_url($user_meta['visiting_card']), $message);
				  // if visiting card send end
				$this->Message_models->smssend($phone,$message);
				echo "Message sent successfully";
				  //only for ticket
				  if ($msg_from=='ticket') {
					//timeline add
					$saveMsgTimelineId=$this->Message_models->AddMsgtimline($msgType,$ticketId,$user_id,0,$message,'Send SMS');
					//save logs
					$this->Message_models->saveMsgLogs($msgType,$ticketId,$user_id,0,$message,$phone,$media_url,$saveMsgTimelineId,' ');
			  }else{
				$save_message=['message'=>$message,'media'=>''];
				$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($Enquiry_id,$save_message,$user_id,' ','Send SMS');
			        }
			}else{
				  // if visiting card send
						$message  =str_replace('@visiting_card',base_url($user_meta['visiting_card']), $message);
				  // if visiting card send end
				$media_url='';
				if ($msg_from=='ticket') {
					$from_id=$ticketId;
				}else{
					$from_id=$Enquiry_id;
			      	}
				$scheduleData=['message'=>$message,'from_id'=>$from_id];
				$scheduledata=json_encode($scheduleData,false);
				$this->Message_models->saveSchedule($msgType,$message_from,$scheduledata,$phone,$user_id,$schedule_time);
	           // echo $this->db->last_query();
				echo "SMS Scheduled successfully";
			}
			}else{
				if(!empty($move_enquiry)){
				  foreach($move_enquiry as $key){
				    $enq = $this->enquiry_model->enquiry_by_id($key);
					$phone=$enq->phone;
				  // if visiting card send
							$message  =str_replace('@visiting_card',base_url($user_meta['visiting_card']), $message);
				  // if visiting card send end
					$this->Message_models->smssend($phone,$message);
					$save_message=['message'=>$message,'media'=>''];
				$saveMsgTimelineId=$this->Message_models->AddMsgtimlineEnquiry($enq->Enquery_id,$save_message,$user_id,' ','Send SMS');
				  }
				  echo "Message sent successfully";
				  
				}
			}
    	}
	}


public function employee_send_sms(){
	    	  
		  $msgType= $this->input->post('mesge_type');
		  $user_id = $this->session->user_id;
          if(!empty($this->input->post('user_ids'))){		  
		  $move_enquiry = explode(',',$this->input->post('user_ids'));
		  }
		  $all_user_id = json_encode($move_enquiry);
		  
		  $message=$this->input->post('message_name');
          $email_subject = $this->input->post('email_subject')??'';
		  
		  $templates_id	=	$this->input->post('templates');
		  if(!empty($templates_id)){
	      	$this->db->where('temp_id',$templates_id);
			$template_row	=	$this->db->get('api_templates')->row_array();
			$template_name=$template_row['template_name'];
		  }else{
			$template_name = '';
		  }

        if($this->input->post('mesge_type')== 1){

        	if(!empty($move_enquiry) && is_array($move_enquiry)){
      	      foreach($move_enquiry as $key){	
      	        $user_row	=	$this->db->select('s_phoneno')->where('pk_i_admin_id',$key)->get('tbl_admin')->row_array();
      	        $phone='91'.$user_row['s_phoneno'];
				  $this->Message_models->sendwhatsapp($phone,$message);
				}
				
				    $data = array(
                        'user_ids'=>$all_user_id,
                        'sent_by'=>$this->session->user_id,
                        'type'=>'1',
                        'status'=>'1'
                    );

                    $this->db->insert('tbl_msg_sent_log',$data);
				
      	       echo "Whatsapp sent successfully";
            }
			
        }else if($this->input->post('mesge_type')== 3){
			
			$temp_id = $this->input->post('templates');
        	$rows	=	$this->db->select('*')
                        ->from('api_templates')
                        ->join('mail_template_attachments', 'mail_template_attachments.templt_id=api_templates.temp_id', 'left')                    
                        ->where('temp_id',$temp_id)                        
                        ->get()
						->row();						

        	$this->db->where('comp_id',$this->session->companey_id);
        	$this->db->where('status',1);
        	$email_row	=	$this->db->get('email_integration')->row_array();
        	if(empty($email_row)){
  				echo "Email is not configured";
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
        	}

            if(!empty($move_enquiry)){
				
	            	if(is_array($move_enquiry))
	            	{						
	            		foreach($move_enquiry as $key){

			      	        $user_row	=	$this->db->select('s_user_email')->where('pk_i_admin_id',$key)->get('tbl_admin')->row_array();
      	                    $email = $user_row['s_user_email'];
			      	       
					        $this->email->initialize($config);
					        $this->email->from($email_row['smtp_user']);
			                $to=$email;
			                $this->email->to($to);
			                $this->email->subject($email_subject); 
			                $this->email->message($message); 
                            $this->email->send();
			  			}
						
					$data = array(
                        'user_ids'=>$all_user_id,
                        'sent_by'=>$this->session->user_id,
                        'type'=>'3',
                        'status'=>'1'
                    );

                    $this->db->insert('tbl_msg_sent_log',$data);
					echo "Mail sent successfully";
	            	}
			}
        	        
    	}else if($this->input->post('mesge_type')== 4){
$this->load->model('Leads_Model');
$templates_id	=	$this->input->post('templates');
	      	$this->db->where('temp_id',$templates_id);
			  $template_row	=	$this->db->get('api_templates')->row_array();
			  $template_name=$template_row['template_name'];
			  
 if(!empty($move_enquiry)){
    foreach($move_enquiry as $key){
        $stage_remark = $template_name;
		$conversation = $message;
        $comment_id = $this->Leads_Model->add_notifications_for_events('',$key,$stage_remark,$conversation);
    }
	$data = array(
                        'user_ids'=>$all_user_id,
                        'sent_by'=>$this->session->user_id,
                        'type'=>'1',
                        'status'=>'1'
                    );

                    $this->db->insert('tbl_msg_sent_log',$data);
	echo "Bell Notification sent successfully";
 }
    }
	}
	
public function chat_start(){
   	$message=$this->input->post('message');
   	$phone= '91'.$this->input->post('phone');
   	$this->Message_models->sendwhatsapp($phone,$message);
    echo "Message sent successfully";
   }

   public function test_start(){
	$this->load->library('email');
	$config['protocol']     = 'smtp';
	$config['smtp_host']    = 'ssl://smtp3.netcore.co.in';
	$config['smtp_port']    = '465';
	$config['smtp_timeout'] = '7';
	$config['smtp_user']    = 'crm@vtransgroup.com';
	$config['smtp_pass']    = '@FebCrm21#';
	$config['charset']      = 'utf-8';
	$config['newline']      = "\r\n";
	$config['mailtype']     = 'text'; // or html
	$config['validation']   = TRUE; // bool whether to validate email or not 
	$this->email->initialize($config);

	// $config = Array(
	// 	'protocol' => 'smtp',
	// 	'smtp_host' => 'smtppro.zoho.com',
	// 	'smtp_port' => 587,
	// 	'smtp_user' => 'kanhaiya@archizsolutions.com',
	// 	'smtp_pass' => 'Archiz321',
	// 	'charset' 	=> "utf-8",		
	// 	'newline' 	=> "\r\n"

	// );
	// $this->load->library('email', $config);

		$this->email->from('crm@vtransgroup.com','TEST');
		$this->email->to('prokanhaiya@gmail.com');
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		if ($this->email->send()) {
			echo "you are luck!";
		} else {
			echo $this->email->print_debugger();
		}
	}

}