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
            <?php   if (user_access('d39')) { ?>

                <div class="btn-group"> 

                    <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#AddBranch" href="javascript:void(0)"> <i class="fa fa-plus"></i> Add Rate</a>  
                    

                </div>
                <div class="modal fade" id="AddBranch" tabindex="-1" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add Rate Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="<?=base_url().'setting/addbranch_rate'?>" enctype="multipart/form-data" method='post'>
          <div class="modal-body">
                <div class="row">
                <div class="col-md-12">
                <label>Booking Branch </label>
                <select name="bbranch" class="form-control">
                 <?php
                 if(!empty($branch))
                 {
                  foreach ($branch as $key => $value) { 
                    ?>
                    <option value="<?= $value->branch_id ?>"><?= $value->branch_name.' ('.($value->type=='zone'?ucwords($value->zone_name):'').' '.$value->type.')' ?></option>
                   <?php
                  }
                 }
                ?>
                 </select>
            </div> 
            <input name="rateid" class="form-control" hidden>

            <div class="col-md-12">
                <label>Delivery Branch </label>
             <select name="dbranch" class="form-control">
             <?php foreach ($branch as $key => $value) { ?>
                    <option value="<?= $value->branch_id ?>"><?= $value->branch_name.' ('.($value->type=='zone'?ucwords($value->zone_name):'').' '.$value->type.')' ?></option>
                   <?php
                 } ?>
             </select>
            </div> 
            <div class="col-md-12">
                <label>Rate </label>
              <input name="rate" class="form-control" required >
            </div>  
            <div class="col-md-12">
                <label>Status </label>
                <div class="form-check">
                <label class="radio-inline">
                  <input type="radio" name="status" value="0" checked="checked">Active</label>
                <label class="radio-inline">
                  <input type="radio" name="status" value="1">Inactive</label>
              </div>
            </div>          
          </div>
          </div>
          <div class="modal-footer" >    
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
    </div>
  </div>
</div>
                <?php }?>

            </div>

            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>S No.</th>
                    <th>Booking Branch</th>
                    <th>Delivery Branch</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl=1; foreach ($branch_list as $branch) {?>
                        <tr >
                            <td><?php echo $sl; ?></td>
							<td width=""><?= $branch->from.' ('.($branch->btype=='zone'?$branch->zone_name:'').' '.ucwords($branch->btype).')'?></td>
							<td width=""><?= $branch->to.' ('.($branch->dtype=='zone'?$branch->zone_name:'').' '.ucwords($branch->dtype).')'?></td>
                            <td width=""><?= $branch->rate?></td>
                            
                            <td><?php echo (($branch->rate_status==0)?display('active'):display('inactive')); ?></td>

              <td width=""><?= $branch->created_at?></td>
              <td class="center">
            <?php   if (user_access('e30')) { ?>
                  <a href="<?= base_url('setting/editbranchrate/' . $branch->id . '')?>" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
            <?php    } if (user_access('e31')) { ?>
                  <a href="<?= base_url('setting/branchrate_delete/' . $branch->id . '') ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
               <?php } ?>
                </td>
                        </tr>

                        <?php $sl++; ?>

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

<!-- Course Upload  -->


<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
