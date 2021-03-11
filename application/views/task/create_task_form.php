<form id='set_reminder' action="<?=base_url().'task/save_task'?>" class="form-inner" enctype="multipart/form-data" method="post">
    <input name='task_notification_id' type='hidden'/>
    <div class="">
        <div class="form-group col-sm-6">
            <label>Subject <i class="text-danger ">*</i></label>
            <input type="text" class="form-control" id='task_subject' name="subject" placeholder="Subject">
        </div>
        <div class="form-group col-sm-6">
            <label>Task Type <i class="text-danger ">*</i></label>
            <select class="form-control" name="task_type">
                <?php
                $task_type = array('1'=>'Task','2'=>'Follow Up','3'=>'Appointment');

                if(!empty($task_type)){
                    foreach($task_type as $key=>$value){
                        ?>
                        <option value="<?=$key?>"><?=$value?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group col-sm-4">
            <label>Task Date <i class="text-danger">*</i></label>
            <input class="form-control form-date" type='text' name="task_date">
        </div>

        <div class="form-group col-sm-4">
            <label>Task Time<i class="text-danger">*</i></label>
            <input type="time" class="form-control" name="task_time">
        </div>

        <div class="form-group col-sm-4">
            <label>Status<i class="text-danger">*</i></label>
            <select class="form-control" name="task_status">
            <?php
            if(!empty($taskstatus_list)){
                foreach($taskstatus_list as $key=>$value){
                    ?>
                    <option value="<?=$value->taskstatus_id?>">
                    <?=$value->taskstatus_name?>
                    </option>
                    <?php
                }
            }
            ?>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label style="width:100%;">Company <span class="text-danger">*</span>
              <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
              </a>
            </label>
            <select class="form-control" name="company" onchange="filter_related_to(this.value)" required>
              <option value="-1">Select</option>
              <?php
              if(!empty($company_list))
              {
                foreach ($company_list as $key =>  $row)
                {
                  echo '<option value="'.$row->id.'">'.$row->company_name.'</option>';
                }
              }
              ?>
            </select>
        </div>

        <div class="form-group col-sm-6">
            <label>Client Name <i class="text-danger">*</i></label>
            <select class="form-control" name="related_to" id='task_related_to'>
            <?php
                // if(!empty($related_to)){
                //     foreach($related_to as $key=>$value){
                //         ?>
                      <!--  <option value="<?=$value->Enquery_id?>"> -->
                //             <?php
                //             if($value->name){
                //                 echo $value->name_prefix.' '.$value->name.' '.$value->lastname.(empty($value->email)?'':'( '.$value->email.') ');
                //             }else{
                //                 if(empty($value->email)){
                //                     echo $value->phone;
                //                 }else{
                //                     echo $value->email;
                //                 }
                //             }
                //             ?>
                        <!-- </option> -->
                //         <?php
                //     }
                // }
            ?>
            </select>
        </div>
        
        <div class="form-group col-sm-12">
            <label>Description<i class="text-danger">*</i></label>
            <textarea id='task_description' rows="6" class="form-control" name="task_remark"
                placeholder='Start typing the details about the task...'></textarea>
        </div>
        <div class="form-group text-center">
            <input type="submit" name="create" class="btn btn-primary" id='set_reminder_btn' value="Create">
        </div>
    </div>
</form>
<script>
    $('.form-date').datepicker({ dateFormat: 'yy-mm-dd' });
    $('#set_reminder_btn').on('click', function(e) {
        e.preventDefault();
        var uid = "<?=$this->session->user_id?>";
        var rem_date = $("input[name='task_date']").val();
        var rem_time = $("input[name='task_time']").val();
        type = $("select[name='task_type]").val();
        if(type == 1){
            task_type = 'Task';
        }else if(type == 2){
            task_type = 'Follow Up';
        }else if(type == 3){
            task_type = 'Appointment';
        }else{
            task_type = 'Task';
        }
        var reminder_txt = task_type+' -> '+$("#task_subject").val()+'<br>'+$("#task_description").val();
        var enq_id = $("#task_related_to").val();;
        id = writeUserData(uid, reminder_txt, enq_id, rem_date, rem_time);  
        $("input[name='task_notification_id']").val(id);      
        //alert(id);
        $("#set_reminder").submit();
    });


function filter_related_to(v)
{
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $("select[name=related_to]").html(q);
               $("select[name=related_to]").trigger('change');
            }
      });
}
</script>