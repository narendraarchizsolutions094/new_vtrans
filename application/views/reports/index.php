<div class="row">
   <!--  table area -->
   <div class="col-sm-12">
   <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>  
      <div  class="panel panel-default thumbnail">
         <div class="panel-heading no-print">
            <div class="btn-group"> 
               <a class="btn btn-success" href="<?php echo base_url("report/view_details") ?>"> <i class="fa fa-plus"></i>  <?php echo display('report_filter_create') ?> </a>  
            </div>
         </div>
         <div class="panel-body">
            <table class="datatable1 table table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th><?php echo display('serial') ?></th>                     
                     <th>Title</th>
                     <th>Created Date</th>
                     <th>Created By</th>
                     <th>Actions</th>                     
                  </tr>
               </thead>
               <tbody>               
                  <?php
                  if (!empty($reports)) {
                     $i = 1;
                     foreach ($reports as $key => $value) { ?>
                        <tr>
                           <td><?=$i?></td>
                           <td>
                              <?=$value['name']?>
                           </td>
                           <td>
                              <?=$value['created_date']?>                              
                           </td>
                           <td>
                              <?=$value['created_by_name']?>                              
                           </td>
                           <td>
                              <a href="<?=base_url().'report/view/'.$value['id'].'/'.base64_encode($value['name'])?>" class='btn btn-warning'>View</a>
                              <button  data-toggle="modal" data-target="#create_task<?=$value['id']  ?>"   class='btn btn-primary' >Set Schedule</button>
                              <div id="create_task<?=$value['id']  ?>" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg">    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Schedule</h4>
      </div>
      <form action="<?= base_url('report/set_schedule') ?>" method="POST">
      <div class="modal-body" >
           <div class="row">
           <div class="col-md-6">
            <label>Select Employee</label> 
            <input name="id" value="<?= $value['id'] ?>"  hidden>
            <select class="form-control process"  name="users[]"   multiple required>
           <?php  foreach ($created_bylist as $user) { 
              $users=explode(',',$value['mail_users']);
              ?>
              <option value="<?= $user->pk_i_admin_id?>" <?php if(in_array($user->pk_i_admin_id, $users)){echo'selected';} ?>><?= $user->s_display_name ?>(<?= $user->s_user_email?>)                               
                             </option><?php } ?>
                     </select>
           </div>
           <div class="col-md-6">
			<label>Status </label>
			<div class="form-check">
            <label class="radio-inline">
			<input type="radio" name="status" value="1" <?php if($value['schedule_status']==1){echo'checked';} ?>>Active</label>
            <label class="radio-inline">
            <input type="radio" name="status" value="0" <?php if($value['schedule_status']==0){echo'checked';} ?>>Inactive</label>
            </div>
           </div>
          

          
           </div>
      </div> 
      <div class="modal-footer" >
            <button class="btn btn-primary" type="submit">Submit</button>
           </div>
      </form>
    </div>
    </div>
  </div>
</div>
                              <a href="javascript:void(0)" class='btn btn-danger btn-sm' onclick="delete_row(<?=$value['id']?>)">Delete</a>
                           </td>
                        </tr>                        
                     <?php
                     $i++;
                     }
                  }
                  ?>
               </tbody>
            </table>
            <!-- /.table-responsive -->
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
  function delete_row(id){
    var result = confirm("Want to delete?");
    if (result) { 
      url = "<?=base_url().'report/delete_report_row'?>"
      $.ajax({
        type: "POST",
        url: url,
        data: {'id':id},
        success: function(data){                
          alert('Deleted Successfully');
          location.reload();
        }
      });
    }
  }
</script>
<script type="text/javascript">
  function set_schedule(id){
      url = "<?=base_url().'report/set_schedule'?>"
      $.ajax({
        type: "POST",
        url: url,
        data: {'id':id},
        success: function(data){       
         $('#task_content').html(data);       
        }
      });
  }
</script>


<script type="text/javascript">
    $('.process').select2({});     
 </script>