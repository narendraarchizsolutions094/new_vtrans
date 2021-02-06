<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default thumbnail">
      <div class="panel-body">
      <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#add_expense">
        Add  expense</button>
        <br>
        <br>
        <table id="expanple" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>S No.</th>
              <th>Title</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $sl = 1;
            foreach ($list as $docList) { ?>
              <tr>
                <td><?php echo $sl; ?></td>
                <td><?= $docList->title ?></td>
                <td><?= $docList->created_at ?></td>
                <td>
                  <a  class="btn btn-xs  btn-primary"  data-toggle="modal" data-target="#edit_expense_<?= $docList->id ?>"><i class="fa fa-pencil"></i> </a>
                  <div id="edit_expense_<?= $docList->id ?>" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Upadte Expense</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>setting/add_expense" method="POST">  
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
            <label>Title</label>
            <input name="title" class="form-control" value="<?= $docList->title ?>">
            <input name="expense_id" class="form-control" value="<?= $docList->id ?>" hidden>
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
                  <label>Status </label>
                  <input name="status"  type="radio" value="0"  <?php 
                    if($docList->status==0){
                        echo'checked';
                    }
                   ?>>&nbsp;&nbsp;No 
                  <input  name="status"  type="radio" value="1" <?php 
                    if($docList->status==1){
                        echo'checked';
                    }
                   ?>>&nbsp;&nbsp;Yes
               </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success">
              Update</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
                  <!-- //edit form -->
                 
                  <a  class="btn btn-xs  btn-danger" href="<?= base_url('setting/delete_expense/'.$docList->id.'') ?>"><i class="fa fa-trash"></i></a>
                </td>

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
<div id="add_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Add Expense</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>setting/add_expense" method="POST">  
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
            <label>Title</label>
            <input name="title" class="form-control" >
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
                  <label>Status </label>
                  <input name="status"  type="radio" value="0" checked>&nbsp;&nbsp;No 
                  <input  name="status"  type="radio" value="1" >&nbsp;&nbsp;Yes
               </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success">
              Save</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>