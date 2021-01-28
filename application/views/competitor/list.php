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
          <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#Addcompetitors" href="javascript:void(0)"> <i class="fa fa-plus"></i> Add Competitors</a>
        </div>
        <?php } ?>
      </div>
      <div class="panel-body">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>S No.</th>
              <th>Competitors</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $sl = 1;
            foreach ($competitor_list as $list) { ?>
              <tr>
                <td><?php echo $sl; ?></td>
                <td width=""><?= $list->name ?></td>
                <td><?php echo (($list->status == 0) ? display('active') : display('inactive')); ?></td>
                <td width=""><?= $list->created_at ?></td>
                <td class="center">
<?php   if (user_access('d36')) { ?>

                  <a data-toggle="modal" data-target="#editBranch" id="<?php echo $list->id ?>" href="javascript:void(0)" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a>
<?php  }  if (user_access('d38')) { ?>
                
                  <a href="<?= base_url('setting/competitor_delete/' . $list->id . '') ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
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
<div class="modal fade" id="Addcompetitor" tabindex="-1" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add Competitor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url() . 'setting/addcompetitor' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Competitor Name </label>
              <input type="text" name="branch" class="form-control">
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
        <h5 class="modal-title" id="">Edit Competitor</h5>
        </button>
      </div>
      <form action="<?= base_url() . 'setting/addcompetitor' ?>" enctype="multipart/form-data" method='post'>
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
        url: "<?= base_url('setting/editcompetitor/') ?>",
        method: "POST",
        data: {
          branch_id: branch_id
        },
        success: function(data) {
          $('#branch_data').html(data);

        }
      });
    }
  });
</script>