<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ChatController extends CI_Controller {
 	public function __construct()
        {
                parent::__construct();
				$this->load->model('ChatModel');
				$this->load->model('user_model');
                //$this->SeesionModel->not_logged_in();
				//$this->SeesionModel->is_logged_in();
				$this->load->helper('string');
				$this->load->library('session');
				$this->load->library('parser');
        }
	public function send_text_message(){
		
		$post = $this->input->post();
		$messageTxt='NULL';
		$attachment_name='';
		$file_ext='';
		$mime_type='';
		
		if(isset($post['type'])=='Attachment'){ 
		
			$AttachmentData = $this->ChatAttachmentUpload();
			
			//print_r($AttachmentData);
			$attachment_name =  (!empty($AttachmentData['file_name'])) ?  $AttachmentData['file_name'] : "";
			$file_ext 		 = (!empty($AttachmentData['file_ext'])) ? $AttachmentData['file_ext'] : "";
			$mime_type		 = (!empty($AttachmentData['file_type'])) ? $AttachmentData['file_type'] : "" ;
			 
		}else{
			$messageTxt = reduce_multiples($post['messageTxt'],' ');
		}	
		 
				$data=[
 					'sender_id' => $this->session->user_id,
					'receiver_id' => $this->input->post('receiver_id'),
					'message' =>   $messageTxt,
					'attachment_name' => $attachment_name,
					'file_ext' => $file_ext,
					'mime_type' => $mime_type,
					'message_date_time' => date('Y-m-d H:i:s'), //23 Jan 2:05 pm
					'ip_address' => $this->input->ip_address(),
				];
		     
 				$query = $this->ChatModel->SendTxtMessage($data); 
 				$response='';
				if($query == true){
					$response = ['status' => 1 ,'message' => '' ];
				}else{
					$response = ['status' => 0 ,'message' => 'sorry we re having some technical problems. please try again !' 						];
				}
             
 		   echo json_encode($response);
	}
	public function ChatAttachmentUpload(){
		 
		
		$file_data='';
		if(isset($_FILES['attachmentfile']['name']) && !empty($_FILES['attachmentfile']['name'])){	
				$config['upload_path']          = './uploads';
				$config['allowed_types']        = 'jpeg|jpg|png|txt|pdf|docx|xlsx|pptx|rtf';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('attachmentfile'))
				{
					echo json_encode(['status' => 0,
					'message' => '<span style="color:#900;">'.$this->upload->display_errors(). '<span>' ]); die;
				}
				else
				{
					$file_data = $this->upload->data();
					return $file_data;
				}
		    }
 		 
	}
	public function get_chat_history_by_vendor(){
		
		$receiver_id = $this->input->get('receiver_id');
		$action      = (isset($_GET["action"])) ? $_GET["action"] : "";
		
		$Logged_sender_id = $this->session->user_id; 
		$history = $this->ChatModel->GetReciverChatHistory($receiver_id);
		
		$usrarr = explode(",", trim($receiver_id, ","));
		
		$msgarr = array();
		if(!empty($usrarr)){
			
			foreach($usrarr as $ind => $rcvr){
				
				$history = $this->ChatModel->GetReciverChatHistory($rcvr);
				
				if($action == "read"){
					
					$this->db->where("sender_id", $rcvr  );
					$this->db->where("receiver_id", $Logged_sender_id);
					$this->db->update("chat", array("status" => 2));
					
				}
				
				$jarr = $userarr = array();
				$usrmsg = "";
				if(!empty($history)){
				
					$msgarr[$rcvr]["status"] = "success";
					
					foreach($history as $chat){
						
						$user_data=$this->user_model->read_by_id($chat['sender_id']);
						
						$jarr["name"] = $jarr["photo"] = '';
						
						if(!empty($user_data)){
							$jarr["name"]    = $user_data->s_display_name.''.$user_data->last_name ;
							$jarr["photo"]   = (!empty($user_data->picture)) ? base_url($user_data->picture) : base_url('assets/images/no-img.png'); 
							$jarr["msgid"]   = $chat['id'];
							$jarr["sendrid"] = $chat['sender_id'];
							$jarr["message"] = $chat['message'];
							$jarr["msgdate"] = date('d M H:i A',strtotime($chat['message_date_time']));
							$jarr["userno"]  = $user_data->pk_i_admin_id;
							
							$msgdate  = $chat['message_date_time'];
							
							if($Logged_sender_id!=$chat['sender_id']){
								
								$jarr["type"] = 1; 
							}else{
								$jarr["type"] = 2; 
								
					}
					
					$jarr["msgbody"]   = $messageBody = '';
					if(!empty($chat['message'] =='NULL')){ 
						$classBtn = 'right';
						$imgclass = "img-show";
						if($Logged_sender_id==$chat['sender_id']){$classBtn = 'left';}
						
						$attachment_name = $chat['attachment_name'];
						$file_ext = $chat['file_ext'];
						$mime_type = explode('/',$chat['mime_type']);
						
						$document_url = base_url('uploads/'.$attachment_name);
						
							  if($mime_type[0]=='image'){
								$messageBody.='<img style="height:100px;width:100px;" src="'.$document_url.'" onClick="ViewAttachmentImage('."'".$document_url."'".','."'".$attachment_name."'".');" class="attachmentImgCls">';	
							  }else{
								$messageBody='';
								 $messageBody.='<div class="attachment">';
									   $messageBody.='<p class="filename">';
										$messageBody.= $attachment_name;
									  $messageBody.='</p>';
					
									
										$messageBody.='<a   class="btn btn-primary btn-xs pull-'.$classBtn.'"  download href="'.$document_url.'"><i class="fa fa-download"></i> Download</a>';
									 
									$messageBody.='</div>';
								}
									
														
							}else{
								$imgclass = "text-show";
							//	$messageBody = $chat['message'];
							//	$search  = "/[^\:\;](.*)[^\:\;]/";
							//	$replace = "your new inner text";
							//	$messageBody = echo preg_replace(, $replace, $chat['message']);
								$messageBody = str_replace(" fa-"," view-emoji-icon fa-", $chat['message']);
							}
							
							$now = new DateTime();
							$status_date = new DateTime($chat['message_date_time']);
							$diff = $now->diff($status_date);
							
							if($diff->d > 0){
								$msgdate =  date('d M H:i A',strtotime($chat['message_date_time']));
							}else{
								
								$msgdate =  date('H:i A',strtotime($chat['message_date_time']));
							}
							
						if($Logged_sender_id != $chat['sender_id']){?>     
							<?php $message  = '<div class="d-flex justify-content-start mb-4"><div class="img_cont_msg"><img src="'.$jarr["photo"].'" class="rounded-circle user_img_msg"></div><div class="msg_cotainer">'.$messageBody.'<span class="msg_time">'.$msgdate.'</span></div></div>' ?>	
					<?php }else{
							
							$message =	'<div class="d-flex justify-content-end mb-4"><div class="msg_cotainer_send '.$imgclass.'">'.$messageBody.'<span class="msg_time_send">'.$msgdate.'</span></div></div>';
								
						}
						$usrmsg .= $message;
						$jarr["msgbody"] = $message; 
							
							$userarr[] = $message;
						}
			}
			
		}else{
			$msgarr[$rcvr]["status"] = "failed";
			
		}
			$msgarr[$rcvr]["data"] = $usrmsg;
		}
			
		}
		die(json_encode($msgarr));
 		
	}
	
	
	
	public function getloguser(){
		
	
		
				$user_id = $this->session->user_id;
			 $this->db->select("tbl_admin.*");
			 $this->db->from('tbl_admin');        
			 $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');    
			 $this->db->join("chat", "receiver_id = '$user_id'", "LEFT");
			 $this->db->where('tbl_admin.companey_id',$this->session->companey_id); 
			 $this->db->where('tbl_admin.b_status',1);   
			 $this->db->group_by('chat.receiver_id');	
			 $this->db->order_by('chat.message_date_time','DESC');	
		$data['all_user']  = $this->db->get()->result();
		
		
	/*		 $this->db->select('receiver_id, COUNT(receiver_id) as total');
		 $this->db->where(array("receiver_id"=> $user_id));
		 $this->db->where("sender_id != '$user_id'");
		 $this->db->where("status != 2");
		 $this->db->group_by('receiver_id'); 
		 $unread = $this->db->get('chat')->result(); */
		
		
		$all_user = $data['all_user'];
		
		$usrlogarr = array();
		if(!empty($all_user)){
			
			$usrlogarr["total"] = count($all_user);
			
			foreach($all_user as $v){
				
				if($v->pk_i_admin_id!=$this->session->user_id){
					
					$lstlog = $v->last_log; 
					
					$unrdmsg = $this->db->where("status != 2")->where(array("receiver_id"=> $user_id, "sender_id" => $v->pk_i_admin_id))->count_all_results("chat");
					
					$prev = $now = 0;
					if(!empty($lstlog)){
						
						$now = strtotime(date("Y-m-d h:i:s"));
						$prev = strtotime($lstlog);
						
					}
					$date = new DateTime($v->last_log);
					$date2 = new DateTime(date("Y-m-d h:i:s"));
					$interval = date_diff($date, $date2);
				
					
					$diff =  abs( $now - $prev);
					$years   = $interval->y; 
					$months  = $interval->m; 
					$days    = $interval->d;
					$hours   = $interval->h; 
					$minuts  = $interval->i; 
					$seconds = $interval->s; 
					
					if($days > 0){
						
						$lastlog =  $days." Days".$hours." hrs ";
					}else if(($hours > 0)){
						$lastlog =  $hours." hrs ".$minuts. "min";
					}else if(($diff > 180)){
						
						$lastlog =  $minuts." minutes";
					}else {
						$lastlog = "online";
						//echo $seconds." Second";
					}
					
					
					if($diff < 180){
					
						$clr = "green";	
					}else{
						$clr = "red";
					}
					$usrlogarr["users"][$v->pk_i_admin_id] = array("color" => $clr ,"lastlog" => $lastlog,"unread" => $unrdmsg); 
				}
			}
		}else{
			$usrlogarr["total"] = 0;
		}		
			$updarr = array("last_log" => date("Y-m-d h:i:s"));
		$this->db->where("pk_i_admin_id", $user_id)
					->update("tbl_admin", $updarr);
		
		die(json_encode($usrlogarr));
		
		echo "<span style = 'display:none;'>Hello</span>";
	  
		
	  
		$this->load->view("chat-view", $data);
		
	}
	
	public function getunread(){
		if(!empty($_SESSION) && !empty($this->session->user_id)){
			$user_id = $this->session->user_id;
			$updarr = array("last_log" => date("Y-m-d h:i:s"));
			$this->db->where("pk_i_admin_id", $user_id)
			->update("tbl_admin", $updarr);
			
			$this->db->select('sender_id,receiver_id, COUNT(sender_id) as total');
			$this->db->where(array("receiver_id"=> $user_id));
			$this->db->where("sender_id != '$user_id'");
			$this->db->where("status != 2");
			$this->db->group_by('sender_id'); 
			//$this->db->order_by('message_date_time','DESC'); 
			$unread = $this->db->get('chat')->result();
			//echo $this->db->last_query();
			$chtarr = array();	
			
			if(!empty($unread)){
				$total = 0;
				foreach($unread as $ind => $rcvr){
					
					$chtarr["users"][$rcvr->sender_id] = $rcvr->total; 
					$total = $total + $rcvr->total;		
				}
				$chtarr["total"] = $total;
			}else{
				$chtarr["total"] = 0; 
			}
			die(json_encode($chtarr));
			
			// $unread =  $this->db->where(array("receiver_id"=> $user_id))->where("sender_id != '$user_id'")->where("status != 2")->get('chat')->result();
			
			}else{
				die(json_encode(array('redirect'=>1)));
			}
		}
		
	
	public function chat_clear_client_cs(){
		$receiver_id = $this->OuthModel->Encryptor('decrypt', $this->input->get('receiver_id') );
		
		$messagelist = $this->ChatModel->GetReciverMessageList($receiver_id);
		
		foreach($messagelist as $row){
			
			if($row['message']=='NULL'){
				$attachment_name = unlink('uploads/attachment/'.$row['attachment_name']);
			}
 		}
		
		$this->ChatModel->TrashById($receiver_id); 
 
 		
	}
}