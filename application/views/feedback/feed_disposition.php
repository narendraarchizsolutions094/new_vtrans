<script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />
<div class="row">   
      <div  class="panel panel-default thumbnail">
         <div class="panel-heading no-print">
            <div class="btn-group"> 
                <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
            </div>
         </div>
         <div class="panel-body">
            <div class="col-md-3 col-height">          
              <h5 style="text-align:center"> 
                <br>
                <?php
                echo 'FTL No - '.$ftlfeed->tracking_no;
                ?>
              <br>
            <?php
             if(!empty($ftlfeed->client)){ ?>
               <a href="<?php if(!empty($enquiry->enquiry_id)){ echo base_url().'client/view/'.$enquiry->enquiry_id;}?>"><?php  (!empty($enquiry->name)) ? '<br>'.ucwords($enquiry->name_prefix." ".$enquiry->name." ".$enquiry->lastname).'<br>' : ""; ?></a>
              <?php 
              if(!empty($enquiry->gender))
              {
                if($enquiry->gender == 1) {
                 echo 'Male<br>'; 
                }else if($enquiry->gender == 2){
                  echo 'Female<br>';
                }else if($enquiry->gender == 3){
                  echo 'Other<br>';
                } 
              }
             } 
             
            
              $p = (!empty($ftlfeed->phone)) ? $ftlfeed->phone : '';
              if (user_access(450)) {
                $p = '##########';
              }
            ?>
            <a href='javascript:void(0)' onclick='send_parameters("<?php if(!empty($ftlfeed->phone)) {echo $ftlfeed->phone;} ?>")'><?php echo $p ?></a>
            <br><?php if(!empty($ftlfeed->email)) { echo $ftlfeed->email; }             
            ?>            
         </h5>
         <div class="row text-center">
            <?php
            if(user_access('314'))
            {
            ?>
              <a class="btn btn-primary btn-sm"  data-toggle="modal" type="button" title="Send SMS" data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('2','Send SMS')">
                <i class="fa fa-paper-plane-o"></i>
              </a>
              <?php
            }
            if(user_access('316'))
              {
                ?>
              <a class="btn btn-info btn-sm"  data-toggle="modal" type="button" title="Send Email" data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('3','Send Email')">
                <i class="fa fa-envelope"></i>
              </a>
              <?php
            }
            if(user_access('315'))
            {
            ?>
              <a class="btn btn-primary btn-sm"  data-toggle="modal" type="button" title="Send Whatsapp" data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('1','Send Whatsapp')">
                <i class="fa fa-whatsapp"></i>
              </a>
              <?php
            }
            ?>
            <select name='quick_feed_status' class="btn btn-success btn-sm quick_btn fa fa-ticket"  type="button" title="Change Status">                       
            <?php
              if(!empty($feed_status))
              {
                ?>
                <option value=''>- Select -</option>
                <?php
                foreach($feed_status as $status)
                {  
                  ?>                              
                  <option value="<?=$status->id?>" <?=($status->id==$ftlfeed->status?'selected':'')?>><?php echo $status->status_name; ?></option>
                  <?php 
                }
              }?>
            </select>
         </div>
              <button class="btn btn-basic" type="button" style="width: 100%; margin-top: 5px;margin-bottom: 5px;">Disposition</button>
            
              <div id="disposition-section" class="mobile-hide">
                <div class="row" > 
                   <?php echo form_open_multipart('ticket/ftl_disposition/'.$ftlfeed->fdbk_id,array('id'=>'ticket_disposition_form','class'=>'form-inner')) ?>                     
                   <input type="hidden" name="client" value="<?php if(!empty($enquiry->enquiry_id)) { echo $enquiry->enquiry_id;}?>">
                   <input type="hidden" name="ticketno" value="<?=$ftlfeed->tracking_no?>">                   

                    <div class="form-group">                 
                      <select class="form-control" id="lead_stage_change" name="lead_stage" onchange="find_description()">
                        <option>---Select Stage---</option>
                        <?php
                          if(!empty($feed_stages))
                          {
                            foreach($feed_stages as $single)
                            {  
                              ?>                              
                              <option value="<?=$single->stg_id?>" <?php if($single->stg_id==$ftlfeed->ftl_stage){ echo 'selected';} ?>><?php echo $single->lead_stage_name; ?></option>
                              <?php 
                            }
                          }
                           ?>
                       </select>
                    </div>
                    <div class="form-group">                           
                       <select class="form-control" id="lead_description" name="lead_description">
                           <option value=''>---Select Description---</option>
                        
                       </select>
                    </div>     

                    

                    <div class="form-group">
                       <input type="text" name="c_date" id='disposition_c_date' class="form-control form-date" placeholder=""  >
                    </div>
                    <div class="form-group">
                        <input type="time" name="c_time" id='disposition_c_time' class="form-control" placeholder=""  >
                        <input type="hidden" name="dis_notification_id" >
                    </div>          
                    <div class="form-group">
                      <textarea class="form-control" name="conversation"></textarea>
                    </div>


                  <div style="display:none;">                 
                    <input type="text" value="<?php if(!empty($ftlfeed->phone)){echo $ftlfeed->phone;} ?>" name="mobile">
                 </div>
                 
                 <div style="display:none;">                 
                    <input type="text" value="<?php if(!empty($ftlfeed->email)){echo $ftlfeed->email;} ?>" name="email">
                 </div>


                    <div class="form-group">                           
                       <select class="form-control" id="" name="feedbk_status">
                           <option value='0'>---Select Status---</option>
                          <?php
                           if(!empty($feed_status))
                          {
                            foreach($feed_status as $status)
                            {  
                              ?>                              
                              <option value="<?=$status->id?>" <?=($status->id==$ftlfeed->status?'selected':'')?>><?php echo $status->status_name; ?></option>
                              <?php 
                            }
                          }?>
                       </select>
                    </div> 
                    <?php
                    if(user_access('319'))
                    {?>
                    <div class="form-group">
                      <label for="mail_alert"> <input id="mail_alert" type="checkbox" name="mail_alert"> Notify with mail</label>
                    </div>
                    <?php
                    }
                    ?>
                   <div class="sgnbtnmn form-group text-center">
                      <div class="sgnbtn">
                         <input id="ticket_disposition_save" type="button" value="Submit" class="btn btn-primary"  name="Submit">
                      </div>
                   </div>       
                   <?php echo form_close()?>
                </div>         
              </div>
            </div>
<script type="text/javascript">
  $("#ticket_disposition_save").on('click',function(e){
    //alert('name');
    e.preventDefault();
    if("<?=$this->session->companey_id?>"==65 && $("#lead_description").val() == ''){
      alert('Please select sub stage.');
      return;
    }
    var disposition = getSelectedText('lead_stage_change');
    var name = $("#ticket_holder").val();
    var time  =   $("#disposition_c_time").val();      
    var task_date =   $("#disposition_c_date").val();            
    var uid = "<?=$this->session->user_id?>"; 
    var msg = disposition+" : "+name;
    var ticketno = $("input[name=ticketno]").val();
    var response_id = writeUserData(uid,msg,ticketno,task_date,time);
    $("input[name=dis_notification_id]").val(response_id);
    if("<?=$this->session->companey_id?>" == 82 && "<?=!empty($this->session->call_parameters['phone'])?>"){
      var phone             =   "<?=$this->session->call_parameters['phone']??''?>";
      var campaignId        =   "<?=$this->session->call_parameters['campaignId']??''?>";
      var crtObjectId       =   "<?=$this->session->call_parameters['crtObjectId']??''?>";
      var userCrtObjectId   =   "<?=$this->session->call_parameters['userCrtObjectId']??''?>";
      var userId            =   "<?=$this->session->call_parameters['userId']??''?>";
      var customerId        =   "<?=$this->session->call_parameters['customerId']??''?>";
      var sessionId         =   "<?=$this->session->call_parameters['sessionId']??''?>";
      var disposition       =   $("#lead_stage_change option:selected").text();
      //alert(phone);
      u = 'https://emergems.ameyo.net:8443/dacx/dispose?phone='+phone+'&campaignId='+campaignId+'&crtObjectId='+crtObjectId+'&userCrtObjectId='+userCrtObjectId+'&customerId='+customerId+'&dispositionCode='+disposition+'&sessionId='+sessionId;
      
      $.ajax({
        url:u,
        type:'get',
        success:function(q){                               
          alert('url '+u+" response "+q);
        }
      });
      
    }else{
      $("#ticket_disposition_form").submit();
    }
  });

  <?php
if(!empty($ftlfeed->ftl_stage)){
  if(!empty($ftlfeed->ftl_substage)){
    echo'$("select[name=lead_description]").load("'.base_url('message/find_substage/').$ftlfeed->ftl_stage.'/'.$ftlfeed->ftl_substage.'");';
  }else{
    echo'$("select[name=lead_description]").load("'.base_url('message/find_substage/').$ftlfeed->ftl_stage.'");';

  }
}
?>
</script>
			
<script>
  $("select[name='feedbk_status'],select[name='quick_feed_status']").on('change',function(e){  
    if("<?=$ftlfeed->current_status?>" == 3){
      alert('Can not change. Feedback is closed');
      $(this).val("<?=$ftlfeed->current_status?>");
    }else{
      if(confirm('Are you sure ?')){
        has_close_authority(false,$(this));
      }else{
      $(this).val("<?=$ftlfeed->current_status?>");
    }  

    }

  });

  function has_close_authority(auto=true,e){
    if(e.val() == 3){
      var url = "<?=base_url().'ticket/has_close_authority/'.$ftlfeed->added_by?>";    
      $.ajax({
        url: url,
        type: 'POST',
        success: function(result) {
          if(result == 0){
            $("#ticket_disposition_save").attr('disabled',true);
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'You can not close this ticket!'              
            }).then((result) => {
              location.reload();
            });
          }else{
            if(!auto){
              change_feedbk_status(e);
            }
            if("<?=$ftlfeed->current_status?>" !=3 ){
              $("#ticket_disposition_save").removeAttr('disabled');
            }
          }
        }
      });
    }else{
      if(!auto){
        change_feedbk_status(e);
      }
      if("<?=$ftlfeed->current_status?>" !=3 ){
        $("#ticket_disposition_save").removeAttr('disabled');
      }
    }
  }


  
  if(<?=$ftlfeed->current_status?>==3){
    $("#ticket_disposition_save").attr('disabled',true);
  }
  has_close_authority(true,$("select[name='feedbk_status']"));

  function change_feedbk_status(e){
    var status = e.val();    
    if(status){
      var url = "<?=base_url().'ticket/change_feedback_status/'.$ftlfeed->fdbk_id?>";    
      $.ajax({
        url: url,
        type: 'POST',
        data:{
          feedbk_status:status,
          ticketno:"<?=$ftlfeed->tracking_no?>"
        },
        beforeSend:function(){
          Swal.fire({
              title:'Please Wait..',
              imageUrl:'https://mir-s3-cdn-cf.behance.net/project_modules/disp/35771931234507.564a1d2403b3a.gif',
              imageWidth: 110,
              imageHeight: 110,
              showConfirmButton:false,
              allowEscapeKey : false,
              allowOutsideClick: false

          });
        },
        success: function(result) {
            Swal.close();
          $("select[name='feedbk_status']").val(status);
          $("select[name='quick_feed_status']").val(status);
          //alert(status);
          if(status=='3')
            var msg = 'Feedback Closed Successfully.';
          else 
            var msg = 'Feedback Changed Successfully!';
          Swal.fire(
            'Good job!',
            msg,
            'success'
          ).then((result)=>{
            if(e.hasClass('quick_btn')){
              location.reload();
            }
          });
        }
      });
    }

  }
</script>