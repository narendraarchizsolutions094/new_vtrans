<div class="row">

    <!--  table area -->

    <div class="col-sm-12">

        <div  class="panel panel-default thumbnail">

 

            <div class="panel-heading no-print">
            <?php 
             if (user_access(3051)) {
            ?>
                <div class="btn-group"> 

                    <a class="btn btn-success" href="<?php echo base_url("lead/add_subsource") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_subsource') ?> </a>  

                </div>
<?php } ?>
            </div>

            <div class="panel-body">

                <table class="datatable table table-striped table-bordered" cellspacing="0" width="100%">

                <thead>

                <tr>

                    <th><?php echo display('serial') ?></th>

                    <th><?php echo display('subsource_name') ?></th>

                    <th><?php echo display('lead_Source')?></th>                          

                    <th><?php echo display('status') ?></th>

                    <th><?php echo display('action') ?></th>

                </tr>

                </thead>

                <tbody>

                <?php if (!empty($subsource_list)) { 

                    $sl = 1;foreach ($subsource_list as $subsource) {?>

                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">

                            <td><?php echo $sl; ?></td>

                            <td><?php echo $subsource->subsource_name; ?></td>

                            <td><?php echo $subsource->lead_name; ?></td>                               

                            <td><?php echo (($subsource->status==1)?display('active'):display('inactive')); ?></td>

                            <td class="subsource">
<?php   if (user_access(3052)) { ?>

                                <a href="<?php echo base_url("lead/edit_subsource/$subsource->subsource_id") ?>" class="btn btn-xs  btn-primary"><i class="fa fa-edit"></i></a> 
<?php } if (user_access(3054)) { ?>

                                <a href="<?php echo base_url("lead/delete_subsource/$subsource->subsource_id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a> 
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