<div class="row">
    <div class="col-sm-12" id="PrintMe">
        <div  class="panel panel-default thumbnail"> 
            <!-- <div class="panel-heading no-print">
                 <div class="btn-group">
                </div>
            </div> -->
            <div class="panel-body">  
                <div class="row">
					<div class="col-md-12">
						<h4><small>Filtered : </small><?php echo (!empty($filter)) ? $filter : "All"; ?> <small>Total Result :</small> <?php echo count($result); ?></h4>
                    <?php if (user_access(67)==true) { ?>                                
                        <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#AssignSelected"><?php echo display('assign_selected'); ?></a><br>
                    <?php 
                    } 
                    ?>
						<hr />
					</div>
					<div class="col-sm-12" >
                    <form id='enquery_assing_from'>
					
                     <table class="table table-striped table-bordered add-data-table" id="filtered_Data1" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>
                                        <input type='checkbox' class="checked_all1" value="check all" >
                                        </th>
										<th>Sr no</th>
										<th>Enquiry Id</th>
										<th>Name</th>
										<th>Client Name</th>
										<th>Company Group Name</th>
										<th>Type</th>
                                        <th>Mobile</th> 
                                        <th>Email</th> 
                                        <th>Address</th> 
                                        <th>Created By</th>
                                        <th>Assign To</th>
                                      </tr>
                                    </thead>
                                    <tbody>
								<?php   if(!empty($result)){
											foreach($result as $key => $rslt){
                                                $leadSataus=$rslt->status;
												if($rslt->status == 1){
												//Enquery
												$url  = "enquiry/view/".$rslt->enquiry_id;
												$type = '<a class="btn-sm btn-primary" href = "'.base_url($url).'">'.ucwords((display('enquiry'))).'</a>'; 
											}else if($rslt->status == 2){
												//LEad
												$url = "lead/lead_details/".$rslt->enquiry_id;
												$type = '<a class="btn-sm btn-warning"  href = "'.base_url($url).'">'.ucwords((display('lead'))).'</a>';
											}else if($rslt->status == 3){
												//Client
												$url = "client/view/".$rslt->enquiry_id;
												$type = '<a class="btn-sm btn-success"  href = "'.base_url($url).'">'.ucwords((display('client'))).'</a>';
											}else{
                                                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                                                if (!empty($enquiry_separation) and $leadSataus  >= 3) {
                                                $enquiry_separation = json_decode($enquiry_separation, true);
                                                if ($leadSataus != 3) {
                                                    foreach ($enquiry_separation as $key => $value) {
                                                        if ($leadSataus== $key) {
                                                            $ctitle = $enquiry_separation[$key]['title'];
                                                            if($ctitle!=''){
                                                            $firstChar = $ctitle;
                                                            $url = 'client/view/'.$rslt->enquiry_id.'?stage='.$key;
												            $type = '<a class="btn-sm btn-success"  href = "'.base_url($url).'">'.$firstChar.'</a>';
                                                            }else{
												            $type = '<a class="btn-sm btn-success">N/A</a>';
                                                            }
                                                        }
                                                    }
                                                }
                                            }else{
												$url ="#";

                                            }
											} ?>
										<tr>	
											<td><input type='checkbox' name='enquiry_id[]'' class='checkbox1' value="<?=$rslt->enquiry_id?>"></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $key + 1; ?></a></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->Enquery_id; ?></a></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->name_prefix." ".$rslt->name." ".$rslt->lastname; ?></a></td>
											<td><?php echo $rslt->client_name;  ?></td>
											<td><?php echo $rslt->company_name;  ?></td>
											<td><?php echo $type;  ?></td>
										
											<td>
                                                <a href="<?php echo base_url($url); ?>">
                                                    <?php 
                                                    if (user_access(450)) {
                                                        echo '##########'; 
                                                    }else{
                                                        echo $rslt->phone; 
                                                    }
                                                    ?>
                                                        
                                                    </a>
                                            </th>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->email; ?></a></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->address; ?></a></td>
											<td><?php echo $rslt->username; ?></td>
											<td><?php echo $rslt->asignuser; ?></td>
										</tr>		
								<?php		}
										
										}else{
											
										?><tr><td colspan = "9">NO RECORD FOUND</td></tr><?php	
										} ?>
                                     
                                    </tbody>
                                </table>

                                                                

                                <div id="AssignSelected" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><?=display('enquiry')?> Assignment</h4>
                                    </div>
                                    <div class="modal-body">
                                    
                                                <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="dept_name"><?=display('department')?></label> 									
                                                <select class="form-control" name="dept_name" id="dept_name" onchange="find_employee();">
                                                    <option value=''>---Select Department----</option>
                                                        <?php  if (!empty($dept_lists)) {
                                                        foreach ($dept_lists as $key => $value) { ?>
                                                    <option value="<?= $value->id;?>" <?php if($value->id == $this->session->dept_name){ echo "selected";} ?>><?= $value->dept_name;?></option>
                                                        <?php
                                                            }
                                                            } ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="sale_resions"><?=display('sales_resion')?></label> 									
                                                <select class="form-control" name="sale_region" onchange="find_area();find_employee();">
                                                    <option value="">---Select Region---</option>
                                                    <?php
                                                        if (!empty($region_lists)) {
                                                        foreach ($region_lists as $key => $value) { ?>
                                                    <option value="<?= $value->region_id;?>" <?php if($value->region_id == $this->session->sales_region){ echo "selected";} ?>><?= $value->name;?></option>
                                                    <?php
                                                        }
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="sale_area"><?=display('sales_area')?></label> 									
                                                <select class="form-control" name="sale_area" id="filtered_area" onchange="find_branch();find_employee();">
                                                <option value="">---Select Area---</option>
                                                    <?php  if (!empty($area_lists)) {
                                                    foreach ($area_lists as $key => $value) { ?>
                                                <option value="<?= $value->area_id;?>" <?php if($value->area_id == $this->session->sales_area){ echo "selected";} ?>><?= $value->area_name;?></option>
                                                    <?php
                                                    }
                                                    } ?>
                                                </select>
                                            </div>
                                                                
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="sale_branch"><?=display('sales_branch')?></label> 									
                                                <select class="form-control" name="sale_branch" id="filtered_branch" onchange="find_employee();">
                                                <option value="">---Select Branch---</option>
                                                    <?php  if (!empty($branch_lists)) {
                                                    foreach ($branch_lists as $key => $value) { ?>
                                                <option value="<?= $value->branch_id;?>" <?php if($value->branch_id == $this->session->branch_name){ echo "selected";} ?>><?= $value->branch_name;?></option>
                                                    <?php
                                                    }
                                                    } ?>
                                                </select>
                                            </div>			
                                            
                                            <div class="form-group col-md-12">  
                                            <label>Select Employee</label> 
                                            <div id="imgBack"></div>
                                            <select class="form-control"  name="assign_employee" id="assign_employee">                    
                                            <?php foreach ($created_bylist as $user) { 
                                                            
                                                        if (!empty($user->user_permissions)) {
                                                            $module=explode(',',$user->user_permissions);
                                                        }                           
                                                            
                                                            ?>
                                                            <option value="<?php echo $user->pk_i_admin_id; ?>">
                                                            <?=$user->s_display_name ?>&nbsp;<?=$user->last_name.' - '.$user->s_user_email; ?>                                
                                                            </option>
                                                            <?php 
                                                        //}
                                                        } ?>                                                      
                                            </select> 
                                            </div>
                                            
                                        <input type="hidden" value="" class="enquiry_id_input" >
                                        
                                            <div class="form-group col-sm-12">        
                                            <button class="btn btn-success" type="button" onclick="assign_enquiry();">Assign</button>        
                                            </div>
                                        
                                    
                                                    
                                                    
                                                </div>
                                                

                                        
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                </div>
                                </div>
                                </form>
                    </div> 



                    <div class="col-sm-8"> 


                    </div>

                </div>  



            </div> 



            <div class="panel-footer">

                <div class="text-center">

                </div>

            </div>

        </div>

    </div>

</div>



<script type="text/javascript">
    $('#button').on('click',function(){
        var textValue = $('#number').val();
        // alert(textValue);
        $.ajax({
            url:"<?php echo base_url('lead/get_number_details'); ?>",
            type:"POST",
            cache:false,
            data:{number:textValue},
            success:function(data)
            {
                // console.log(data);
                html ="";
                var obj = JSON.parse(data);
                for(i = 0 ; i < (obj.length) ; i++)
                {
                     html += '<tr><td>' + obj[i].name_prefix + ' ' + obj[i].name + ' ' +  obj[i].lastname + 
                    '</td><td>' + obj[i].phone + '</td>' +
                    '</td><td>' + obj[i].email + '</td>' +
                    '</td><td>' + obj[i].address + '</td>' +
                    '</td><td>' + obj[i].lead_name + '</td>' +
                    '</td><td>' + obj[i].member_name + ' ' + obj[i].lname + '</td>' +
                    '</td><td>' + obj[i].product_name + '</td>' +
                    '</td><td>' + obj[i].company + '</td>';
                    if(obj[i].assign_to_name == null)
                    {
                        html+='</td><td>N/A</td>';
                    }
                    else
                    {
                        html+='</td><td>' + obj[i].assign_to_name + ' ' + obj[i].assign_lname + '</td>';
                    }
                    html+= '</td><td>' + obj[i].created_date + '</td></tr>';
                }
                $('#filtered_Data1 tr').first().after(html);
            }
        })
    });
    $('.checked_all1').on('change', function() {     
    $('.checkbox1').prop('checked', $(this).prop("checked"));    
});
function assign_enquiry(){
  if($('.checkbox1:checked').size() > 1000){
    alert('You can not assign more than 1000 <?=display('enquiry')?> at once');
  }else{    
    var p_url = '<?php echo base_url();?>enquiry/assign_enquiry';       
    $.ajax({
        type: 'POST',
        url: p_url,
        data: $('#enquery_assing_from').serialize(),
        beforeSend: function(){
            $("#imgBack").html('uploading').show();
        },
        success:function(data){
            alert(data);         
            location.reload();
        }
    });
  }
}

</script>


