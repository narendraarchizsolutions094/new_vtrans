<style>
.morecontent span {
    display: none;
}
.morelink {
    display: block;
}
a:hover, a:focus {
    text-decoration: none;
    outline: none;
    color: #37a000;
	font-weight:900;
}
</style>
<div class="row">

    <!--  table area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-success btn-sm" href="<?php echo base_url("cron/add") ?>"> <i class="fa fa-plus"></i> Add Cron</a>  
                </div>
            </div>

            <div class="panel-body">
                <table id="" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
               <th>S No.</th>
                <th>Minute</th>
                <th>Hour</th>
                <th>Day</th>
                <th>Month</th>
                <th>Weekday</th>
                <th>Next Running Time</th>
                <th>Url</th>
                <th>Status</th>
                <th>Action</th>
                </tr>
                </thead>

                <tbody>
                    <?php $i=1;
                      foreach ($crons as $key => $value) {   ?>
                    <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $value->minute ?></td>
                            <td><?= $value->hour ?></td>
                            <td><?= $value->day ?></td>
                            <td><?= $value->month ?></td>
                            <td><?= $value->weekday ?></td>
                            <td><?= $value->running_time ?></td>
                            <td>
                            <!-- <a href="<?= base_url('cron/view_log/'.$value->id);?>"><?= $value->url ?></a>     -->
                            <?= $value->url ?>
                            </td>
                            <td><?php if($value->status==0){  echo'Active'; }else{  echo'Inactive';  } ?></td>
                            <td class="center">
                            <a style="display:none;" href="<?php echo base_url("cron/delete_cron/$value->id") ?>" onclick="return confirm('<?php echo display('are_you_sure') ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 

                            <a href="javascript:void(0)" onclick="cron_update(<?=$value->id?>)" class="btn btn-xs  btn-primary" data-toggle="modal" data-target="#editCronModal"><i class="fa fa-edit"></i></a> 

                        </tr>
                        <?php } ?>

                </tbody>

              </table>
             
            </div>

            <!-- /.card-body -->

          </div>

          <!-- /.card -->

        </div>

        <!-- /.col -->

      </div>


      <!--Edit Cron Modal -->
    <div class="modal fade" id="editCronModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Cron </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo base_url().'cron/update_cron_time'; ?>" method="post" >
                    <div class="modal-body">
                        <input type="hidden" name="cron_id" >
                        <div style="text-align:center;"><label id='cron_url' style="background:#37a000;text-align:center;"></label></div><br>
                        <label>Next Running Time</label><input type="text" name="next_running_time" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<script>
    function cron_update(id){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>cron/get_cron_deails/"+id,
            success: function(res) {
                res_json = JSON.parse(res);
                console.log(res_json);
                $("input[name='cron_id']").val(res_json.id);
                $("#cron_url").html(res_json.url);
                $("input[name='next_running_time']").val(res_json.running_time);
            }
        });
    }

$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
