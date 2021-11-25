<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/query-builder.default.min.css">
<link href="<?=base_url()?>assets/css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/select2.min.js"></script>
<div>	
   	<div class="row">  
	   	<div class="col-sm-12">
	      	<div  class="panel panel-default thumbnail">
		        <div class="panel-heading no-print">
		            <div class="btn-group"> 
		               <a class="btn btn-primary" href="<?php echo base_url("leadRules") ?>"> <i class="fa fa-list"></i> <?php echo display('leadrules') ?> </a>  
		            </div> 
		        </div>
		        <div class="panel-body">
		        	<div class="row">					   		   	
					   	<div class="col-sm-3">					   	
					   		<label>Rule For<i style="color: red;">*</i></label>
					   		<select class="form-control" id="rule" onchange="builder_fun(this.value)">
					   			<option value="">Choose Rule</option>
					   			<option value="1" <?=(!empty($rule_data['type']) && $rule_data['type']==1)?'selected':''?> >Lead Score</option>
					   			<option value="2" <?=(!empty($rule_data['type']) && $rule_data['type']==2)?'selected':''?>>Lead Assignment</option>
					   			<option value="3" <?=(!empty($rule_data['type']) && $rule_data['type']==3)?'selected':''?>>Mail Send</option>
					   			<option value="4" <?=(!empty($rule_data['type']) && $rule_data['type']==4)?'selected':''?>>Auto Followup</option>
					   			<option value="5" <?=(!empty($rule_data['type']) && $rule_data['type']==5)?'selected':''?>>Support Ticket Escalation Rule</option>
					   			<option value="6" <?=(!empty($rule_data['type']) && $rule_data['type']==6)?'selected':''?>>Send SMS </option>
					   			<option value="7" <?=(!empty($rule_data['type']) && $rule_data['type']==7)?'selected':''?>>Send WhatsApp</option>
					   			<option value="8" <?=(!empty($rule_data['type']) && $rule_data['type']==8)?'selected':''?>>Auto Ticket Priority</option>
					   			<option value="9" <?=(!empty($rule_data['type']) && $rule_data['type']==9)?'selected':''?>>Default Ticket Disposition</option>
								
								<option value="10" <?=(!empty($rule_data['type']) && $rule_data['type']==10)?'selected':''?>>Ticket move to sales</option>
								
								   
								 <option value="11" <?=(!empty($rule_data['type']) && $rule_data['type']==11)?'selected':''?>>Lead Aging Notifications</option>

					   		</select>
					   	</div>
					   	<div class="col-sm-3">
					   		<label>Rule Title <i style="color: red;">*</i></label>
					   		<input type="text" name="title" id="ruletitle" class="form-control" required value="<?=!empty($rule_data['title'])?$rule_data['title']:''?>">
					   	</div>
					   	<div class="col-sm-3">
					   		<label>Rule Status <i style="color: red;">*</i></label><br>
					   		<input type="checkbox" name="rule_status" value='1' class="switch" <?=(!empty($rule_data['status']) && $rule_data['status']==1)?'checked':''?>>
					   	</div>
				   </div>
				   <br>
				   <br>
				   <div class="row">   
					   	<div class="col-sm-12">
			           <div id="builder"></div>
				   		<br>
			           	<div class="row text-center action-section" id="score_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Lead Score<i style="color: red;">*</i></label>
			        			<input type="text" name="action" class="form-control text-center" value="<?=!empty($rule_data['rule_action'])?$rule_data['rule_action']:''?>">
		        			</div>
		        		</div>
		        		<div class="row text-center action-section" id="assignment_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Assign To<i style="color: red;">*</i></label>
			        			<select class="form-control text-center multiple-select" name="assignment_action" multiple>			    
			        				<?php
			        				$assignment_action_data = array();
			        				if(!empty($rule_data['rule_action']))
			        					$assignment_action_data = explode(',', $rule_data['rule_action']);

			        				if (!empty($user_list)) {
			        					foreach ($user_list as $key => $value) {
			        						?>
			        						<option value="<?=$value->pk_i_admin_id?>" <?=(	!empty($rule_data['rule_action']) && in_array($value->pk_i_admin_id, $assignment_action_data) )?'selected':''?>>
			        							<?=$value->s_user_email?>
			        						</option>
			        						<?php
			        					}
			        				}
			        				?>    			
			        			</select>
		        			</div>
		        		</div>
		        		<div class="row text-center action-section" id="email_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Select Template<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="action" id="email_template">			    
			        			</select>
		        			</div>
		        		</div>
						<div class="row text-center action-section" id="priority_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Select Priority<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="action" id="default_priority">
			        			<option value="1" <?=@$rule_data['rule_action']=='1'?'selected':''?>>Low</option>
			        			<option value="2"  <?=@$rule_data['rule_action']=='2'?'selected':''?>>Medium</option>
			        			<option value="3"  <?=@$rule_data['rule_action']=='3'?'selected':''?>>High</option>			    
			        			</select>
		        			</div>
		        		</div>
						
		        		<div class="row text-center action-section" id="sms_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Select Template<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="action" id="sms_template">			    
			        			</select>
		        			</div>
		        		</div>
		        		<div class="row text-center action-section" id="whatsapp_action" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Select Template<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="action" id="whatsapp_template">			    
			        			</select>
		        			</div>
		        		</div>
		        		
		        		<div class="row text-center action-section" id="auto_followup_section" style="display: none;">   
		        			<h3>Action</h3>
		        			<div class="col-md-4"></div>
		        			<div class="col-md-4">		        				
			        			<label>Auto Followup after<i style="color: red;">*</i></label>
			        			<input class="form-control text-center" name="action" id="auto_followup" type="number" min="1" max="8760" value="<?=!empty($rule_data['rule_action'])?$rule_data['rule_action']:''?>">
		        			</div>
		        		</div>
		        		<?php
		        		$esc_within = '';
		        		$assign_to = '';
		        		if (!empty($rule_data['rule_action']) && $rule_data['type'] == 5) {
		        			$act = json_decode($rule_data['rule_action'],true);		        			
		        			$esc_within = $act['esc_hr'];
		        			$assign_to  = $act['assign_to'];
		        		}
		        		?>
		        		<div id="ticket_esc_action" class="action-section text-center row">
		        				<h3>Action</h3>
		        				<div class="col-md-2"></div>
		        			<div class="col-md-4">
			        			<label>Escalate Within (Hours)<i style="color: red;">*</i></label>		        		
		        				<input type="number" name="esc_within" class="form-control text-center" value="<?=!empty($rule_data['rule_action'])?$esc_within:''?>">
		        			</div>
		        			<div class="col-md-4">		        				
			        			<label>Escalate To<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="esc_to">			    
			        				<?php
			        				if (!empty($user_list)) {
			        					foreach ($user_list as $key => $value) {
			        						?>
			        						<option value="<?=$value->pk_i_admin_id?>" <?=(	!empty($rule_data['rule_action']) && $assign_to==$value->pk_i_admin_id)?'selected':''?>>
			        							<?=$value->s_user_email?>
			        						</option>
			        						<?php
			        					}
			        				}
			        				?>    			
			        			</select>
		        			</div>
		        		</div>		 
		        		<div id="disposition_action" class="action-section text-center row">
		        				<h3>Action</h3>
		        			<div class="col-md-1"></div>
		        			<div class="col-md-3">
			        			<label>Stage<i style="color: red;">*</i></label>		        		
		        				<select class="form-control" name="stage"></select>

		        			</div>
		        			<div class="col-md-3">		        				
			        			<label>Sub Stage<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="sub_stage"></select>
		        			</div>
							<div class="col-md-3">		        				
			        			<label>Ticket Status<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="ticket_status"></select>
		        			</div>
						</div>
						<div id="disposition_actionOnly" class="action-section text-center row">
		        				<h3>Action</h3>
		        			<div class="col-md-3">
			        			<label>Select Status<i style="color: red;">*</i></label>		        		
		        				<select class="form-control" name="stage_only">
									<?php foreach (json_decode($rule_enquiry_status) as $key => $rule_enquiry) {
										$assignment_action_data = explode(',', $rule_data['rule_action']);
										$stdata='{"stage":"'.$key.'"';
										
										?>
										<option value="<?= $key?>" <?=(	!empty($rule_data['rule_action']) && in_array($stdata, $assignment_action_data) )?'selected':''?>><?= $rule_enquiry ?></option>
										<?php  	} ?>
								</select>

							</div>
							<div class="col-md-3">
			        			<label>Select Source<i style="color: red;">*</i></label>		        		
		        				<select class="form-control" name="defaultsource">
									<?php
			        					$assignment_action_data = explode(',', $rule_data['rule_action']);
									foreach (json_decode($lead_source) as $key => $lead_sources) {
										$srdata='"source":"'.$key.'"}'; ?>

										<option value="<?= $key?>" <?=(	!empty($rule_data['rule_action']) && in_array($srdata, $assignment_action_data) )?'selected':''?>><?= $lead_sources ?></option>
										<?php  	} ?>
								</select>

		        			</div>
							<div class="col-md-3">		        				
			        			<label>Assign To<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="assignto">			    
			        				<?php
									$assignment_action_data = array();
								
			        				if(!empty($rule_data['rule_action']))
			        					$assignment_action_data = explode(',', $rule_data['rule_action']);

			        				if (!empty($user_list)) {
			        					foreach ($user_list as $key => $value) {
											$sdata='"assignto":"'.$value->pk_i_admin_id.'"';
			        						?>
			        						<option value="<?=$value->pk_i_admin_id?>" <?=(	!empty($rule_data['rule_action']) && in_array($sdata, $assignment_action_data) )?'selected':''?>>
			        							<?=$value->s_user_email?>
			        						</option>
			        						<?php
			        					}
			        				}
			        				?>    			
			        			</select>
							</div>
							<div class="col-md-3">		        				
			        			<label>Default Process<i style="color: red;">*</i></label>
			        			<select class="form-control text-center" name="defaultProcess">			    
								<?php 
								if(!empty($rule_data['rule_action'])){
								$assignment_action_data = explode(',', $rule_data['rule_action']);
								}
								foreach (json_decode($rule_process) as $key => $value) {
									 $pdata='"defaultProcess":"'.$key.'"';
									
									?>
									 <option value="<?= $key ?>" <?php if(!empty($rule_data['rule_action']) AND in_array($pdata, $assignment_action_data) ){ echo'selected'; } ?>><?= $value ?></option>
								<?php } ?>		
			        			</select>
							</div>
						</div>
						<div id="aginig_notification" class="action-section text-center row">
								<div class="col-md-5">
								</div>
								<div class="col-md-2">
									 <input type="time" name="aging_exec_time" class="form-control" value='<?=!empty($rule_data["rule_action"])?$rule_data["rule_action"]:""?>'>
								</div>
						</div>	
						<br>
							        		
					   <button class="btn btn-success" id="btn-set"><?=!empty($id)?'Update Rule':'Set Rules'?></button>		
					   <button class="btn btn-warning" id="btn-reset">Reset</button>
					</div>
		        </div>
	     	</div>
	 	</div>
 	</div>
 </div>
	
<script type="text/javascript">

var leadSource = <?=$lead_source?>;
var subSource = <?=$sub_source?>;
var Products = <?=$products?>;
var Country  =  <?=$country?>;
var State = <?=$state?>;
var City  = <?=$city?>;
var LeadStage = <?=$lead_stages?>;
var SubDisposition = <?=$lead_description?>;
var Process = <?=$rule_process?>;
var EnquiryStage = <?=$rule_enquiry_status?>;
var TicketStatus = <?=$rule_ticket_status?>;
var TicketDisposition = <?=$ticket_stages?>;
var TicketSubDisposition  = <?=$ticket_description?>;
var probability = <?=$rule_lead_score?>;
var salesbranch = <?=$sale_branch?>;

// function manageValues(key)
// {

// 	if((['1','2','4',]).includes(key))
// 	{
// 		var ruleType = 'sales';

// 		$.ajax({
// 			url:"<?=base_url('message/all_stages_json')?>",
// 			type:'post',
// 			data:{for:[1,2,3]},
// 			dataType:'JSON',
// 			success:function(res)
// 			{	//alert(res);
// 				LeadStage = res;
// 				//alert(JSON.stringify(TicketDisposition));
// 				builder_fun(key);
// 			}
// 		});
// 	}
// 	else if((['3','6','7']).includes(key))
// 	{
// 		var ruleType  = 'both';

// 		$.ajax({
// 			url:"<?=base_url('message/all_stages_json')?>",
// 			type:'post',
// 			data:{for:[1,2,3,4]},
// 			success:function(res)
// 			{	//alert(res);
// 				TicketDisposition = JSON.parse(res);
// 				alert(JSON.stringify(TicketDisposition));
// 				builder_fun(key);
// 			}
// 		});

// 	}
// 	else if((['5','8','9','10']).includes(key))
// 	{
// 		var ruleType = 'support';

// 		$.ajax({
// 			url:"<?=base_url('message/all_stages_json')?>",
// 			type:'post',
// 			data:{for:[4]},
// 			success:function(res)
// 			{	//alert(res);
// 				TicketDisposition = JSON.parse(res);
// 				alert(JSON.stringify(TicketDisposition));
// 				builder_fun(key);
// 			}
// 		});
// 	}
// }


var rules_basic = <?=!empty($rule_data['rule_json'])?$rule_data['rule_json']:"''"?>;

function builder_fun(rule_type)
				{	//alert(rule_type);
					var filterArray = new Array();

				if((['1','2','3','4','6','7','11']).includes(rule_type))
				{
					if(rules_basic=='')
					{
						rules_basic = {				  
								    condition: 'OR',
								    rules: [{
								      id: 'country_id'		      
								    }]	  
								}
					}

					filterArray.push({
						    id: 'enquiry_source',
						    label: 'Lead Source',
						    type: 'integer',
						    input: 'select',
						    values: leadSource,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'sub_source',
						    label: 'Lead Sub Source',
						    type: 'integer',
						    input: 'select',
						    values: subSource,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
			
					filterArray.push({
						    id: 'enquiry_subsource',
						    label: 'Product',
						    type: 'integer',
						    input: 'select',
						    values: Products,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'state_id',
						    label: 'State',
						    type: 'integer',
						    input: 'select',
						    values: State,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'city_id',
						    label: 'City',
						    type: 'integer',
						    input: 'select',
						    values: City,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'lead_stage',
						    label: 'Disposition',
						    type: 'integer',
						    input: 'select',
						    values: LeadStage,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'lead_discription',
						    label: 'Sub Disposition',
						    type: 'integer',
						    input: 'select',
						    values: SubDisposition,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'product_id',
						    label: 'Sales Process',
						    type: 'integer',
						    input: 'select',
						    values: Process,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
						  
					filterArray.push({
						    id: 'enquiry.status',
						    label: 'Enquiry Stage',
						    type: 'integer',
						    input: 'select',
						    values: EnquiryStage,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					

					filterArray.push({
						    id: 'country_id',
						    label: 'Country',
						    type: 'integer',
						    input: 'select',
						    values: Country,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });

					filterArray.push({
						id: 'lead_score',
						label: 'Sales Conversion Probability',
						type: 'integer',
						input: 'select',
						values: probability,
						operators: ['equal', 'not_equal','is_null', 'is_not_null']
					});
					var days = {
							'date(NOW() - INTERVAL 1 DAY)':'1 day',
							'date(NOW() - INTERVAL 2 DAY)':'2 days',
							'date(NOW() - INTERVAL 3 DAY)':'3 days',
							'date(NOW() - INTERVAL 4 DAY)':'4 days',
							'date(NOW() - INTERVAL 5 DAY)':'5 days',
							'date(NOW() - INTERVAL 6 DAY)':'6 days',
							'date(NOW() - INTERVAL 7 DAY)':'7 days',
							'date(NOW() - INTERVAL 8 DAY)':'8 days',
							'date(NOW() - INTERVAL 9 DAY)':'9 days',
							'date(NOW() - INTERVAL 10 DAY)':'10 days',
							'date(NOW() - INTERVAL 11 DAY)':'11 days',
							'date(NOW() - INTERVAL 12 DAY)':'12 days',
							'date(NOW() - INTERVAL 13 DAY)':'13 days',
							'date(NOW() - INTERVAL 14 DAY)':'14 days',
							'date(NOW() - INTERVAL 15 DAY)':'15 days',
							'date(NOW() - INTERVAL 16 DAY)':'16 days',
							'date(NOW() - INTERVAL 17 DAY)':'17 days',
							'date(NOW() - INTERVAL 18 DAY)':'18 days',
							'date(NOW() - INTERVAL 19 DAY)':'19 days',
							'date(NOW() - INTERVAL 20 DAY)':'20 days',
							'date(NOW() - INTERVAL 21 DAY)':'21 days',
							'date(NOW() - INTERVAL 22 DAY)':'22 days',
							'date(NOW() - INTERVAL 23 DAY)':'23 days',
							'date(NOW() - INTERVAL 24 DAY)':'24 days',
							'date(NOW() - INTERVAL 25 DAY)':'25 days',
							'date(NOW() - INTERVAL 26 DAY)':'26 days',
							'date(NOW() - INTERVAL 27 DAY)':'27 days',
							'date(NOW() - INTERVAL 28 DAY)':'28 days',
							'date(NOW() - INTERVAL 29 DAY)':'29 days',
							'date(NOW() - INTERVAL 30 DAY)':'30 days',
							'date(NOW() - INTERVAL 31 DAY)':'31 days',
							'date(NOW() - INTERVAL 32 DAY)':'32 days',
							'date(NOW() - INTERVAL 33 DAY)':'33 days',
							'date(NOW() - INTERVAL 34 DAY)':'34 days',
							'date(NOW() - INTERVAL 35 DAY)':'35 days',
							'date(NOW() - INTERVAL 36 DAY)':'36 days',
							'date(NOW() - INTERVAL 37 DAY)':'37 days',
							'date(NOW() - INTERVAL 38 DAY)':'38 days',
							'date(NOW() - INTERVAL 39 DAY)':'39 days',
							'date(NOW() - INTERVAL 40 DAY)':'40 days',
							'date(NOW() - INTERVAL 41 DAY)':'41 days',
							'date(NOW() - INTERVAL 42 DAY)':'42 days',
							'date(NOW() - INTERVAL 43 DAY)':'43 days',							
							'date(NOW() - INTERVAL 45 DAY)':'45 days',
							'date(NOW() - INTERVAL 46 DAY)':'46 days',
							'date(NOW() - INTERVAL 47 DAY)':'47 days',
							'date(NOW() - INTERVAL 48 DAY)':'48 days',
							'date(NOW() - INTERVAL 49 DAY)':'49 days',
							'date(NOW() - INTERVAL 50 DAY)':'50 days',
							'date(NOW() - INTERVAL 51 DAY)':'51 days',
							'date(NOW() - INTERVAL 52 DAY)':'52 days',
							'date(NOW() - INTERVAL 43 DAY)':'43 days',
							'date(NOW() - INTERVAL 54 DAY)':'54 days',
							'date(NOW() - INTERVAL 55 DAY)':'55 days',
							'date(NOW() - INTERVAL 56 DAY)':'56 days',
							'date(NOW() - INTERVAL 57 DAY)':'57 days',
							'date(NOW() - INTERVAL 58 DAY)':'58 days',
							'date(NOW() - INTERVAL 59 DAY)':'59 days',
							'date(NOW() - INTERVAL 60 DAY)':'60 days'
							};
					filterArray.push({
						id: 'date(enquiry.created_date)',
						label: 'Lead Total Age',
						type: 'integer',
						input: 'select',
						values: days,
						operators: ['equal', 'not_equal','less','greater','is_null', 'is_not_null']
					});
					
					filterArray.push({
						id: 'enquiry.update_date',
						label: 'Lead Last Activity Age',
						type: 'integer',
						input: 'select',
						values: days,
						operators: ['equal', 'not_equal','less','greater','is_null', 'is_not_null']
					});
					
					filterArray.push({
						    id: 'sales_branch',
						    label: 'Sales Branch',
						    type: 'integer',
						    input: 'select',
						    values: salesbranch,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
				}

					if(['3','5','6','7','8','9','10'].includes(rule_type))
					{
						if(rules_basic=='')
						{
							rules_basic = {				  
									    condition: 'OR',
									    rules: [{
									      id: 'tbl_ticket.ticket_status'		      
									    }]	  
									}
						}
					filterArray.push({
						    id: 'tbl_ticket.ticket_status',
						    label: 'Ticket Status',
						    type: 'integer',
						    input: 'select',
						    values: TicketStatus,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'tbl_ticket.complaint_type',
						    label: 'Ticket Type',
						    type: 'integer',
						    input: 'select',
						    values: {"1":"Complaint","2":"Query"},
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'ticket_stage',
						    label: 'Ticket Disposition',
						    type: 'integer',
						    input: 'select',
						    values: TicketDisposition,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					filterArray.push({
						    id: 'ticket_substage',
						    label: 'Ticket Sub Disposition',
						    type: 'integer',
						    input: 'select',
						    values: TicketSubDisposition,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
						  filterArray.push({
						    id: 'process_id',
						    label: 'Process',
						    type: 'integer',
						    input: 'select',
						    values: Process,
						    operators: ['equal', 'not_equal','is_null', 'is_not_null']
						  });
					}
					try{

					 	$('#builder').queryBuilder({
						  plugins: ['bt-tooltip-errors'],		  
						  filters: filterArray,
						  rules: rules_basic
						});
					}catch(e){alert(e);}finally{
						//alert(JSON.stringify(TicketDisposition));
					}
				}

	$(document).ready(function(){
		// $rule_data['rule_json']
		var run = $("#rule").val();
		//alert(run);
		if(run!='')
			builder_fun(run);

				$("#rule").change(function(){

					var rule_key = $(this).val();

					$("#builder").queryBuilder('destroy');
					rules_basic='';

					//manageValues(rule_key);

					builder_fun(rule_key);
				});
	    /*********	Triggers and Changers QueryBuilder   ********************/		

		$('#btn-reset').on('click', function() {
		  $('#builder').queryBuilder('reset');
		}); 
		$('#btn-set').on('click', function() {		  
		  var result = $('#builder').queryBuilder('getRules');
		  if (!$.isEmptyObject(result)) {
		  	var rules = result;
		  	var rule_type	=	$("#rule").val();
		  	var title		=	$("#ruletitle").val();
		  	var rule_status	=	$("input[name='rule_status']:checked").val();
		  	if (rule_type==1) {
		  		var action_value	=	$("input[name='action']").val();
		  	}else if (rule_type==2){
		  		var action_value	=	$("select[name='assignment_action']").val();
		  		action_value	=	action_value.toString();
		  	}else if (rule_type==3){
		  		var action_value	=	$("#email_template").val();
		  	}else if (rule_type==4){
		  		var action_value	=	$("#auto_followup").val();
		  	}else if (rule_type==5){
		  		var esc_hr		=	$("input[name='esc_within']").val();
		  		var assign_to	=	$("select[name='esc_to']").val();
		  		action_value =	JSON.stringify({'esc_hr':esc_hr,'assign_to':assign_to});		  	
		  	}else if (rule_type==6){
		  		var action_value	=	$("#sms_template").val();
		  	}else if (rule_type==7){
		  		var action_value	=	$("#whatsapp_template").val();
		  	}else if (rule_type==8){
		  		var action_value	=	$("#default_priority").val();
		  	}else if (rule_type==9){
		  		var stage		=	$("select[name='stage']").val();
		  		var sub_stage	=	$("select[name='sub_stage']").val();
		  		var ticket_status =	$("select[name='ticket_status']").val();
		  		var	action_value =	JSON.stringify({'stage':stage,'sub_stage':sub_stage,'ticket_status':ticket_status});
		  	}else if (rule_type==10){
		  		var assignto		=	$("select[name='assignto']").val();
				  var stage_only	=	$("select[name='stage_only']").val();
				  var defaultsource	=	$("select[name='defaultsource']").val();
				  var defaultProcess	=	$("select[name='defaultProcess']").val();
				  
		  		var	action_value =	JSON.stringify({'stage':stage_only,'assignto':assignto,'defaultProcess':defaultProcess,'source':defaultsource});
		  	}else if (rule_type==11){
		  		var	action_value =	$("input[name='aging_exec_time']").val();
		  	}
		  	
		  	if (action_value && title) {
		  		$.ajax({
				      type: 'POST',
				      url: "<?=base_url().'leadRules/save_rule/'.$id?>",
				      data: {
				      	type:rule_type,
				      	rule_json:result,
				      	rule_action:action_value,
				      	rule_title:title,
				      	rule_status:rule_status
				      },				      
				      success: function(resultData) { 
				      	resultData	=	JSON.parse(resultData);
				      	if (resultData.status) {
				      		Swal.fire(
							  'Good job!',
							  resultData.msg,
							  'success'
							);
							window.location = "<?=base_url().'leadRules'?>";
				      	}else{
				      		Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  html: resultData.msg							  
							});
				      	}
				      }
				});
		  	}else{
		  		Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  html: 'Rule Action is required!'							  
				});
		  	}
		  }
		});
		action_show();
		$("#rule").on('change',function(){
			action_show();
		});
		function action_show(){
			var rule	=	$("#rule").val();
			$(".action-section").hide(1000);			
			if (rule == 1) {			
				$("#score_action").show(1000);
			}else if (rule == 2) {				
				$("#assignment_action").show(1000);
			}else if (rule == 3) {				
				$("#email_action").show(1000);
			}else if (rule == 4) {				
				$("#auto_followup_section").show(1000);
			}else if (rule == 5) {				
				$("#ticket_esc_action").show(1000);
			}else if (rule == 6) {				
				$("#sms_action").show(1000);
			}else if (rule == 7) {				
				$("#whatsapp_action").show(1000);
			}else if (rule == 8) {				
				$("#priority_action").show(1000);
			}else if (rule == 9) {				
				$("#disposition_action").show(1000);
				<?php
				 if(empty($rule_data['rule_action']) or $rule_data['type']!='9')
				 {
					echo'$("select[name=stage]").load("'.base_url().'message/all_stages/4");';
					echo'$("select[name=ticket_status]").load("'.base_url().'ticket/ticket_status");';
				}
				?>
			}else if (rule == 10) {				
				$("#disposition_actionOnly").show(1000);
				
			}else if (rule == 11) {				
				$("#aginig_notification").show(1000);
				
			}
		}
		$("#email_template").load("<?=base_url().'message/get_templates_without_process/3'?>");
		$("#sms_template").load("<?=base_url().'message/get_templates_without_process/2'?>");
		$("#whatsapp_template").load("<?=base_url().'message/get_templates_without_process/1'?>");
	});
	$(document).ajaxComplete(function() {
		if ("<?=!empty($rule_data['type'])?>") {
	  		$("#email_template").val('<?=!empty($rule_data["rule_action"])?$rule_data["rule_action"]:""?>');
	  		$("#sms_template").val('<?=!empty($rule_data["rule_action"])?$rule_data["rule_action"]:""?>');
	  		$("#whatsapp_template").val('<?=!empty($rule_data["rule_action"])?$rule_data["rule_action"]:""?>');
		}
	});
	$(".multiple-select").select2();

	$("select[name=stage]").change(function(){ 
		$("select[name=sub_stage]").load("<?=base_url('message/find_substage/')?>"+this.value);
	});

	<?php
	if( !empty($rule_data['rule_action']) && $rule_data['type']==9)
	{
		$res = json_decode($rule_data['rule_action']);

		echo'$("select[name=stage]").load("'.base_url('message/all_stages/4/').$res->stage.'");';
		echo'$("select[name=sub_stage]").load("'.base_url('message/find_substage/').$res->stage.'/'.$res->sub_stage.'");';
		echo'$("select[name=ticket_status]").load("'.base_url().'ticket/ticket_status/'.$res->ticket_status.'");';

	}

	if( !empty($rule_data['rule_action']) && $rule_data['type']==10)
	{
		$res = json_decode($rule_data['rule_action']);
		// echo'$("select[name=stage_only]").load("'.base_url('message/all_stages/4/').$res->stage.'");';
	}
	?>

</script>
<script type="text/javascript" src="<?=base_url()?>assets/js/query-builder.standalone.min.js"></script>