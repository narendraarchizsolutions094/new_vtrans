<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" type="text/javascript"></script> -->
<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
  <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
          <?php
          if(user_access('1020'))
          {
          ?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a> 
          <?php
          }
          ?>   
          <?php
if(empty($_COOKIE['visits_filter_setting'])) {
	$variable=[];
} else {
$variable=explode(',',$_COOKIE['visits_filter_setting']);
}
?>   

        </div>
        <div class="col-md-2" style="float: right;">
		    <div class="btn-group dropdown-filter">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by <span class="caret"></span>
              </button>              
              <ul class="filter-dropdown-menu dropdown-menu">   
                    <li>
                      <label>
                      <input type="checkbox" value="date" id="datecheckbox" name="filter_checkbox" <?php if(in_array('date',$variable)){echo'checked';} ?>> Date </label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="for" id="forcheckbox" name="filter_checkbox" <?php if(in_array('for',$variable)){echo'checked';} ?>> For</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="rating" id="ratingcheckbox" name="filter_checkbox" <?php if(in_array('rating',$variable)){echo'checked';} ?>> Rating</label>
                    </li>
                    <li>
                      <label>
                      <input type="checkbox" value="difference" id="differencecheckbox" name="filter_checkbox" <?php if(in_array('difference',$variable)){echo'checked';} ?>> Difference</label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="createdby" id="createdbycheckbox" name="filter_checkbox" <?php if(in_array('createdby',$variable)){echo'checked';} ?>> Created By</label>
                    </li>   
                    <li>
                      <label>
                      <input type="checkbox" value="company" id="companycheckbox" name="filter_checkbox" <?php if(in_array('company',$variable)){echo'checked';} ?>>Company</label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="expensetype" id="expensetypecheckbox" name="filter_checkbox" <?php if(in_array('expensetype',$variable)){echo'checked';} ?>> Expense Type</label>
                    </li>        
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>
            <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="fa fa-sliders"></i>
              </a>  
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto; left: -136px;">
            <?php if(user_access('1024'))  {  ?>

               <a class="btn" data-toggle="modal"  data-target="#approve_expense" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;" onclick="">Approve</a>                        
               <?php } ?>
               <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;">Table Config</a>                        
            </div>                                         
          </div>
		</div>
</div>

<div class="row" style=" margin: 15px 0px; padding: 15px; <?php if(empty($_COOKIE['visits_filter_setting'])){ echo'display:none'; }  ?>" id="filter_pannel">
<div id="datefilter" style="<?php if(!in_array('date',$variable)){echo'display:none';} ?>">
	<div class="col-lg-3"  >
        <div class="form-group">
          <label>From</label>
          <input class="v_filter form-control form-date" name="from_date" >
       
        </div>
    </div>

      <div class="col-lg-3" id="tofilter">
        <div class="form-group">
          <label>To</label>
           <input  class="v_filter form-control form-date" name="to_date" >
        </div>
      </div>
</div>
    <div class="col-lg-3" id="forfilter" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>For</label>
        	<select class="v_filter form-control" name="enquiry_id" >
        		<option value="">Select</option>
        		<?php
        		// if(!empty($all_enquiry))
        		// {
        		// 	foreach ($all_enquiry as $row) 
        		// 	{  
          //       $row  = (array)$row;
        		// 		echo'<option value="'.$row['enquiry_id'].'">'.$row['name_prefix'].' '.$row['name'].' '.$row['lastname'].'</option>';
        		// 	}
        		// }
        		?>
        	</select>
        </div>
    </div>
     <div class="col-lg-3" id="ratingfilter" style="<?php if(!in_array('rating',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Rating</label>
       	<select class="form-control v_filter" name="rating">
              <option value="">Select</option>
              <option value="1 star">1 star</option>
              <option value="2 star"> 2 star</option>
              <option value="3 star"> 3 star</option>
              <option value="4 star"> 4 star</option>
              <option value="5 star"> 5 star</option>
            </select>
        </div>
    </div>
     <div class="col-lg-3" id="differencefilter" style="<?php if(!in_array('difference',$variable)){echo'display:none';} ?>">
        <div class="form-group">
            <!-- <label for="amount">Difference range: <span id="range_value">0 - 100</span></label> -->
            <label>Minimum Difference </label>
            <input class="form-control" id="min" onkeyup="refresh_table()">
           
        </div>
    </div>
    <div class="col-lg-3" id="differencefilter" style="<?php if(!in_array('difference',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        
            <label>Maximum Difference</label>
            <input class="form-control"  id="max" onkeyup="refresh_table()">
          <!-- <div id="slider-range"></div> -->
        </div>
    </div>

    <div class="col-lg-3" id="companyfilter" style="<?php if(!in_array('company',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Company</label>
        	<select class="v_filter form-control" name="company" >
        		<option value="">Select</option>
        		<?php
        		if(!empty($company_list))
        		{
        			foreach ($company_list as $row) 
        			{  
                $row  = (array)$row;
        				echo'<option value="'.$row['company'].'">'.$row['company'].'</option>';
        			}
        		}
        		?>
        	</select>
        </div>
    </div>
    <div class="form-group col-md-3" id="createdbyfilter" style="<?php if(!in_array('createdby',$variable)){echo'display:none';} ?>">
                          <label for="">Created By</label>
                         <select name="createdby" class="v_filter form-control"> 
                          <option value="">Select</option>
                         <?php 
                          if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>"  ><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>
                        <div class="col-lg-3" id="expensetypefilter" style="<?php if(!in_array('expensetype',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Expense </label>
       	<select class="form-control" id="expensetype" name="expensetype" onchange="refresh_table_ex();">
              <option value="">Select</option>
              <option >Fully Approved</option>
              <option >Partially Approved</option>
              <option >Rejected</option>
              <option >Pending</option>
            </select>
        </div>
    </div>
</div>
<script>

$(document).ready(function(){
    $("#save_advance_filters").on('click',function(e){
        e.preventDefault();
        var arr = Array();  
        $("input[name='filter_checkbox']:checked").each(function(){
        arr.push($(this).val());
        });        
      setCookie('visits_filter_setting',arr,365);      
      // alert('Your custom filters saved successfully.');
          Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Your custom filters saved successfully.',
                    showConfirmButton: false,
                    timer: 1000
          });
      });
});
$('input[name="filter_checkbox"]').click(function(){  
  if($('#createdbycheckbox').is(":checked")||$('#companycheckbox').is(":checked")||$('#datecheckbox').is(":checked")||$('#forcheckbox').is(":checked")||$('#ratingcheckbox').is(":checked")||$('#differencecheckbox').is(":checked")){ 
    $('#filter_pannel').show();
  }else{
    $('#filter_pannel').hide();
  }
});
$('input[name="filter_checkbox"]').click(function(){              
        if($('#datecheckbox').is(":checked")){
         $('#datefilter').show();
        } else{
           $('#datefilter').hide();
             }
      
		if($('#forcheckbox').is(":checked")){
        $('#forfilter').show();
            }
        else{
          $('#forfilter').hide();
		}
		if($('#ratingcheckbox').is(":checked")){
        $('#ratingfilter').show();
            }
        else{
          $('#ratingfilter').hide();
		}
		if($('#differencecheckbox').is(":checked")){
        $('#differencefilter').show();
            }
        else{
          $('#differencefilter').hide();
    }
    if($('#companycheckbox').is(":checked")){
        $('#companyfilter').show();
            }
        else{
          $('#companyfilter').hide();
    }
    if($('#createdbycheckbox').is(":checked")){
        $('#createdbyfilter').show();
            }
        else{
          $('#createdbyfilter').hide();
    }
    if($('#expensetypecheckbox').is(":checked")){
        $('#expensetypefilter').show();
            }
        else{
          $('#expensetypefilter').hide();
    }
});

</script>
<br>

	<div class="row" >
 
	<div class="col-lg-12" >

				<table id="datatable" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
				      <thead>
				        <tr>
                <th width="7%"><INPUT type="checkbox" onchange="checkAll(this)" name="chk[]" /> S. No.</th>
				          <th id="th-1" width="15%">Visit Date</th>
				          <th id="th-2" width="15%">Visit Time</th>
						  <th id="th-13" width="15%">Purpose of meeting</th>
				          <th id="th-3" >Name</th>
				          <th id="th-10">Company Name</th>
				          <th id="th-4">Shortest Distance</th>
				          <th id="th-5">Actual Distancee</th>
				          <th id="th-6">Rating</th>
				          <th id="th-11" >Difference (%)</th>
				          <th >Travel Expense</th>
				          <th>Other Expense</th>
				          <th>Total Expense</th>
				          <th>Expense Sttaus</th>
                  <th id="th-9">Action</th>
				        </tr>
				      </thead>
				      <tbody>
		     		 </tbody>
    			</table>

          <br>
            <div class="col-md-12">
            <div class="col-md-4" ></div>
            <div class="col-md-4" ></div>
            <div class="col-md-4" >
            
            <table class="table table-responsive table-bordered" >
            <tbody>
            <tr>
            <td width="50%"><b>Total Travel Expense:</b></td><td><span id="totaltravelExp"></span> ₹</td>
            </tr>
            <tr><td width="50%"><b>Total Other Expense:</b> </td><td><span id="totalotherExpense"></span> ₹</td>
            </tr>
            <tr><td width="50%"><b>Total Expense:</b></td><td><span id="totalExpense"></span> ₹</td>
            </tr></tbody>
            </table>
            </div>
            </div>
	</div>
</div>
<div id="approve_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title"> Expense Approval</h4>
         </div>
         <div class="modal-body">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
              <label>Status</label>
             <select id="approve_status" name="approve_status" class="form-control">
               <option value="2">Approve</option>
               <option value="1">Reject</option>
             </select>
            </div>
            </div>
            <input id="visit_id" class="form-control visit_id" name="visit_id"  value="0" hidden>

            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>

            <textarea class="form-control" name="remarks" id="remarks" cols="4"></textarea>
            </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit" onclick="expense_status();">
              Update Expense</button>                    
               <br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
<style>.tr_hover {
background-color: #ffb099; 
}</style>
<!-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" type="text/javascript"></script> -->
<!-- https://code.jquery.com/jquery-3.5.1.js -->

<script type="text/javascript">
function expense_status(){

      var x = new Array(); 
      $($(".checkbox1:checked")).each(function(k,v){
        x.push($(v).val());
      });
       approve_status = document.getElementById("approve_status").value;
       remarks = document.getElementById("remarks").value;
      $.ajax({
              type: 'POST',
              url: '<?= base_url('client/visit_expense_status') ?>',
              data: {exp_ids:x,status:approve_status,remarks:remarks},
              success:function(data){
              //  alert(data);
               location.reload();
              } 
              });
}

$( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 100,
      values: [ 0, 100 ],
      slide: function( event, ui ) {
        $("#min").val(ui.values[ 0 ]);
        $("#max").val(ui.values[ 1 ]);
        $("#range_value").html(ui.values[ 0 ]+' - '+ui.values[ 1 ]);
        refresh_table();
      }
    });

function refresh_table(){
      var min = parseInt( $('#min').val(), 10 ) || 0;
      var max = parseInt( $('#max').val(), 10 ) || 100000;

      var tr_list = $("#datatable_wrapper tbody").find('tr');
      $(tr_list).each(function(k,v){
          var diff = $(v).find('td > span.diff').text();
          if(parseInt(diff)>=min && parseInt(diff) <=max)
          {
            $(v).show();
          }
          else
          { 
            $(v).hide();
          }
      });
}

function refresh_table_ex(){
      var exstatus=$('#expensetype').val();
     // alert(exstatus);
      var tr_list = $("#datatable_wrapper tbody").find('tr');
      $(tr_list).each(function(k,v){
          var diff = $(v).find('td span.expstatus').text();
         // alert(diff);
          if(diff.trim()==exstatus.trim())
          {
            //alert('1');
            $(v).show();
          }
          else
          { 
            $(v).css('background','red');
          }
      });
}

function refresh_table_exs(){
      // alert(exstatus);
      var tr_list = $("#datatable_wrapper tbody").find('tr');
      $(tr_list).each(function(k,v){
          var diff = $(v).find('td > span.diff').text();
          // alert(diff);
          if(diff>=20)
          {
            $(v).addClass('tr_hover');
          }
         
      });
}

var c = getCookie('visit_allowcols');

var Data = {"from_data":"","to_date":"","from_time":"","to_time":""};
$(".v_filter").change(function(){

 $("#datatable").DataTable().ajax.reload(); 
});
$(document).ready(function(){

var table2  =$('#datatable').DataTable({ 
          "processing": false,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/visit_load_data'?>",
              "type": "POST",
              "data":function(d){
                      var obj = $(".v_filter:input").serializeArray();
                     d.from_date = obj[0]['value'];
                     d.from_time = '';//obj[1]["value"];
                     d.enquiry_id =obj[2]["value"];
                     d.rating = obj[3]["value"];
                     d.to_date = obj[1]['value'];
                     d.company = obj[4]['value'];
                     d.createdby = obj[5]['value'];
                    //  d.expensetype = obj[6]['value'];
                     d.to_time = '';//obj[5]['value'];
                     d.view_all=true;
                    if(c && c!='')
                      d.allow_cols = c;
                    //  console.log(JSON.stringify(d));
                    return d;
// alert(d.totaltravelExp);

              },
          },
          "drawCallback": function(settings) {
        $("#totaltravelExp").html(settings.json.totaltravelExp);
          $("#totalotherExpense").html(settings.json.totalotherExpense);
          $("#totalExpense").html(settings.json.totalExpense);
          refresh_table_exs();
},
          
          "columnDefs": [{ "orderable": false, "targets":0 }],
           "order": [[ 1, "desc" ]],
           
           
  });
});


function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }

 
$("select").select2();

</script>  
<div id="add_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Add Expense</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>client/add_expense" method="POST" enctype='multipart/form-data'>  
            <div class="row">
          <input id="visit_id" class="form-control visit_id" name="visit_id"  value="0" hidden>

            <table class="table table-responsive">
                  <thead>
                  <th>Title</th>
                  <th>Amount</th>
                  <th>File(if any)</th>
                  <th><input type="button" value="+ " id="add" class="btn btn-primary"></th>
                  </thead>
                  <tbody class="detail">
                  <tr>
                  <td width="30%">
           <select name="expense[]" class="form-control">
           <?php 
           $expenselist=$this->db->where(array('comp_id'=>$this->session->companey_id,'status'=>1))->get('tbl_expenseMaster')->result();
           foreach ($expenselist as $key => $value) { ?>
            <option value="<?= $value->id ?>"> <?= $value->title ?></option>
          <?php } ?>
           </select> 
          </td>
                  <td width="30%">
                  <input name="amount[]" class="form-control amount" onkeyup="total()" id="amount" value="0"  >
                  </td>
                  <td width="30%">
                  <input name="imagefile[]"  class="form-control" onchange="Filevalidation(this)"  type="file"  >
                  </td>
                  <td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                  </tr>
                  </tbody>
            <tfoot>
            <tr>
            
            <th style="text-align:right">Total: </th>
            <th id="total" class="total"></th><th></th>
            <th></th></tr></tfoot>
            </table>
          
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit">
              Add Expense</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>

<div id="Save_Visit" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Visits</h4>
         </div>
         <div class="modal-body">
            <div class="row" >

<form id="visit_create_form" action="<?=base_url('enquiry/add_visit')?>" class="form-inner" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
          <div class="row">
                        <div class="form-group col-md-12">
                        <label>Select Visit Type</label>
                        <div class="form-check">
                              <label class="radio-inline">
                              <input name="type"  name="type" value="1" type="radio" checked onclick="handleClick(this);">Current Visit</label>
                              <label class="radio-inline">
                              <input type="radio" name="type" value="2" onclick="handleClick(this);">Future Visit</label>
                          </div>
                        </div>
                        </div>
						
				<div class="form-group col-md-6 visit-time col-md-6">     
                    <label>Purpose of meeting</label>
                    <input type="text" name="m_purpose" id="m_purpose" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label style="width:100%;">Company <a style="float: right;" href="<?= base_url('enquiry/create?status=1&red=visits') ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a></label>
                    <select class="form-control" name="company" onchange="filter_related_to(this.value)" required>
                      <option value="-1">Select</option>
                      <?php
                      if(!empty($company_list))
                      {
                        foreach ($company_list as $key =>  $row)
                        {
                          echo '<option value="'.$row->id.'">'.$row->company_name.'</option>';
                        }
                      }
                      ?>
                    </select>
                </div>

               <div class="form-group col-md-6">
                  <label>Account Name</label>
                  <select class="form-control" name="enq_id" onchange="filter_contact(this.value)" required>
                    <option value="">Select</option>
                    <?php
                  // if(!empty($all_enquiry))
                  // {
                  //   foreach ($all_enquiry as $row)
                  //   {
                  //     echo'<option value="'.$row->enquiry_id.'" >'.$row->name_prefix.' '.$row->name.' '.$row->lastname.'</option>';
                  //   }
                  // }
                    ?>
                  </select>
               </div>

                <div class="form-group col-md-6">
                  <label>Contact Name</label>
                  <select class="form-control" name="contact_id" required>
                    <option value="">Select</option>
                  </select>
               </div>

                <div class="form-group col-md-6 visit-date col-md-6">     
          <label>Visit Date</label>
          <input type="date" name="visit_date" id="vdate" disabled class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" id="vtime" disabled class="form-control" value="<?= date('H:i') ?>" required>
        </div>
     
        <!-- <div class="form-group col-md-6 distance-travelled-type col-md-6">      
        <label>DISTANCE TRAVELLED TYPE</label>
           <input type="text" name="travelled_type" class="form-control">
        </div> -->
    
        <!-- <div class="form-group col-md-6 customer-rating col-md-6">      
        <label>Customer Rating</label>
          <select class="form-control" name="rating">
              <option value="">Select</option>
              <option value="1 star">1 star</option>
              <option value="2 star"> 2 star</option>
              <option value="3 star"> 3 star</option>
              <option value="4 star"> 4 star</option>
              <option value="5 star"> 5 star</option>
            </select>
        </div> -->
        
<!--          
      <div class="col-md-12">
      <label style="color:#283593;">Next Visit Information<i class="text-danger"></i></label>
       <hr>
      </div>
        
          <div class="form-group col-md-6 next-visit-date col-md-6">      
            <label>Next Visit Date</label>
             <input type="date" name="next_visit_date" class="form-control">
          </div>
      
          <div class="form-group col-md-6 next-visit-location col-md-6">      
           <label>Next Visit Location</label>
             <input type="text" name="next_location" class="form-control">
          </div>
                   -->
        <input type="hidden" name="dis_notification_id" value="">
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input id="visit_create_btn" type="submit" name="submit_only" class="btn btn-primary" value="Save">
            </div>
         </div>

</form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>   


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
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="1">  Visit Date</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="2">  Visit Time</label>
            </div>
			<div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="13">  Purpose of meeting</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="3"> Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="10"> Company Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4"> Actual Distance</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="5">  Shortest Distance</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="6">  Rating</label>
            </div>
          
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="11"> Diffrence</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="12"> Expense</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="9">  Action</label>
            </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-success" onclick="save_table_conf()"><i class="fa fa-save"></i> Save</button>
    </div>
  </div>
</div>

<script type="text/javascript">
function checkvisit(visitid){
document.getElementById("visit_id").value =visitid;
}


$(function() {
    $('#add').click(function() {
      addnewrow();
    });
    $('body').delegate('.remove', 'click', function() {
      $(this).parent().parent().remove();
    });
    $('body').delegate('.qtys,.price', 'keyup', function() {
      var tr = $(this).parent().parent();
    });
  });
//   $( "#amount" ).keypress(function() {
//     var t = 0;
//     $('#amount').each(function(i, e) {
//       var amount = $(this).val() - 0;
//       t += amount;
//     });
//     $('#total').html(t);
//     alert(t);
// });

  function total() {
    var t = 0;
    $('.amount').each(function(i, e) {
      var amount = $(this).val() - 0;
      t += amount;
    });
    $('.total').html(t);
  }
  function Filevalidation(t)  {
    var filesize =t.files[0].size;
    filesize=filesize/1024;
   var filesizeinkb= filesize.toFixed(0);
    // alert(filesizeinkb);

    if(filesizeinkb > 1024){
   alert('File Size not exceed ');
    }
	}
  function addnewrow() {
    var n = ($('.detail tr').length - 0) + 1;
    var s = n + 3
    var r = n + 1
    var tr = '<tr>' + '<td width="30%"><select name="expense[]" class="form-control"><?php foreach ($expenselist as $key => $value) { ?><option value="<?= $value->id ?>"><?= $value->title ?></option><?php } ?></select></td>'+'<td width="30%"><input id="amount'+n+'" class="form-control amount" name="amount[]"  onkeyup="total()"></td>'+'<td width="30%"><input name="imagefile[]" class="form-control " onchange="Filevalidation(this)"  id="file'+n+'" type="file"  ></td>'+'<td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>' + '</tr>';
    $('.detail').append(tr);
    // $.getScript("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
    document.getElementById('amount' + n).addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });
     

  }
  document.getElementById('amount').addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });


function filter_related_to(v)
{
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $("select[name=enq_id]").html(q);
               $("select[name=enq_id]").trigger('change');
            }
      });
   
  }

function filter_contact(v)
{
      $.ajax({
            url:"<?=base_url('client/contact_by_account')?>",
            type:'get',
            data:{account_id:v},
            success:function(q){
              $("select[name=contact_id]").html(q);
               $("select[name=contact_id]").trigger('change');
            }
      });
   
  }

function save_table_conf()
{
      var x = $(".choose-col:checked");
      var Ary = new Array();
      $(x).each(function(k,v){
        Ary.push(v.value);
      });
      var list = Ary.join(',');
      document.cookie = "visit_allowcols="+list+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
      Swal.fire({
        title:'Table Configuration Saved.',
        icon:'success',
        type:'success',

      });
      location.reload();
}

if(c && c!='')
{ 
    var z = c.split(',');

    if($('.choose-col').length == z.length)
        $('#selectall').prop('checked',true);

    $("th[id*=th-").addClass('rmv');
    $(z).each(function(k,v){
        $('.choose-col[value='+v+']').prop('checked',true);
        $('#th-'+v).removeClass('rmv');
     });
    $('.rmv').remove();
}
else
{
  $('.choose-col').prop('checked',true);
  $('#selectall').prop('checked',true);

}

$("#selectall").click(function(){
    if(this.checked)
    {
      $('.choose-col').prop('checked',true);
    }
    else
    {
      $('.choose-col').prop('checked',false);
    }
});

$('.choose-col').change(function(){
    if($('.choose-col').length == $('.choose-col:checked').length)
        $('#selectall').prop('checked',true);
    else
      $('#selectall').prop('checked',false);
});

function handleClick(myRadio) {
  var valuer= myRadio.value;
  if(valuer==1){
  document.getElementById("vdate").disabled = true;  
  document.getElementById("vtime").disabled = true;  
  }else{
    document.getElementById("vdate").disabled = false;  
  document.getElementById("vtime").disabled = false;  
  } 
}


</script>
<style type="text/css">
  #slider-range
  {
    border:1px solid black;
  }
  #slider-range .ui-slider-handle
  {
    background: #4f4f4f;
  }
  #slider-range .ui-slider-range
  {
    background: #26c726;
  }
</style>