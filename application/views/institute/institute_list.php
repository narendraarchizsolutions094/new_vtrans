<div class="row">

    <!--  table area -->

    <div class="col-sm-12">

        <div  class="panel panel-default thumbnail">

 

            <div class="panel-heading no-print">
            <?php if (user_access('e39')) { ?>
                <div class="btn-group"> 

                    <a class="btn btn-success" href="<?php echo base_url("lead/add_institute") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_institute') ?> </a>  

                </div>
                <?php } ?>
            </div>

            <div class="panel-body">

                <table id="example" class="table table-striped table-bordered" style="width:100%">

                <thead>

                <tr>

                    <th><?php echo display('serial') ?></th>

                    <th><?php echo display('institute_name') ?></th>
                    
                    <th><?php echo display('profile_image') ?></th>
                    
                    <th><?php echo display('agreement_comision') ?></th>
                    
                    <th><?php echo display('agreement_doc') ?></th>
                    
                    <th><?php echo display('from_date') ?></th>
                    
                    <th><?php echo display('to_date') ?></th>

                    <th><?php echo display('country_name') ?></th> 

                    <th><?php echo display('state') ?></th>					

                    <th><?php echo display('status') ?></th>

                    <th><?php echo display('action') ?></th>

                </tr>

                </thead>

                <tbody>

                <?php if (!empty($institute_list)) { 

                    $sl = 1;foreach ($institute_list as $institute) {?>

                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">

                            <td><?php echo $sl; ?></td>

                            <td><?php echo $institute->institute_name; ?></td>
                            
                            <td><?php if($institute->profile_image!=NULL){ ?><img src="<?php echo base_url($institute->profile_image); ?>" alt="<?php echo display('profile_image') ?>" width="50" height="50"><?php }else{echo'N/A';} ?></td> 
                            
                            <td><?php echo $institute->agreement_comision; ?></td> 
                            
                            <td><?php if($institute->agreement_doc!=NULL){ ?><a href="<?php echo base_url($institute->agreement_doc); ?>" target="_blank"><i class="fa fa-file" aria-hidden="true" style="color:#37a000"></i></a><?php }else{echo'N/A';} ?></td> 
                            
                            <td><?php echo $institute->from_date; ?></td> 
                            
                            <td><?php echo $institute->to_date; ?></td> 
                            
                            <td><?php echo $institute->country_name; ?></td>
							
							<td><?php echo $institute->state; ?></td>

                            <td><?php echo (($institute->status==1)?display('active'):display('inactive')); ?></td>

                            <td class="center">
                            <?php if (user_access('f30')) { ?>

                                <a href="<?php echo base_url("lead/edit_institute/$institute->institute_id") ?>" class="btn btn-xs  btn-primary"><i class="fa fa-edit"></i></a> 
                            <?php }  if (user_access('f32')) { ?>

                                <a href="<?php echo base_url("lead/delete_institute/$institute->institute_id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
                                <?php } ?>
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
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>