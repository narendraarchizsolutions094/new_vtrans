<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous"></script>

<style type="text/css">
  .wd-14{
    /*width: 18%;
    display: inline-block;
    margin: 9px;*/
  }
.wd-14 p{
  text-align: left;
}
.short_dashboard button{
  margin:4px;
}
.short_dashboard
{
  margin: 0px 5px;
}
input[name=top_filter]{
  visibility: hidden;
}
#active_class{
  font-size: 12px;
}


.border_bottom{
  border-bottom:2px solid #E4E5E6;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
}  
.border_bottom_active{
  border-bottom:2px solid #20A8D8;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
} 
@media screen and (max-width: 900px) {
  #active_class{
    display: none;
  }
}
</style>
<?php
if(empty($_COOKIE['deals_filter_setting'])) {
	$variable=[];
} else {
$variable=explode(',',$_COOKIE['deals_filter_setting']);
}

?>   
<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
  <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>
          <?php
          if(user_access('1000'))
          {
          ?>   
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Deal" title="Add Deal"></a> 
          <?php
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
                      <input type="checkbox" value="datefilter" id="datecheckbox" name="filter_checkbox" <?php if(in_array('datefilter',$variable)){echo'checked';} ?>> Date </label>
                    </li>  
                     <li>
                      <label>
                      <input type="checkbox" value="companyfilter" id="companycheckbox" name="filter_checkbox" <?php if(in_array('companyfilter',$variable)){echo'checked';} ?>> Company</label>
                    </li>   
                    <li>
                      <label>
                      <input type="checkbox" value="for" id="forcheckbox" name="filter_checkbox" <?php if(in_array('for',$variable)){echo'checked';} ?>> Client Name</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="booking_type_filter" id="branchcheckbox" name="filter_checkbox" <?php if(in_array('booking_type_filter',$variable)){echo'checked';} ?>> Booking Type</label>
                    </li>
                   
                    <!--  <li>
                      <label>
                      <input type="checkbox" value="booking_branch_filter" id="bookingbox" name="filter_checkbox" <?php if(in_array('booking_branch_filter',$variable)){echo'checked';} ?>> Booking Branch</label>
                    </li>   -->
                  <!--   <li>
                      <label>
                      <input type="checkbox" value="delivery_branch_filter" id="deliverybox" name="filter_checkbox" <?php if(in_array('delivery_branch_filter',$variable)){echo'checked';} ?>> Delivery Branch</label>
                    </li>   -->
                   <!--  <li>
                      <label>
                      <input type="checkbox" value="paymode_filter" id="paymodebox" name="filter_checkbox" <?php if(in_array('paymode_filter',$variable)){echo'checked';} ?>> Paymode</label>
                    </li>    

                    <li>
                      <label>
                      <input type="checkbox" value="potential_amount_filter" id="pamountbox" name="filter_checkbox" <?php if(in_array('potential_amount_filter',$variable)){echo'checked';} ?>> Potential Amount</label>
                    </li>  -->        
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
               <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;">Table Config</a>                        
            </div>                                         
          </div>
		</div>
</div>



<div class="row" style=" padding: 5px 0px; <?=!empty($this->uri->segment(3))?'display: none;':''?> <?php if(empty($_COOKIE['deals_filter_setting'])){ echo'display:none'; }  ?>" id="filter_pannel">
 <div id="datefilter" style="<?php if(!in_array('datefilter',$variable)){echo'display:none';} ?>" >
	<div class="col-lg-3">
        <div class="form-group">
          <label>From</label>
          <input class="d_filter form-control form-date" name="d_from_date">
        </div>
    </div>

      <div class="col-lg-3">
        <div class="form-group">
          <label>To</label>
           <input  class="d_filter form-control form-date" name="d_to_date">
        </div>
      </div>
 </div>
 <div class="col-lg-3"  id="companyfilter" style="<?php if(!in_array('companyfilter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Company</label>
          <select class="d_filter form-control" name="d_company">
            <option value="">Select</option>
            <?php
            if(!empty($company_list))
            {
              foreach ($company_list as $row) 
              {  
                $row  = (array)$row;
                echo'<option value="'.$row['id'].'">'.$row['company_name'].'</option>';
              }
            }
            ?>
          </select>
        </div>
    </div>
    <div class="col-lg-3"  id="for" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Client Name</label>
        	<select class="d_filter form-control" name="d_enquiry_id">
        		<option value="">Select</option>
        		<?php
        		if(!empty($all_enquiry))
        		{
        			foreach ($all_enquiry as $row) 
        			{  
                $row  = (array)$row;
        				echo'<option value="'.$row['enquiry_id'].'">'.$row['client_name'].'</option>';
        			}
        		}
        		?>
        	</select>
        </div>
    </div>

<!--     <div class="col-lg-3" style="<?php if(!in_array('business_type',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Business Type</label>
          <input class="d_filter form-control" name="d_business_type">
        </div>
    </div> -->

    <div class="col-lg-3" id="booking_type_filter" style="<?php if(!in_array('booking_type_filter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Booking Type</label>
           <select class="d_filter form-control" name="d_booking_type">
                    <option value="">-Select-</option>
                    <option value="sundry" selected>Sundry</option>
                    <option value="ftl" >FTL</option>
                </select>
        </div>
    </div>

    <div class="col-lg-3" id="booking_branch_filter" style="<?php if(!in_array('booking_branch_filter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Booking Branch</label>
          <select class="d_filter form-control" name="d_booking_branch">
                  <option value="">-Select-</option>
                <?php 
                foreach($branch as $dbranch){ ?>
                      <option value="<?= $dbranch->branch_id ?>"><?= $dbranch->branch_name ?></option>
                     <?php }  ?>
               </select>
        </div>
    </div>

    <div class="col-lg-3" id="delivery_branch_filter" style="<?php if(!in_array('delivery_branch_filter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Delivery Branch</label>
          <select class="d_filter form-control" name="d_delivery_branch">
                  <option value="">-Select-</option>
                <?php 
                foreach($branch as $dbranch){ ?>
                      <option value="<?= $dbranch->branch_id ?>"><?= $dbranch->branch_name ?></option>
                     <?php }  ?>
               </select>
        </div>
    </div>

  <div class="col-lg-3" id="paymode_filter" style="<?php if(!in_array('paymode_filter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Paymode</label>
          <select class="d_filter form-control" name="d_paymode">
                   <option value="1" selected>Paid</option>
                   <option value="2" >To-Pay</option>
                   <option value="3" >Tbb</option>
                </select>
        </div>
    </div>

  <div class="col-lg-3" id="potential_amount_filter" style="<?php if(!in_array('potential_amount_filter',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Potential Amount</label>

          <input type="" name="d_p_amnt_from" class="form-control d_filter" style="width: 48%; display:inline-block;" placeholder="From">
          <input type="" name="d_p_amnt_to" class="form-control d_filter" placeholder="To" style="width: 48%; display: inline-block;">
          <!-- <input class="d_filter form-control" id="p_amnt_from" name="d_potential_amount_from" style="display: none;">
          <input class="d_filter form-control" id="p_amnt_to" name="d_potential_amount_to" style="display: none;">
          <center><span id="range_view"></span></center>
          <div id="slider-range"></div> -->
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
	  setCookie('deals_filter_setting',arr,365);      
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
// $('input[name="filter_checkbox"]').click(function(){  
//   if($('#datecheckbox').is(":checked")||$('#forcheckbox').is(":checked")){ 
//     $('#filter_pannel').show();
//   }else{
//     $('#filter_pannel').hide();
//   }
// });
// $('input[name="filter_checkbox"]').click(function(){              
//         if($('#datecheckbox').is(":checked")){
//          $('#datefilter').show();
//         } else{
//            $('#datefilter').hide();
//              }
      
// 		if($('#forcheckbox').is(":checked")){
//         $('#forfilter').show();
//             }
//         else{
//           $('#forfilter').hide();
// 		}
		
		
// });

$("input[name=filter_checkbox]").click(function(){
  manage_filters();
});

function manage_filters()
{
 var list2 = $("input[name=filter_checkbox]");
 $(list2).each(function(k,v){
    var x = "#"+v.value;
    if(v.checked)
      {
          $(x).show();
          $("#filter_pannel").show();
      }
    else
      {
          $(x).hide();
      }
 });
}
</script>

<div class="row row text-center short_dashboard" id="active_class" style="<?=!empty($this->uri->segment(3))?'display: none;':''?>">
    <div class="wd-14 col-sm-3" style="">
        <div  class="col-12 border_bottom border_bottom_active" >
            <p style="margin-top: 2vh;font-weight:bold;">
              <input id='all_deals_radio' value="all" type="radio" name="top_filter" class="d_filter " checked="checked"><i class="fa fa-list" ></i><label for="all_deals_radio">&nbsp;&nbsp;<?php echo display('all_deals'); ?></label>
              <span  style="float:right;" class="badge badge-pill badge-primary " id="all_deals"><i class="fa fa-spinner fa-spin"></i></span>
            </p>
        </div>
    </div>
   <div class="wd-14 col-sm-3">
      <div  class="col-12 border_bottom" >
            <p style="margin-top: 2vh;font-weight:bold;"  title="<?php echo display('done_deals'); ?>"> 
              <input type="radio" name="top_filter" value="done" class="d_filter" id="done_deals_radio"><i class="fa fa-check" ></i><label for="done_deals_radio">&nbsp;&nbsp;<?php echo display('done_deals'); ?></label><span style="float:right;" class="badge badge-pill badge-success " id="all_done"><i class="fa fa-spinner fa-spin"></i></span>
            </p>
        </div>
    </div>
   
    <div class="wd-14 col-sm-3" style="">
            <div  class="col-12 border_bottom" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='pending_radio' value="pending" type="radio" name="top_filter" class="d_filter"><i class="fa fa-times" ></i><label for="pending_radio">&nbsp;&nbsp;<?php echo display('pending_deals'); ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-warning " id="all_pending"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
    </div>

     <div class="wd-14 col-sm-3">
              <div  class="col-12 border_bottom" >
                  <p style="margin-top: 2vh;font-weight:bold;"   title="<?php echo display('deferred_deals'); ?>">
                      <input type="radio" name="top_filter" value="deferred" class="d_filter" id="deferred_deals_radio">
                      <i class="fa fa-warning" ></i><label for="deferred_deals_radio">&nbsp;&nbsp;<?php echo display('deferred_deals'); ?></label><span style="float:right;background:#E5343D" class="badge badge-danger" id="all_deferred"><i class="fa fa-spinner fa-spin"></i></span>              
                  </p>
              </div>
    </div>

</div>

<div class="row" style="margin-top: 10px;">
				<table id="deals_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
				     <thead class="thead-light">
               <tr>                              
                  <th>S.N.</th>
                  <th id="th-1">Name</th>
                  <th id="th-21">Company</th>
                  <th id="th-22">Client Name</th>
              <!--     <th id="th-2">Branch Type</th> -->
                  <th id="th-3">Business Type</th>
                  <th id="th-4">Booking Type</th>
<!--                   <th id="th-5">Booking Branch</th>
                  <th id="th-6">Delivery Branch</th>
                  <th id="th-7">Rate</th>
                  <th id="th-8">Discount</th>
                  <th id="th-9">Insurance</th>
                  <th id="th-10">Paymode</th>
                  <th id="th-11">Potential Tonnage</th>
                  <th id="th-12">Potential Amount</th>
                  <th id="th-13">Expected  Tonnage</th>
                  <th id="th-14">Expected  Amount</th>
                  <th id="th-15">Vehicle Type</th>
                  <th id="th-16">Vehicle Carrying Capacity</th>
                  <th id="th-17">Invoice Value</th> -->
                  <th id="th-18">Create Date</th>
                  <th id="th-19">Status</th>
                  <th id="th-20">Action</th>
               </tr>
            </thead>
				      <tbody>
		     		 </tbody>
    			</table>

</div>

<script>
                  
function editComInfo(id)
{
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/editinfo/'+id,
            data: {id:id,},
            success:function(data){
               //  Swal.fire('Status Updated');
            $("#editcomInfoData").html(data);

            }
        });
} 
</script>
<script type="text/javascript">
var c = getCookie('deals_allowcols');
//alert(c);
var specific_list = "<?=!empty($this->uri->segment(3))?$this->uri->segment(3):''?>";

specific_list = atob(specific_list);

var TempData = {};
$(".d_filter").on('change',function(){

  $('#deals_table').DataTable().ajax.reload();
 
});
$(document).ready(function(){

  $('#deals_table').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/deals_load_data'?>",
              "type": "POST",
              "data":function(d){
                     //  var obj = $(".v_filter:input").serializeArray();

                     d.top_filter = $("input[name=top_filter]:checked").val();
                     d.date_from = $("input[name=d_from_date]").val();
                     d.date_to = $("input[name=d_to_date]").val();
                     d.enq_for = $("select[name=d_enquiry_id]").val();
                     d.booking_type = $("select[name=d_booking_type]").val();
                     d.company = $("select[name=d_company]").val();
                     // d.from_date = obj[0]['value'];
                     // d.from_time = '';//obj[1]["value"];
                     // d.enquiry_id =obj[2]["value"];
                     // d.rating = obj[3]["value"];
                     // d.to_date = obj[1]['value'];
                     // d.to_time = '';//obj[5]['value'];
                     d.view_all=true;
                     d.specific_list = specific_list;
                     TempData = d;

                      if(c && c!='')
                      d.allow_cols = c;

                     console.log(JSON.stringify(d));
                    return d;
              }
          },
          "drawCallback":function(settings ){
            update_top_filter();
          },
          columnDefs: [
                       { orderable: false, targets: -1 }
                    ]
  });

});



function update_top_filter()
{
  //alert(JSON.stringify(TempData));
  TempData.top_filter='';
    $.ajax({
       
        url: "<?=base_url().'client/short_dashboard_count_deals'?>",
        type: 'post',
        data:TempData,
        dataType: 'json',
        success: function(responseData){
          console.log(responseData);
       //alert(JSON.stringify(responseData));
        $('#all_deals').html(responseData.all_deals_num);
        $('#all_done').html(responseData.all_done_num);
        $('#all_pending').html(responseData.all_pending_num);
        $('#all_deferred').html(responseData.all_deferred_num);
        // $('#today_updated').html(responseData.all_update_num);
        // $('#active_drop').html(responseData.all_drop_num);
        // $('#total_active').html(responseData.all_enquery_num);
        // $('#pending').html(responseData.all_no_activity_num);
        // $('#assigned').html(responseData.all_assigned_num);
        // $('#un_assigned').html(responseData.all_unassigned_num);
      }
    });
}

</script>
 <script>             
function update_info_status(id,status)
{
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/update_info_status/?>',
            data: {id:id,status:status},
            success:function(data){
                Swal.fire({
                  title:'Saved!',
                  type:'success',
                  icon:'success',
                });
            }
        });
}

</script>

<script type='text/javascript'>
$(window).load(function(){
  //stage_counter();
  $("#active_class p").click(function() {
      $('.border_bottom_active').removeClass('border_bottom_active');
      $(this).addClass("border_bottom_active");
      //$(this).find('input[type=radio]').attr('checked','checked');
  });
});  
</script>



<div id="Save_Deal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Commercial Info</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
                <div class="form-group col-md-6">
                    <label>Company</label>
                    <select class="form-control" name="company" onchange="filter_related_to(this.value)">
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
                  <label>Client Name <span style="color:red;">*</span> <a href="<?= base_url('enquiry/create?status=1') ?>" target="_blank"  style=" float:right;margin-left: 30px;"> <i class="fa fa-plus-square"> </i></a> </label>
                  <select class="form-control" name="enquiry_id" required onchange="match()">
                    <!-- <option value="">Select</option> -->
                    <?php
                  // if(!empty($all_enquiry))
                  // {
                  //   foreach ($all_enquiry as $row)
                  //   {
                  //     echo'<option value="'.$row->enquiry_id.'">'.$row->name.'</option>';
                  //   }
                  // }
                    ?>
                  </select>
               </div>
            </div>
         </div>
         <div class="modal-footer">
           <a id="red" href="" onclick="{
                if($('select[name=enquiry_id]').val()=='' || $('select[name=enquiry_id]').val()==null)
                {
                  alert('Client Name Must Be selected.')
                  return false;
                }
                
           }">
            <button type="button" class="btn btn-primary">Create</button></a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>   

<div id="downloadQuatation" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Download</h4>
         </div>
         <form action="<?= base_url('dashboard/pdf_gen/') ?>" method="POST">

         <div class="modal-body" style="padding: 0px;">
            <!-- <input name="idType" hidden class="idType" id="idType"> -->
            <input id="enq_id_for_download" name="enquiry_id" type="hidden" value="">
             <div id="data_value" class="data_value" style="padding:0px;"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <div class="downlaod-panel" style="display: inline-block;">
            <input type="submit" name="submit" class="btn btn-primary" value="Download">
            <input type="submit" onclick="return confirm('Are you sure?')" name="submit" class="btn btn-primary" value="Email">
          </div>
         </div>
         </form> 

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
              <label class=""><input type="checkbox" class="choose-col" value="1"> Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="21"> Company name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="22"> Client Name</label>
            </div>

           
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="3"> Business Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4"> Booking Type</label>
            </div>

            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="18"> Create Date</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="19"> Status</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="20"> Action</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-success" onclick="save_table_conf()"><i class="fa fa-save"></i> Save</button>
    </div>
  </div>
</div>

<script>
function match()
{ 
  var x = $('select[name=enquiry_id]').val();
 
  if(x=='')
    $('#red').attr('href','');
  else
     $('#red').attr('href','<?=base_url('client/commercial_info/')?>'+x+'/by_deals');
}
function quotation_pdf(info_id) {

    // $(".data_value").html('<center><i class="fa fa-spinner fa-spin" style="font-size:34px;"></i></center>');
    // $(".data_value").html('<embed src="<?=base_url();?>dashboard/pdf_gen/'+info_id+'" type="application/pdf">');
   // var elem = document.getElementById('view_sdatas');
   window.open('<?=base_url();?>dashboard/quotation_preview/'+info_id,'Quotation','fullscreen=yes');
    // $.ajax({
    //         type: 'POST',
    //         url: '<?php echo base_url();?>dashboard/printPdf_gen',
    //         data: {info_id:info_id},
    //         success:function(res){
    //             $(".data_value").html(res);
    //         }
    //   });
}
</script>

<script type="text/javascript">

  function filter_related_to(v)
  {
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $("select[name=enquiry_id]").html(q);
               $("select[name=enquiry_id]").trigger('change');
            }
      });
      match();
  }



function save_table_conf()
{
      var x = $(".choose-col:checked");
      var Ary = new Array();
      $(x).each(function(k,v){
        Ary.push(v.value);
      });
      var list = Ary.join(',');
      //alert(list);
      document.cookie = "deals_allowcols="+list+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
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
    //alert(z.length);
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


$(function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        var from = ui.values[ 0 ];
        var to =  ui.values[ 1 ];
        $( "#p_amnt_from" ).val(from);
        $("#p_amnt_to").val(to);

        $("#range_view").html("<i class='fa fa-inr'></i> "+from+" - <i class='fa fa-inr'></i> "+to); 
      }
    });
    var from = $( "#slider-range" ).slider( "values", 0 );
    var to = $( "#slider-range" ).slider( "values", 1 );

    $( "#p_amnt_from" ).val(from);
     $( "#p_amnt_to" ).val(to);  
     $("#range_view").html("<i class='fa fa-inr'></i> "+from+" - <i class='fa fa-inr'></i> "+to); 

});
$("select").select2();
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