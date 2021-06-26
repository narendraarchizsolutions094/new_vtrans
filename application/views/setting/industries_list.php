<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-default thumbnail"> 
            <div class="panel-body">                
                
                <div class="col-12">
                <a href="#" class="btn btn-raised btn-success" data-toggle="modal" data-target="#createindustries"><i class="ti-plus text-white"></i> &nbsp;Add New Industries</a>
                </div>
                <br>
<div id="createindustries" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Industries</h4>
      </div>
      <div class="modal-body">
      <?php echo form_open_multipart('lead/industries','class="form-inner"') ?> 
<div class="row">
    <div class="form-group col-md-12">
        <label>Industries Name</label>
        <input class="form-control" name="indus_name"  type="text" value="" required>
    </div>
</div>
<div class="row">       
    <div class="sgnbtnmn form-group col-md-12">
      <div class="sgnbtn">
        <input id="signupbtn" type="submit" value="Add Industries" class="btn btn-success"  name="addindus">
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
                            <th>Industries Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            <?php $sl = 1; ?>
                            <?php foreach ($all_industries as $industries) {  ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?> clickable-row" style="cursor:pointer;"  >
                                    <td><?php echo $sl;?></td>
                                    <td><?php echo $industries->indus_name; ?></td>
                                     <td class="center">
                                        <a href="" class="btn btn-xs  btn-primary" data-toggle="modal" data-target="#Editindus<?php echo $industries->id;?>"><i class="fa fa-edit"></i></a> 
                                        <a href="<?php echo base_url("lead/delete_industries/$industries->id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
                                    </td>
                                    
                                </tr>
                                
        
        <div id="Editindus<?php echo $industries->id;?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Industries</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open_multipart('lead/update_industries','class="form-inner"') ?>        
        <div class="row">
        <input type="hidden" name="indus_id" value="<?php echo $industries->id;?>">
        <div class="form-group col-md-6">
        <label>Industries Name</label>
        <input class="form-control" name="indus_name"  type="text" value="<?php echo $industries->indus_name;?>" required>
        </div>
        
        </div>
    <div class="row">      
        <div class="sgnbtnmn form-group col-md-12">
        <div class="sgnbtn">
        <input id="signupbtn" type="submit" value="Update Industries" class="btn btn-success"  name="addindustries">
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