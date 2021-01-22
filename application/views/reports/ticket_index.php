
<div class="row">
   <!--  table area -->
   <div class="col-sm-12">
      <div  class="panel panel-default thumbnail">
         <div class="panel-heading no-print">
            <div class="btn-group"> 
               <a class="btn btn-success" href="<?php echo base_url("report/ticket_report") ?>"> <i class="fa fa-plus"></i>  <?php echo display('report_filter_create') ?> </a>  
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
                              <a href="<?=base_url().'report/ticket_report_view/'.$value['id'].'/'.base64_encode($value['name'])?>" class='btn btn-success'>View</a>
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