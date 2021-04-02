
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
<div class="col-md-12" style="padding: 15px;">

  <?php   if (user_access('d39')) { ?>
                <div class="btn-group"> 

                    <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#AddBranch" href="javascript:void(0)"> <i class="fa fa-plus"></i> Add Rate</a>&nbsp;&nbsp;&nbsp;
                    <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#Addbulk" href="javascript:void(0)"> <i class="fa fa-plus"></i> Upload Branch Data</a>					
                 </div>
                <div class="modal fade" id="AddBranch" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add Rate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="<?=base_url().'setting/addbranch_rate'?>" enctype="multipart/form-data" method='post'>
          <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                      <label>Type </label>
                      <select id="type" name="rate_type" class="form-control" onchange="load_branch(this)">
                        <option value="branch">Branch</option>
                        <option value="zone">Zone</option>
                      </select>
                  </div>
                <div class="col-md-12">
                <label>Booking <span class="type_id">Branch</span> </label>
                <select name="bbranch" class="form-control op-list">
                 <?php
                 if(!empty($branch) && 0)
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
                <label>Delivery <span class="type_id">Branch</span> </label>
             <select name="dbranch" class="form-control op-list">
             <?php 
             if(0)
             {
              foreach ($branch as $key => $value) { ?>
                    <option value="<?= $value->branch_id ?>"><?= $value->branch_name.' ('.($value->type=='zone'?ucwords($value->zone_name):'').' '.$value->type.')' ?></option>
                   <?php
                 } 
              }?>
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

<!----------------------------------------------------Bulk Upload Start-------------------------------->
<div class="modal fade" id="Addbulk" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Upload Rate Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url() . 'location/upload_booking_ratelist' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row">

            <div class="form-group">
              <label id="label">Choose File </label>
              <input type="file" name="file" class="form-control">
            </div>
			
			<div class="form-group">                                                               
				  <div class="col-md-12"><span><a download="<?php echo base_url(); ?>assets/csv/bdr.csv" href="<?php echo base_url(); ?>assets/csv/bdr.csv">Download sample(.csv only)</a></span></div>
            </div>
			
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!----------------------------------------------------bulk Upload End-------------------------------->


    <!--  table area -->

    <div class="col-sm-12">

        <div  class="panel panel-default thumbnail">
            <div class="panel-heading no-print">
            Branch Rate List
            </div>

            <div class="panel-body">
              <table id="rate_table" class="table table-striped table-bordered" style="width:100%">
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
                <!--<?php $sl=1; foreach ($branch_rate_list as $branch) {?>
                <tr>
                <td><?php echo $sl; ?></td>
							  <td width=""><?= $branch->from?></td>
							  <td width=""><?= $branch->to?></td>
                <td width=""><?= $branch->rate?></td>
                <td><?php echo (($branch->rate_status==0)?display('active'):display('inactive')); ?></td>

                <td width=""><?= $branch->created_at?></td>
                <td class="center">
                <?php   if (user_access('e30')) { ?>
                      <a href="<?= base_url('setting/editbranchrate/' . $branch->id . '/'.$branch->type)?>" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
                <?php    } if (user_access('e31')) { ?>
                      <a href="<?= base_url('setting/branchrate_delete/' . $branch->id) ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
                   <?php } ?>
                  </td>
                </tr>

                        <?php $sl++; ?>

                    <?php } ?>--> 

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
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        Zone Rate List
      </div>
      <div class="panel-body">

          <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>S No.</th>
                    <th>Booking Zone</th>
                    <th>Delivery Zone</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl=1; foreach ($zone_rate_list as $branch) {?>
                <tr>
                <td><?php echo $sl; ?></td>
                <td width=""><?= $branch->from?></td>
                <td width=""><?= $branch->to?></td>
                <td width=""><?= $branch->rate?></td>
                <td><?php echo (($branch->rate_status==0)?display('active'):display('inactive')); ?></td>

                <td width=""><?= $branch->created_at?></td>
                <td class="center">
                <?php   if (user_access('e30')) { ?>
                      <a href="<?= base_url('setting/editbranchrate/' . $branch->id . '/'.$branch->type)?>" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
                <?php    } if (user_access('e31')) { ?>
                      <a href="<?= base_url('setting/branchrate_delete/' . $branch->id ) ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
                   <?php } ?>
                  </td>
                </tr>

                        <?php $sl++; ?>

                    <?php } ?> 

                </tbody>

              </table>

      </div>

    </div>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $('#rate_table').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000] ],
          "ajax": {
              "url": "<?=base_url().'setting/rate_load_data'?>",
              "type": "POST",
          },
          "columnDefs": [{ "orderable": false, "targets": 0 }],
              "order": [[ 1, "desc" ]]
  });

});

</script>

<script>

$(document).ready(function() {

    $('#example').DataTable();
    $('#type').trigger('change');
} );


function load_branch(t)
{
  var dtype = '';
  var key = t.value;

  $.ajax({
    url:'<?=base_url('setting/load_branchs')?>',
    type:'POST',
    data:{dtype:dtype,key:key},
    beforeSend:function(){
      $(".type_id").html(key.charAt(0).toUpperCase()+key.slice(1));
      // if(dtype=='booking')
      //  $("#booking_branch").parent().find('font').html('<');
      // else
      //  $("#delivery_branch").parent().find('font').html();
    },
    success:function(res)
    { 
      $('.op-list').html(res);
      $('.op-list').select2();
    }
  })
}
</script>
