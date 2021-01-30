<div class="row">
			<div class="col-md-12"> 
					<div class="panel-heading no-print" style ="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
						<div class="row">
							<div class="btn-group"> 
                  <a class="btn btn-primary" href="<?php echo base_url("report/ticket_reports") ?>"> <i class="fa fa-list"></i>
                        <?php echo display('reports_list') ?> </a>

                    <?php if(user_access(220)) { if(!empty($this->session->telephony_token)){  ?>
                    <a class="btn btn-success" href="<?php echo base_url("call_report/index") ?>"
                        style="margin-left: 5 px !important ;"> <i class="fa fa-list"></i>
                        <?php echo display('telephone_call_reports') ?> </a>
                    <?php } }?>
                
				            </div>
							<div class="col-md-4 col-sm-4 col-xs-4 pull-right" >  
					          <div style="float: right;">   

                      <button class="btn btn-success" data-toggle="modal" data-target="#table-col-conf">Report Header</button>

					         
					        </div>       
					      </div>
						</div>
					</div>
					<div class="row">
						<div class="">
							<div class="panel-body">
							<!-- Filter Panel Start -->

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
                      if($this->session->companey_id==65)
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
<div id="table-col-conf" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="width: 96%;">
 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Table Column Configuration</h4>
      </div>
      <div class="modal-body">         
           <div class="row">
             <div class="col-md-3">
                <label class=""><input type="checkbox" id="selectall" onclick="select_all()">&nbsp;Select All</label>
             </div>
           </div>
        <hr>
          <div class="row">
          <div class = "col-md-4">             
          <label class=""><input type="checkbox" class="choose-col" id="choose-col" value = "1" <?php echo ($showall == true or in_array(1, $acolarr)) ? "checked" : ""; ?>> Ticket</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "2"  <?php echo ($showall == true or in_array(2, $acolarr)) ? "checked" : ""; ?>>  Client</label> 
          </div>
          <div class = "col-md-4">  
          <label  class=""><input type="checkbox" class="choose-col"  value = "3"  <?php echo ($showall == true or in_array(3, $acolarr)) ? "checked" : ""; ?>> Email</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "4"  <?php echo ($showall == true or in_array(4, $acolarr)) ? "checked" : ""; ?>>  Phone </label>
          </div>
          
          
          
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "5"  <?php echo ($showall == true or in_array(5, $acolarr)) ? "checked" : ""; ?>>  Product </label>
              </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "6"  <?php echo ($showall == true or in_array(6, $acolarr)) ? "checked" : ""; ?>>  Assign To </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "17"  <?php echo ($showall == true or in_array(17, $acolarr)) ? "checked" : ""; ?>>  Assign By </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "7"  <?php echo ($showall == true or in_array(7, $acolarr)) ? "checked" : ""; ?>> Created By</label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "8"  <?php echo ($showall == true or in_array(8, $acolarr)) ? "checked" : ""; ?>>  Priority</label>  &nbsp;
          </div>

          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "19"  <?php echo ($showall == true or in_array(19, $acolarr)) ? "checked" : ""; ?>>  <?=display('ticket_problem')?> </label>  &nbsp;
          </div>
          
          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "9"  <?php echo ($showall == true or in_array(9, $acolarr)) ? "checked" : ""; ?>>     <?php echo display("create_date"); ?></label> &nbsp;
          </div>
          <div class = "col-md-4">            
              <label class=""><input type="checkbox" class="choose-col"  value = "18"  <?php echo ($showall == true or in_array(18, $acolarr)) ? "checked" : ""; ?>>     <?php echo display("last_updated"); ?></label> &nbsp;
          </div>

        <div class = "col-md-4">  
          
           <label  class=""><input type="checkbox" class="choose-col"  value = "10"  <?php echo ($showall == true or in_array(10, $acolarr)) ? "checked" : ""; ?>> <?php echo "<th>Referred By</th>"; ?></label>  &nbsp; 
         </div>

         <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "11"  <?php echo ($showall == true or in_array(11, $acolarr)) ? "checked" : ""; ?>>   <?php echo display("data_source"); ?></label>  &nbsp; 
           </div>
          
         <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "12"  <?php echo ($showall == true or in_array(12, $acolarr)) ? "checked" : ""; ?>>  Stage</label>  &nbsp;
          </div>

          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "13"  <?php echo ($showall == true or in_array(13, $acolarr)) ? "checked" : ""; ?>>  Sub Stage</label>  &nbsp;
          </div>

           <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "14"  <?php echo ($showall == true or in_array(14, $acolarr)) ? "checked" : ""; ?>>  Review</label>  &nbsp;
          </div>

        
         <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "16"  <?php echo ($showall == true or in_array(16, $acolarr)) ? "checked" : ""; ?>>Ticket Status</label>  &nbsp;
          </div>
           
        <?php   
        if(!empty($dfields)) 
        {           
            foreach($dfields as $ind => $fld)
            {
              if(in_array($fld->input_id,$table_config_list))
              {
            ?>
            <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="dchoose-col"  value = "<?php echo $fld->input_id; ?>"  <?php echo (in_array($fld->input_id, $dacolarr)) ? "checked" : ""; ?>>   <?php echo ucwords($fld->input_label); ?></label>  &nbsp;
          </div>
            <?php   
              }
            }
            ?>
             </div>
          <?php } ?>
                
              <div class="col-12" style="padding: 0px;">
                <div class="row">              
                  <div class="col-12" style="text-align:center;">                                                
                               
                  </div>
                </div>                                   
              </div> 
                  
         
      </div>
      <div class="modal-footer">
        <button class="btn btn-success set-col-table" type="button">Save</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

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

<script>
  
$(document).ready(function(){
   $("#save_advance_filters").on('click',function(e){
    e.preventDefault();
    var arr = Array();  
    $("input[name='filter_checkbox']:checked").each(function(){
      arr.push($(this).val());
    });        
    setCookie('ticket_filter_setting',arr,365);      
    alert('Your custom filters saved successfully.');
  });
});

var run = 0 ;
$(document).ready(function() {


  $('#go_filter').click(function(e) {
        e.preventDefault();
        var form_data = $("#ticket_filter").serialize();       
        //alert(form_data);
        $.ajax({
        url: '<?=base_url()?>ticket/ticket_set_filters_session',
        type: 'post',
        data: form_data,
        success: function(responseData){
          $("#ticket_filter").submit();
        }
        });
        
    });
});

$(document).ready(function() {

$("#filter_and_save").on("click", function(e) {
        e.preventDefault();
        // var data= $("#ticket_filter").serialize();
        // alert(data);
        e.preventDefault();
        var form_data = $("#ticket_filter").serialize();       
        //alert(form_data);
        $.ajax({
        url: '<?=base_url()?>ticket/ticket_set_filters_session',
        type: 'post',
        data: form_data,
        success: function(responseData){
          $("#ticket_filter").submit();
        }
        });
        var title = window.prompt("Enter Report Name");
        if (title) {
            var url = "<?=base_url('report/create_report')?>";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'filters': $("#ticket_filter").serialize(),
                    'report_name': title
                },
                success: function(result) {
                    result = JSON.parse(result);
                    if (result.status) {
                        $("#ticket_filter").submit();
                    } else {
                        alert(result.msg);
                    }
                }
            });
        } else {
            alert("Report not saved");
        }
    });   
    });   

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
            "scrollY": 520,
            "serverSide": true,          
            "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
            "columnDefs": [{ "orderable": false, "targets": 0 }],
            "order": [[ 1, "desc" ]],
            "ajax": {
                "url": "<?=base_url().'Ticket/ticket_load_data'?>",
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


<!--   Table Config -->

<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script> 
<!-- DataTables JavaScript -->
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>  
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>