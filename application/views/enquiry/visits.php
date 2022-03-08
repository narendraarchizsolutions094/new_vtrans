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
          <!-- <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a>  -->
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
                      <input type="checkbox" value="for" id="forcheckbox" name="filter_checkbox" <?php if(in_array('for',$variable)){echo'checked';} ?>> Client Name</label>
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
                      <input type="checkbox" value="company" id="companycheckbox" name="filter_checkbox" <?php if(in_array('company',$variable)){echo'checked';} ?>> Company group name</label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="contact" id="contactcheckbox" name="filter_checkbox" <?php if(in_array('contact',$variable)){echo'checked';} ?>> Contact</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="expensetype" id="expensetypecheckbox" name="filter_checkbox" <?php if(in_array('expensetype',$variable)){echo'checked';} ?>> Expense Type</label>
                    </li>        

                    <li>
                      <label>
                      <input type="checkbox" value="region" id="regioncheckbox" name="filter_checkbox" <?php if(in_array('region',$variable)){echo'checked';} ?>> Region</label>
                    </li>        

                    <li>
                      <label>
                      <input type="checkbox" value="area" id="areacheckbox" name="filter_checkbox" <?php if(in_array('area',$variable)){echo'checked';} ?>> Area</label>
                    </li>        

                    <li>
                      <label>
                      <input type="checkbox" value="branch" id="branchcheckbox" name="filter_checkbox" <?php if(in_array('branch',$variable)){echo'checked';} ?>> Branch</label>
                    </li> 
                    
                    <li>
                      <label>
                      <input type="checkbox" value="emp_region" id="empregioncheckbox" name="filter_checkbox" <?php if(in_array('emp_region',$variable)){echo'checked';} ?>> Employee Region</label>
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

<form method="post" id="visit_filter" >
<div class="row" style=" margin: 15px 0px; padding: 15px; <?php if(empty($_COOKIE['visits_filter_setting'])){ echo'display:none'; }  ?>" id="filter_pannel">
<div id="datefilter" style="<?php if(!in_array('date',$variable)){echo'display:none';} ?>">
	<div class="col-lg-3"  >
        <div class="form-group">
          <label>From</label>
          <input class="v_filter form-control form-date" name="from_date" value="<?=$filterData['from_date']=='' || $filterData['from_date']=='0000-00-00' ?date("d-m-Y"):$filterData['from_date'] ?>">
       
        </div>
    </div>

      <div class="col-lg-3" id="tofilter">
        <div class="form-group">
          <label>To</label>
           <input  class="v_filter form-control form-date" name="to_date" value="<?=$filterData['to_date']=='' || $filterData['to_date']=='0000-00-00' ?date("d-m-Y"):$filterData['to_date'] ?>">
        </div>
      </div>
</div>
    
     <div class="col-lg-3" id="ratingfilter" style="<?php if(!in_array('rating',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Rating</label>
       	<select class="form-control v_filter" name="rating" id="v_reset_rating">
              <option value="">Select</option>
              <option value="1 star" <?php if($filterData['rating']=='1 star') {echo 'selected';}?>>1 star</option>
              <option value="2 star" <?php if($filterData['rating']=='2 star') {echo 'selected';}?>> 2 star</option>
              <option value="3 star" <?php if($filterData['rating']=='3 star') {echo 'selected';}?>> 3 star</option>
              <option value="4 star" <?php if($filterData['rating']=='4 star') {echo 'selected';}?>> 4 star</option>
              <option value="5 star" <?php if($filterData['rating']=='5 star') {echo 'selected';}?>> 5 star</option>
            </select>
        </div>
    </div>
     <div class="col-lg-3" id="differencefilter" style="<?php if(!in_array('difference',$variable)){echo'display:none';} ?>">
        <div class="form-group">
            <!-- <label for="amount">Difference range: <span id="range_value">0 - 100</span></label> -->
            <label>Minimum Difference </label>
            <input class="form-control" name="min" id="min" onkeyup="refresh_table()" value="<?=$filterData['min']=='' ?'':$filterData['min'] ?>">
           
        </div>
    </div>
    <div class="col-lg-3" id="differencefilter" style="<?php if(!in_array('difference',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        
            <label>Maximum Difference</label>
            <input class="form-control"  name="max" id="max" onkeyup="refresh_table()" value="<?=$filterData['max']=='' ?'':$filterData['max'] ?>">
          <!-- <div id="slider-range"></div> -->
        </div>
    </div>

    <!--<div class="col-lg-3" id="companyfilter" style="<?php if(!in_array('company',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Company group name</label>
        	<select class="v_filter form-control" name="company" id="v_reset_company" onchange="load_filter_account(this.value)">
        		<option value="">Select</option>
        		<?php
        		if(!empty($company_list))
        		{
        			foreach ($company_list as $row) 
        			{  
                $row  = (array)$row;
        				echo'<option value="'.$row['id'].'" '.(($row['id']==$filterData['company'])?"selected":"").'>'.$row['company_name'].'</option>';
        			}
        		}
        		?>
        	</select>
        </div>
    </div>-->
	
	<div class="form-group col-md-3" id="companyfilter" style="<?php if(!in_array('company',$variable)){echo'display:none';} ?>">
        <label for="">Company group name</label>
        <input type="text" name="company" class="form-control v_filter" id="v_reset_company" value="<?= $filterData['company'] ?>">
    </div>

    <!--<div class="col-lg-3" id="forfilter" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Client Name </label>
          <select class="v_filter form-control" name="enquiry_id" id="v_reset_enquiry_id" onchange="load_filter_contact(this.value)">
            <option value="">Select</option>
            <?php
            if(!empty($all_enquiry))
            {
              foreach ($all_enquiry as $row) 
              { 
                if($row->client_name!=''){			  
                $row  = (array)$row;
                echo'<option value="'.$row['client_name'].'" '.(($row['client_name']==$filterData['enquiry_id'])?"selected":"").'>'.$row['client_name'].'</option>';
				}
              }
            }
            ?>
          </select>
        </div>
    </div>-->
	
	<div class="form-group col-md-3" id="forfilter" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <label for=""><?php echo 'Client name'; ?></label>
        <input type="text" name="enquiry_id" id="v_reset_enquiry_id" class="form-control v_filter" value="<?= $filterData['enquiry_id'] ?>">
    </div>

    <div class="col-lg-3" id="contactfilter" style="<?php if(!in_array('contact',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Contact Person</label>
          <select class="v_filter form-control" name="contact" id="v_reset_contact">
            <option value="">Select</option>
            <?php
          $all_contact=  $this->db->where('comp_id',$this->session->companey_id)->get('tbl_client_contacts')->result();
            if(!empty($all_contact))
            {
              foreach ($all_contact as $row) 
              {  
                $row  = (array)$row;
                echo'<option value="'.$row['cc_id'].'" '.(($row['cc_id']==$filterData['contact'])?"selected":"").'>'.$row['c_name'].'</option>';
              }
            }
            ?>
          </select>
        </div>
    </div>

    <div class="form-group col-md-3" id="createdbyfilter" style="<?php if(!in_array('createdby',$variable)){echo'display:none';} ?>">
                          <label for="">Created By</label>
                         <select name="createdby" class="v_filter form-control" id="v_reset_createdby"> 
                          <option value="">Select</option>
                         <?php 
                          if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['createdby']) {echo 'selected';}?>><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>
                        <div class="col-lg-3" id="expensetypefilter" style="<?php if(!in_array('expensetype',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        <label>Expense Status</label>
       	<select class="form-control v_filter" id="expensetype" name="expensetype">
              <option value="">Select</option>
              <option value="1" <?php if($filterData['expensetype']=='1') {echo 'selected';}?>>Approved</option>
              <option value="2" <?php if($filterData['expensetype']=='2') {echo 'selected';}?>>Pending</option>
              <option value="3" <?php if($filterData['expensetype']=='3') {echo 'selected';}?>>Rejected</option>
              <option value="4" <?php if($filterData['expensetype']=='4') {echo 'selected';}?>>Partial</option>
            </select>
        </div>
    </div>

    <div class='col-md-3' id="regionfilter" style="<?php if(!in_array('region',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Region</label> 
          <select class="form-control v_filter"  name="region" onchange="find_area();" id="v_reset_region">
                <option value="">Select</option>
                <?php
                if(!empty($region_list)){
                  foreach($region_list as $key=>$value){
                    ?>
                    <option value="<?=$value->region_id?>" <?php if($value->region_id==$filterData['region']) {echo 'selected';}?>><?=$value->name?></option>
                    <?php
                  }
                }
                ?>                
          </select>
        </div>
    </div>

    <div class='col-md-3' id="areafilter" style="<?php if(!in_array('area',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Area</label>
          <select class="form-control v_filter" id="filtered_area" name="area" onchange="find_branch();">
                <option value="">Select</option>
                <?php
                if(!empty($area_list)){
                  foreach($area_list as $key=>$value){
                    ?>
                    <option value="<?=$value->area_id?>" <?php if($value->area_id==$filterData['area']) {echo 'selected';}?>><?=$value->area_name?></option>
                    <?php
                  }
                }
                ?>
          </select>
        </div>
    </div>

    <div class='col-md-3' id="branchfilter" style="<?php if(!in_array('branch',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Branch</label>
          <select class="form-control v_filter" name="branch" id="filtered_branch">
                <option value="">Select</option>
                <?php
                if(!empty($branch_list)){
                  foreach($branch_list as $key=>$value){
                    ?>
                    <option value="<?=$value->branch_id?>" <?php if($value->branch_id==$filterData['branch']) {echo 'selected';}?>><?=$value->branch_name?></option>
                    <?php
                  }
                }
                ?>
          </select>
        </div>
    </div>
	
	<div class="col-lg-3" id="empregionfilter" style="<?php if(!in_array('emp_region',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label> Employee Region</label>
           <select class="v_filter form-control" name="emp_region" id="v_reset_emp_region">
                    <option value="" selected>-Select-</option>
                <?php 
                foreach($region_list as $dregion){ ?>
                      <option value="<?= $dregion->region_id ?>" <?php if($dregion->region_id==$filterData['emp_region']) {echo 'selected';}?>><?= $dregion->name ?></option>
                     <?php }  ?>
                </select>
        </div>
    </div>

    <div class="form-group col-md-3">
		<button class="btn btn-warning btn-sm" id="reset_filterbutton" type="button" onclick="visit_reset_filter();" style="margin: 25px 5px;">Reset</button>
		<button class="btn btn-primary btn-sm" id="find_filterbutton" type="button" style="margin: 25px 5px;">Filter</button>
        <button class="btn btn-success btn-sm" id="save_filterbutton" type="button" onclick="visit_save_filter();" style="margin: 25px 5px;">Save</button>        
    </div>
</div>
</form>
<script>

 function find_area() { 

            var reg_id = $("select[name='region']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>user/select_area_by_region',
            data: {region:reg_id},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';;
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].area_id)+'">'+(obj[i].area_name)+'</option>';
                }
                
                $("#filtered_area").html(html);
                
            }           
            });
}

 function find_branch() { 

            var reg_id = $("select[name='region']").val();
			var area_id = $("select[name='area']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>user/select_branch_by_arearegion',
            data: {region:reg_id,area:area_id},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';;
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].branch_id)+'">'+(obj[i].branch_name)+'</option>';
                }
                
                $("#filtered_branch").html(html);
                
            }           
            });
}

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
  if($('#createdbycheckbox').is(":checked")||$('#companycheckbox').is(":checked")||$('#datecheckbox').is(":checked")||$('#forcheckbox').is(":checked")||$('#ratingcheckbox').is(":checked")||$('#differencecheckbox').is(":checked")||$('#regioncheckbox').is(":checked")||$('#empregioncheckbox').is(":checked")||$('#areacheckbox').is(":checked")||$('#branchcheckbox').is(":checked")){ 
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

    if($('#regioncheckbox').is(":checked")){
        $('#regionfilter').show();
            }
        else{
          $('#regionfilter').hide();
    }
	
	if($('#empregioncheckbox').is(":checked")){
        $('#empregionfilter').show();
            }
        else{
          $('#empregionfilter').hide();
    }

    if($('#areacheckbox').is(":checked")){
        $('#areafilter').show();
            }
        else{
          $('#areafilter').hide();
    }

    if($('#branchcheckbox').is(":checked")){
        $('#branchfilter').show();
            }
        else{
          $('#branchfilter').hide();
    }


    if($('#contactcheckbox').is(":checked")){
        $('#contactfilter').show();
            }
        else{
          $('#contactfilter').hide();
    }
});

</script>
<br>
<a class="dropdown-toggle" data-toggle="modal" data-target="#Add_Contact" id="open_contact_form" title="Add Contact" style=""></a>
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
				          <th id="th-10">Company group name</th>
                  <th id="th-14">Client Name</th>
                  <th id="th-15">Contact Person</th>
                  <th id="th-16">Start Location</th>
                  <th id="th-17">End Location</th>
				          <th id="th-4">Shortest Distance</th>
				          <th id="th-5">Actual Distance</th>
						  <th id="th-30">Manual Distance</th>
				          <th id="th-6">Rating</th>
				          <th id="th-28">Remark</th>
						      <th id="th-29">Employee Region</th>
				          <th id="th-7">Employee</th>
				          <th id="th-11" >Difference (%)</th>
				          <th id="th-8">Travel Expense</th>
				          <th id="th-18">Other Expense</th>
				          <th id="th-19">Total Expense</th>
				          <th id="th-20">Expense Staus</th>
				          <th id="th-21">Region</th>
				          <th id="th-22">Branch</th>
				          <th id="th-23">Area</th>
				          <th id="th-24">Stage Of Call</th>
				          <th id="th-25">Time Spend</th>
				          <th id="th-26">City</th>
				          <th id="th-27">Rate</th>
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

            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>

            <textarea class="form-control" name="remarks" id="remarks" cols="4"></textarea>
            </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit" onclick="expense_status();">
              Submit</button>                    
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

//CHANGE DUE TO RESET BUTTON
/* $(".v_filter").change(function(){
 $("#datatable").DataTable().ajax.reload(); 
}); */
//END

$('#find_filterbutton').click(function() {
     $("#datatable").DataTable().ajax.reload();
 });

$(document).ready(function(){

var table2  = $('#datatable').DataTable({ 
          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
         // dom: "<'row '<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp",         
          dom: "<'row text-center'<'text-left col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>rtip",
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
          ] ,
          "ajax": {
              "url": "<?=base_url().'enquiry/visit_load_data'?>",
              "type": "POST",
              "data":function(d){
                      var obj = $(".v_filter:input").serializeArray();
                      console.log(obj);
                     d.from_date = obj[0]['value'];
                     d.from_time = '';//obj[1]["value"];
                     d.enquiry_id = obj[4]["value"];
                     d.rating = obj[2]["value"];
                     d.to_date = obj[1]['value'];
                     d.company = obj[3]['value'];
                     d.contact = obj[5]['value'];
                     d.createdby = obj[6]['value'];
                     d.expensetype = obj[7]['value'];
                     d.region = obj[8]['value'];
                     d.area = obj[9]['value'];
                     d.branch = obj[10]['value'];
					 d.emp_region = obj[11]['value'];
                     d.to_time = '';//obj[5]['value'];
                     d.view_all=true;
                    if(c && c!='')
                      d.allow_cols = c;
                     console.log(JSON.stringify(d));
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
          <input id="visit_id" class="form-control visit_id" name="visit_id"  value="0" type="hidden">

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


 


<div id="Add_Contact" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Contact</h4>
          </div>
          <div class="modal-body">
            <div class="row" id="contact_form">
            </div>
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
          <!--<div class="row">
                <div class="form-group col-md-12">
                    <label>Select Visit Type</label>
                    <div class="form-check">
                        <label class="radio-inline">
                        <input name="type"  name="type" value="1" type="radio" checked onclick="handleClick(this);">Current Visit</label>
                        <label class="radio-inline">
                        <input type="radio" name="type" value="2" onclick="handleClick(this);">Future Visit</label>
                    </div>
                  </div>
                </div>-->
						
				<div class="form-group col-md-6 visit-time col-md-6">     
                    <label>Purpose of meeting <span class="text-danger">*</span></label>
                    <input type="text" name="m_purpose" id="m_purpose" class="form-control" required>
                </div>
				
		<div class="form-group col-md-6 col-md-6">     
          <label>Start Location</label>
          <input type="text" name="start_loc" id="sloc" class="form-control">
        </div>
		<div class="form-group col-md-6 col-md-6">     
          <label>End Location</label>
          <input type="text" name="end_loc" id="eloc" class="form-control">
        </div>
		<div class="form-group col-md-6 col-md-6">     
          <label>Mannual KM</label>
          <input type="number" name="mannual_km" id="mkm" class="form-control">
        </div>


                <div class="form-group col-md-6">
                    <label style="width:100%;">Company group name <span class="text-danger">*</span>
                      <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                        <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
                      </a>
                    </label>
                    <input class="form-control" value="<?php  echo set_value('company');?> " name="company_nm" id="company_list" type="text"  placeholder="Enter Company" onblur="find_company_id(this.value)" required>
				    <input class="form-control" name="company" type="hidden">
                </div>
					
                <!--<div class="form-group col-md-6">
                    <label style="width:100%;">Company group name <span class="text-danger">*</span>
                      <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                        <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
                      </a>
                    </label>
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
                </div>-->

               <div class="form-group col-md-6">
                  <label style="width: 100%">Client Name <span class="text-danger">*</span>
                     <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                        <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
                      </a>
                    </label>
                  <select class="form-control" name="enq_id" required>
                    <option value="">Select</option>
                  </select>
               </div>

                <div class="form-group col-md-6">
                  <label style="width: 100%">Contact Name <span class="text-danger">*</span>
                    <span style="float: right; color:gray;" onclick="add_contact()">
                    <i class="fa fa-plus"></i>
                    </span>
                  </label>
                  <select class="form-control" name="contact_id" required>
                    <option value="">Select</option>
                  </select>
               </div>

        <div class="form-group col-md-6">               
          <label>Back Date  <input value='1' type="checkbox" name='allowbackdate' class="form-control" /> </label>
        </div>

        <div class="form-group col-md-6 visit-date col-md-6">     
         <label>Visit Date  </label>
          <input type="date" name="visit_date" id="vdate" readonly class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" id="vtime" readonly class="form-control" value="<?= date('H:i') ?>" required>
        </div>
     
        <input type="hidden" name="visit_notification_id" value="">
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input id="visit_create_btn" type="submit" name="submit_only" class="btn btn-primary" value="Save">
            </div>
         </div>

</form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" id="close_visit" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>   


<div id="table-col-conf" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" >
 
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
              <label class=""><input type="checkbox" class="choose-col" value="10"> Company group name</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="14"> Client Name</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="15"> Contact Person</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="16"> Start Location</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="17"> End Location</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4"> Actual Distance</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="5">  Shortest Distance</label>
            </div>
			<div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="30">  Manual Distance</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="6">  Rating</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="28">  Remark</label>
            </div>
			
			<div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="29">  Employee Region</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="7">  Employee</label>
            </div>
          
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="11"> Diffrence</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="8"> Expense</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="18"> Other Expense</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="19"> Total Expense</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="20"> Expense Staus</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="21"> Region</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="22"> Branch</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="23"> Area</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="24"> Stage Of Call</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="25"> Time Spend</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="26"> City</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="27"> Rate</label>
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


  $("input[name='allowbackdate']").on('change', function(){
    var ischecked = $('input[name="allowbackdate"]:checked').val();
    if(ischecked){
      $("input[name='visit_date']").removeAttr('readonly');
      $("input[name='visit_time']").removeAttr('readonly');
    }else{
      $("input[name='visit_date']").prop("readonly", true);;
      $("input[name='visit_time']").prop("readonly", true);;
    }
  })
function add_contact()
{
  var enq = $("select[name=enq_id]").val();
  if(enq!='')
  {
    $("#close_visit").click(); 
    $.ajax({
        url:"<?=base_url('client/contact_form_ajax/')?>",
        type:"post",
        data:{enq_id:enq},
        success:function(res)
        {
              if(res){
                $("#open_contact_form").click();
                $("#contact_form").html(res);
                $("#contact_form select").select2();
              }
        },
        error:function(u,v,w)
        {
          alert(w);
        }
    });
  }
  else
  { 
    alert('Please Select Client Name First.');  
  }

}

function checkvisit(visitid){
  $("#add_expense").find('input[name=visit_id]').val(visitid);
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


function load_filter_account(v)
{
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $("select[name=enquiry_id]").html('<option value="" selected>Select</option>'+q);
               //$("select[name=enquiry_id]").trigger('change');
            }
      });
    
       $.ajax({
            url:"<?=base_url('client/contact_by')?>",
            type:'get',
            data:{key:v,by:'company'},
            success:function(q){
              $("select[name=contact]").html('<option value="" selected>Select</option>'+q);
              //$("select[name=contact_id]").trigger('change');
            }
      });
  }


function load_filter_contact(v)
{
       $.ajax({
            url:"<?=base_url('client/contact_by')?>",
            type:'get',
            data:{key:v,by:'account'},
            success:function(q){
              $("select[name=contact]").html('<option value="" selected>Select</option>'+q);
              // $("select[name=contact_id]").trigger('change');
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

function visit_save_filter(){
var form_data = $("#visit_filter").serialize();
$.ajax({
url: '<?=base_url()?>ticket/ticket_save_filter/vis',
type: 'post',
data: form_data,
success: function(responseData){
  Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'filted data saved',
  showConfirmButton: false,
  timer: 500
});

}
});
}

function visit_reset_filter(){
$('input[name=from_date').val('');
$('input[name=to_date').val('');
$('input[name=min').val('');
$('input[name=max').val('');
$('#v_reset_emp_region').val(null).trigger("change");
$('#filtered_branch').val(null).trigger("change");
$('#filtered_area').val(null).trigger("change");
$('#v_reset_region').val(null).trigger("change");
$('#expensetype').val(null).trigger("change");
$('#v_reset_createdby').val(null).trigger("change");
$('#v_reset_contact').val(null).trigger("change");
$('#v_reset_enquiry_id').val(null).trigger("change");
$('#v_reset_company').val(null).trigger("change");
$('#v_reset_rating').val(null).trigger("change");

var form_data = $("#visit_filter").serialize();       

$.ajax({
url: '<?=base_url()?>ticket/ticket_save_filter/vis',
type: 'post',
data: form_data,
success: function(responseData){
  Swal.fire({
  position: 'top-end',
  icon: 'warning',
  title: 'filted data Reset',
  showConfirmButton: false,
  timer: 500
});
}
});
$('#find_filterbutton').click();
  }
 
 
$(function() {   
          $("input[name=company_nm]").autocomplete({
            source: function( request, response ) {
                 $.ajax({
                  url: "<?=base_url('enquiry/suggest_company')?>",
                  type: 'post',
                  dataType: "json",
                  data: {
                   search: request.term
                  },
                  success: function( data ) {
                   response(data);
                  }
                 });
              },
          });
        });
		
function find_company_id(key)
{
    $.ajax({
            url:"<?=base_url('client/company_by_name')?>",
            type:'get',
            data:{key:key},
            success:function(q){
              q = q.trim();
              if(q)
              {
				$('input[name=company]').val(q);
                filter_related_to(q);
              }

            }
  });
}

function filter_related_to(v)
  {
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v,escape_lead:1},
            success:function(q){
              $("select[name=enq_id]").html(q);
               $("select[name=enq_id]").trigger('change');
            }
      });
	  
	  $.ajax({
            url:"<?=base_url('client/contact_by')?>",
            type:'get',
            data:{key:v,by:'company'},
            success:function(q){
              $("select[name=contact_id]").html(q);
               $("select[name=contact_id]").trigger('change');
            }
      });
      //match();
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
  .ui-widget-content{
	  z-index:99999;
  }
</style>