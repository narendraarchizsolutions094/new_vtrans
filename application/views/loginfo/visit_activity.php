              
    <?php if($i == 0){ ?>
<div class="row">
    <div class="col-sm-12">        
        <div class="panel-body">                        
            <table id="vistb2" class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day In</th>
                        <th>Day Out</th>
						<!--<th>Active Hours</th>-->						
						<th>Current Location</th>
						<th>Map</th>
                    </tr>
                </thead>
                <tbody>
                    <?php } ?>
                    <?php if (!empty($users_activity)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($users_activity as $user) {
                        $waypoints  = json_decode($user->l_end);
                        if(!empty($waypoints)){
                            $last_waypoint = end($waypoints);
                            $latitude=$last_waypoint[0]; 
                            $longitude=$last_waypoint[1];
                            $latlong = $latitude.','.$longitude;
                                // set your API key here
                            $api_key = "AIzaSyAaoGdhDoXMMBy1fC_HeEiT7GXPiCC0p1s";
                            $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlong.'&key='.$api_key; 
                            $file_contents = file_get_contents($request);
                            $json_decode = json_decode($file_contents);
                                if(isset($json_decode->results[0])) {
                                    $response = array();
                                    foreach($json_decode->results[0]->address_components as $addressComponet) {
                                        if(in_array('political', $addressComponet->types)) {
                                                $response[] = $addressComponet->long_name; 
                                        }
                                    }
                                    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
                                    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
                                    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
                                    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
                                    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }
                                $res = $first.', '.$second.', '.$third.', '.$fourth.', '.$fifth;
                                $location = $res;
                                }
                                else
                                    $location = ''; 
                        }else{
                            $location = 'NA';
                        }
						?>
                            <tr>
                                <td><?php echo $current_date;?></td>                                     
                        		<td><?php if(!empty($user->check_in)){ echo $user->check_in; }else{ echo $user->new_check_in; };?></td>                                     
                                <td><?php echo $user->check_out;?></td>
								<!--<td><?php// echo $user->total;?></td>-->								
								<td><?php echo $location;?></td>
								<td><a href="javascript:void(0)" onclick="get_modal_content(<?=$user->pk_i_admin_id?>,'<?=$current_date?>')" class="btn btn-sm btn-success"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td>
                            </tr>                                
                            <?php $sl++; ?>
                        <?php } ?> 
                    <?php } 
                    if($is_end == $current_date){
                    ?>                     
                </tbody>
            </table>  <!-- /.table-responsive -->
            
        </div>
    </div>
</div>
            <?php
                }
?>