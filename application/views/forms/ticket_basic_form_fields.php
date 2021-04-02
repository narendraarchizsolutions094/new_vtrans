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
  // define('PIN_CODE',14); 
  // define('SUB_SOURCE',15);  
echo'<div class="trackingDetails"></div>';
        if(!empty($company_list)){
          foreach($company_list as $companylist)
          {

                if($companylist['field_id']==COMPLAINT_TYPE){?>
                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Complaint Type</label>
                        <div>             
                          <input type="radio" name="complaint_type" value="1" checked> <label>Is Complaint</label>
                          <input type="radio" name="complaint_type" value="2"> <label>Is Query</label>
                        </div>
                      </div>
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
                            <select class="form-control" name="referred_by">
                              <?php
                              if(!empty($referred_type))
                              {
                                foreach ($referred_type as $res)
                                {
                                  echo'<option value="'.$res->id.'">'.$res->name.'</option>';
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
                    $("input[name=complaint_type]").on('change',function(){
                        var x = $("input[name=complaint_type]:checked").val();
                        if(x=='1')
                        {
                          $('input[name=tracking_no]').attr("required","required");
                          $('select[name=relatedto]').attr("required","required");
                          $(".opt").show();
                        }
                        else if(x=='2')
                        {
                          $('input[name=tracking_no]').removeAttr("required");
                          $('select[name=relatedto]').removeAttr("required");
                          $(".opt").hide();
                        }
                    });

                    if("<?=$this->session->process[0]?>"!=199){
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
                          tracking_no_check(that.value);
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
                        <label><?=display('tracking_no')?> <i class="text-danger opt">*</i>
                        <?php
                        if($this->session->companey_id==65){
                            if(is_array($this->session->process) && !empty($this->session->process[0]) && $this->session->process[0] == 141){
                            ?>                            
                             <a href='http://203.112.143.175/ecargont/' target="_blank" class='float-right'><u> Go To Ecargo </u></a>
                            <?php
                            }
                            if(is_array($this->session->process) && !empty($this->session->process[0]) && $this->session->process[0] == 198){
                              ?>                            
                               <a href='http://180.179.114.148/ecargovx/' target="_blank" class='float-right'><u> Go To Ecargo </u></a>
                              <?php
                              }
                        }                        
                        ?>
                        </label>
                        <input type="text" name="tracking_no" class="form-control" onblur="loadTracking(this),match_previous(this.value)" required>
                      </div>
                    </div>

                   <?php
                   }
                   ?>
                   <?php
                    if($companylist['field_id']==PROBLEM_FOR){
                    ?>
                     <div class="col-md-6" id='client_div'>
                      <div class="form-group">
                        <label><?=display('problem_for')?> </label>
                        <select class="form-control  choose-client" name = "client" id='client'>
                          <option value = "" style ="display:none;">---Select---</option>
                          <?php if(!empty($clients)){
                            foreach($clients as $ind => $clt){
                              $n = $clt->company_name;
                              if(!empty($n)){
                              ?><option value ="<?php echo $clt->enquiry_id ?>"><?php echo $n; ?> (<?= $clt->name ?>) </option><?php
                              }
                            }
                          } ?>
                        </select>
                        <i class="fa fa-plus" id='addmoreorg' onclick="add_more_org('add_more_org')" style="float:right;margin-top:-23px;margin-right:10px;color:red;position:relative;"></i>
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
                        <label>Name <i class="text-danger">*</i></label>
                        <input type = "text" class="form-control" name = "name" required>
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
                          <?php
                          $required = 'required';
                          if($this->session->companey_id == 90){
                            $required = ''; ?>
                            <label>Phone</label>
                            <?php
                          }else{
                            ?>
                            <label>Phone <i class="text-danger">*</i></label>
                            <?php
                          }
                          ?>
                          <input type = "text" class="form-control" name = "phone" <?=$required?> value="<?=!empty($_GET['phone'])?$_GET['phone']:''?>" onkeyup="autoFill('phone',this.value)"> 
                          <div id="is-avl-mobile"></div>
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
                        <input type = "text" class="form-control" name = "email" onblur="autoFill('email',this.value)">
                      </div>
                    </div>
                   
                     <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==PRODUCT){
                    ?>      
                    
                    <?php if($this->session->companey_id!=83){ ?>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Product</label>
                            <select class="form-control  chg-product" name = "product">
                              <?php if(!empty($product)) {
                                foreach($product as $ind => $prd){
                                  ?><option value ="<?php echo $prd->id ?>"><?php echo ucfirst($prd->country_name); ?> </option><?php
                                }
                              } ?>
                            </select>
                          </div>
                        </div>
                        <?php } ?>

                    <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==PROBLEM){
                    ?>      
                   
                    <div class="col-md-6">
                        <div class="form-group">
                          <label><?=display('ticket_problem')?><i class="text-danger opt">*</i></label>
                          <select class="form-control " name = "relatedto" required>
                          <option value = "">Select Subject</option>
                        <?php  if(!empty($problem)) {
                              foreach($problem as $ind => $prblm){
                                ?><option value = "<?php echo $prblm->id ?>"><?php echo ucfirst($prblm->subject_title) ?> </option><?php
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
                    <?php
                      if($this->session->companey_id!=65)
                      {
                      ?>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Nature of Complaint</label>
                          <select class="form-control" name = "issue">
                          <option value = ""> -- Select --</option>
                        <?php  if(!empty($issues)) {
                              foreach($issues as $ind => $issue){
                                ?><option value = "<?php echo $issue->id ?>"><?php echo ucfirst($issue->title) ?> </option><?php
                              } 
                            } ?>
                          </select>
                        </div>
                      </div>
                      <?php
                      }
                      ?>
                      <?php
                   }                   
                  if($companylist['field_id']==PRIORITY && $this->session->companey_id !=65){
                    ?>                                     
                    
                    <?php if($this->session->user_right!=214){ ?>
          
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Priority</label>
                        <select class="form-control " name = "priority">
                          <option value = "">Select</option>
                          <option value = "1">Low</option>
                          <option value = "2">Medium</option>
                          <option value = "3">High</option>
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
                        <select class="form-control " name = "source">
                          <?php
                           if(!empty($source)) {
                            foreach($source as $ind => $prblm){
                              if(!empty($_GET['phone']) && $prblm->lsid == 134 && $this->session->companey_id == 65){
                                $sel = 'selected';
                              }else{
                                $sel = '';
                              }
                              ?><option <?=$sel?> value = "<?php echo $prblm->lsid ?>"><?php echo $prblm->lead_name ?> </option><?php
                            } 
                          } ?>
                        </select>
                      </div>
                    </div>

                    <?php
                      }
                    }
                  if($companylist['field_id']==ATTACHMENT)
                  {
                   ?>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Attachment</label>
                      <input type="file" name="attachment[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple>
                    </div>
                  </div>

                  <?php
                  }
                  if($companylist['field_id']==DESCRIPTION)
                  {
                  ?>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?=display('ticket_remark')?></label>
                      <textarea name="remark" class="form-control"></textarea>
                    </div>
                  </div>  

                  <?php
                  }
           }
          }
          ?> 
          <script>
          $(document).ready(function () {
            $('select[class~="form-control"]').select2();        
          });
            function add_more_org(type='add_more_org'){
              $("#addmoreorg").hide();                            
              $("#client").val("").trigger('change');
              html = '<div class="col-md-6"><div class="form-group"><label>'+"<?='New '.display('problem_for')?>"+'</label><input type="text" name="client_new" class="form-control"></div></div>';
              $("#client_div").after(html);
              $("#client").attr('disabled',true);
              
            }
            </script>