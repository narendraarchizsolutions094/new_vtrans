<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ticketa</title>
    <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="<?= base_url() ?>" sizes="32x32" />
    <!-- Font Awesome 4.7.0 -->
    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- semantic css -->
    <link href="<?= base_url() ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css" />
    <!-- sliderAccess css -->
    <link href="<?= base_url() ?>assets/css/jquery-ui-timepicker-addon.min.css" rel="stylesheet"
        type="text/css" />
    <!-- slider  -->
    <link href="<?= base_url() ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables CSS -->
    <link href="<?= base_url() ?>assets/datatables/css/dataTables.min.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- pe-icon-7-stroke -->
    <link href="<?= base_url() ?>assets/css/pe-icon-7-stroke.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- themify icon css -->
    <link href="<?= base_url() ?>assets/css/themify-icons.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- Pace css -->
    <link href="<?= base_url() ?>assets/css/flash.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?= base_url() ?>assets/css/custom.css?v=1.0" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <!-- jQuery  -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js?v=1.0" type="text/javascript"></script>
<script type="text/javascript">
  window.getCookie = function(name) {
  var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  if (match) return match[2];
  else return false;
}
</script>
<div class="row">
			<div class="col-md-12"> 
					<div class="row">
						<div class="">
							<div class="panel-body">
 <?php 
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
 ?>

							<!-- Filter Panel End -->
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-12">
									<table id="ticket_table" class=" table table-striped table-bordered" style="width:100%;">
										<thead>
										<th class="noExport sorting_disabled">
                    <input type='checkbox' class="checked_all1" value="check all" >
                     </th>
											<th>S.No.</th>
                      <?=($showall or in_array(1,$acolarr))?'<th>Ticket</th>':''?>

                      <?php
                      if($this->session->comp_id==65)
                      {
                      ?>
                        <?=($showall or in_array(15,$acolarr))?'<th>'.display('tracking_no').'</th>':''?>
                      <?php
                      }
                      ?>
                      <?=($showall or in_array(7,$acolarr))?'<th>Created By</th>':''?>
                      <?=($showall or in_array(9,$acolarr))?'<th>Created Date</th>':''?>
                      <?=($showall or in_array(18,$acolarr))?'<th>'.display('last_updated').'</th>':''?>                      
											<?=($showall or in_array(2,$acolarr))?'<th>Client</th>':''?>
										  <?=($showall or in_array(3,$acolarr))?'<th>Email</th>':''?>
											<?=($showall or in_array(4,$acolarr))?'<th>Phone</th>':''?>
											<?=($showall or in_array(5,$acolarr))?'<th>Product</th>':''?>
											<?=($showall or in_array(6,$acolarr))?'<th>Assign To</th>':''?>
                      <?=($showall or in_array(17,$acolarr))?'<th>Assign By</th>':''?>
                      <?=($showall or in_array(8,$acolarr))?'<th>Priority</th>':''?>
                      <?=($showall or in_array(19,$acolarr))?'<th>'.display('ticket_problem').'</th>':''?>
										  <?=($showall or in_array(10,$acolarr))?'<th>Referred By</th>':''?>
                      <?=($showall or in_array(11,$acolarr))?'<th>'.display('data_source').'</th>':''?>
                      <?=($showall or in_array(12,$acolarr))?'<th>'.display('stage').'</th>':''?>
                      <?=($showall or in_array(13,$acolarr))?'<th>Sub Stage</th>':''?>
                      <?=($showall or in_array(14,$acolarr))?'<th>Review</th>':''?>
                      <?=($showall or in_array(16,$acolarr))?'<th>Status</th>':''?>
                      <?php 
                      if(!empty($dacolarr) and !empty($dfields))
                      {
                        foreach($dfields as $ind => $flds)
                        {                
                          if(!empty(in_array($flds->input_id, $dacolarr )))
                          {            
                          ?><th><?php echo $flds->input_label; ?></th><?php 
                          }
                        }
                       } ?>

                      <?php
                    //   if($followup)
                    //   {
                    //     echo'<th>Ticket Subject</th>
                    //           <th>Ticket Stage</th>
                    //           <th>Ticket Sub Stage</th>
                    //           <th>Ticket Remark</th>';
                    //   }
                      ?>

										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							</form>
								<!--<?php echo form_close(); ?>-->
						</div>
					</div>
				</div>
			</div>
		</div>
<!--------------------TABLE COLOUMN CONFIG----------------------------------------------->
<script>
  function select_all(){
    var select_all = document.getElementById("selectall"); //select all checkbox
    var checkboxes = document.getElementsByClassName("choose-col"); //checkbox items
    var dcheckboxes = document.getElementsByClassName("dchoose-col"); //checkbox items
    //select all checkboxes
    select_all.addEventListener("change", function(e){
      for (i = 0; i < checkboxes.length; i++) { 
        checkboxes[i].checked = select_all.checked;
      }
      for (i = 0; i < dcheckboxes.length; i++) { 
        dcheckboxes[i].checked = select_all.checked;
      }
    });
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].addEventListener('change', function(e){ 
        if(this.checked == false){
          select_all.checked = false;
        }
        if(document.querySelectorAll('.choose-col:checked').length == checkboxes.length){
          select_all.checked = true;
        }
      });
  }

for (var i = 0; i < dcheckboxes.length; i++) {
  
  dcheckboxes[i].addEventListener('change', function(e){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){
      select_all.checked = false;
    }
    //check "select all" if all checkbox items are checked
    if(document.querySelectorAll('.dchoose-col:checked').length == dcheckboxes.length){
      select_all.checked = true;
    }
  });
}
}
</script>
<script type="text/javascript">
  $(document).on("click", ".set-col-table", function(e){    
    e.preventDefault();
    if($(".choose-col:checked").length == 0 && $(".dchoose-col:checked").length == 0 ){      
      return false;
    }
    var chkval = "";
    $(".choose-col:checked").each(function(){      
      chkval += $(this).val()+",";
    });
    var dchkval = "";
    $(".dchoose-col:checked").each(function(){      
      dchkval += $(this).val()+",";
    });    
    document.cookie = "ticket_allowcols="+chkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    document.cookie = "ticket_dallowcols="+dchkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";   
    location.reload();    
  });




  function reset_input(){
$('input:checkbox').removeAttr('checked');
}
</script>

<script type="text/javascript">
  <?php  
  $_POST=$filters;
  if(!empty($_POST)){
  ?>
  $(document).ready(function() {
    $('#ticket_table').DataTable({         
            "processing": true,
            "scrollX": true,
            "scrollY": 820,
            "serverSide": true,          
            "lengthMenu": [ [ -1], ["All"] ],
            "columnDefs": [{ "orderable": false, "targets": 0 }],
            "order": [[ 1, "desc" ]],
            "ajax": {
                "url": "<?=base_url().'Ticket/report_ticket_load_data'?>",
                "type": "POST",
                error:function(u,v,w) 
                {
                  alert(w);
                }
                },              
                <?php if(user_access(317)) { ?>
          dom: "<'row text-center'<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp", 
          buttons: [  
              {extend: 'copy', className: 'btn-xs btn',exportOptions: {
                          columns: "thead th:not(.noExport)"
                      }}, 
              {extend: 'csv', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                          columns: "thead th:not(.noExport)"
                      }}, 
              {extend: 'excel', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn', title: 'exportTitle',exportOptions: {
                          columns: "thead th:not(.noExport)"
                      }}, 
              {extend: 'pdf', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                          columns: "thead th:not(.noExport)"
                      }}, 
              {extend: 'print', className: 'btn-xs btn',exportOptions: {
                          columns: "thead th:not(.noExport)"
                      }} 
              ]   <?php  } ?>  
      });
  });
  <?php
  }
  ?>

</script>


<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script> 
<!-- DataTables JavaScript -->
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>  
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script>
    $('#something').click(function() {
        //alert('hi');  
        //document.location = '';
    });
    </script>
    <!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-database.js"></script>

    <script src="<?= base_url() ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <!---- new js file added by pp ------------->
    <script src="<?= base_url() ?>assets/js/dashboard_js.js" type="text/javascript"></script>
    <!----------------------------------------------------------------------------------------------->
    <!-- bootstrap js -->
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- bootstrap timepicker -->
    <script src="<?= base_url() ?>assets/js/jquery-ui-sliderAccess.js?v=1.0" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/select2.min.js?v=1.0" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/sparkline.min.js?v=1.0" type="text/javascript"></script>

    <!-- ChartJs JavaScript -->
    <script src="<?= base_url() ?>assets/js/Chart.min.js?v=1.0" type="text/javascript"></script>
    <!-- semantic js -->
    <script src="<?= base_url() ?>assets/js/semantic.min.js?v=1.0" type="text/javascript"></script>
    <!-- DataTables JavaScript -->
    <script src="<?= base_url() ?>assets/datatables/js/dataTables.min.js?v=1.0"></script>
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>     -->
    <script src="<?= base_url() ?>assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="<?= base_url() ?>assets/js/tableHeadFixer.js?v=1.0" type="text/javascript"></script>
    <!-- Admin Script -->
    <script src="<?= base_url() ?>assets/js/frame.js?v=1.0?v=1.0" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url() ?>assets/js/custom.js?v=1.0.1" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
</body>

</html>
