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
                <table id="example" class="table table-striped table-bordered" style="width:100%">
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
                            <a href="<?php echo base_url("cron/delete_cron/$value->id") ?>" onclick="return confirm('<?php echo display('are_you_sure') ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
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
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
