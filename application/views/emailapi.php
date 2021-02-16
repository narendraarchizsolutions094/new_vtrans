<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
<link href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/c3/c3.min.css" rel="stylesheet" type="text/css" />
<div class="row">
   <!--  table area -->
   <div class="col-sm-12">
      <div class="panel panel-default thumbnail">
         <div class="col-md-12">
            <br>
            <?php if ($this->session->flashdata('SUCCESSMSG')) { ?>
               <div role="alert" class="alert alert-success">
                  <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                  <strong>Well done!</strong>
                  <?= $this->session->flashdata('SUCCESSMSG') ?>
               </div>
            <?php } ?>
            <br>
            <div class="btn-group col-md-1" role="group" aria-label="Button group" style="float: right">
               <button class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-check-circle"></i>&nbsp;
               </button>
               <div class="dropdown-menu" style="max-height: 400px; overflow: auto; position: absolute; will-change: transform; top: 26px; left: 0px; margin-left: -110px; transform: translate3d(0px, -7px, 0px);" x-placement="top-start">
                  <ul style="list-style-type:none;">
                     <li><label><input type="checkbox" onclick="hide_td('th0','tt0')" id="tt0" checked> S.No.</label></li>
                     <li><label><input type="checkbox" onclick="hide_td('th1','tt1')" id="tt1" checked> API Name</label></li>
                     <li><label><input type="checkbox" onclick="hide_td('th2','tt2')" id="tt2" checked> URL</label></li>
                  </ul>
               </div>
               <br>
            </div>
         </div>
         <br>
         <div class="panel-body">
            <div id="exTab3" class="">
               <ul class="nav nav-pills">
                  <li class="active">
                     <a href="#tab-integration" data-toggle="tab">Integration</a>
                  </li>
                  <li>
                     <a href="#tab-templates" data-toggle="tab">Templates</a>
                  </li>
               </ul>
               <div class="tab-content clearfix">
                  <div class="tab-pane active" id="tab-integration">
                     <?php if (user_access(58) == true) { ?>

                        <br>
                        <button class="btn btn-sm btn-success" style="float: left" type="button" data-toggle="modal" data-target="#createnewintegration"><i class="fa fa-plus"></i> Add New Integration</button>
                        <br>
                     <?php } ?>
                     <br>
                     <form action='' method="post" id="apitable">
                        <table width="100%" class="datatable1 table table-striped table-bordered table-hover">
                           <thead>
                              <tr>
                                 <th class="sorting_asc wid-20 th0" tabindex="0" rowspan="1" colspan="1"><input type='checkbox' class="checked_all" value="check all">&nbsp; <?php echo display('serial') ?></th>
                                 <th class="th1">Title</th>
                                 <th class="th2">SMTP HOST</th>
                                 <th class="th2">SMTP User</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if (!empty($api_list)) { ?>
                                 <?php $sl = 1; ?>
                                 <?php foreach ($api_list as $list) { ?>
                                    <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>" style="cursor:pointer;" data-toggle="modal" data-target="#createnewintegration<?php echo $list['id']; ?>">
                                       <td class="th0"><input type='checkbox' onclick="event.stopPropagation();" name='user_status[]' class="checkbox" value='<?php echo $list['id']; ?>'>&nbsp; <?php echo $sl; ?></td>
                                       <td class="th1"><?php echo $list['name']; ?></td>
                                       <td class="th2"><?php echo $list['smtp_host']; ?></td>
                                       <td class="th2"><?php echo $list['smtp_user']; ?></td>
                                    </tr>
                                    <?php $sl++; ?>
                                 <?php } ?>
                              <?php } ?>
                           </tbody>
                        </table>
                        <?php if (user_access(511) == true) { ?>

                           <button class="btn btn-danger" type="button" onclick="return delete_emailapi()">
                              <i class="ion-close-circled"></i>
                              Delete
                           </button>
                        <?php } ?>
                     </form>
                     <!--------------- EDIT API ------------->
                     <?php if (user_access(58) == true or user_access(59)) { ?>
                        <?php foreach ($api_list as $list) { ?>
                           <div id="createnewintegration<?php echo $list['id']; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                 <!-- Modal content-->
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                       <h4 class="modal-title">Send Email notifications to your client, enter details below to configure</h4>
                                    </div>
                                    <div class="modal-body">
                                       <!--<form>-->
                                       <?php echo form_open_multipart('emailapi/create_email_conf', 'class="form-inner"') ?>
                                       <input type="hidden" name="id" value="<?php echo $list['id']; ?>">
                                       <div class="row">
                                          <div class="form-group   col-sm-6">
                                             <label>Title*</label>
                                             <input class="form-control" name="title" value="<?php echo $list['name']; ?>" type="text" required="">
                                          </div>
                                          <div class="form-group col-sm-6">
                                             <label>Protocol*</label>
                                             <input class="form-control" name="protocol" value="<?php echo $list['protocol']; ?>" type="text" required="">
                                          </div>
                                          <div class="form-group col-sm-6">
                                             <label>SMTP HOST*</label>
                                             <input class="form-control" name="smtp_host" value="<?php echo $list['smtp_host']; ?>" type="text" required="">
                                          </div>

                                          <div class="form-group col-sm-6">
                                             <label>SMTP User*</label>
                                             <input class="form-control" name="smtp_user" value="<?php echo $list['smtp_user']; ?>" type="text" required="">
                                          </div>
                                          <div class="form-group col-sm-6">
                                             <label>SMTP Password*</label>
                                             <input class="form-control" name="smtp_password" value="<?php echo $list['smtp_pass']; ?>" type="text" required="">
                                          </div>
                                          <div class="form-group col-sm-6">
                                             <label>SMTP Port*</label>
                                             <input class="form-control" name="smtp_port" value="<?php echo $list['smtp_port']; ?>" type="text" required>
                                          </div>

                                       </div>
                                       <div class="col-12" style="padding: 0px;">
                                          <div class="row">
                                             <div class="col-12" style="text-align:center;">
                                                <button class="btn btn-success" type="submit">Save</button>
                                             </div>
                                          </div>
                                       </div>
                                       </form>
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!---------------------------------------------------------------->
                     <?php }
                     } ?>
                  </div>
                  <div class="tab-pane" id="tab-templates">
                     <br>
                     <button class="btn btn-sm btn-success" style="float: left" type="button" data-toggle="modal" data-target="#createnewtemplate"><i class="fa fa-plus"></i> Add New Template</button>
                     <br>
                     <br>
                     <form action='' method="post" id="temptable">
                        <table width="100%" class="datatable table table-striped table-bordered table-hover">
                           <thead>
                              <tr>
                                 <th class="sorting_asc wid-20 th0" tabindex="0" rowspan="1" colspan="1"><input type='checkbox' class="checked_alltemp" value="check all">&nbsp; <?php echo display('serial') ?></th>
                                 <th nowrap>Template Name</th>
                                 <th nowrap>Template Content</th>
                                 <th nowrap>Process   </th>
                                 <th nowrap>Stage   </th>
                                 <!--<th>Attachments</th>-->
                              </tr>
                           </thead>
                           <tbody>
                              <?php if (!empty($temp_list)) { ?>
                                 <?php $sl = 1; ?>
                                 <?php foreach ($temp_list as $tlist) { ?>
                                    <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>" style="cursor:pointer;" data-toggle="modal" data-target="#createnewtemplate<?php echo $tlist->temp_id; ?>">
                                       <td class="th0"><input type='checkbox' onclick="event.stopPropagation();" name='sel_temp[]' class="checkboxtemp" value='<?php echo $tlist->temp_id; ?>'>&nbsp; <?php echo $sl; ?></td>
                                       <td class="th1"><?php echo $tlist->template_name; ?></td>
                                       <td class="th2"><?php echo $tlist->template_content; ?></td>
                                       <td class="th3"><?php
                                         $stage = explode(',', $tlist->stage);
                                         $process = explode(',', $tlist->process); 
                                        foreach ($products as $product) { 
                                            if (in_array($product->sb_id, $process)) {
                                                                                             echo  $product->product_name,',';
                                                                                          } } ?></td>
                                                                                          
                                              <td class="th3">
                                              <?php  if (in_array('0', $stage)) { echo 'Ticket'.','; } ?></option>
                                                 <?php if (in_array('1', $stage)) {  echo display("enquiry").','; }?>
                                                     <?php  if (in_array('2', $stage)) { echo display("lead").','; } ?>
                                                     <?php  if (in_array('3', $stage)) { echo display("client").','; } ?></option>
                                                <?php
                                                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                                                if (!empty($enquiry_separation)) {
                                                   $enquiry_separation = json_decode($enquiry_separation, true);
                                                   foreach ($enquiry_separation as $key => $value) { 
                                                       if (in_array($key, $stage)) {?>
                                                       <?= $ctitle = $enquiry_separation[$key]['title'],',';  ?>
                                                                                  <?php  } } } ?></td>
                                                                                          
                                       <!--<td>
                                    <?php $temp = $tlist->temp_id;
                                    $attr  = $this->db->select('*')->from('mail_template_attachments')->where('templt_id', $temp)->get()->result();

                                    $i = 1;

                                    foreach ($attr  as $attach) { ?>
                                    
                                        
                                    
                                            <a href="<?= $attach->files ?>" target="_blank">View attachments <?= $i++ ?></a>
                                    
                                        
                                    
                                        <?php }
                                          ?>
                                    
                                    
                                    
                                    </td>--->
                                    </tr>
                                    <?php $sl++; ?>
                                 <?php } ?>
                              <?php } ?>
                           </tbody>
                        </table>
                        <?php if (user_access(511) == true) { ?>
                           <button class="btn btn-danger" type="button" onclick="return is_deleteTemp()">
                              <i class="ion-close-circled"></i>
                              Delete
                           </button>
                        <?php } ?>
                     </form>

                     <?php foreach ($temp_list as $tlist) { ?>
                        <div id="createnewtemplate<?php echo $tlist->temp_id; ?>" class="modal fade" role="dialog">
                           <div class="modal-dialog modal-lg">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Edit Template <?php echo $tlist->template_name; ?></h4>
                                 </div>

                                 <div class="modal-body">
                                    <!--<form>-->
                                    <?php echo form_open_multipart('emailapi/template_details', 'class="form-inner"') ?>
                                    <div class="row">
                                       <?php
                                       $stage = explode(',', $tlist->stage);
                                       $process = explode(',', $tlist->process);
                                       ?>
                                       <div class="form-group col-sm-6">
                                          <div class="form-group">
                                             <label>Process</label>
                                             <select class="form-control process multiple" name="process[]" multiple required>
                                                <?php foreach ($products as $product) { ?>
                                                   <option value="<?= $product->sb_id ?>" <?php if (in_array($product->sb_id, $process)) {
                                                                                             echo 'selected';
                                                                                          } ?>><?= $product->product_name ?></option>
                                                <?php } ?>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="form-group col-sm-6">
                                          <div class="form-group">
                                             <label>Stage</label>
                                             <select class="form-control process multiple" name="stage[]" multiple required>
                                                <option value="0" <?php if (in_array('0', $stage)) {
                                                                     echo 'selected';
                                                                  } ?>>Ticket</option>
                                                                  <option value="1" <?php if (in_array('1', $stage)) {
                                                                     echo 'selected';
                                                                  } ?>><?= display("enquiry") ?></option>
                                                <option value="2" <?php if (in_array('2', $stage)) {
                                                                     echo 'selected';
                                                                  } ?>><?= display("lead") ?></option>
                                                <option value="3" <?php if (in_array('3', $stage)) {
                                                                     echo 'selected';
                                                                  } ?>><?= display("client") ?></option>
                                                <?php
                                                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                                                if (!empty($enquiry_separation)) {
                                                   $enquiry_separation = json_decode($enquiry_separation, true);
                                                   foreach ($enquiry_separation as $key => $value) { ?>
                                                      <option value="<?= $key ?>" <?php if (in_array($key, $stage)) {
                                                                                       echo 'selected';
                                                                                    } ?>> <?= $ctitle = $enquiry_separation[$key]['title'];  ?></option>
                                                <?php }
                                                } ?>
                                             </select>
                                          </div>
                                       </div>
                                       <input type="hidden" name="template_id" value="<?php echo $tlist->temp_id; ?>">
                                       <div class="form-group   col-sm-12">
                                          <label>Template Name*</label>
                                          <input class="form-control" name="template_name" type="text" required="" value="<?php echo $tlist->template_name; ?>">
                                       </div>
                                       <div class="form-group   col-sm-12">
                                          <label>Subject Line *</label>
                                          <input class="form-control" name="mail_subject" type="text" value="<?php echo $tlist->mail_subject; ?>">
                                       </div>
                                       <div class="form-group col-sm-12">
                                          <label>Template Content</label>
                                          <!--<textarea class="form-control" name="template_content" rowspan="8"><?php echo $tlist->template_content; ?></textarea>-->
                                          <!--<div id="sample">
                                          <textarea  name="template_content" style="width: 700px; height: 350px;" class="form-control">
                                          
                                              <?php echo $tlist->template_content; ?>
                                          
                                          </textarea>
                                          
                                                    </div>-->
                                          <textarea name="template_content" class="form-control summernote" rows="7"><?php echo $tlist->template_content; ?></textarea>
                                       </div>
                                       <!--<div class="form-group col-sm-6">
                                       <?php $temp = $tlist->temp_id;
                                       $attr  = $this->db->select('*')->from('mail_template_attachments')->where('templt_id', $temp)->get()->result();

                                       $i = 1;

                                       if (!empty($attr)) {

                                          foreach ($attr  as $attach) { ?>
                                       
                                            
                                       
                                             <b><?= $i++ ?>.</b>&nbsp;<a href="<?= $attach->files ?>" target="_blank"><?= str_replace('assets/attachments/mail/', ' ', $attach->files) . "<br>" ?></a><input type="file" name="updatefile[]" class="form-control">
                                       
                                            
                                       
                                            <?php }
                                       }
                                             ?>
                                       
                                        
                                       
                                        
                                       
                                       </div>-->
                                    </div>
                                    <div class="col-12" style="padding: 0px;">
                                       <div class="row">
                                          <div class="col-12" style="text-align:center;">
                                             <button class="btn btn-success" type="submit">Save</button>
                                          </div>
                                       </div>
                                    </div>
                                    </form>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <!-- /.table-responsive -->
         </div>
      </div>
   </div>
</div>
<script>
   $("#service").click(function() {

      if ($('#another-element:visible').length)

         $('#another-element').hide();

      else

         $('#another-element').show();

   });





   $("#task_create_div").click(function() {

      if ($('#task_create:visible').length)

         $('#task_create').hide();

      else

         $('#task_create').show();

   });
</script>
<style>
   #exTab3 .nav-pills>li>a {
      border-radius: 4px 4px 0 0;
   }

   #exTab3 .tab-content {
      background-color: #f1f3f6;
      padding: 5px 15px;
   }

   .nav-pills>li.active>a,
   .nav-pills>li.active>a:focus,
   .nav-pills>li.active>a:hover {
      color: white;
      background-color: #37a000;
   }

   .nav-pills>li>a {
      border-radius: 5px;
      padding: 10px;
      color: #000;
      font-weight: 600;
   }

   .nav-pills>li>a:hover {
      color: #000;
      background-color: transparent;
   }

   input[type=number]::-webkit-inner-spin-button,
   input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
   }

   input[type=number]::-webkit-inner-spin-button,
   input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      margin: 0;
   }
</style>
<!--------------- ADD NEW CLIENT ------------->
<div id="createnewintegration" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Send Email notifications to your client, enter details below to configure</h4>
         </div>
         <div class="modal-body">
            <!--<form>-->
            <?php echo form_open_multipart('emailapi/create_email_conf', 'class="form-inner"') ?>
            <div class="row">
               <div class="form-group   col-sm-6">
                  <label>Title*</label>
                  <input class="form-control" name="title" type="text" required="">
               </div>
               <div class="form-group col-sm-6">
                  <label>Protocol*</label>
                  <input class="form-control" name="protocol" type="text" required="">
               </div>
               <div class="form-group col-sm-6">
                  <label>SMTP HOST*</label>
                  <input class="form-control" name="smtp_host" type="text" required="">
               </div>

               <div class="form-group col-sm-6">
                  <label>SMTP User*</label>
                  <input class="form-control" name="smtp_user" type="text" required="">
               </div>
               <div class="form-group col-sm-6">
                  <label>SMTP Password*</label>
                  <input class="form-control" name="smtp_password" type="text" required="">
               </div>
               <div class="form-group col-sm-6">
                  <label>SMTP Port*</label>
                  <input class="form-control" name="smtp_port" type="text" required>
               </div>

            </div>
            <div class="col-12" style="padding: 0px;">
               <div class="row">
                  <div class="col-12" style="text-align:center;">
                     <button class="btn btn-success" type="submit">Save</button>
                  </div>
               </div>
            </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!---------------------------------------------------------------->
<!--------------- ADD NEW Template ------------->
<div id="createnewtemplate" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Template</h4>
         </div>
         <div class="modal-body">
            <!--<form>-->
            <?php echo form_open_multipart('emailapi/createtemplate', 'class="form-inner" id="email_api"') ?>
            <div class="row">
               <div class="form-group col-sm-6">
                  <div class="form-group">
                     <label>Process</label>
                     <select class="form-control process multiple" name="process[]" multiple required>
                        <?php foreach ($products as $product) { ?>
                           <option value="<?= $product->sb_id ?>"><?= $product->product_name ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="form-group col-sm-6">
                  <div class="form-group">
                     <label>Stage</label>
                     <select class="form-control process multiple" name="stage[]" multiple required>
                        <option value="1"><?= display("enquiry") ?></option>
                        <option value="2"><?= display("lead") ?></option>
                        <option value="3"><?= display("client") ?></option>
                        <?php
                        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                        if (!empty($enquiry_separation)) {
                           $enquiry_separation = json_decode($enquiry_separation, true);
                           foreach ($enquiry_separation as $key => $value) { ?>
                              <option value="<?= $key ?>"> <?= $ctitle = $enquiry_separation[$key]['title'];  ?></option>
                        <?php }
                        } ?>
                        <option value="0">Ticket</option>

                     </select>
                  </div>
               </div>
               <div class="form-group   col-sm-6">
                  <label>Template Name*</label>
                  <input class="form-control" name="template_name" type="text" required="">
               </div>
               <div class="form-group   col-sm-6">
                  <label>Template Response*</label>
                  <select name="template_response" id="template_response" class="form-control" onchange="Myfunction()">
                     <option value="1">Normal</option>
                     <option value="2">Auto</option>
                  </select>
               </div>
               <div class="form-group col-sm-12">
                  <div class="form-group col-sm-6" style="display:none;margin-left:0px !important" id="auto-div">
                     <label>Auto mail for</label>
                     <select class="form-control" name="auto_mail_for" id="auto-mail-to">
                        <option value="" style="display:none;">---Select---</option>
                        <option value="1">Enquiry customer introduction mail</option>
                        <option value="4">Enquiry channel partner introduction mail</option>
                        <option value="2">Customer welcome mail</option>
                        <option value="3">Channel partner welcome mail</option>
                        <option value="5">CAF Form</option>
                     </select>
                  </div>
                  <div class="form-group col-sm-6 " style="margin-lef:0px !important">
                     <label>Mail subject Line*</label>
                     <input type="text" class="form-control" name="mail-subject" id="mail-subject">
                  </div>
               </div>
               <!-- <div class="form-group col-sm-6"> 
                  <label>Template Content</label>
                  
                  <textarea class="form-control" name="template_content" rowspan="8"></textarea>
                  
                  </div> -->
               <!--<div class="form-group row col-md-12">
                  <label for="message" class="col-xs-3 col-form-label"><?php echo display('message') ?> <i class="text-danger">*</i></label>
                  
                  <div class="col-md-12" style="width:100%;">
                  
                      <textarea name="template_content" class="form-control tinymce"  placeholder="Enter your message"  rows="12"></textarea>
                  
                  </div>
                  
                  </div>-->
               <div class="form-group row col-md-12">
                  <label for="message" class="col-xs-3 col-form-label"><?php echo display('message') ?> <i class="text-danger">*</i></label>
                  <div class="col-lg-12">
                     <!--<textarea id="txtEditor" name="template_content"></textarea>--
                        <div id="sample">
                        
                                               <textarea  name="template_content" style="width: 700px; height: 350px;" class="form-control">
                        
                                                    
                        
                                             </textarea>
                        
                                             </div>-->
                     <textarea name="template_content" class="form-control summernote" rows="7"></textarea>
                  </div>
               </div>
               <div class="form-group row col-md-12">
                  <label for="message" class="col-xs-3 col-form-label">Upload file <i class="text-danger">*</i></label>
                  <div class="col-md-12">
                     <div class="file-upload-wrapper">
                        <input type="file" id="input-file-max-fs" name="upload-attachments[]" class="file-upload" data-max-file-size="2M" multiple>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-12" style="padding: 0px;">
               <div class="row">
                  <div class="col-12" style="text-align:center;">
                     <button class="btn btn-success" type="submit">Save</button>
                  </div>
               </div>
            </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url('assets/js/editor/editor.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
   $('.multiple').select2({});

   function hide_td(id, id2) {

      var a = document.getElementById(id2);

      if (a.checked == true) {

         $('.' + id).css('visibility', 'visible');

         $('.' + id).css('display', 'table-cell');

         //  document.getElementsByClassName("th1").style.visibility = "hidden";

      } else {

         $('.' + id).css('visibility', 'hidden');

         $('.' + id).css('display', 'none');





      }

   }
</script>
<script>
   $('.checked_all').on('change', function() {

      $('.checkbox').prop('checked', $(this).prop("checked"));

   });

   $('.checkbox').change(function() {

      if ($('.checkbox:checked').length == $('.checkbox').length) {

         $('.checked_all').prop('checked', true);

      } else {

         $('.checked_all').prop('checked', false);

      }

   });





   $('.checked_alltemp').on('change', function() {

      $('.checkboxtemp').prop('checked', $(this).prop("checked"));

   });

   $('.checkboxtemp').change(function() {

      if ($('.checkboxtemp:checked').length == $('.checkboxtemp').length) {

         $('.checked_alltemp').prop('checked', true);

      } else {

         $('.checked_alltemp').prop('checked', false);

      }

   });




   function delete_emailapi() {
      var x = confirm('Are you sure want to delete ? ');
      if (x == true) {
         $.ajax({
               type: 'POST',
               url: '<?php echo base_url(); ?>emailapi/delete_emailapi',
               data: $('#apitable').serialize()
            })
            .done(function(data) {
               alert("success!");
               location.reload();
            })
            .fail(function() {
               alert("fail!");
            });
      } else {
         location.reload();
      }
   }




   function is_delete() {

      var x = confirm('Are you sure want to delete ? ');

      if (x == true) {

         $.ajax({

               type: 'POST',

               url: '<?php echo base_url(); ?>email/delete_api',

               data: $('#apitable').serialize()

            })

            .done(function(data) {

               alert("success!");

               location.reload();

            })

            .fail(function() {

               alert("fail!");



            });

      } else {

         location.reload();

      }

   }





   function is_deleteTemp() {

      var x = confirm('Are you sure want to delete ? ');

      if (x == true) {

         $.ajax({

               type: 'POST',

               url: '<?php echo base_url(); ?>emailapi/delete_template',

               data: $('#temptable').serialize()

            })

            .done(function(data) {

               alert("success!");

               location.reload();

            })

            .fail(function() {

               alert("fail!");



            });

      } else {

         location.reload();

      }

   }
</script>
<script>
   function Myfunction() {



      var v = document.getElementById('template_response').value;



      if (v == 2) {



         document.getElementById('auto-div').style.display = 'block';



      } else {



         document.getElementById('auto-div').style.display = 'none';

      }





   }
</script>
<script type="text/javascript">
   //bkLib.onDomLoaded(function(){ nicEditors.allTextAreas() });
</script>
<script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.min.js"></script>
<script>
   jQuery(document).ready(function() {

      $('.summernote').summernote({

         height: 200, // set editor height

         minHeight: null, // set minimum height of editor

         maxHeight: null, // set maximum height of editor

         focus: false // set focus to editable area after initializing summernote

      });

   });
</script>