<div class="row">
  <!--  table area -->
  <div class="col-sm-12">

    <div class="panel panel-default thumbnail">
      <div class="col-md-12">

        <br>

        <?php if ($this->session->flashdata('SUCCESSMSG')) { ?>
          <div role="alert" class="alert alert-success">
            <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
            <strong>Well done!</strong>
            <?= $this->session->flashdata('SUCCESSMSG') ?>
          </div>
        <?php } ?>

        <br>

        <div class="btn-group col-md-1" role="group" aria-label="Button group" style="float: right">
          <button class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-check-circle"></i>&nbsp;

          </button>
          <div class="dropdown-menu" style="max-height: 400px; overflow: auto; position: absolute; will-change: transform; top: 26px; left: 0px; margin-left: -110px; transform: translate3d(0px, -7px, 0px);" x-placement="top-start">
            <ul style="list-style-type:none;">
              <li><label><input type="checkbox" onclick="hide_td('th0','tt0')" id="tt0" checked> S.No.</label></li>
              <li><label><input type="checkbox" onclick="hide_td('th1','tt1')" id="tt1" checked> API Name</label></li>
              <li><label><input type="checkbox" onclick="hide_td('th2','tt2')" id="tt2" checked> URL</label></li>




            </ul>

          </div>
          <br>


        </div>




      </div>
      <br>

      <div class="panel-body">
        <div id="exTab3" class="">
          <ul class="nav nav-pills">

            <li class="active">
              <a href="#tab-integration" data-toggle="tab">Integration</a>
            </li>

            <li>
              <a href="#tab-templates" data-toggle="tab">Templates</a>
            </li>

          </ul>

          <div class="tab-content clearfix">
            <div class="tab-pane active" id="tab-integration">
              <?php if (user_access(50) == true) { ?>

                <br>
                <button class="btn btn-sm btn-success" style="float: left" type="button" data-toggle="modal" data-target="#createnewintegration"><i class="fa fa-plus"></i> Add New Integration</button>
                <br>
              <?php } ?>
              <br>
              <form action='' method="post" id="apitable">
                <table width="100%" class="datatable1 table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="sorting_asc wid-20 th0" tabindex="0" rowspan="1" colspan="1"><input type='checkbox' class="checked_all" value="check all">&nbsp; <?php echo display('serial') ?></th>
                      <th class="th1">API Name</th>
                      <th class="th2">API URL</th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($api_list)) { ?>
                      <?php $sl = 1; ?>
                      <?php foreach ($api_list as $list) { ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>" style="cursor:pointer;" data-toggle="modal" data-target="#createnewintegration<?php echo $list->api_id; ?>">
                          <td class="th0"><input type='checkbox' name='user_status[]' onclick="event.stopPropagation();" class="checkbox" value='<?php echo $list->api_id; ?>'>&nbsp; <?php echo $sl; ?></td>

                          <td class="th1"><?php echo $list->api_name; ?></td>
                          <td class="th2"><?php echo $list->api_url; ?></td>

                        </tr>


                        <!--------------- ADD NEW API ------------->





                        <?php $sl++; ?>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>
                <?php if (user_access(53) == true) { ?>

                  <button class="btn btn-danger" type="button" onclick="return is_delete()">
                    <i class="ion-close-circled"></i>
                    Delete
                  </button>
                <?php } ?>
              </form>

              <?php if (user_access(51) == true) { ?>

                <?php foreach ($api_list as $list) { ?>
                  <div id="createnewintegration<?php echo $list->api_id; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Send Whatsapp notifications to your client, enter details below to configure</h4>
                        </div>
                        <div class="modal-body">
                          <!--<form>-->
                          <?php echo form_open_multipart('whatsappapi/api_details', 'class="form-inner"') ?>


                          <div class="row">
                            <input type="hidden" name="id" value="<?php echo $list->api_id; ?>">
                            <div class="form-group   col-sm-6">
                              <label>API Name*</label>

                              <input class="form-control" name="api_name" type="text" required="" value="<?php echo $list->api_name; ?>">

                            </div>


                            <div class="form-group col-sm-6">
                              <label>HTTP API*</label>
                              <input class="form-control" name="api_url" type="text" required="" value="<?php echo $list->api_url; ?>">
                            </div>

                          </div>
                          <div class="col-12" style="padding: 0px;">
                            <div class="row">
                              <div class="col-12" style="text-align:center;">
                                <button class="btn btn-success" type="submit">Save</button>
                              </div>
                            </div>
                          </div>


                          <?php echo form_close() ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!---------------------------------------------------------------->



              <?php }
              } ?>
            </div>

            <div class="tab-pane" id="tab-templates">
              <br>
              <?php if (user_access(50) == true) { ?>

                <button class="btn btn-sm btn-success" style="float: left" type="button" data-toggle="modal" data-target="#createnewtemplate"><i class="fa fa-plus"></i> Add New Template</button>

                <br>
                <br>
              <?php } ?>
              <form action='' method="post" id="temptable">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="sorting_asc wid-20 th0" tabindex="0" rowspan="1" colspan="1"><input type='checkbox' class="checked_alltemp" value="check all">&nbsp; <?php echo display('serial') ?></th>
                      <th class="th1">Template Name</th>
                      <th class="th2">Template Content</th>
                      <th class="th3">Uploaded Media</th>
                      <th class="th3">Process</th>
                      <th class="th3">Stage</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($temp_list)) { ?>
                      <?php $sl = 1; ?>
                      <?php foreach ($temp_list as $tlist) { ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>" style="cursor:pointer;" data-toggle="modal" data-target="#createnewtemplate<?php echo $tlist->temp_id; ?>">
                          <td class="th0"><input type='checkbox' name='sel_temp[]' onclick="event.stopPropagation();" name='enquiry_id[]' class="checkboxtemp" value='<?php echo $tlist->temp_id; ?>'>&nbsp; <?php echo $sl; ?></td>

                          <td class="th1"><?php echo $tlist->template_name; ?></td>
                          <td class="th2"><?php echo $tlist->template_content; ?></td>
                          <td class="th3">

                            <?php if (!empty($tlist->media)) { ?>
                              <a href="<?php echo base_url($tlist->media); ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <?php } ?>
                          </td>
                          <td class="th3"><?php
                                         $stage = explode(',', $tlist->stage);
                                         $process = explode(',', $tlist->process); 
                                        foreach ($products as $product) { 
                                            if (in_array($product->sb_id, $process)) {
                                                                                             echo  $product->product_name,',';
                                                                                          } } ?></td>
                                                                                          
                                              <td class="th3">
                                                 <?php if (in_array('1', $stage)) {  echo display("enquiry").','; }?>
                                                     <?php  if (in_array('2', $stage)) { echo display("lead").','; } ?>
                                                     <?php  if (in_array('3', $stage)) { echo display("client").','; } ?></option>
                                                <?php
                                                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                                                if (!empty($enquiry_separation)) {
                                                   $enquiry_separation = json_decode($enquiry_separation, true);
                                                   foreach ($enquiry_separation as $key => $value) { 
                                                       if (in_array($key, $stage)) {?>
                                                       <?= $ctitle = $enquiry_separation[$key]['title'],',';  ?>
                                                                                  <?php  } } } ?></td>
                        </tr>




                        <?php $sl++; ?>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>

                <button class="btn btn-danger" type="button" onclick="return is_deleteTemp()">
                  <i class="ion-close-circled"></i>
                  Delete
                </button>
              </form>


              <?php foreach ($temp_list as $tlist) { ?>
                <div id="createnewtemplate<?php echo $tlist->temp_id; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Template <?php echo $tlist->template_name; ?></h4>
                      </div>
                      <div class="modal-body">
                        <!--<form>-->
                        <?php echo form_open_multipart('whatsappapi/template_details', 'class="form-inner"') ?>


                        <div class="row">
                          <div class="form-group col-sm-6">
                            <div class="form-group">
                              <label>Process</label>
                              <?php
                              $stage = explode(',', $tlist->stage);
                              $process = explode(',', $tlist->process);
                              ?>
                              <select class="form-control process multiple" name="process[]" multiple required>
                                <?php foreach ($products as $product) { ?>
                                  <option value="<?= $product->sb_id ?>" <?php if (in_array($product->sb_id, $process)) {
                                                                          echo 'selected';
                                                                        } ?>><?= $product->product_name ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-sm-6">
                            <div class="form-group">

                              <label>Stage</label>
                              <select class="form-control process multiple" name="stage[]" multiple required>
                                <option value="1" <?php if (in_array('1', $stage)) {
                                                    echo 'selected';
                                                  } ?>><?= display("enquiry") ?></option>
                                <option value="2" <?php if (in_array('2', $stage)) {
                                                    echo 'selected';
                                                  } ?>><?= display("lead") ?></option>
                                <option value="3" <?php if (in_array('3', $stage)) {
                                                    echo 'selected';
                                                  } ?>><?= display("client") ?></option>
                                <?php
                                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                                if (!empty($enquiry_separation)) {
                                  $enquiry_separation = json_decode($enquiry_separation, true);
                                  foreach ($enquiry_separation as $key => $value) { ?>
                                    <option value="<?= $key ?>" <?php if (in_array($key, $stage)) {
                                                                  echo 'selected';
                                                                } ?>> <?= $ctitle = $enquiry_separation[$key]['title'];  ?></option>
                                <?php }
                                } ?>
                              </select>
                            </div>
                          </div>
                          <input type="hidden" name="template_id" value="<?php echo $tlist->temp_id; ?>">

                          <div class="form-group   col-sm-12">
                            <label>Template Name*</label>

                            <input class="form-control" name="template_name" type="text" required="" value="<?php echo $tlist->template_name; ?>">

                          </div>


                          <div class="form-group col-sm-12">
                            <label>Template Content</label>
                            <textarea class="form-control" name="template_content" rows="8"><?php echo $tlist->template_content; ?></textarea>
                          </div>


                          <div class="col-sm-12" style="padding: 0px; text-align:center;">


                            <button class="btn btn-success" type="submit">Save</button>

                          </div>
                        </div>

                        <?php echo form_close() ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>



              <?php } ?>
            </div>


          </div>
        </div>


        <!-- /.table-responsive -->



      </div>
    </div>





  </div>
</div>
<script>
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
  #exTab3 .nav-pills>li>a {
    border-radius: 4px 4px 0 0;
  }

  #exTab3 .tab-content {
    background-color: #f1f3f6;
    padding: 5px 15px;
  }

  .nav-pills>li.active>a,
  .nav-pills>li.active>a:focus,
  .nav-pills>li.active>a:hover {
    color: white;
    background-color: #37a000;
  }

  .nav-pills>li>a {
    border-radius: 5px;
    padding: 10px;
    color: #000;
    font-weight: 600;
  }

  .nav-pills>li>a:hover {
    color: #000;
    background-color: transparent;
  }


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

<!--------------- ADD NEW API ------------->


<div id="createnewintegration" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Whatsapp notifications to your client, enter details below to configure</h4>
      </div>
      <div class="modal-body">
        <!--<form>-->
        <?php echo form_open_multipart('whatsappapi/createapi', 'class="form-inner"') ?>


        <div class="row">

          <div class="form-group   col-sm-6">
            <label>API Name*</label>

            <input class="form-control" name="api_name" type="text" required="">

          </div>


          <div class="form-group col-sm-6">
            <label>HTTP API*</label>
            <input class="form-control" name="api_url" type="text" required="">
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
<!---------------------------------------------------------------->


<!--------------- ADD NEW Template ------------->


<div id="createnewtemplate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Template</h4>
      </div>
      <div class="modal-body">
        <!--<form>-->
        <?php echo form_open_multipart('whatsappapi/createtemplate', 'class="form-inner"') ?>
        <div class="row">
          <div class="form-group col-sm-12">
            <div class="form-group">
              <label>Process</label>
              <select class="form-control process multiple" name="process[]" multiple required>
                <?php foreach ($products as $product) { ?>
                  <option value="<?= $product->sb_id ?>"><?= $product->product_name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group col-sm-12">
            <div class="form-group">
              <label>Stage</label>
              <select class="form-control process multiple" name="stage[]" multiple required>
                <option value="1"><?= display("enquiry") ?></option>
                <option value="2"><?= display("lead") ?></option>
                <option value="3"><?= display("client") ?></option>
                <?php
                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                  foreach ($enquiry_separation as $key => $value) { ?>
                    <option value="<?= $key ?>"> <?= $ctitle = $enquiry_separation[$key]['title'];  ?></option>
                <?php }
                } ?>
              </select>
            </div>
          </div>
          <div class="form-group   col-sm-12">
            <label>Template Name*</label>
            <input class="form-control" name="template_name" type="text" required="">
          </div>
          <div class="form-group   col-sm-12">
            <label>Media Upload*</label>
            <input class="form-control" name="media" type="file">
          </div>
          <div class="form-group col-sm-12">
            <label>Template Content</label>
            <textarea class="form-control" name="template_content" rows="10"></textarea>
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


<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
  $('.multiple').select2({});


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


  $('.checked_alltemp').on('change', function() {
    $('.checkboxtemp').prop('checked', $(this).prop("checked"));
  });
  $('.checkboxtemp').change(function() {
    if ($('.checkboxtemp:checked').length == $('.checkboxtemp').length) {
      $('.checked_alltemp').prop('checked', true);
    } else {
      $('.checked_alltemp').prop('checked', false);
    }
  });




  function is_delete() {
    var x = confirm('Are you sure want to delete ? ');
    if (x == true) {
      $.ajax({
          type: 'POST',
          url: '<?php echo base_url(); ?>whatsappapi/delete_api',
          data: $('#apitable').serialize()
        })
        .done(function(data) {
          alert("success!");
          location.reload();
        })
        .fail(function() {
          alert("fail!");

        });
    } else {
      location.reload();
    }
  }


  function is_deleteTemp() {

    var x = confirm('Are you sure want to delete ? ');
    if (x == true) {
      $.ajax({
          type: 'POST',
          url: '<?php echo base_url(); ?>whatsappapi/delete_template',
          data: $('#temptable').serialize()
        })
        .done(function(data) {
          alert("success!");
          location.reload();
        })
        .fail(function() {
          alert("fail!");

        });
    } else {
      location.reload();
    }
  }
</script>