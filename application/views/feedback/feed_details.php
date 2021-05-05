<div class="col-md-6 col-sm-12 card card-body col-height details-column" style="border: 1px solid #c8ced3;padding: 15px;border-top: none;">
<div class="exTab3">
	<ul  class="nav nav-tabs" role="tablist"> 
		<li class="active"><a  href="#basic" data-toggle="tab" style="padding: 10px 10px; ">Basic</a></li>
		<li><a  href="#feedback" data-toggle="tab" style="padding: 10px 10px;">Feedback</a></li>
		<li><a  href="#prefeedback" data-toggle="tab" style="padding: 10px 10px;">Previous Feedback</a></li>
	</ul>
	<div class="tab-content clearfix">
        <div class="tab-pane active" id="basic">
       <form id="disable_form">
			<div class="row">

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12" style ="background: #f7f7f7;border: 1px solid #ccc;padding: 15px;border-radius: 10px;margin-bottom:25px;">
				    <div class="row">
	<div class="form-group col-md-4">
			<label>GC No.</label>
			<input type="text" class="form-control" value="<?php echo $ftlfeed->tracking_no; ?>"> </div>

	<div class="form-group col-md-4">
			<label>Name</label>
			<input type="text" class="form-control" value="<?php echo $ftlfeed->name; ?>"> </div>

	<div class="form-group col-md-4">
			<label>Phone</label>
			<input type="text" class="form-control" value="<?php echo $ftlfeed->phone; ?>"> </div>

	<div class="form-group col-md-4">
			<label>Email</label>
			<input type="email" class="form-control" value="<?php echo $ftlfeed->email; ?>"> </div>

	<div class="form-group col-md-4">
		<label>GC Date</label>
		<input type="text" class="form-control" value="<?php echo $ftlfeed->gc_date; ?>"> </div>
		
	<div class="form-group col-md-4">
		<label>Bkg Branch</label>
		<input type="text" value="<?php echo $ftlfeed->branch_name; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Bkg Region</label>
		<input type="text" value="<?php echo $ftlfeed->region; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Delivery Branch</label>
		<input type="text" value="<?php echo $ftlfeed->delbrcnh; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Dly Type</label>
		<input type="text" value="<?php echo $ftlfeed->dly_type; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Pay Mode</label>
		<input type="text" value="<?php echo $ftlfeed->pay_mode; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Charged Weight</label>
		<input type="text" value="<?php echo $ftlfeed->charged_weight; ?>" class="form-control"> </div>
		
    <div class="form-group col-md-4">
		<label>No Of Articles</label>
		<input type="text" value="<?php echo $ftlfeed->no_of_articles; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Actual Weight</label>
		<input type="text" value="<?php echo $ftlfeed->actual_weight; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignor Name</label>
		<input type="text" value="<?php echo $ftlfeed->consignor_name; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignor Tel No</label>
		<input type="text" value="<?php echo $ftlfeed->consignor_tel_no; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignor Mobile No</label>
		<input type="text" value="<?php echo $ftlfeed->consignor_mobile_no; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignee Name</label>
		<input type="text" value="<?php echo $ftlfeed->consignee_name; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignee Tel No</label>
		<input type="text" value="<?php echo $ftlfeed->consignee_tel_no; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Consignee Mobile No</label>
		<input type="text" value="<?php echo $ftlfeed->consignee_mobile_no; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Current Status</label>
		<input type="text" value="<?php echo $ftlfeed->current_status; ?>" class="form-control"> </div>
		
	<div class="form-group col-md-4">
		<label>Vehicle No</label>
		<input type="text" value="<?php echo $ftlfeed->vehicle_no; ?>" class="form-control"> </div>
    </div>
					
					<!--<div class="text-center">					
						<input type ="hidden" name = "ticketno" value = "<?php echo $ftlfeed->fdbk_id; ?>">
						<input type ="hidden" name = "client" value = "<?php echo $ftlfeed->tracking_no; ?>">
					</div>-->
					
				</div>
				<!--<center><button type = "submit" class="btn btn-success">Update</button></center>-->
			</div>	
		</div>	
	  </div>
	  </form>
	</div>
	
	<div class="tab-pane" id="feedback">
       <form id="customer_feed" method="POST">
			<div class="row">

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12" style ="background: #f7f7f7;border: 1px solid #ccc;padding: 15px;border-radius: 10px;margin-bottom:25px;">
				
					<div class="row">
					
		<div class="form-group col-md-6">
			<label>How Are The Services ?</label>
			<select class="form-control" name="service">
				<option >Select</option>
				<option value="Average">Average</option>
				<option value="Good">Good</option>
				<option value="Very Good">Very Good</option>
				<option value="Excellent">Excellent</option>
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label>Is This First FTL</label>
			<select class="form-control" name="first_ftl">
				<option >Select</option>
				<option value="YES">YES</option>
				<option value=" NO">NO</option>
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label>Other Locations Where FTL Service Is Required</label>
			<input type="text" name="other_loc" class="form-control">
		</div>
		
		<div class="form-group col-md-6">
			<label>If Using Any Other Transporter</label>
			<select class="form-control" name="other_trans">
				<option >Select</option>
				<option value="YES">YES</option>
				<option value="NO">NO</option>
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label>If Yes Please Specify Name :</label>
			<input type="text" name="trans_name" class="form-control">
		</div>
		
		<div class="form-group col-md-12">
			<label>Remarks On Improvement Required</label>
			<textarea name="improvement_rmk" class="form-control"></textarea>
		</div>
		
		<div class="form-group col-md-6">
			<label>Next FTL Booking Expected</label>
			<input type="text" name="exp_booking" class="form-control">
		</div>
		
		<div class="form-group col-md-6">
			<label>Customer Feedback</label>
			<select class="form-control" name="cust_feed">
				<option >Select</option>
				<?php foreach($customer_feed as $cfeed){ ?>
				<option value="<?php echo $cfeed->id; ?>"><?php echo $cfeed->feedback; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label>Action Taken</label>
			<input type="text" name="action_taken" class="form-control">
		</div>
		
		<div class="form-group col-md-6">
			<label>Response By</label>
			<input type="text" name="resp_by" class="form-control">
		</div>
		
		<div class="form-group col-md-12">
			<label>Response Remark</label>
			<textarea name="resp_rmk" class="form-control"></textarea>
		</div>
					</div>
					<div class="text-center">
						<input type ="hidden" name = "client_gc" value = "<?php echo $ftlfeed->tracking_no; ?>">
					</div>
					
				</div>
				<center><button type = "button" id="add_feedback_form" class="btn btn-success">Add New</button></center>
			</div>	
		</div>	
	  </div>
	  </form>
	</div>
	
	<div class="tab-pane" id="prefeedback">
			<div class="row">

		<div class="col-md-12">
			<div class="row">
					
		    <table width="100%" id="predatatable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
				  <th>S.no</th>
				  <th>GC No</th>
                  <th>How Are The Services</th>
                  <th>Is This First FTL</th>
                  <th>Other Locations Where FTL Service Is Required</th>
				  <th>If Using Any Other Transporter</th>
				  <th>If Yes Please Specify Name</th>
				  <th>Remarks On Improvement Required</th>
				  <th>Next FTL Booking Expected</th>
				  <th>Customer Feedback</th>
				  <th>Action Taken</th>
				  <th>Response By</th>
				  <th>Response Remark</th>
                </tr>
            </thead>
            <tbody>
			<?php $i=1; foreach($feed_tab as $tab){ ?>
                <tr>
				  <th><?php echo $i; ?></th>
				  <th><?php echo $tab->gc_no; ?></th>
                  <th><?php echo $tab->service; ?></th>
                  <th><?php echo $tab->first_ftl; ?></th>
                  <th><?php echo $tab->other_loc; ?></th>
				  <th><?php echo $tab->other_trans; ?></th>
				  <th><?php echo $tab->trans_name; ?></th>
				  <th><?php echo $tab->improvement_rmk; ?></th>
				  <th><?php echo $tab->exp_booking; ?></th>
				  <th><?php foreach($customer_feed as $cfeed){ if($cfeed->id==$tab->cust_feed){ echo $cfeed->feedback;}} ?></th>
				  <th><?php echo $tab->action_taken; ?></th>
				  <th><?php echo $tab->resp_by; ?></th>
				  <th><?php echo $tab->resp_rmk; ?></th>
                </tr>
			<?php $i++; } ?>
            </tbody>            
          </table>
				
			</div>	
		</div>	
	  </div>
	</div>
	
  </div>
</div>
</div>
 <style>
 	 .col-height{
    min-height: 700px;
    max-height: 700px;
    overflow-y: auto;
    border-bottom: solid #c8ced3 1px;
  }
		.nav-tabs
        {
         overflow-x: hidden;
         overflow-y:hidden;
         white-space: nowrap;
         height: 50px;
        }
        .nav-tabs > li
        {
           white-space: nowrap;
           float: none;
           display: inline-block;
           font-size: 11px;
           background-color: #283593;
        }

		.nav-tabs > li.active > a {
		    color: #555 !important;
		    background-color: #fff;
		}
        .nav-tabs > li > a {
         border-radius: 4px 4px 0 0 ;
         color: #fff!important;
         }
         #exTab3 .tab-content {
         /*color : white;*/
         background-color: #fff;
         padding : 5px 15px;
         }
      .nav-tabs > li.active > a:hover {
	    color: #555;
	    cursor: default;
	    background-color: #fff;
	    border: none!important;
	   }
	   .nav-tabs > li.active > a {
	    color: #555;
	    cursor: default;
	    background-color: #fff;
	    border: none!important;
	   }

         .card {
         position: relative;
         display: -ms-flexbox;
         display: flex;
         -ms-flex-direction: column;
         flex-direction: column;
         min-width: 0;
         word-wrap: break-word;
         /*background-color: #fff;*/
         background-clip: border-box;
         border: 1px solid #c8ced3;
         border-radius: 0.25rem;
         }
         .card-body {
         -ms-flex: 1 1 auto;
         flex: 1 1 auto;
         padding: 1.25rem;
         }
         .list-group {
         display: -ms-flexbox;
         display: flex;
         -ms-flex-direction: column;
         flex-direction: column;
         padding-left: 0;
         margin-bottom: 0;
         }
         .list-group-item {
         position: relative;
         display: block;
         padding: 0.75rem 1.25rem;
         margin-bottom: -1px;
         background-color: #fff;
         border: 1px solid rgba(0, 0, 0, 0.125);
         }
         .list-group-item-action {
         width: 100%;
         color: #5c6873;
         text-align: inherit;
         }
         .active .badge{color: white!important;}
      </style>

<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>      
<!-- DataTables JavaScript -->  
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script type="text/javascript">
        $(document).ready(function() {
        $('#predatatable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );
</script>
<script>
    $(document).ready(function(){
        $("#disable_form :input").prop("disabled", true);
    });
</script>
<script>
$("#add_feedback_form").click(function(e) {
	//alert('hi');
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var url =  '<?php echo base_url();?>ticket/add_feedback';
      $.ajax({
         type: "POST",
         url: url,
         data: $('#customer_feed').serialize(),		 // serializes the form's elements.
         success: function(data)
         {
if(data==1){			 
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Feedback Create successfully',
  showConfirmButton: false,
  timer: 1500
});
}else{
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Feedback Update successfully',
  showConfirmButton: false,
  timer: 1500
});
}
         }
       });
  });
</script>