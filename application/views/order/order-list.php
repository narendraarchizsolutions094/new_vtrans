<style>
  /*TAG STYLE START*/
.tag {
  background: #eee;
  border-radius: 3px 0 0 3px;
  color: red;
  display: inline-block;
  height: 17px;
  line-height: 17px;
  padding: 0 10px 0 19px;
  position: relative;  
  text-decoration: none;
  -webkit-transition: color 0.2s;
  font-size: xx-small !important;  
}

.tag::before {
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  content: '';
  height: 6px;
  left: 10px;
  position: absolute;
  width: 6px;
  top: 6px;
}

.tag::after {
  background: #fff;
  border-bottom: 8px solid transparent;
  border-left: 10px solid #eee;
  border-top: 9px solid transparent;
  content: '';
  position: absolute;
  right: 0;
  top: 0;
}

.tag:hover {
  background-color: crimson;
  color: white;
}

.tag:hover::after {
   border-left-color: crimson; 
}
/*TAG STYLE END*/


.col-half-offset{
  margin-left:2.166667%;
}
.enq_form_filters{
  width: 0px;
}
#active_class{
  font-size: 12px;
}
.lead_stage_filter{
  padding: 6px;
  background-color: #e6e9ed;
  cursor: pointer;
}
.lead_stage_filter:active{  
  background-color: #20a8d8;  
}
.lead_stage_filter:hover{  
  background-color: #20a8d8;  
}
.border_bottom_active > label{
  cursor: pointer;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
    color: white;
    background-color: #37a000;
}

.nav-pills > li > a {
    border-radius: 5px;
    padding: 10px;
    color: #000;
    font-weight: 600;
}

.nav-pills > li > a:hover {
    color: #000;
    background-color: transparent;
}
              .dropdown-header {
    padding: 8px 20px;
    background: #e4e7ea;
    border-bottom: 1px solid #c8ced3;
}

.dropdown-header {
    display: block;
    padding: 0 1.5rem;
    margin-bottom: 0;
   
    color: #73818f;
    white-space: nowrap;
}
input[name=top_filter]{
	visibility: hidden;
}

input[name=lead_stages]{
  visibility: hidden;
}

.dropdown_css {
  left:auto!important;
  right: 0 ! important;
}
.dropdown_css a,.dropdown_css a h4{
  width:100%;text-align:left! important;
  border-bottom: 1px solid #c8ced3! important;
}

.border_bottom{
  border-bottom:2px solid #E4E5E6;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
}  
.border_bottom_active{
  border-bottom:2px solid #20A8D8;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
} 

.filter-dropdown-menu li{
  padding-left: 6px;
}

.filter-dropdown-menu li{
  padding-left: 6px;
}

</style>
	
	
	<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">  
        <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>        
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" id="enq-create" href="<?php echo base_url("order/addorder"); ?>" title="Add Stock"></a>         
        </div>
         <div class="col-md-4 col-sm-8 col-xs-8 pull-right">  
         
      </div>
</div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
				<div class = "row">
					<div class = "col-md-12">
				<div class="table-responsive">
										<table  class="table table-bordered table-hover" id = "add-datatable" style="width:100%;">
							<thead>
												<tr>
													<th class="wd-15p">S.No.</th>
													<th class="wd-15p"> Order No </th>
												
													<th class="wd-15p">Product</th>
													<th class="wd-15p">Quantity</th>
													<th class="wd-15p">Price</th>
													
													<th class="wd-15p">Payment</th>
													<th class="wd-15p">Pay</th>
													<th class="wd-15p">Balance</th>
													<th class="wd-15p">Delivery Date</th>
													<th class="wd-15p"> Date</th>
													
													<th class="wd-15p">Status</th>
													<th class="wd-15p">Action</th>
													
												</tr>
											</thead>

											<tbody>
												<?php if(!empty($orders)){

												   $sl=1;
                                                  foreach($orders as $ind =>  $ord){
												?>
												<tr>

													<td><?= $ind + 1; ?></td>
													<td><?php echo $ord->ord_no; ?></td>
											
													<td><?= ucwords($ord->product_name)  ?></td>
													<td>
															<?= $ord->quantity; ?>
													</td>
													<td><?= $ord->price; ?>
													</td>
													<td>
													<span class = "badge badge-info">
														Mode : <?php 
												/*	if($ord->pay_mode == 1){
														echo "Online";	
													}else if($ord->pay_mode == 2){
														echo "Account Transfer";	
													}else if($ord->pay_mode == 3){
														echo "By Check";	
													}else if($ord->pay_mode == 4){
														echo "By Cash";	
													} */ ?>
													</span><br />
														<span class = "badge badge-warning">
															Status : <?php  
														/*	if($ord->pay_status == 1){
																echo "Pending";
															}else{
																echo "Complete";
															} */ ?>
														</span>
													</td>
													<td><?php // $ord->balance; ?></td>
													<td><?php echo $ord->customer;  ?></td>
													<td><?php // date("d,M Y", strtotime($ord->delivery_date)); ?></td>
													<td><?php // date("d, M Y", strtotime($ord->order_date)); ?></td>
													<td>
													<?php if($ord->status  == 1 ){
															echo "Request";
													}else if($ord->status  == 2 ){
															echo "Waiting";
													}else if($ord->status  == 3 ){
															echo "Half Confirm";
													}else if($ord->status  == 4 ){
															echo "Full Confirm";
													}else if($ord->status  == 5 ){
															echo "Reject";
													} ?>
													</td>
													<td>
												
														<a href="<?php echo base_url("order/update/".urlencode(base64_encode(base64_encode(@$ord->ord_no)))); ?>" class="btn btn-info">
														<i class="fe fe-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i></a>

														<a href="<?php echo urlencode(base64_encode(base64_encode($ord->id))); ?>"  class="btn btn-danger delete-stocks">
														<i class="fe fe-trash" data-toggle="tooltip" title="" data-original-title="Delete"></i></a>

													</td>
												</tr>
												<?php  $sl++; }

											}?>
											</tbody>
										</table>
									</div>
								</div>
							</div>			
        						<!-- table-wrapper -->
		 </div>
		</div>
	</div>	
	<script>
	$(document).ready(function(){
			$("#add-datatable").dataTable({"serverSide":"true","lengthMenu":[10,10,100,500,1000,-1,50,100,500,1000,"All"],"ajax":{"url":"<?php echo base_url('order/loadorders'); ?>","type":"post"},"columnDefs":{"orderable":"false","target":0},"order":[1,"desc"]});
			
		});
	</script>
	<script>
		$(document).on("click", ".enq_form_filters",function(){
				
			var val = $(this).val();
			
			$(".border_bottom").removeClass("border_bottom_active");
			$(this).closest(".border_bottom").addClass("border_bottom_active");
			$('#add-datatable').dataTable().fnDestroy();
			 $("#add-datatable").dataTable({"serverSide":"true","lengthMenu":[10,10,100,500,1000,-1,50,100,500,1000,"All"],"ajax":{"url":"<?php echo base_url('order/loadorders'); ?>?action="+val,"type":"post"},"columnDefs":{"orderable":"false","target":0},"order":[1,"desc"]});
			 
		});
	</script>
	<script>
		$(document).on("click", ".slide-chng-div", function(){
			
			var trgt = $(this).data("target");
			$(trgt).slideToggle(trgt);
		});
	</script>