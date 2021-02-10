<style>
  .morecontent span {
    display: none;
  }

  .morelink {
    display: block;
  }

  a:hover,
  a:focus {
    text-decoration: none;
    outline: none;
    color: #37a000;
    font-weight: 900;
  }
</style>
<div class="row">

  <!--  table area -->

  <div class="col-sm-12">

    <div class="panel panel-default thumbnail">
      <div class="panel-heading no-print">
<?php   if (user_access('d35')) { ?>

        <div class="btn-group">
          <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#AddBranch" href="javascript:void(0)"> <i class="fa fa-plus"></i> Add Branch</a>
        </div>
        <?php } ?>
      </div>
      <div class="panel-body">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>S No.</th>
              <th>Name</th>
              <th>Type</th>
              <th>Zone</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $sl = 1;
            foreach ($common_list as $branch) { ?>
              <tr>
                <td><?php echo $sl; ?></td>
                <td width=""><?= $branch->branch_name ?></td>
                <td width=""><?= ucwords($branch->type)??'NA' ?></td>
                <td><?=$branch->zone_name?></td>
                <td><?php echo (($branch->branch_status == 1) ? display('active') : display('inactive')); ?></td>
                <td width=""><?= $branch->created_at ?></td>
                <td class="center">
<?php   if (user_access('d36')) { ?>

                  <a data-toggle="modal" data-target="#editBranch" id="<?php echo $branch->branch_id ?>" href="javascript:void(0)" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
<?php  }  if (user_access('d38')) { ?>
                
                  <a href="<?= base_url('setting/branch_delete/' . $branch->branch_id . '') ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
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
<?php   if (user_access('d35')) { ?>

<!-- Course Upload  -->
<div class="modal fade" id="AddBranch" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url() . 'setting/addbranch' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row">
            <div class="form-group">
              <label>Branch Name </label>
              <input type="text" name="branch" class="form-control">
            </div>
            <div class="form-group">
              <label>Type </label>
              <select class="form-control" name="type" onchange="//{if(this.value=='zone')$('#zone_box').show(); else $('#zone_box').hide();}">
                <option value="branch">Branch</option>
                <!-- <option value="zone">Zone</option> -->
                <option value="area">Area</option>
              </select>
            </div>
           <div id="zone_box" class="form-group" style="display: block;">
              <label>Select Zone </label>
              <select class="form-control" name="zone">
                <?php
                if(!empty($zone_list))
                {
                  foreach ($zone_list as $key => $zone)
                  {
                      echo'<option value="'.$zone->zone_id.'">'.$zone->name.'</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Status </label>
              <div class="form-check" style="width: 100%; padding: 0px 10px;">
                <label class="radio-inline">
                  <input type="radio" name="status" value="1" checked="checked">Active</label>
                <label class="radio-inline">
                  <input type="radio" name="status" value="0">Inactive</label>
              </div>
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
<?php } ?>
<?php   if (user_access('d36')) { ?>

<div class="modal fade" id="editBranch" tabindex="-1" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h5 class="modal-title" id="">Edit Branch</h5>
        </button>
      </div>
      <form action="<?= base_url() . 'setting/addbranch' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row" id="branch_data">
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
<?php } ?>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
  $(document).on('click', '.view_data', function() {
    var branch_id = $(this).attr("id");
    if (branch_id != '') {
      $.ajax({
        url: "<?= base_url('setting/editbranch/') ?>",
        method: "POST",
        data: {
          branch_id: branch_id
        },
        success: function(data) {
          $('#branch_data').html(data);
          $(document).ready(function(){
            $("select").select2();
          });
        }
      });
    }
  });
  $(document).ready(function(){
            $("select").select2();
          });
</script>