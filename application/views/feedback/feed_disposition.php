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
            <select name='quick_ticket_status' class="btn btn-success btn-sm quick_btn fa fa-ticket"  type="button" title="Change Status">                       
            <?php
              if(!empty($feed_status))
              {
                ?>
                <option value=''>- Select -</option>
                <?php
                foreach($feed_status as $status)
                {  
                  ?>                              
                  <option value="<?=$status->id?>" <?=($status->id==$ftlfeed->current_status?'selected':'')?>><?php echo $status->status_name; ?></option>
                  <?php 
                }
              }?>
            </select>
         </div>
              <button class="btn btn-basic" type="button" style="width: 100%; margin-top: 5px;margin-bottom: 5px;">Disposition</button>
            
              <div id="disposition-section" class="mobile-hide">
                <div class="row" > 
                   <?php echo form_open_multipart('ticket/ticket_disposition/'.$ftlfeed->fdbk_id,array('id'=>'ticket_disposition_form','class'=>'form-inner')) ?>                     
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
                              <option value="<?=$single->stg_id?>"><?php echo $single->lead_stage_name; ?></option>
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
                    <input type="text" value="<?php if(!empty($ticket->phone)){echo $ticket->phone;} ?>" name="mobile">
                 </div>
                 
                 <div style="display:none;">                 
                    <input type="text" value="<?php if(!empty($ticket->email)){echo $ticket->email;} ?>" name="email">
                 </div>


                    <div class="form-group">                           
                       <select class="form-control" id="" name="ticket_status">
                           <option value='0'>---Select Status---</option>
                          <?php
                           if(!empty($ticket_status))
                          {
                            foreach($ticket_status as $status)
                            {  
                              ?>                              
                              <option value="<?=$status->id?>" <?=($status->id==$ticket->ticket_status?'selected':'')?>><?php echo $status->status_name; ?></option>
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