 ?>
              <div class="tab-pane" id="COMMERCIAL_INFORMATION" >
            <!-- <div class="col-md-12"><a class="btn btn-primary" style="cursor: pointer;" data-toggle="modal" data-target="#downloadQuatation">Download Quatation</a></div> -->
                 <div  style="overflow-x:auto;">
        <table class="table table-responsive-sm table-responsive table-hover table-bordered" >
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Branch Type</th>
                              <th>Business Type</th>
                              <th>Booking Type</th>
                              <th>Booking Branch</th>
                              <th>Delivery Branch</th>
                              <th>Rate</th>
                              <th>Discount</th>
                              <th>Insurance</th>
                              <th>Paymode</th>
                              <th>Potential Tonnage</th>
                              <th>Potential Amount</th>
                              <th>Expected  Tonnage</th>
                              <th>Expected  Amount</th>
                              <th>Vehicle Type</th>
                              <th>Vehicle Carrying Capacity</th>
                              <th>Invoice Value</th>
                              <th>Create Date</th>
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>

                           <?php 
                              $sl = 1;
                              if(!empty($CommercialInfo)){ 
                                foreach ($CommercialInfo as $value) {
                                   if($value->branch_type==1){
                                    $branch_type='Branch';
                                   }elseif($value->branch_type==2){
                                    $branch_type='Zone';
                                   }elseif($value->branch_type==3){
                                    $branch_type='Area wise';
                                   }
                                   if($value->business_type==0){
                                    $business_type='Inward';
                                   }elseif($value->business_type==1){
                                    $business_type='Outword';
                                   }
                                   if($value->insurance==0){
                                    $insurance='Carrier';
                                   }elseif($value->insurance==1){
                                    $insurance='Owner Risk';
                                   }
                                   if($value->booking_type==0){
                                    $booking_type='Sundy';
                                   }elseif($value->booking_type==1){
                                    $booking_type='FTL';
                                   }
                                   
                                   if($value->paymode==1){
                                    $paymode='Paid';
                                   }elseif($value->paymode==2){
                                    $paymode='To-Pay';
                                   }elseif($value->paymode==3){
                                    $paymode='Tbb';
                                   }
                                   ?>
                        <tr>
                              <td><?= $sl++ ?></td>
                              <td><?= $branch_type ?></td>
                              <td><?=$business_type ?> </td>
                              <td><?=$booking_type ?> </td>
                              <td><?=$value->branch_name ?></td>
                              <td><?=$value->bn ?></td>
                              <td><?=$value->rate ?></td>
                              <td><?=$value->discount ?></td>
                              <td><?=$insurance ?></td>
                              <td><?=$paymode ?></td>
                              <td><?=$value->potential_tonnage ?></td>
                              <td><?=$value->potential_amount ?></td>
                              <td><?=$value->expected_tonnage ?></td>
                              <td><?=$value->expected_amount ?></td>
                              <td><?=$value->vehicle_type ?></td>
                              <td><?=$value->carrying_capacity ?></td>
                              <td><?=$value->invoice_value ?></td>
                              <td><?=$value->creation_date ?></td>
                              <th>
                                <select onchange="update_info_status(<?=$value->id?>,this.value)">
                                  <option <?php if($value->status==1){ echo'selected';} ?> value="1">Done</option>
                                  <option <?php if($value->status==0){ echo'selected';} ?> value="0">Pending</option>
                                  <option <?php if($value->status==2){ echo'selected';} ?> value="2">Deferred</option>
                                </select>
                              </th>
                              <td class="center">
                  <a  class="btn btn-xs  btn-primary" data-toggle="modal"  data-target="#editComInfo" onclick="editComInfo(<?= $value->id ?>);" data-bid="<?= $value->id ?>" data-equid=""><i class="fa fa-edit"></i></a>
                  <!-- <a href="<?= base_url('enquiry/editinfo/' . $value->id . '')?>" class="btn btn-xs  btn-primary view_data"><i class="fa fa-edit"></i></a> -->
                  <a href="<?= base_url('enquiry/deleteInfo/' . $value->id . '/'.$value->enquiry_id.'/') ?>" onclick="return confirm('Are You Sure ? ')" class="btn btn-xs  btn-danger"><i class="fa fa-trash"></i></a>
                  <a class="btn btn-primary btn-xs view_datas" id="view_sdatas" onclick="quotation_pdf(<?= $value->booking_type ?>,<?= $value->enquiry_id ?>)" style="cursor: pointer;" data-toggle="modal"  data-target="#downloadQuatation" data-id="" data-equid=""><i class="fa fa-download"></i></a>
                </td>
                           </tr> 
                           <?php  } }  ?>
                            </tbody>
                                             </table>
                 </div>
                  <hr>
                  <form class="form-inner" action="<?=base_url('enquiry/insertCommercialInfo/') ?>" method="POST">
                  <input type="hidden" name="enquiry_id" value="<?= $details->enquiry_id ?>">
                    <!--------------------------------------------------start-----------------------------> 
                    <div class="row">

                    <div class=" col-sm-3">
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
                                                } ?>
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
                           <select class="form-control" name="delivery_branch[]" id="delivery_branch" multiple required>
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
                                 <input class="form-control rate" readonly name="rate" id="rate" type="text"  >  
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

                     <div class="col-md-12" >
                        <div class="row">
                           <center>
                              <button class="btn btn-primary" type="submit" name="save_com_info">Save</button>   
                           </center>         
                        </div>
                     </div>
                  </form>
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
function update_info_status(id,status)
{
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/update_info_status/<?=$enquiry->Enquery_id?>',
            data: {id:id,status:status},
            success:function(data){
                Swal.fire('Status Updated');
            }
        });
}
$('#delivery_branch').on('change', function() {

            var delivery_branch = $("select[name='delivery_branch[]']").val()??[];
            var booking_branch = $("select[name='booking_branch']").val();
            
            delivery_branch =  delivery_branch.join(',');

            if(delivery_branch.includes(',') || delivery_branch=='')
            {
                $("#rate").closest('.form-group').hide();
                $("#potential_amount").closest(".form-group").hide();
                $("#expected_amount").closest(".form-group").hide();
            }
            else
            { 
                $("#rate").closest('.form-group').show();
                $("#potential_amount").closest(".form-group").show();
                $("#expected_amount").closest(".form-group").show();

                 $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url();?>enquiry/get_rate',
                  data: {delivery_branch:delivery_branch,booking_branch:booking_branch},
                  success:function(data){
                      var obj = JSON.parse(data);

                      $("#rate").val(obj.rate);
                  }
                  });
            }
            });
$('#booking_branch').on('change', function() {

    var delivery_branch = $("select[name='delivery_branch[]']").val()??[];
    var booking_branch = $("select[name='booking_branch']").val();


    $("#delivery_branch").find('option').removeAttr('disabled');
    $("#delivery_branch").find('option[value="'+booking_branch+'"]').attr('disabled','disabled');
    if(delivery_branch.includes(booking_branch))
    { var ARY =new Array();
       $(delivery_branch).each(function(k,v){
          if(v!=booking_branch)
            ARY.push(v);
       });
       //alert(ARY.toString());
       $("#delivery_branch").val(ARY);
       $('#delivery_branch').trigger('change');
    }


   });
$('#booking_branch').trigger('change');
$('#potential_tonnage').on('change', function() {
                var discount = $("#discount").val();

                var rate = document.getElementById('rate').value;           
                var potential_tonnage = document.getElementById('potential_tonnage').value;    
                var weightinKg= potential_tonnage*1000;       
               var total_ptAmount=weightinKg*rate;
               // alert(total_ptAmount);
                total_ptAmount =  (total_ptAmount - ((total_ptAmount * discount)/100));
                total_ptAmount = total_ptAmount.toFixed(2);
                        $("#potential_amount").val(total_ptAmount);
                    });

  $('#expected_tonnage').on('change', function() {
               var discount = $("#discount").val();
                var rate = document.getElementById('rate').value;           
                var expected_tonnage = document.getElementById('expected_tonnage').value;    
                var weightinKg= expected_tonnage*1000;       
               var total_extAmount=weightinKg*rate;
               // alert(total_ptAmount);

                total_extAmount =  (total_extAmount - ((total_extAmount * discount)/100));
                total_extAmount = total_extAmount.toFixed(2);
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
  </script>
<script>
   $(document).ready(function(){
    var dl = document.getElementById('delivery_branch');
    var event = new Event('change'); 
    dl.dispatchEvent(event);
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
});

</script>

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
            <input name="enquiry_id" hidden value="<?= $details->enquiry_id ?>">
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
<script>

function quotation_pdf(typeId,enqid) {
   $(".data_value").empty();
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
<?php
