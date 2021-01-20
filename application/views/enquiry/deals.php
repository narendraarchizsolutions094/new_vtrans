<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


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
                      <input type="checkbox" value="for" id="forcheckbox" name="filter_checkbox" <?php if(in_array('for',$variable)){echo'checked';} ?>> For</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="booking_type" id="branchcheckbox" name="filter_checkbox" <?php if(in_array('booking_type',$variable)){echo'checked';} ?>> Booking Type</label>
                    </li>
                  <!--   <li>
                      <label>
                      <input type="checkbox" value="business_type" id="forcheckbox" name="filter_checkbox" <?php if(in_array('business_type',$variable)){echo'checked';} ?>> Business Type</label>
                    </li>    -->
                     <li>
                      <label>
                      <input type="checkbox" value="booking_branch" id="bookingbox" name="filter_checkbox" <?php if(in_array('booking_branch',$variable)){echo'checked';} ?>> Booking Branch</label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="delivery_branch" id="deliverybox" name="filter_checkbox" <?php if(in_array('delivery_branch',$variable)){echo'checked';} ?>> Delivery Branch</label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="paymode" id="paymodebox" name="filter_checkbox" <?php if(in_array('paymode',$variable)){echo'checked';} ?>> Paymode</label>
                    </li>    

                    <li>
                      <label>
                      <input type="checkbox" value="potential_amount" id="pamountbox" name="filter_checkbox" <?php if(in_array('potential_amount',$variable)){echo'checked';} ?>> Potential Amount</label>
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
    <div class="col-lg-3"  id="for" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>For</label>
        	<select class="d_filter form-control" name="d_enquiry_id">
        		<option value="">Select</option>
        		<?php
        		if(!empty($all_enquiry))
        		{
        			foreach ($all_enquiry as $row) 
        			{  
                $row  = (array)$row;
        				echo'<option value="'.$row['enquiry_id'].'">'.$row['name_prefix'].' '.$row['name'].' '.$row['lastname'].'</option>';
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

    <div class="col-lg-3" id="booking_type" style="<?php if(!in_array('booking_type',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Booking Type</label>
           <select class="d_filter form-control" name="d_booking_type">
                    <option value="">-Select-</option>
                    <option value="0" selected>Sundry</option>
                    <option value="1" >FTL</option>
                </select>
        </div>
    </div>

    <div class="col-lg-3" id="booking_branch" style="<?php if(!in_array('booking_branch',$variable)){echo'display:none';} ?>">
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

    <div class="col-lg-3" id="delivery_branch" style="<?php if(!in_array('delivery_branch',$variable)){echo'display:none';} ?>">
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

  <div class="col-lg-3" id="paymode" style="<?php if(!in_array('paymode',$variable)){echo'display:none';} ?>">
        <div class="form-group">
          <label>Paymode</label>
          <select class="d_filter form-control" name="d_paymode">
                   <option value="1" selected>Paid</option>
                   <option value="2" >To-Pay</option>
                   <option value="3" >Tbb</option>
                </select>
        </div>
    </div>

  <div class="col-lg-3" id="potential_amount" style="<?php if(!in_array('potential_amount',$variable)){echo'display:none';} ?>">
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
                  <th id="th-2">Branch Type</th>
                  <th id="th-3">Business Type</th>
                  <th id="th-4">Booking Type</th>
                  <th id="th-5">Booking Branch</th>
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
                  <th id="th-17">Invoice Value</th>
                  <th id="th-18">Create Date</th>
                  <th id="th-19">Status</th>
                  <th id="th-20">Action</th>
               </tr>
            </thead>
				      <tbody>
		     		 </tbody>
    			</table>

</div>

<div id="editComInfo" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update </h4>
         </div>
      <form class="form-inner" action="<?=  base_url('enquiry/insertCommercialInfo/') ?>" method="POST">
         <div class="modal-body" >
         <div  id="editcomInfoData">
           
         </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" name="edit" class="btn btn-primary" value="Updatte">
         </div>
         </form> 

      </div>
   </div>
</div>
<!-- edit -->

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
                     d.booking_branch  =  $("select[name=d_booking_branch]").val();
                     d.delivery_branch  =  $("select[name=d_delivery_branch]").val();
                     d.paymode  =  $("select[name=d_paymode]").val();
                     d.p_amnt_from  =  $("input[name=d_p_amnt_from]").val();
                     d.p_amnt_to =  $("input[name=d_p_amnt_to]").val();
                     
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

$("select").select2();


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

<script>
   $(document).ready(function(){

    $('#booking_type').on('change', function() {
      if ( this.value == '1')
      {
        $("#ftl").show();
        $("#sundry").hide();
      }  else {
        $("#sundry").show();
        $("#ftl").hide();
      }
    });


    $('#delivery_branch').on('change', function() {
            var delivery_branch = $("select[name='delivery_branch']").val();
            var booking_branch = $("select[name='booking_branch']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/get_rate',
            data: {delivery_branch:delivery_branch,booking_branch:booking_branch},
            success:function(data){
                var obj = JSON.parse(data);
                $("#rate").val(obj.rate);
            }
            });
            });

$('#potential_tonnage').on('change', function() {
                var rate = document.getElementById('rate').value;           
                var potential_tonnage = document.getElementById('potential_tonnage').value;    
                var weightinKg= potential_tonnage*1000;       
               var total_ptAmount=weightinKg*rate;
               // alert(total_ptAmount);
                        $("#potential_amount").val(total_ptAmount);
                    });
                    $('#expected_tonnage').on('change', function() {
                var rate = document.getElementById('rate').value;           
                var expected_tonnage = document.getElementById('expected_tonnage').value;    
                var weightinKg= expected_tonnage*1000;       
               var total_extAmount=weightinKg*rate;
               // alert(total_ptAmount);
                        $("#expected_amount").val(total_extAmount);
                    });

$('#infotype').on('change', function() {
            var infotype = $("select[name='type']").val();
            if(infotype==1){
               $("#textdisplay").html('Booking Branch');
               $("#textdisplay2").html('Delivery Branch');
            }else if(infotype==2){
               $("#textdisplay").html('Booking Zone');
               $("#textdisplay2").html('Delivery Zone');

            }else if(infotype==3){
               $("#textdisplay").html('Booking Area');
               $("#textdisplay2").html('Delivery Area');

            }else{
               $("#textdisplay").html('Booking Branch');
               $("#textdisplay2").html('Delivery Branch');

            }
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
<form class="form-inner" action="<?=  base_url('enquiry/insertCommercialInfo/') ?>" method="POST">
               
            <div class="row">

              <div class="form-group col-md-12">
                    <label>Company</label>
                    <select class="form-control" name="company" onchange="filter_related_to(this.value)">
                      <option value="-1">Select</option>
                      <?php
                      if(!empty($company_list))
                      {
                        foreach ($company_list as $key =>  $row)
                        {
                          echo '<option value="'.$key.'">'.$row->company.'</option>';
                        }
                      }
                      ?>
                    </select>
                </div>


              <div class="form-group col-md-6">
                  <label>Related To (Primary Contact) <a href="<?= base_url('enquiry/create?status=1') ?>" target="_blank"  style=" float:right;margin-left: 30px;"> <i class="fa fa-plus-square"> </i></a> </label>
                  <select class="form-control" name="enquiry_id">
                    <option value="">Select</option>
                    <?php
                  if(!empty($all_enquiry))
                  {
                    foreach ($all_enquiry as $row)
                    {
                      echo'<option value="'.$row->enquiry_id.'">'.$row->name.'</option>';
                    }
                  }
                    ?>
                  </select>
               </div>

               <div class=" col-sm-6">
                    <div class="form-group"  > 
                        <label>Info Type</label>
                        <select class="form-control" name="type" id="infotype">
                        <?php
                        $branch_type=0;
                        if($commInfoCount==1){
                               $branch_type=$commInfoData->branch_type;
                            }
                            $booking_type=0;
                            if($commInfoCount==1){
                                   $booking_type=$commInfoData->booking_type;
                                }
                                $business_type=0;
                                if($commInfoCount==1){
                                       $business_type=$commInfoData->business_type;
                                    }
                                    $insurance=0;
                                    if($commInfoCount==1){
                                           $insurance=$commInfoData->insurance;
                                        }
                                        $paymode=0;
                                        if($commInfoCount==1){
                                               $paymode=$commInfoData->paymode;
                                            } 
                                            $booking_branch=0;
                                        if($commInfoCount==1){
                                               $booking_branch=$commInfoData->booking_branch;
                                            }
                                            $delivery_branch=0;
                                            if($commInfoCount==1){
                                                   $delivery_branch=$commInfoData->delivery_branch;
                                                } 
                                                 $rate=0;
                                                    if($commInfoCount==1){
                                                           $rate=$commInfoData->rate;
                                                        }

                                                ?>
                            <option value="">-Select-</option> 
                            <option value="1" <?php if($branch_type==1){ echo'selected';} ?>>Branch</option>
                            <option value="2" <?php if($branch_type==2){ echo'selected';} ?>>Zone</option>
                            <option value="3" <?php if($branch_type==3){ echo'selected';} ?>>Areawise</option>
                        </select>
                     </div>
                </div>
            </div>
              <br>
             <center><h5><u>DISPTACH LOCATION</u></h5></center>
             <br>
             <div class="form-group col-sm-6"> 
                <label>Booking Type</label>
               
                <select class="form-control" name="booking_type" id="booking_type">
                    <option value="">-Select-</option>
                    <option value="0" <?php if($booking_type==0){ echo'selected';} ?>>Sundry</option>
                    <option value="1" <?php if($booking_type==1){ echo'selected';} ?>>FTL</option>
                </select>
             </div>
             <div class="form-group col-sm-6"> 
                <label>Business Type</label>
                <select class="form-control" name="business_type" id="business_type">
                    <option value="">-Select-</option>
                    <option value="0" <?php if($business_type==0){ echo'selected';} ?>>Inward</option>
                    <option value="1" <?php if($business_type==1){ echo'selected';} ?>>outward</option>
                </select>
             </div>
              <div class="form-group col-sm-6"> 
                 <label>Insurance</label>
                 <select class="form-control" name="insurance" id="insurance">
                    <option value="0" <?php if($insurance==0){ echo'selected';} ?>>Carrier</option>
                    <option value="1" <?php if($insurance==0){ echo'selected';} ?>>Owner risk</option>
                 </select>
              </div>
            <div class="form-group col-sm-6"> 
                <label>Pay Mode</label>
                <select class="form-control" name="paymode" id="paymode">
                   <option value="1" <?php if($paymode==1){ echo'selected';} ?>>paid</option>
                   <option value="2" <?php if($paymode==2){ echo'selected';} ?>>To-Pay</option>
                   <option value="3" <?php if($paymode==3){ echo'selected';} ?>>Tbb</option>
                </select>
            </div>
            <div class="form-group col-sm-6"> 
               <label id="textdisplay">Booking Branch</label>
               <select class="form-control" name="booking_branch" id="booking_branch">
                  <option value="">-Select-</option>
                <?php 
                foreach($branch as $dbranch){ ?>
                      <option value="<?= $dbranch->branch_id ?>" <?php if($booking_branch==$dbranch->branch_id){ echo'selected';} ?>><?= $dbranch->branch_name ?></option>
                     <?php }  ?>
               </select>
            </div>
            <div class="form-group col-sm-6"> 
               <label id="textdisplay2">Delivery Branch</label>
               <select class="form-control" name="delivery_branch" id="delivery_branch" >
                  <option value="">-Select-</option>
                  <?php  
                  foreach($branch as $dbranch){ ?>
                      <option value="<?= $dbranch->branch_id ?>" <?php if($delivery_branch==$dbranch->branch_id){ echo'selected';} ?>><?= $dbranch->branch_name ?></option>
                     <?php }  ?>
               </select>
            </div>
                            
            <div class="sundry" id="sundry" <?php if($booking_type==1){ echo'style="display:none"';} ?>>
                <div class="form-group col-sm-6"> 
                   <label>Rate</label>
                   <input class="form-control rate" readonly name="rate" id="rate" type="text" value="<?=$rate?>"  >  
                </div>
                <div class="form-group col-sm-6"> 
                   <label>Discount</label>
                   <input class="form-control" name="discount" id="discount" type="number" step="0.00"  >  
                </div>
                             
                <div class="form-group col-sm-6"> 
                   <label>Potential Tonnage</label>
                   <input class="form-control" name="potential_tonnage" id="potential_tonnage" type="text"  >  
                </div>
                <div class="form-group col-sm-6"> 
                   <label>Potential Amount</label>
                   <input class="form-control" readonly name="potential_amount" id="potential_amount" type="text"  >  
                </div>
                <div class="form-group col-sm-6"> 
                   <label>Expected Tonnage</label>
                   <input class="form-control" name="expected_tonnage" id="expected_tonnage" type="text"  >  
                </div>
                <div class="form-group col-sm-6"> 
                   <label>Expected Amount</label>
                   <input class="form-control"  name="expected_amount" id="expected_amount" type="text"  >  
                </div>
            </div>
                              
            <div class="ftl" id="ftl" <?php if($booking_type==0){ echo'style="display:none"';} ?>>
               <div class="form-group col-sm-6"> 
                  <label>Vehicle type</label>
                  <input class="form-control" name="vehicle_type" id="Vehicle_type" type="text"  >  
               </div>
               <div class="form-group col-sm-6"> 
                  <label>Vehicle Carrying Capacity</label>
                  <input class="form-control" name="capacity" id="capacity" type="text"  >  
               </div>
            
               <div class="form-group col-sm-6"> 
                  <label>Invoice Value</label>
                  <input class="form-control" name="invoice_value" id="invoice_value" type="text"  >  
               </div>
              
               <div class="form-group col-sm-6"> 
                  <label>Potential Amount</label>
                  <input class="form-control" name="ftlpotential_amount" id="ftlpotential_amount" type="text"  >  
               </div>
               <div class="form-group col-sm-6"> 
                  <label>Expected Amount</label>
                  <input class="form-control" name="ftlexpected_amount" id="ftlexpected_amount" type="text"  >  
               </div>
            </div>


         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input type="submit" name="submit_only" class="btn btn-primary" value="Save">
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

<div id="downloadQuatation" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Download</h4>
         </div>
         <form action="<?= base_url('dashboard/pdf_gen/') ?>" method="POST">

         <div class="modal-body">
            <!-- <input name="idType" hidden class="idType" id="idType"> -->
            <input id="enq_id_for_download" name="enquiry_id" type="hidden" value="">
             <div id="data_value" class="data_value" style="padding:10px;"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" name="download" class="btn btn-primary" value="Download">
            <input type="submit" name="email" class="btn btn-primary" value="Email">
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
              <label class=""><input type="checkbox" class="choose-col" value="2"> Branch Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="3"> Business Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4"> Booking Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="5"> Booking Branch</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="6"> Delivery Branch</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="7"> Rate</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="8"> Discount</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="9"> Insurance Location</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="10"> Paymode</label>
            </div>

             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="11"> Potential Tonnage</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="12"> Potential Amount</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="13"> Expected  Tonnage</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="14"> Expected  Amount</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="15"> Vehicle Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="16"> Vehicle Carrying Capacity</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="17"> Invoice Value</label>
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

function quotation_pdf(typeId,enqid) {
  $(".data_value").empty();
  
 $("#enq_id_for_download").val(enqid);
 
   var elem = document.getElementById('view_sdatas');
  $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>dashboard/printPdf_gen',
            data: {typeId:typeId,enqid:enqid},
            success:function(data){
                $(".data_value").html(data);
            }
            });
}
</script>

<script type="text/javascript">
  var LIST = <?php echo !empty($company_list)? json_encode($company_list): '{}'?>;
  var OLD_LIST  = <?=!empty($all_enquiry) ? json_encode($all_enquiry):'{}'?>;
  function filter_related_to(v)
  {
      if(Object.keys(LIST).length>0 && v!='-1')
      { 
        var l = '';
        var y = LIST[v];
        var ids = y.enq_ids.split(',');
        var names = y.enq_names.split(',');
        $(ids).each(function(k,id){
            l+="<option value='"+id+"'>"+names[k]+"</option>";
        });
        //alert(l);
        $("select[name=enquiry_id]").html(l);
      }
      else
      { var l = '';
          $(OLD_LIST).each(function(k,v){
            l+="<option value='"+v.enquiry_id+"'>"+v.name_prefix+" "+v.name+" "+v.lastname+"</option>";
          });
          $("select[name=enquiry_id]").html(l);
      }
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