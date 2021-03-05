<style type="text/css" media="print">
   
    .noprint { 
      visibility: hidden; 
   }
   img:not(#Lalantop_logo){
   	visibility: hidden;
   }
   .content-wrapper{
   	min-height: unset!important;
   }
</style> 
		
<div class="row">
    <!--  form area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail"> 
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-primary" href="<?php echo base_url("order") ?>"> <i class="fa fa-list"></i> Order List </a>  
                </div>
            </div>
			<div class="panel-body panel-form">
                <div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6 col-sm-12 col-xs-12">
												<img id="Lalantop_logo" style="width: 26%;" src="https://v-trans.thecrm360.com/assets/images/Lalantop_logo.jpg" class="m-r-sm">
											</div>

											<div class="text-right ml-auto col-md-6">
												<h2 class="mb-1"> <?php echo $ord->ord_no; ?></h2>
												<p class="mb-1"><span class="font-weight-semibold">Order Date :</span> <?php echo date("d, F Y", strtotime($ord->order_date)); ?> </p>
											
											</div>
										</div>
										<hr>
								
										<div class="table-responsive push">
											<table class="table table-bordered table-hover mb-0">
												<tbody>
								
												<tr class=" ">
													<th class="text-center " style="width: 1%"></th>
													<th>Item</th>
													<th class="text-center" style="width: 1%">Quantity</th>
													<th class="text-center" style="width: 1%">Confirm Order</th>
													<th class="text-center" style="width: 1%">Pending Order</th>
													<th class="text-right">Unit Price</th>
													<!-- <th class="text-right">Others</th> -->
													<th class="text-right">GST</th>
													<th class="text-right">Discount</th>
													<th class="text-right">Sub Total</th>
												</tr>
											<?php $total =  0; 
												$mord = $ord;	
												if(!empty($orders)) { 
													foreach($orders as $ind => $ord){ ?>
														
													<tr>
														<td class="text-center"><?php echo $ind + 1; ?></td>
														<td><?php echo $ord->product_name; ?></td>
														<td class="text-center"><?php echo $qty = $ord->quantity; ?></td>
														<td class="text-center" style="width: 1%">
														<?php
																$cnt = ""; 
																$totconfrm = 0;

															$pending = $ord->quantity;
															$isanyconf = false;
															if(!empty($delivery[$ord->product])){
																$delvarr = $delivery[$ord->product];
																$isanyconf = true;
															?>																
															<?php
															foreach($delvarr as $ind => $dlv){
																	$totconfrm = $totconfrm + $dlv->delv_qty;
																	$cnt .= "<li><a href = '#'>".$dlv->delivery_date." : <span class = 'badge badge-warning'>".$dlv->delv_qty."</span></a></li>";
																}															
															$pending = $ord->quantity - $totconfrm;
															?>
																		<div class="btn-group">
																		<a href = "#" class="dropdown-toggle" data-toggle="dropdown">
																			<?php echo $totconfrm ?>
																		</a>
																		<ul class="dropdown-menu" role="menu">
																			<?php echo $cnt; ?>
																		</ul>
																	</div>
															
															
															<?php
															
															
															
															
															}else{
																?>-<?php
															}				
														?>
								
														
														</td>
														<td class="text-center" style="width: 1%"><?php echo $pending; ?></td>
														<td class="text-right"><i class ="fa fa-rupee"></i> <i ><?php echo $price = $ord->unit_price; ?></td>
														<!-- <td class="text-right"><?php echo $oprice = $ord->other_price; ?></td> -->
														<td class="text-right"><?php echo  (!empty($ord->tax)) ? $ord->tax."%" : ""; ?></td>
														<td class="text-right"><?php echo $ord->offer; ?></td>
														<td class="text-right"><i class ="fa fa-rupee"></i>
															<?php 
															if ($ord->tax) {
																$p = $qty * (float)$price;
																echo $ptotal = $p+($p*($ord->tax/100)  - (float)$ord->offer); 	
															}else{
																echo $ptotal = (($qty * (float)$price)  - (float)$ord->offer); 
															}
															?>
														</td>
													</tr>		
														
											<?php	 $total = $total  + $ptotal;
											
													}
												} ?>
								
												<tr>
													<td colspan="8" class="font-weight-bold text-uppercase text-right">Total</td>
													<td class="font-weight-bold text-right h4"> <i class ="fa fa-rupee"></i>  <?php echo $total; ?></td>
												</tr>
											</tbody></table>
											
										</div>
										<div>
									<?php if(!empty($payment)) { 
											$data["payments"] = $payment;
											$data["totprice"] = $mord->total_price;
											$data["hideact"]      = true;
											$data["orderno"]   = $ord->ord_no;
											$this->load->view("payment/pages/payment-list",$data);
									}else{ ?>
									<?php if($isanyconf == true) { ?>
												<div class = "card">
										
											<div class="card-body">
												<div class = "col-md-12 text-center">
												
													<h4>	Balance :  <i class = "fa fa-rupee"></i> <?php echo $mord->total_price; ?> <a class = "btn btn-info btn-pill" href = "<?php echo base_url("payment/add/".$mord->ord_no); ?>"> Add Payment </a> </h4>
												</div>
													</div>
									</div>	
									<?php } ?>	
							<?php	}  ?>
										</div>
									</div>
									<div class="card-footer text-right">
										<!-- <a href = "<?php echo base_url("order/pdfinvoice/".$mord->ord_no); ?>" class="btn btn-primary mb-1" target = "_blank"><i class="si si-wallet"></i> Pdf</a> -->
									
										<button type="button" class="btn btn-info mb-1 noprint" onclick="javascript:window.print();"><i class="si si-printer"></i> Print Invoice</button> 
									</div>
								</div>
							</div>
						</div>	<!-- end row -->
					</div>	
				</div>
			</div>
		</div>