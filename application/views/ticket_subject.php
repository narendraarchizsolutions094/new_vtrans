<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-default thumbnail">
            <div class="col-md-12">
                <?php if($this->session->flashdata('SUCCESSMSG')) { ?>
                <div role="alert" class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span
                            class="sr-only">Close</span></button>
                    <?=$this->session->flashdata('SUCCESSMSG')?>
                </div>
                <?php } 
                if(user_access('524'))
                {
                ?>
                <button class="btn btn-sm btn-success" style="float: left" type="button" data-toggle="modal"
                    data-target="#createnewReason"><i class="fa fa-plus"></i>
                    <?=display('add_ticket_problem_master')?></button>
                <?php
                }
                ?>
            </div>
            <br>
            <div class="panel-body">
                <table width="100%" class="datatable1 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="sorting_asc wid-20 th0" tabindex="0" rowspan="1" colspan="1">
                                <input type='checkbox' class="checked_all" value="check all">&nbsp;
                                <?php echo display('serial') ?></th>
                            <th class="th1">Title</th>
                            <th class="th1">Branch</th>
                            <th class="th1"><?=display('proccess')?></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subject)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($subject as $sub) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>" style="cursor:pointer;">
                            <td class="th0">
                                <input type='checkbox' onclick="event.stopPropagation();" name='enquiry_id[]'
                                    class="checkbox" value='<?php echo $sub->id;?>' style="display: inline;">
                                &nbsp; <?php echo $sl;?>
                            </td>
                            <td class="th1"><?php echo $sub->subject_title; ?></td>
                            <td class="th1"><?php echo $sub->branch_name; ?></td>
                            <td class="th1"><?php 
                            $process_in_each  = $sub->process_id;
                            $process_in_each  = explode(',',$process_in_each);
                            $p = '';
                            if(!empty($process_in_each)){
                              foreach($process_in_each as $e){
                                $process_name  = $this->common_model->get_process_name_by_id($e);
                                $p .= $process_name.', ';
                              }
                            }       
                            $p = rtrim($p, ", ");                   
                            echo $p;  
                            ?>
                            </td>
                            <td class="center">
                                <?php
                                if(user_access('525'))
                                {
                                    ?>
                                <a class="btn btn-xs  btn-primary" data-toggle="modal"
                                    data-target="#Editreason<?php echo $sub->id;?>"><i class="fa fa-edit"></i></a>
                                <?php
                                }
                                if(user_access('526'))
                                {
                                ?>
                                <a href="<?php echo base_url("ticket/delete_subject/$sub->id") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <div id="Editreason<?php echo $sub->id;?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Edit</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!--<form>-->
                                        <?php echo form_open_multipart('ticket/update_subject','class="form-inner"') ?>
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Title*</label>
                                                <input type="hidden" name="drop_id" value="<?php echo $sub->id;?>">
                                                <input class="form-control" name="subject" type="text"
                                                    value="<?php echo $sub->subject_title;?>" required="">
                                            </div>
                                            <div class="form-group col-sm-12">
                                              <label><?=display('proccess')?></label>
                                            <select  class="form-control" name="process[]" multiple required>        
                                              <?php foreach($products as $product){?>
                                              <option value="<?=$product->sb_id ?>" <?=(in_array($product->sb_id,explode(',',$sub->process_id)))?'selected':''?>><?=$product->product_name ?></option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-12" style="padding: 0px;">
                                            <div class="row">
                                                <div class="col-12" style="text-align:center;">
                                                    <button class="btn btn-success" type="submit">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
<script>
$('.checked_all').on('change', function() {
    $('.checkbox').prop('checked', $(this).prop("checked"));
});
$('.checkbox').change(function() {
    if ($('.checkbox:checked').length == $('.checkbox').length) {
        $('.checked_all').prop('checked', true);
    } else {
        $('.checked_all').prop('checked', false);
    }
});
$("#service").click(function() {
    if ($('#another-element:visible').length)
        $('#another-element').hide();
    else
        $('#another-element').show();
});
$("#task_create_div").click(function() {
    if ($('#task_create:visible').length)
        $('#task_create').hide();
    else
        $('#task_create').show();
});
</script>
<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
<!--------------- ADD NEW Drop Reason ------------->
<div id="createnewReason" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New</h4>
            </div>
            <div class="modal-body">
                <!--<form>-->
                <?php echo form_open_multipart('ticket/add_subject','class="form-inner"') ?>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Title<span style="color:red;">*</span></label>
                        <input class="form-control" name="subject" type="text" required="">
                    </div>
                    <div class="form-group col-sm-12">
                        <label><?=display('proccess')?></label>
                      <select  class="form-control" name="process[]" multiple required>        
                        <?php foreach($products as $product){?>
                        <option value="<?=$product->sb_id ?>"><?=$product->product_name ?></option>
                        <?php } ?>
                      </select>
                    </div>
                </div>
                <div class="col-12" style="padding: 0px;">
                    <div class="row">
                        <div class="col-12" style="text-align:center;">
                            <button class="btn btn-success" type="submit">Save</button>
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
<script>
function hide_td(id, id2) {
    var a = document.getElementById(id2);
    if (a.checked == true) {
        $('.' + id).css('visibility', 'visible');
        $('.' + id).css('display', 'table-cell');
        //  document.getElementsByClassName("th1").style.visibility = "hidden";
    } else {
        $('.' + id).css('visibility', 'hidden');
        $('.' + id).css('display', 'none');
    }
}
</script>