<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-default thumbnail"> 
            <div class="panel-body">                
                
                <div class="col-12">
                <a href="#" class="btn btn-raised btn-success" data-toggle="modal" data-target="#createbranch"><i class="ti-plus text-white"></i> &nbsp;Add New Department</a>
                </div>
                <br>
<div id="createbranch" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Department</h4>
      </div>
      <div class="modal-body">
      <?php echo form_open_multipart('lead/department','class="form-inner"') ?> 
<div class="row">
    <div class="form-group col-md-12">
        <label>Department Name</label>
        <input class="form-control" name="dept_name"  type="text" value="" required>
    </div>
</div>
<div class="row">       
    <div class="sgnbtnmn form-group col-md-12">
      <div class="sgnbtn">
        <input id="signupbtn" type="submit" value="Add Department" class="btn btn-success"  name="adddept">
      </div>
    </div>
</div>
 
 </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                                                
    
                
                
                
                
                
                
                
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('serial') ?></th>
                            <th>Department Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            <?php $sl = 1; ?>
                            <?php foreach ($all_department as $department) {  ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?> clickable-row" style="cursor:pointer;"  >
                                    <td><?php echo $sl;?></td>
                                    <td><?php echo $department->dept_name; ?></td>
                                     <td class="center">
                                        <a href="" class="btn btn-xs  btn-primary" data-toggle="modal" data-target="#Editdept<?php echo $department->id;?>"><i class="fa fa-edit"></i></a> 
                                        <a href="<?php echo base_url("lead/delete_department/$department->id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
                                    </td>
                                    
                                </tr>
                                
        
        <div id="Editdept<?php echo $department->id;?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Department</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open_multipart('lead/update_dept','class="form-inner"') ?>        
        <div class="row">
        <input type="hidden" name="dept_id" value="<?php echo $department->id;?>">
        <div class="form-group col-md-6">
        <label>Department Name</label>
        <input class="form-control" name="dept_name"  type="text" value="<?php echo $department->dept_name;?>" required>
        </div>
        
        </div>
    <div class="row">      
        <div class="sgnbtnmn form-group col-md-12">
        <div class="sgnbtn">
        <input id="signupbtn" type="submit" value="Update Department" class="btn btn-success"  name="adddepartment">
        </div>
        </div>
        </div>        
        </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div> 
        </div>
        </div>
                                
                                
                                 <?php $sl++; ?>
                            <?php } ?> 
                       
                    </tbody>
                </table>  <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>