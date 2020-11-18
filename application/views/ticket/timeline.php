<link rel="stylesheet" type="text/css" href="<?=base_url().'assets/css/activity_timeline.css'?>">
<span class="toogle-timeline badge badge-danger pull-right" data-vis="1"><i class="fa fa-caret-right"></i></span>
<div class="timeline col-md-3 col-height">
	<h3 class="text-center">Activity Timeline</h3>
  <hr>
  	<ul class="cbp_tmtimeline" style="margin-left:-30px;">
      <?php
      
	  if(!empty($conversion)){		
      foreach($conversion as $cnv){
        $ticketId=$cnv->tck_id;
        $subj=$cnv->subj;
        ?>
        <li>
          <div class="cbp_tmicon cbp_tmicon-phone" style="background:#cb4335;"></div>
         
          <div class="cbp_tmlabel"  style="background:#95a5a6;"  <?php 
          if( $subj=='Send Whatsapp' OR $subj =='Send Mail' OR $subj  =='Send SMS'){ ?> onclick="getTimelinestatus('<?= trim($cnv->id) ?>');" data-toggle="modal"  data-target="#timelineshow" data-toggle="modal" <?php } ?> >
            <span style="font-weight:900;font-size:15px;"><?php echo $cnv->subj; ?></span>
            <?php
            if (!empty($cnv->lead_stage_name)) { ?>
              <br><span style="font-weight:900;font-size:12px;">Stage - </span>  <span style="font-weight:900;font-size:12px;"><?php echo $cnv->lead_stage_name; ?></span>
              <?php
            }

            if (!empty($cnv->sub_stage)) { ?>
              <br><span style="font-weight:900;font-size:12px;">Sub Stage - </span><span style="font-weight:900;font-size:12px;"><?php echo $cnv->sub_stage; ?></span>
              <?php
            }
             if (!empty($cnv->status_name)) { ?>
              <br><span style="font-weight:900;font-size:12px;">Status - </span><span style="font-weight:900;font-size:12px;"><?php echo $cnv->status_name; ?></span><br>
              <?php
            }
            if (!empty($cnv->msg)) { ?>
            <br><span style="font-weight:900;font-size:12px;">Remark - </span><span style="font-weight:900;font-size:12px;"><?php echo $cnv->msg; ?></span>               
              <?php
            }

            if (!empty($cnv->updated_by)) { ?>
              <br><span style="font-weight:100;font-size:12px;">By - </span><span style="font-weight:100;font-size:12px;"><?php echo $cnv->updated_by; ?></span><br>
              <?php
            }
            
             if (!empty($cnv->assignedTo)) { ?>
              <br><span style="font-weight:100;font-size:12px;">To - </span><span style="font-weight:100;font-size:12px;"><?php echo $cnv->assignedTo; ?></span><br>
              <?php
            } ?>
            <p style="font-size: 13px;"> Updated: <?php echo date("j-M-Y h:i:s A",strtotime($cnv->send_date)); ?><br>
           </p>
          </div>
        </li>
        <?php } 
      }
      ?>              
    </ul>
</div>


 		</div>
	</div>
</div>




<div id="sendsms" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <?php echo form_open_multipart('message/send_sms','class="form-inner" id="whatsaap"') ?>
      <div class="modal-content card">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="modal-titlesms"></h4>
         </div>
         <div>
            <div class="form-group col-sm-12">
               <label>Template</label>
               <select class="form-control" name="templates" required id="templates"  onchange="getMessage(),this.form.reset();">
               </select>
            </div>
            <div class="form-group col-sm-12">
               <label><?php echo display('subject') ?></label>
               <input type="text" name="email_subject" class="form-control" id="email_subject">
               <label><?php echo display('message') ?></label>
               <textarea class="form-control summernote" name="message_name"  rows="10" id="template_message"></textarea>  
            </div>
         </div>
         <div class="col-md-12">
            <input type="hidden"  id="mesge_type" name="mesge_type">
            <input type="hidden" name="ticketId" value="<?= $ticketId ?>">
            <input type="hidden"  name="msg_from"  value="ticket">
            <input type="hidden" id="mobile" name="mobile" value="<?php if(!empty($ticket->phone)){echo $ticket->phone;} ?>">
            <input type="hidden" id="mail" name="mail" value="<?php if(!empty($ticket->email)){echo $ticket->email;} ?>">
            <button class="btn btn-primary" onclick="send_sms()" type="button">Send</button>            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
      </form>
   </div>
</div>
<?php 
// if( $subj=='Send Whatsapp' OR $subj =='Send Mail' OR $subj  =='Send SMS'){
?>
<div id="timelineshow" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content card">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="timlineTitle"></h4>
         </div>
         <div class="row" style="padding: 30px;">
         <div id="timeline-tempname"></div>
         <div id="timelinesdata">
         </div>
         </div>
         <div class="modal-footer">
           <div class="row">
             <div class="col-md-4">
             <h4  id="timeline-cratedate"></h4>
             </div>
             <div class="col-md-4">
             </div>
             <div class="col-md-4">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             </div>
            
           </div>
         </div>
      </div>
      </form>
   </div>
</div>

<script type="text/javascript">
	function getTemplates(SMS,type){       
    if(type != 'Send Email'){
      $("#email_subject").hide();
      $("#email_subject").prev().hide();
      $("#template_message").summernote('code','');
      $("#template_message").summernote('destroy');
      //alert("empt");
    }else{
      $("#template_message").html('');
      $("#template_message").summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false                 // set focus to editable area after initializing summernote
      });
      $("#email_subject").show();
      $("#email_subject").prev().show();
    }
   $.ajax({
   type: 'POST',
   url: '<?php echo base_url();?>message/get_templates/'+SMS,
   })
   .done(function(data){
       $('#modal-titlesms').html(type);
       $('#mesge_type').val(SMS);       
       $('#templates').html(data);
   })
   .fail(function() {
   alert( "fail!" );   
   });
   } 
   function  send_sms(){   
     var sms_type = $("#mesge_type").val();
      if ("<?=$this->session->companey_id?>" == 81 && sms_type!=1) {
        url =  '<?php echo base_url();?>message/send_sms_career_ex';
      }else{
       url =  '<?php echo base_url();?>message/send_sms';
      }    
       $.ajax({            
           type: 'POST',
           url: url,
           data: $('#whatsaap').serialize()
           })
           .done(function(data){               
              //console.log(data);
               alert(data);
               location.reload();
           })
           .fail(function() {
           alert( "fail!" );       
       });     
       }

    function getMessage(){           
     var tmpl_id = document.getElementById('templates').value;           
     $.ajax({               
         url : '<?php echo base_url('enquiry/msg_templates') ?>',
         type: 'POST',
         data: {tmpl_id:tmpl_id},
         success:function(data){
             var obj = JSON.parse(data);

              // $('#templates option[value='+tmpl_id+']').attr("selected", "selected");
              // $(".summernote").summernote("code", obj.template_content);
              // $("#email_subject").val(obj.mail_subject);


               $("#template_message").summernote('destroy');
                
              if($("#email_subject").is(':visible'))
              {
                   $("#template_message").summernote("code", obj.template_content);
                   $("#email_subject").val(obj.mail_subject);
              }
              else
              {
                $("#template_message").html(obj.template_content);
              }
              

             //$("#template_message").html(obj.template_content);
         }               
     });        
    } 
     function find_description() { 
        var l_stage = $("#lead_stage_change").val();
        $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>lead/select_des_by_stage',
        data: {lead_stage:l_stage},            
        success:function(data){
            var html='';
            var obj = JSON.parse(data);                
            html +='<option value="">---Select---</option>';            
            for(var i=0; i <(obj.length); i++){                    
              html +='<option value="'+(obj[i].id)+'">'+(obj[i].description)+'</option>';
            }                
            $("#lead_description").html(html);                
        }
        });
       }

       function getTimelinestatus(timelineId){     
        $('#timlineTitle').empty();      
        $('#timelinesdata').empty(); 
        $('#timeline-cratedate').empty(); 
        $('#timeline-tembname').empty(); 
             
     $.ajax({               
         url : '<?php echo base_url('enquiry/timelinePopup') ?>',
         type: 'POST',
         data: {timelineId:timelineId},
         success:function(data){
          var obj = JSON.parse(data);
          
             $("#timlineTitle").append(obj.subject);
             $("#timelinesdata").append(obj.msg);
             $("#timeline-cratedate").append(obj.created_at);
             $("#timeline-tembname").append(obj.tempname);

         }               
     });        
    }     

$(".toogle-timeline").click(function(){
    if($(this).data('vis')=='1')
      { 
        $(".timeline").hide(500,function(){
            $(".details-column").removeClass('col-md-6');
            $(".details-column").addClass('col-md-9');
        });
        $(this).data('vis','0');
        $(this).find('i').removeClass('fa-caret-right');
        $(this).find('i').addClass('fa-caret-left');
      }
    else
    {
        $(".timeline").show(500);
        $(this).data('vis','1');
        $(".details-column").addClass('col-md-6');
        $(".details-column").removeClass('col-md-9');
        $(this).find('i').addClass('fa-caret-right');
        $(this).find('i').removeClass('fa-caret-left');
    }
    //setTimeout(manageScroll,1000);
});
</script>

<style type="text/css">
  .toogle-timeline{
    position: absolute;
    right: 5px;
  }
</style>