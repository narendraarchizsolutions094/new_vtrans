<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
 
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-success" href="<?php echo base_url("lead/add_datasource") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_datasource') ?> </a>  
                </div>
				 <div class="btn-group" style="float:right;">
              <a href="#"  class="btn btn-success" data-toggle="modal" data-target="#uploadbulk" title="upload Enquiry"> <i class="fa fa-upload" ></i> Upload</a> 
              
          </div>
		   <div class="btn-group" style="float:right;margin-right:10px;">
              <a href="#"  class="btn btn-success" data-toggle="modal" data-target="#AssignSelected" title="upload Enquiry"> Assign</a> 
              
          </div>
            </div>
            <div class="panel-body">
                <table class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?php echo display('serial') ?></th>
                    <th><?php echo display('datasource_name') ?></th> 
					<th>Available Enq</th>					
                    <th><?php echo display('status') ?></th>
                    <th><?php echo display('action') ?></th>
                </tr>
                </thead>
                <tbody>
			
                <?php if (!empty($datasource_list)) { 
                    $sl = 1;$j=0;foreach ($datasource_list as $datasource) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $datasource->datasource_name; ?></td> 
                            <td><?php  if(!empty($datasource_list2[$j]->count_enq)&& $datasource_list2[$j]->datasource_id==$datasource->datasource_id){echo $datasource_list2[$j]->count_enq;$j++;}else{ echo '0';} ?></td>  
                                                       
                            <td><?php echo (($datasource->status==1)?display('active'):display('inactive')); ?></td>
                            <td class="center">
                                <a href="<?php echo base_url("lead/edit_datasource/$datasource->datasource_id") ?>" class="btn btn-xs  btn-primary"><i class="fa fa-edit"></i></a> 
                                <a href="<?php echo base_url("lead/delete_datasource/$datasource->datasource_id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
                                <a href="<?php echo base_url("lead/view_datasource_data/$datasource->datasource_id") ?>" class="btn btn-xs  btn-info">View Data</a> 

                            </td>
                        </tr>
                        <?php $sl++; ?>
                    <?php } ?> 
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
	 <div id="uploadbulk" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
        <?php echo form_open_multipart('enquiry/upload_enquiry','class="form-inner"  id="upload_form"') ?> 
    <div class="modal-content card">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-title"></h4>
      </div>
      <div>
                <div class="form-group col-sm-12"> 
                <label>Data Source</label>
				   <select class="form-control"  name="datasource_name"> 
				<?php if (!empty($datasource_list)) { 
                    $sl = 1;foreach ($datasource_list as $datasource) {?>
                              <option value="<?php echo $datasource->datasource_id; ?>"><?=$datasource->datasource_name ?></option>
			
				<?php $sl++; ?>
                    <?php } ?> 
                <?php } ?>
				 </select> 
                </div> 
               <div class="form-group col-sm-12"> 
                <label>Upload Enquiry</label>
                <input type="file" name="img_file" class="form-control" accept=".csv"> 
                </div>				
      </div>
      
                <div class="col-md-6">                                                               
				    <label> For Download sample Please Select Process <i class="text-danger"></i></label>
                        <select name="product_id" id="pid" onchange="allcsv()" class="form-control">
                           <option value="" style="display:none;">Select</option>
                           <?php foreach($products as $product){?>
                           <option value="<?=$product->sb_id ?>"><?=$product->product_name ?></option>
                           <?php } ?>
                        </select> 
                </div>
       <div class="col-md-6">
        <button class="btn btn-success" type="submit" id="assign"><?php echo display('save'); ?></button>        
        <img src='<?= base_url('assets/images/loader.gif'); ?>' width='60px' height='60px' id="loader" style="display: none;">     
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo display('close'); ?></button>
      </div>
    </div>
</form>
  </div>
</div> 
<script>
 function allcsv(){
var pd=document.getElementById("pid").value;	 
window.location.href='<?php echo base_url();?>lead/createcsv/'+pd;
   }
</script> 


 <div id="AssignSelected" class="modal fade" role="dialog">
       <?php echo form_open_multipart('enquiry/assign_rowdata','class="form-inner"  id="upload_rawform"') ?> 
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Assignment</h4>
      </div>
      <div class="modal-body">
      
                <div class="row">
                  
            <div class="form-group col-sm-12"> 
                <label>Data Source</label>
				   <select class="form-control"  name="datasource_name"> 
				<?php if (!empty($datasource_list)) { 
                    $sl = 1;foreach ($datasource_list as $datasource) {?>
                              <option value="<?php echo $datasource->datasource_id; ?>"><?=$datasource->datasource_name ?></option>
			
				<?php $sl++; ?>
                    <?php } ?> 
                <?php } ?>
				 </select> 
                </div> 
            <div class="form-group col-md-12">  
            <label>Select Employee</label>                  
            <select class="form-control"  name="assign_employee">                    
            <?php foreach ($all_user as $user) { 
                           
                            if($this->session->companey_id==$user->companey_id){
                          ?>
                            <option value="<?php echo $user->pk_i_admin_id; ?>"><?=$user->s_display_name ?>&nbsp;<?=$user->last_name; ?></option>
			<?php }} ?>                                                      
            </select> 
            </div>
          
           <!--<div class="form-group col-md-12 text-center">            
           <label>Automated Call scheduling</label>	<br>	   
            <input type="checkbox" value=1 name="automated_call" class="form-control" >
          </div>-->

            <div class="form-group col-sm-12"> 
           			
            <input  class="btn btn-success" type="submit" value="Assign" id="rawassign">  
             <img src='<?= base_url('assets/images/loader.gif'); ?>' width='60px' height='60px' id="loader1" style="display: none;">         
            </div>
          
     
                    
                    
                </div>
                

        
          
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</form>
</div>

<script>
  $('#upload_form').submit(function(e) {
    // alert('sasas');
     // e.preventDefault();
    $.ajax({
      url       : "", 
      fileElementId :'img_file',
      dataType    : 'json',
       beforeSend: function() {
        // setting a timeout
        $("#loader").show();
        $('#assign').prop('disabled', true)

    },
      success : function (data)
      {  
        

                  $('#assign').prop('disabled', false);
                  location.reload();
        
          
        
      }
    });

  });

   $('#upload_rawform').submit(function(e) {
    // alert('sasas');
     // e.preventDefault();
    $.ajax({
      url       : "", 
      fileElementId :'img_file',
      dataType    : 'json',
       beforeSend: function() {
        // setting a timeout
        $("#loader1").show();
        $('#rawassign').prop('disabled', true)

    },
      success : function (data)
      {  
                  $('#rawassign').prop('disabled', false);
                  location.reload();
        
      }
    });

  });
</script>