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
<?php   //if (user_access('d35')) 
//{ ?>

        <div class="btn-group">
          <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#AddBranch" href="javascript:void(0)"> <i class="fa fa-plus"></i> Add Area</a>
        </div>
        <?php //} ?>
      </div>
      <div class="panel-body">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>S No.</th>
              <th>Area Name</th>
              <th>Region Name</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $sl = 1;
            foreach ($area_list as $zone)
            { 
            ?>
              <tr>
                <td><?php echo $sl; ?></td>
                <td width=""><?= $zone->area_name ?></td>
                <td width=""><?= $zone->region_name ?></td>
                <td width=""><?= $zone->created_at ?></td>
                <td class="center">
<?php   //if (user_access('d36')) { ?>

                  <a data-toggle="modal" data-target="#editBranch" id="<?php echo $zone->area_id ?>" href="javascript:void(0)" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
<?php // }  if (user_access('d38')) { ?>
                
                  <a href="<?= base_url('setting/region_delete/' . $zone->area_id . '') ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
               <?php// } ?>
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
<?php   //if (user_access('d35')) { ?>

<!-- Course Upload  -->
<div class="modal fade" id="AddBranch" tabindex="-1" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add Area</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row">
            <div class="form-group">
              <label>Area Name </label>
              <input type="text" name="area_name" class="form-control">
            </div>
           
           <div class="form-group">
              <label>Select Region </label>
              <select class="form-control" name="region_id">
                <?php
                if(!empty($region_list))
                {
                  foreach ($region_list as $key => $zone)
                  {
                      echo'<option value="'.$zone->region_id.'">'.$zone->name.'</option>';
                  }
                }
                ?>
              </select>
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
<?php //} ?>
<?php   //if (user_access('d36')) { ?>

<div class="modal fade" id="editBranch" tabindex="-1" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h5 class="modal-title" id="">Edit Area</h5>
        </button>
      </div>
      <form action="<?= base_url() . 'setting/edit_sales_area' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row" id="branch_data">
            <center>
              <i class="fa fa-spinner fa-spin" style="font-size: 45px;"></i>
            </center>
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
<?php //} ?>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
  $(document).on('click', '.view_data', function() {
    var vid = $(this).attr("id");
    //alert(branch_id);
    if (vid != '') {
      $.ajax({
        url: "<?= base_url('setting/edit_sales_area') ?>",
        method: "POST",
        data: {
          vid: vid,
          task:'edit'
        },
        success: function(data) {
          //alert(data);
          $('#branch_data').html(data);
          $('select').select2();
        },
        error:function(u,v,w)
        {
          alert(w);
        }
      });
    }
  });

</script>