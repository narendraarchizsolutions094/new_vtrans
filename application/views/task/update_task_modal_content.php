<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
   <h4 class="modal-title">Edit Task</h4>
</div>
<div class="modal-body" style="padding: 0px;">
   <?php echo form_open_multipart('lead/enquiry_response_updatetask',array('id'=>'update_task_form','class'=>"form-inner")) ?>
   <div class="profile-edit">
     
      <div class="form-group col-sm-6" >
         <label>Subject</label>
         <input type="text" class="form-control" name="subject" value="<?= $task->subject?>" placeholder="Subject" id='task_update_subject'>
      </div>

      <div class="form-group col-sm-6" style="display:none;">
         <label>Contact Person Name</label>
         <input type="text" class="form-control" name="contact_person" value="<?= $task->contact_person?>" placeholder="Contact Person Name">
      </div>
      <div class="form-group col-sm-6" style="display:none;">
         <label>Contact Person Designation</label>
         <input type="text" class="form-control" name="designation" value="<?= isset($task->designation)?$task->designation:''?>" placeholder="Contact Person Designation">
      </div>
      <div class="form-group col-sm-6" style="display:none;">
         <label>Contact Person Mobile No</label>
         <input type="text" class="form-control" name="mobileno" value="<?= $task->mobile?>" maxlength='10' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
      </div>
      <div class="form-group col-sm-6" style="display:none;">
         <label>Contact Person Email</label>
         <input type="text" class="form-control" name="email" value="<?= $task->email?>">
      </div>
	  
	  <div class="form-group col-sm-6">
          <label>Status</label>
          <select class="form-control" name="task_status">
             <?php foreach($taskstatus_list as $val){ ?>
             <option value="<?php echo $val->taskstatus_id; ?>" <?php if(($task->task_status == $val->taskstatus_id) || (empty($task->task_status) && $val->taskstatus_name == 'Pending')){echo 'selected';}?>><?php echo $val->taskstatus_name; ?></option>
             <?php } ?>
          </select>
       </div>
      
      <div class="form-group col-sm-6" style="">
         <label>Task Date</label>
         <input  class="form-control form-date" name="task_date" value="<?= date("Y-m-d",strtotime($task->task_date)) ?>" id='task_update_enq_task_date'>
      </div>
      <div class="form-group col-sm-6" style="">
         <label>Task Time</label>
         <input type="time" class="form-control" name="task_time" value="<?=$task->task_time?>" id='task_update_task_time'>
      </div>
        
      <div class="form-group col-sm-12" style="">
         <label>Task Remark</label>
         <textarea class="form-control" name="task_remark"><?=$task->task_remark?></textarea>
      </div>

  <div class="form-group text-center">
     <input type="hidden" name="enq_code"  value="<?php echo  $task->resp_id; ?>" >
     <input type="hidden" name="task_type" value="1">
     <input type="hidden" name="update_notification_id" value="<?=$task->notification_id?>">
     <input type="hidden" name="task_update_create_by" value="<?=$task->create_by?>">
     <input type="hidden" name="task_enquiry_code" value="<?=$task->query_id?>">
     <input type="submit" name="update" class="btn btn-primary" id='task_update_btn' value="<?php echo display('update');?>" >
  </div>
   </div>
</form> 
</div>
<div class="modal-footer" style="visibility: hidden;">
   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>   