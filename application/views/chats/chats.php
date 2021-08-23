<script src="<?php echo base_url('assets/js/jquery.imageviewer.min.js') ?>"></script> 
<div style="width:70%;float:right;">
      <style>
      .class_chat_list {
          position: fixed;
          z-index: 200;
          float: right;
          right: 0px;
          bottom: 0px;
          display: block;
          background: #fff;
          height: 416px;
          width: 290px;
          border: 1px solid #dadada;
          border-radius: 10px 10px 0px 0px;
          overflow: hidden;
          -webkit-box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
          -moz-box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
          box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
      }

      #chatSection,
      .chatSection {		  
          display: inline-block;
          float: right;
          background: #fff;
          height: 488px;
          width: 318px;
          margin-right: 10px;
          border: 1px solid #dadada;
          border-radius: 10px 10px 0px 0px;
          /* overflow: hidden; */
          -webkit-box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
          -moz-box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
          box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
      }

      .minimize-chat-section {
          height: 160px;
      }

      #all-chats-area {
          padding: 10px;
      }

      #chatbox-container {
          position: fixed;
          z-index: 200;
          display: block;
          bottom: 0px;
          right: 300px;
          max-width: 70%;

	  }
	  @media only screen and (max-width: 600px) {
		  #chatSection,
		  .chatSection{
			  position: absolute;
			  bottom:0px;
			  left:0px;
			  z-index:201 !important;
		  }  
		  .class_chat_list{
			z-index:unset;
		  }
	    }

      /*	  .box-footer{position:fixed;z-index:200;float:right;right:300px;bottom:0px;display:block;background:#fff;height:40px;width:250px;
	   border:1px solid #000;} */

      .box-footer {
          padding: 5px;
      }

      .box-footer .input-group {
          display: flex;
          flex-wrap: wrap;
          align-items: stretch;
          width: 100%;
      }

      .mb-4,
      .my-4 {
          margin-bottom: 1.5rem !important;
      }

      .input-group>.form-control,
      .input-group>.form-control-plaintext,
      .input-group>.custom-select,
      .input-group>.custom-file {
          position: relative;
          flex: 1 1 auto;
          width: 1%;
          margin-bottom: 0;
      }

      .upload_attachmentfile {
          position: absolute;
          opacity: 0;
          right: 0;
          top: 0;
      }

      .btnFileOpen {
          margin-top: -50px;
      }

      .direct-chat-warning .right>.direct-chat-text {
          background: #d2d6de;
          border-color: #d2d6de;
          color: #444;
          text-align: right;
      }

      .direct-chat-primary .right>.direct-chat-text {
          background: #3c8dbc;
          border-color: #3c8dbc;
          color: #fff;
          text-align: right;
      }

      .spiner {}

      .spiner .fa-spin {
          font-size: 24px;
      }

      .attachmentImgCls {
          width: 450px;
          margin-left: -25px;
          cursor: pointer;
      }

      ul.users-list {
          list-style-type: none;
      }
      </style>
      <style>
      .chat {
          margin-top: auto;
          margin-bottom: auto;
      }

      .contacts_body {
          padding: 0.75rem 0 !important;
          overflow-y: auto;
          white-space: nowrap;
      }

      .msg_card_body {
          overflow-y: auto;

      }


      .type_msg {
          background-color: rgba(0, 0, 0, 0.3) !important;
          border: 0 !important;
          color: white !important;
          height: 60px !important;
          overflow-y: auto;
      }

      .type_msg:focus {
          box-shadow: none !important;
          outline: 0px !important;
      }



      .user_img {
          height: 70px;
          width: 70px;
          border: 1.5px solid #f5f6fa;

      }

      .user_img_msg {
          height: 40px;
          width: 40px;
          border: 1.5px solid #f5f6fa;

      }

      .img_cont {
          position: relative;
          height: 70px;
          width: 70px;
      }

      .img_cont_msg {
          height: 40px;
          width: 40px;
      }

      .online_icon {
          position: absolute;
          height: 15px;
          width: 15px;
          background-color: #4cd137;
          border-radius: 50%;
          bottom: 0.2em;
          right: 0.4em;
          border: 1.5px solid white;
      }

      .offline {
          background-color: #c23616 !important;
      }

      .user_info {
          margin-top: auto;
          margin-bottom: auto;
          margin-left: 15px;
      }

      .user_info span {
          font-size: 20px;
          color: white;
      }

      .user_info p {
          font-size: 10px;
          color: rgba(255, 255, 255, 0.6);
      }

      .video_cam {
          margin-left: 50px;
          margin-top: 5px;
      }

      .video_cam span {
          color: white;
          font-size: 20px;
          cursor: pointer;
          margin-right: 20px;
      }

      .msg_cotainer {
          margin-top: auto;
          margin-bottom: auto;
          margin-left: 10px;
          border-radius: 25px;
          background-color: #f6f6f6;
          ;
          padding: 10px;
          position: relative;
          max-width: 90%;
          white-space: normal;
          word-wrap: break-word;
          color: #000;
      }

      .msg_cotainer_send {
          margin-top: auto;
          margin-bottom: auto;
          margin-right: 0px;
          border-radius: 10px;
          background-color: #efefef;
          padding: 10px;
          position: relative;
          color: #000;
          font-size: 12px;
          max-width: 90%;
          margin-left: 10%;
          white-space: normal;
          word-wrap: break-word;
      }

      .message {
          border: 1px solid #bababa;
          resize:none;
      }

      .msg_time {
          position: absolute;
          left: 0;
          bottom: -15px;
          color: rgb(97, 95, 95);
          font-size: 10px;
          min-width: 83px;
      }

      .msg_time_send {
          position: absolute;
          right: 0;
          bottom: -18px;
          color: rgb(111, 111, 111);
          font-size: 10px;
          width: 100%;
          display: block;
          min-width: 86px;
          text-align: right;
      }

      .msg_head {
          position: relative;
      }

      #action_menu_btn {
          position: absolute;
          right: 10px;
          top: 10px;
          color: white;
          cursor: pointer;
          font-size: 20px;
      }

      .action_menu {
          z-index: 1;
          position: absolute;
          padding: 15px 0;
          background-color: rgba(0, 0, 0, 0.5);
          color: white;
          border-radius: 15px;
          top: 30px;
          right: 15px;
          display: none;
      }

      .action_menu ul {
          list-style: none;
          padding: 0;
          margin: 0;
      }

      .action_menu ul li {
          width: 100%;
          padding: 10px 15px;
          margin-bottom: 5px;
      }

      .action_menu ul li i {
          padding-right: 10px;

      }

      .action_menu ul li:hover {
          cursor: pointer;
          background-color: rgba(0, 0, 0, 0.2);
      }

      @media(max-width: 576px) {
          .contacts_card {
              margin-bottom: 15px !important;
          }
      }

      #totat-chat-unread {
          position: absolute;
          right: -10px;
          border-radius: 50%;
          line-height: 15px;
          top: -10px;
          padding: 5px;
          min-width: 25px;
          display: block;
      }

      .user-image {
          width: 30px;
          height: 30px;
          margin: 2px;
          border-radius: 50%;
      }

      .message {
          border-radius: 0px;
          border-radius: 0px 4px 4px 0px;
      }

      .btn-flat {
          border-radius: 4px 0px 0px 4px;
          width: 15%;
          border: #bababa;
          border: 1px solid #bababa;
          color: #d4d4d4;
      }

      .remove-chat-window,
      .close-login-user {
          display: block;
          float: right;
          margin-right: 12px;
          border: 1px solid #d4d4d4;
          /* padding: 7px; */
          line-height: 10px;
          margin-top: 9px;
          padding: 5px;
          font-size: 16px;
          color: #d4d4d4;
      }

      .remove-chat-window .btn-default {
          border: 1px solid #b1b1b1;

      }

      .btn-default .fa-upload {
          color: #bababa;
      }

      .user-conv-area {
          overflow-y: scroll;
          height: 390px;
          margin-top: 10px;
          padding: 10px;
          margin: 0;
          font-weight: 600;
      }

      .remove-chat-window .btn,
      .ui-datepicker-buttonpane button,
      .sp-container button {
          line-height: 1;
          color: #bababa;
      }

      .img-show {
          background: #fff;
      }

      .log-sts-red {
          border: 5px solid red;
      }

      .log-sts-green {
          border: 5px solid green;
      }

      .btn-emoji {
          padding: 5px;
          line-height: 20px;
          font-size: 23px;
          color: #d8d8d8;
      }

      .rounded-circle {
          border-radius: 50% !important;
      }

      .emoji-txt-icons .fa,
      .view-emoji-icon {
          font-size: 32px;
          color: #adaf2b;
      }

      .tb-emoji-icon {
          font-size: 22px;
          color: #adaf2b;
      }

      .justify-content-end {
          justify-content: flex-end !important;
      }

      .d-flex {
          display: flex !important;
      }
      </style>

      <section id="bottom-section">
          <div class="minimize-chatbox"
              style="position:fixed;z-index:200;float:right;right:11px;bottom:0px;display:block;" id="maxmize_chates">
              <span class="badge badge-danger" id="totat-chat-unread">0</span>
              <div>
                  <span class="btn btn-success btn-xs" style="bottom:0px;z-index:300;" onclick="open_chates()">
                      <i class="fa fa-commenting" style="font-size:30px;"></i>
                  </span>
              </div>
          </div>
          <div id="chatbox-container">
              <div id="all-chats-area"></div>
          </div>

          <div class="chatSection" id="chatSection" style="display:none">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-primary">
                  <div class="box-header with-border" style="height:40px;border-bottom: 1px solid #dadada;">
                      <img class="user-image" src="">
                      <span class="box-title ReciverName_txt"
                          style="color:#484848;font-weight:900;margin-left:10px;margin-top:8px;line-height:40px;"
                          id="ReciverName_txt"></span>

                      <a href="javascript:void(0)" class="btn btn-default remove-chat-window"
                          onclick="closed_comment();"> X
                          <!--     <i class="fa fa-times btn btn-default"  style="font-size:18px;" onclick="closed_comment();"></i>-->
                      </a>
                  </div>
                  <div class="box-body">
                      <div class="direct-chat-messages" id="content">

                          <div id="dumppy" class="user-conv-area"></div>

                      </div>

                  </div>
                  <div class="box-footer">
                      <div class="input-group">

                          <input type="hidden" id="Sender_Name" value="<?php //$user['name'];?>">
                          <input type="hidden" id="Sender_ProfilePic" value="<?php //$profile_url;?>">

                          <input type="hidden" class="ReciverId_txt">
                          <div class=" btn btn-default btn-flat" style="width:15%"> <i class="fa fa-upload"></i>
                              <input type="file" name="file" class="upload_attachmentfile" />
                          </div>
                          <!--<input type="text" name="message" placeholder="Type Message press enter..." class="form-control message" style="width:70%"> -->
                          <textarea placeholder="Type Message press enter..." class="form-control message" style="width:70%;height:38px;overflow-y:auto;"
                              contenteditable="true"></textarea>

                          <div class="dropup">
                              <a class="btn btn-emoji dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fa fa-smile-o"></i>
                              </a>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <a class="emoji-txt-icons" href="#"><i class="tb-emoji-icon fa fa-smile-o"></i></a>
                                  <a class="emoji-txt-icons" href="#"><i class="tb-emoji-icon fa fa-meh-o"></i></a>
                                  <a class="emoji-txt-icons" href="#"><i class="tb-emoji-icon fa fa-frown-o"></i></a>
                              </div>
                          </div>


                      </div>
                  </div>
              </div>
          </div>

          <div class="class_chat_list" id="user_list_hide" style="display:none">
              <!-- USERS LIST -->
              <div class="box box-danger">
                  <div style="background:#fff !important;">
                      <div style="border-bottom:1px solid #dadada;">
                          <h4
                              style="color: #484848; font-weight: 900; margin-left: 10px; margin-top: 8px;line-height: 34px;font-size: 14px;">
                              User List <a style="font-size:16px;margin-top:2px;" href="javascript:void(0)"
                                  class="btn btn-default close-login-user btn-small" onclick="closed_list();"> X
                              </a></h3>
                      </div>

                  </div>

                  <div>
					    <style>
						  .int-chat-search{							
							text-indent:15px;
						  }
						</style>
					  <div style='padding:7px;'>
							<i class='fa fa-search' style='position: absolute;top: 60px;left: 17px;color:gainsboro;'></i>
							<input type='search'  style="width:100%;::placeholder:color:red;" class='form-control int-chat-search' name='internal-chat' placeholder='Search'>
	                  </div>
                      <div class="users-list" id="chat-user-box"
                          style="height:375px;overflow-y:auto;margin-top:-10px;padding:10px;">
                          <?php 
					 $user_id = $this->session->user_id;
					 $this->db->select("*");
					 $this->db->from('tbl_admin');        
					 $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');        
                     //$this->db->join("chat", "chat.sender_id = tbl_admin.pk_i_admin_id", "LEFT");
					 $this->db->where('tbl_admin.companey_id',$this->session->companey_id); 
                     $this->db->where('tbl_admin.b_status',1);                              
                     //$this->db->order_by('chat.id','DESC');	                  
                     //$this->db->group_by('tbl_admin.pk_i_admin_id');
                     
                     $all_user=$this->db->get()->result();
					 if(!empty($all_user)){
						foreach($all_user as $v){
							if($v->pk_i_admin_id!=$this->session->user_id){
						?>
                          <div style="border-bottom:2px solid #eee;height:50px;padding-top:5px;"
                              id="<?=$v->pk_i_admin_id;?>" title="<?=$v->s_display_name.''.$v->last_name;?>"
                              class="selectuser">

                              <div style="float:left;width:15%;height:50px;">
                                  <?php if($v->picture){?>
                                  <img style="height:40px;width:40px;border-radius:50%;"
                                      src="<?=base_url().$v->picture;?>"
                                      title="<?=$v->s_display_name.''.$v->last_name;?>">
                                  <?php }else{?>
                                  <img style="height:40px;width:40px;border-radius:50%;"
                                      src="<?php echo base_url();?>assets/images/no-img.png"
                                      title="<?=$v->s_display_name.''.$v->last_name;?>">
                                  <?php } ?>
                              </div>
                              <div style="float:right;width:75%;height:50px;">
                                  <span style="font-size:12px;"><?=$v->s_display_name.''.$v->last_name;?> <span
                                          class="total-unread-msg"></span>

                                      <?php $lstlog = $v->last_log; 
								
								$prev = $now = 0;
								if(!empty($lstlog)){
									
									$now = strtotime(date("Y-m-d h:i:s"));
									$prev = strtotime($lstlog);
									
								}
								
								$diff =  abs($now - $prev);
								$years   = floor($diff / (365*60*60*24)); 
								$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
								$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

								$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 

								$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 

								$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 

								
								if($diff < 180){
								
									$clr = "green";	
								}else{
									$clr = "red";
								}
						 
						 ?>

                                      <span class="user-status-point"
                                          style="font-size:10px;float:right;border:5px solid <?php echo $clr; ?>;border-radius:50%"></span><br></span><span
                                      style="font-size:10px;"><?=$v->user_role;?></span><span class="prev-login-user"
                                      style="font-size:10px;float:right;">
                                      <?php $lstlog = $v->last_log; 
								
								$prev = $now = 0;
								if(!empty($lstlog)){
									
									$now = strtotime(date("Y-m-d h:i:s"));
									$prev = strtotime($lstlog);
									//$diff = $now->diff($ref);
									
								}
						 
						 ?>

                                      <?php  	if($diff > 60*60*24){
										echo $days;
										echo " Days";
									}else if(($diff > 60*60)){
										echo $hours." hours";
									}else if(($diff > 60)){
										echo $minuts." minutes";
									}else {
										echo "Online";
									}
						 
						 ?> </span>
                              </div>

                          </div>
                          <?php } }?>

                          <?php }else{?>
                          <li>NO User Found!</li>
                          <?php } ?>
                      </div>
                  </div>
              </div>

          </div>


          <script>
          function closed_list() {
              $('#maxmize_chates').css('display', 'block');
              $('#user_list_hide').css('display', 'none');
              $('#chatSection').css('display', 'none');
              $("#maxmize_chates").addClass("minimize-chatbox").removeClass("maximize-chatbox")
          }

          function open_chates() {
              $('#user_list_hide').css('display', 'block');
              $('#maxmize_chates').css('display', 'none');
              $("#maxmize_chates").addClass("maximize-chatbox").removeClass("minimize-chatbox")

          }

          $(document).on("click", ".remove-chat-window", function() {

              $(this).closest('.chatSection').remove();


          });
          $(function() {

              $(document).on("keypress", ".message", function(event) {

                  var keycode = (event.keyCode ? event.keyCode : event.which);
                  if (keycode == '13') {

                      document.execCommand('insertHTML', false, '<i/>');
                      $('#dumppy').scrollTop($('#dumppy')[0].scrollHeight);
                      sendTxtMessage($(this));
                      return false;
                  }

              });

              $(document).on("click", ".emoji-txt-icons", function() {

                  var icon = $(this).html();

                  var tbobj = $(this).closest(".input-group").find(".message");
                  tbobj.html(tbobj.html() + " " + icon + " &nbsp;");

                  let range = document.createRange()
                  let sel = window.getSelection()
                  let smile = document.getElementByClass('.tb-emoji-icon')
                  range.setStartAfter(smile.childNodes[smile.childNodes.length - 1])
                  range.collapse(true)
                  sel.removeAllRanges()
                  sel.addRange(range)
                  $(".message").focus();
              });

              $('.btnSend').click(function() {
                  sendTxtMessage($('.message'));
              });


              $(document).ready(function() {

                  $('#chat-user-box').scrollTop($('#chat-user-box')[0].scrollHeight);

              });

              $(document).on("click", '.selectuser', function() {


                  var clnobj = $('#chatSection').clone();

                  var totchat = parseInt($(".chatSection").length);
                  var receiver_id = $(this).attr('id');
                  if ($(".chat-usr-" + receiver_id).length > 0) {

                      $(".chat-usr-" + receiver_id).remove();
                  }

                  if (totchat > 3) {

                      var ind = 1;
                      $(".chatSection").each(function() {

                          if (ind > 3) {
                              //$(this).addClass("minimize-chat-section");
                          }
                          ind++;
                      });

                  }

                  clnobj.addClass("chat-usr-" + receiver_id);
                  clnobj.removeAttr("style");
                  clnobj.removeAttr("id");
                  clnobj.find(".user-conv-area").attr("id", "chat-sect-" + receiver_id);
                  clnobj.find(".remove-chat-window").attr("data-chatno", receiver_id)
                  clnobj.find(".user-image").attr("src", $(this).find("img").attr("src"));
                  clnobj.find('.ReciverId_txt').val(receiver_id);
                  clnobj.find('.ReciverName_txt').html($(this).attr('title'));

                  $(this).find(".total-unread-msg").removeClass("badge-success").text('');
                  $("#all-chats-area").prepend(clnobj);

                  GetChatHistory(receiver_id, "1");
                  $('#dumppy').scrollTop($('#dumppy')[0].scrollHeight);

              });
              $(document).on("change", '.upload_attachmentfile', function() {

                  DisplayMessage(
                      '<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
                  ScrollDown();

                  var file_data = $(this).prop('files')[0];
                  var receiver_id = $(this).closest(".input-group").find(".ReciverId_txt").val();
                  var form_data = new FormData();
                  form_data.append('attachmentfile', file_data);
                  form_data.append('type', 'Attachment');
                  form_data.append('receiver_id', receiver_id);

                  $.ajax({
                      url: '<?php echo base_url(); ?>ChatController/send_text_message',
                      dataType: 'json',
                      cache: false,
                      contentType: false,
                      processData: false,
                      data: form_data,
                      type: 'post',
                      success: function(response) {

                          $(this).val('');
                          GetChatHistory(receiver_id, "0");
                      },
                      error: function(jqXHR, status, err) {

                      }
                  });

              });
              $('.ClearChat').click(function() {
                  var receiver_id = $('#ReciverId_txt').val();
                  $.ajax({
                      //dataType : "json",
                      url: 'chat-clear?receiver_id=' + receiver_id,
                      success: function(data) {
                          GetChatHistory(receiver_id, "0");
                      },
                      error: function(jqXHR, status, err) {
                          // alert('Local error callback');
                      }
                  });

              });



          }); ///end of jquery

          function ViewAttachment(message_id) {}

          function ViewAttachmentImage(image_url, imageTitle) {
              $('#modelTitle').html(imageTitle);
              $('#modalImgs').attr('src', image_url);
              $('#myModalImg').modal('show');
          }

          function ScrollDown() {
              var elmnt = document.getElementById("content");
              var h = elmnt.scrollHeight;
              $('#content').animate({
                  scrollTop: h
              }, 1000);
          }
          window.onload = ScrollDown();

          function DisplayMessage(message) {
              var Sender_Name = $('#Sender_Name').val();
              var Sender_ProfilePic = $('#Sender_ProfilePic').val();

              var str = '<div class="direct-chat-msg right">';
              str += '<div class="direct-chat-info clearfix">';
              str += '<span class="direct-chat-name pull-right">' + Sender_Name;
              str += '</span><span class="direct-chat-timestamp pull-left"></span>'; //23 Jan 2:05 pm
              str += '</div><img class="direct-chat-img" src="' + Sender_ProfilePic + '" alt="">';
              str += '<div class="direct-chat-text">' + message;
              str += '</div></div>';
              $('#dumppy').append(str);
          }

          function getrv() {
              if ($(".ReciverId_txt").length == 0) {

                  return false;
              }

              var rcvr = "";
              $(".ReciverId_txt").each(function() {

                  rcvr += $(this).val();
              });
          }

          function sendTxtMessage(tbobj) {

              var messageTxt = tbobj.val().trim();

              tbobj.val('');
              if (messageTxt != '') {

                  //console.log(message);
                  DisplayMessage(messageTxt);

                  var receiver_id = tbobj.closest(".input-group").find(".ReciverId_txt").val();

                  $.ajax({
                      dataType: "json",
                      type: 'post',
                      data: {
                          messageTxt: messageTxt,
                          receiver_id: receiver_id
                      },
                      url: '<?php echo base_url();?>ChatController/send_text_message',
                      success: function(data) {
                          GetChatHistory(receiver_id, "0")
                      },
                      error: function(jqXHR, status, err) {
                          // alert('Local error callback');
                      }
                  });


                  tbobj.closest('.user-conv-area').scrollTop($(this).closest('.user-conv-area')[0].scrollHeight);
                  //ScrollDown();
                  $(tbobj).val('');
                  $(tbobj).focus();
              } else {
                  $(tbobj).focus();
              }
          }

          function GetChatHistory(receiver_id, othr) {

              var field = "";
              if (othr == "1") {

                  field = "&action=read";
              }

              $.ajax({
                  //dataType : "json",
                  url: '<?php echo base_url();?>ChatController/get_chat_history_by_vendor?receiver_id=' +
                      receiver_id + field,
                  success: function(data) {
                      var jdata = JSON.parse(data);

                      jQuery.each(jdata, function(ind, val) {

                          //htmcnt += makehtml(val);
                          $("#chat-sect-" + ind).html(val.data);
                      });



                      //$('#dumppy').html(htmcnt);
                      //ScrollDown();	 
                      $('.user-conv-area').scrollTop($('.user-conv-area')[0].scrollHeight);
                  },
                  error: function(jqXHR, status, err) {
                      // alert('Local error callback');
                  }
              });

          }

          function makehtml(user) {

              if (user.type == "1") {

                  var cls = "justify-content-start";
                  var cntcls = "msg_cotainer";
              } else {

                  var cls = "justify-content-end";
                  var cntcls = "msg_cotainer_send";

              }

              var imgcnt = '<div class="img_cont_msg">' +
                  '<img src="' + user.photo + '" class="rounded-circle user_img_msg">' +
                  '</div>';

              var msgcnt = '<div class="' + cntcls + '">' + user.msgbody + '<span class="msg_time">' + user.msgdate +
                  '</span></div>';

              if (user.type = "1") {

                  htmlcnt = '<div class="d-flex ' + cls + ' mb-4">' + imgcnt + msgcnt + "</div>";

              } else {

                  htmlcnt = '<div class="d-flex ' + cls + ' mb-4">' + msgcnt + imgcnt + "</div>";
              }



          }



          function getloginuser(cont) {
              $.ajax({
                  //dataType : "json",
                  url: '<?php echo base_url();?>ChatController/getloguser',
                  success: function(data) {
                      var jresp = JSON.parse(data);
                      if (parseInt(jresp.total) > 0) {

                          $.each(jresp.users, function(ind, jarr) {

                              $("#" + ind).find(".prev-login-user").text(jarr.lastlog);
                              $("#" + ind).find(".user-status-point").addClass("log-sts-" + jarr
                                  .color);

                              if (parseInt(jarr.unread) > 0) {

                                  if ($("#chat-sect-" + ind).length > 0) {

                                      setTimeout(function() {
                                          GetChatHistory(ind, "1");
                                      }, 10000);

                                  }

                                  $("#" + ind).find(".total-unread-msg").addClass(
                                      "badge badge-success").text(jarr.unread + " New");
                              } else {
                                  $("#" + ind).find(".total-unread-msg").removeClass(
                                      "badge badge-success").text('');
                              }

                          });

                      }

                      //$("#chat-user-box").html(data);

                      //ScrollDown();	 
                  },
                  error: function(jqXHR, status, err) {
                      // alert('Local error callback');
                  }
              });


          }

          function getunread() {
              $.ajax({
                  url: '<?php echo base_url();?>ChatController/getunread',
                  success: function(data) {
                      var jresp = JSON.parse(data);
                      console.log(jresp);
                      $("#totat-chat-unread").html(jresp.total);

                      if (jresp.total > 0) {

                          $.each(jresp.users, function(ind, msg) {

                              $("#" + ind).find(".total-unread-msg").addClass("badge badge-success")
                                  .text(msg + " New");
                                  $("#" + ind).prependTo("#chat-user-box");  
                          });
                      }else if(jresp.redirect){
                        window.location.href = "<?=base_url();?>";
                      }

                  },
                  error: function(jqXHR, status, err) {}
              });
          }

          $(document).ready(function() {

              getunread();

          });
          $(document).on("click", ".minimize-chat-section", function() {


              $(this).removeClass(".minimize-chat-section");
          });
          var cont = 0;
          setInterval(function() {

              if ($("#maxmize_chates").hasClass("minimize-chatbox")) {

                  getunread();
              } else {
                    getunread();
                  getloginuser(cont);
              }
          }, 10000);




          setInterval(function() {
              //var receiver_id = $('#ReciverId_txt').val();

              if ($('.ReciverId_txt').length > 0) {

                  var rcvrid = "";
                  $('.ReciverId_txt').each(function() {

                      if ($(this).val() != "")
                          rcvrid += $(this).val() + ",";

                  });
                  if (rcvrid != '') {
                      GetChatHistory(rcvrid, "0");
                  }

              }

		  }, 10000);
		  
		  $("input[name='internal-chat']").on('keyup',function(){						
				var text = $(this).val();				
				// Hide all content class element
				$('.selectuser').hide();
				// Search 
				$('.selectuser:contains("'+text+'")').closest('.selectuser').show();
				});
				$.expr[":"].contains = $.expr.createPseudo(function(arg) {
					return function( elem ) {
					return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
					};
				});					
				$( document ).ajaxComplete(function() {					
					imageviewer('.attachmentImgCls');		// library function to view image in pop up
				});
          </script>

      </section>
  </div>
  