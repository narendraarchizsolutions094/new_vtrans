<?php
  define('COMPLAINT_TYPE',15);
  define('REFERRED_BY',16);
  define('PROBLEM_FOR',17);
  define('NAME',18);
  define('PHONE',19);
  define('EMAIL',20);
  define('PRODUCT',21);
  define('PROBLEM',22);
  define('NATURE_OF_COMPLAINT',23);
  define('PRIORITY',24);
  define('SOURCE',25);  
  define('ATTACHMENT',26);  
  define('DESCRIPTION',27);
  define('TRACKING_NUMBER',28);
  define('BRANCH',52);
  // define('PIN_CODE',14); 
  // define('SUB_SOURCE',15);  
echo'
 <div class="trackingDetails"></div>
<div class="col-md-6" style="display: none;">
      <div class="form-group">
        <label>Complain Date</label>
        <input type = "text" class="form-control add-date-picker" name = "complaindate" value= "<?php echo date("m/d/Y", strtotime($ticket->coml_date)) ?>">
      </div>
    </div>';


        if(!empty($company_list)){
          foreach($company_list as $companylist)
          {

                if($companylist['field_id']==COMPLAINT_TYPE){?>
                    
                  <input type="hidden" name="complaint_type" value="<?=$ticket->complaint_type?>">
                  <div class="form-group">
                  <label>Ticket Type : </label> 
                  <span class='badge badge-info'><?=$ticket->complaint_type=='1'?'Complaint':($ticket->complaint_type=='2'?'Query':'NA')?></span>
                </div>
                      
                     <?php
                   }
                   ?>
                    <?php
                    if($companylist['field_id']==REFERRED_BY){
                    ?>
                   

                    <div class="col-md-6">
                    <div class="form-group">
                      <label>Referred By</label>
                      <select class="form-control add-select2 choose-client" name = "referred_by" required>
                        <?php 
                        if(!empty($referred_type))
                        {
                          foreach($referred_type as $ref)
                          {
                      echo "<option value =".$ref->id." ".($ref->id==$ticket->referred_by?'selected':'').">".$ref->name."</option>";

                          }
                        } 
                        ?>
                      </select>
                    </div>
                  </div>

                   <?php
                   }
                   ?>
                    <?php
                    if($companylist['field_id']==TRACKING_NUMBER){
                    ?>
                   
                   <script type="text/javascript">
                    if("<?=$ticket->process_id?>"!=199){
                                        
                    function loadTracking(that)
                      { //alert(key);
                        if(that.value=='')
                        {

                        }else{
                          
                          $.ajax({
                            url:'<?=base_url('ticket/view_tracking')?>',
                            type:'post',
                            data:{trackingno:that.value},
                            beforeSend:function(){

                              $(that).parents('form').find('input,select,button').attr('disabled','disabled');
                            },
                            success:function(q)
                            { $(that).parents('form').find('input,select,button').removeAttr('disabled');
                              if(q!='0')
                                $(".trackingDetails").html(q);
                              else
                              {
                                Swal.fire({
                                            title: 'GC. No. Not Found!',
                                            cancelButtonText: 'Ok!'
                                            });
                              }
                            },
                            error:function(u,v,w)
                            {
                              console.info(w);
                            }
                          });
                        }
                      }
                    }

                      function match_previous(tracking_no)
                      { 
                        if(tracking_no=='')
                        {

                        }else{
                          
                          $.ajax({
                            url:'<?=base_url('ticket/view_previous_ticket')?>',
                            type:'post',
                            data:{tracking_no:tracking_no},
                            beforeSend:function(){

                              
                            },
                            success:function(q)
                            { 
                              if(q!=0)
                              {
                                $("#old_ticket").show(500);
                                $(".old_ticket_data").html(q);
                              }
                              
                              //  $(that).parents('form').find('input,select,button').removeAttr('disabled');
                              //$(".trackingDetails").html(q);
                            },
                            error:function(u,v,w)
                            {
                              console.info(w);
                            }
                          });
                        }
                      }


                  </script>
          
              <div class="col-md-6">
                <div class="form-group">
                  <label><?=display('tracking_no')?> <i class="text-danger opt" style="display: none;">*</i>
                        <?php
                        if($ticket->process_id == 141){
                          ?>                            
                           <a href='http://203.112.143.175/ecargont/' target="_blank" class='float-right'><u> Go To Ecargo </u></a>
                          <?php
                          }
                          if($ticket->process_id == 198){
                            ?>                            
                             <a href='http://180.179.114.148/ecargovx/' target="_blank" class='float-right'><u> Go To Ecargo </u></a>
                            <?php
                            }
                        ?></label>
                  <input type="text" name="tracking_no" class="form-control" onblur="loadTracking(this)" value="<?php if(!empty($ticket->tracking_no)){ echo $ticket->tracking_no;} ?>">
                </div>
              </div>
              <?php
              if($ticket->process_id!=199){ ?>
                <script type="text/javascript">
                  $(document).ready(function(){
                    loadTracking($("input[name=tracking_no]").get(0));
                  });
                </script>
              <?php
              }
              ?>

              <?php if($ticket->complaint_type=='1'){
                  echo'<script type="text/javascript">
                  $(".opt").show();
                  $("input[name=tracking_no]").attr("required","required");
                  </script>';
                          }?>

                   <?php
                   }
                   ?>

                   <?php
                    if($companylist['field_id']==PROBLEM_FOR){
                    ?>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=display('problem_for')?></label>
                        <select class="form-control add-select2 choose-client" name = "client" required readonly>
                          <?php 
                          if(!empty($clients))
                          {
                            foreach($clients as $ind => $clt)
                            {                              
                              $n = $clt->company_name;
                              if(!empty($n)){
                                if($clt->enquiry_id==$ticket->client)
                                echo "<option value =".$clt->enquiry_id." selected>".$n."</option>";                                
                              }
                            }
                          } 
                          ?>
                        </select>
                      </div>
                    </div>
                   
                  <?php
                   } 
                   ?>
                   <?php
                    if($companylist['field_id']==NAME){
                    ?>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Name<span class="text-danger">*</span></label> 
                        <input type="text" name="name" id="ticket_holder" class="form-control" value="<?php if(!empty($ticket->name)){ echo $ticket->name;} ?>" required>
                      </div>
                    </div>
                   
                     <?php
                   }
                   ?>
                     <?php
                    if($companylist['field_id']==PHONE){
                    ?>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Phone<span class="text-danger">*</span></label>
                        <input maxlength="10" type="text" name="phone" class="form-control" value="<?php  if(!empty($ticket->phone)){ echo $ticket->phone; } ?>" required>                        
                      </div>
                    </div>


                     <?php
                   }
                   ?>
                   <?php
                    if($companylist['field_id']==EMAIL){
                    ?>
                    
                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" value="<?php if(!empty($ticket->tck_email)){ echo $ticket->tck_email;} ?>">
                    </div>
                  </div>
                   
                     <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==PRODUCT){
                    ?>      
                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Product</label>
                        <select name="product" class="form-control">
						<option value="">---Select Product---</option>
                        <?php
                        foreach ($product as $prd)
                        {
                          echo'<option value="'.$prd->id.'" '.($prd->id==$ticket->product?'selected':'').'>'.$prd->country_name.'</option>';
                        }
                        ?>
                        </select>
                        <!-- <input type="text" class="form-control" value="<?php if(!empty($ticket->country_name)){ echo $ticket->country_name;} ?>" > -->
                      </div>
                    </div>

                    <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==PROBLEM){
                    ?>      
                   
                    <div class="col-md-6">
                        <div class="form-group">
                          <label><?=display('ticket_problem')?></label>
                          <select class="form-control add-select2" name = "relatedto">
                          <option value = "">Select Subject</option>
                        <?php  if(!empty($problem)) {
                              foreach($problem as $ind => $prblm){
                                ?><option value = "<?php echo $prblm->id ?>" <?=$prblm->id==$ticket->category? 'selected':''?>><?php echo ucfirst($prblm->subject_title) ?> </option><?php
                              } 
                            } ?>
                          </select>
                        </div>
                      </div>
                         
                   
                    <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==NATURE_OF_COMPLAINT){
                    ?>                
                   
                     <div class="col-md-6">
                      <div class="form-group">
                        <label>Nature of Complaint</label>
                        <select class="form-control add-select2" name = "issue">
                        <option value = ""> -- Select --</option>
                        <?php  if(!empty($issues)) {
                            foreach($issues as $ind => $issue){
                              ?><option value = "<?php echo $issue->id ?>" <?php echo ($issue->id == $ticket->issue) ? "selected" : ""; ?> ><?php echo ucfirst($issue->title) ?> </option><?php
                            } 
                          } ?>
                        </select>
                      </div>
                    </div>
                     
                  <?php
                   }                   
                  if($companylist['field_id']==PRIORITY){
                    ?>                                     
                    
                    <?php if($this->session->user_right!=214){ ?>
                    <div class="col-md-6">
                    <div class="form-group">
                      <label>Priority</label>
                      <select class="form-control add-select2" name = "priority">

                        <option value = "">Select</option>
                        <option value = "1" <?php echo (1 == $ticket->priority) ? "selected" : ""; ?>>Low</option>
                    
                        <option value = "2" <?php echo (2 == $ticket->priority) ? "selected" : ""; ?>>Medium</option>
                        <option value = "3" <?php echo (3 == $ticket->priority) ? "selected" : ""; ?>>High</option>
                      </select>
                    </div>
                  </div>
                   
                     <?php 
                      }
                   }                    
                    if($companylist['field_id']==SOURCE)
                    {

                      if($this->session->user_right!=214)
                      {
                    ?>                
                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Source</label>
                        <select name="source" class="form-control">
						<option value="">---Select Source---</option>
                        <?php
                        foreach ($source as $sor)
                        {
                          echo'<option value="'.$sor->lsid.'" '.($sor->lsid==$ticket->sourse?'selected':'').'>'.$sor->lead_name.'</option>';
                        }
                        ?>
                        </select>
                        <!-- <input type="text" class="form-control" value="<?php if(!empty($ticket->ticket_source)){ echo ucwords($ticket->ticket_source);} ?>" > -->
                      </div>
                    </div>

                    <?php
                      }
                    }
                  if($companylist['field_id']==ATTACHMENT)
                  {
                   ?>

                   <div class="col-md-6">
      
                    <div class="form-group" >     
                      <label>Attachment <small> ( Only Image/PDF ) </small>:</label>
                      <?php
                      //$error="This file is not availible";
                      if($error =$this->session->flashdata('error'))
                      {
                        echo'<div class="alert alert-danger">'.$error.'</div>';
                      }
                      ?>
                      <?php
                      if($ticket->attachment)
                      {
                        $attachment  = json_decode($ticket->attachment);
                        echo'<ul class="list-group">';
                        $i=0;
                        if(!empty($attachment)){
                          foreach ($attachment as $at)
                          {
                            echo '<li class="list-group-item">'.$at.'
                            <div class="btn-group pull-right">
                            <a href="'.base_url('uploads/ticket/'.$at).'" target="_blank"><span class="btn btn-primary  btn-xs">View</span></a>
                            <a href="'.base_url('ticket/remove_attachment/'.$ticket->ticketno.'/'.$i).'"><span class="btn btn-danger  btn-xs">Delete</span></a>
                            </div>
                            </li>';
                            $i++;
                          }
                        }
                        echo'</ul><br>';
                      }
                      ?>
                      <input type="file" name="attachment[]" class="attachFiles" accept=".jpg,.jpeg,.png,.pdf" multiple>
                    </div>

                  </div>
                  <?php
                  }
				  
				  if($companylist['field_id']==BRANCH)
                {
                ?>	  
			<div class="form-group col-md-6">
                <label class="control-label" for="emp_branch">Branch</label> 									
                <select class="form-control add-select2" name="emp_branch" id="emp_brnh">
				<option value="">---Select Branch---</option>
                    <?php  if (!empty($branch_lists)) {
                    foreach ($branch_lists as $key => $value) {
                              $n = $value->branch_name;
                              if(!empty($n)){
								  
                                echo "<option value =".$value->branch_id." ".(($value->branch_id==$ticket->branch_for)?"selected":"").">".$n."</option>";
                                
                              }
                    }
                    } ?>
                </select>
            </div>
			<?php
                }
				
                  if($companylist['field_id']==DESCRIPTION)
                  {
                  ?>

                  
              <div class="col-md-12">
                <label><?=display('ticket_remark')?></label>
                <!-- <div style = "padding: 10px;border: 1px solid #e5e1e1;margin-right:25px;border-radius: 10px;font-size:16px;margin-bottom:10px;"><?php if(!empty($ticket->message)){ echo $ticket->message;} ?></div> -->
                <textarea name="remark" class="form-control"><?=$ticket->message?></textarea>
              </div>

                  <?php
                  }
           }
          }
          ?> 
<script>
  $(document).ready(function () {
    $('select[class~="add-select2"]').select2();        
  });