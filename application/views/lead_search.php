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
						<hr />
					</div>
					<div class="col-sm-12" align="center">

					
                     <table class="table table-striped table-bordered add-data-table" id="filtered_Data1" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
										<th>Sr no</th>
										<th>Enquiry Id</th>
										<th>Name</th>
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
                                                            $firstChar = mb_substr($ctitle, "UTF-8");
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
											<td><a href="<?php echo base_url($url); ?>"><?php echo $key + 1; ?></a></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->Enquery_id; ?></a></td>
											<td><a href="<?php echo base_url($url); ?>"><?php echo $rslt->name_prefix." ".$rslt->name." ".$rslt->lastname; ?></a></td>
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
</script>


