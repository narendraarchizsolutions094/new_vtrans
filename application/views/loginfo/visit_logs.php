<div class="row">
    <!--  table area -->
    <div class="col-sm-12">        
        <div class="panel-body">
            <!--<h2><?=$att_date?></h2>-->
            <table id="vistbl" class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo display('serial') ?></th>
                        <th>Emp Id</th>
                        <th><?php echo ucfirst(display('disolay_name')) ?></th>
						<th>Designation/UR</th>
						<th>Region</th>
                        <!--<th>Visit Date</th>
                        <th>Visit Time</th>-->
						<th>Day In</th>
                        <th>Day Out</th>
						<th>Active Hours</th>
						<th>Today Leads</th>
						<th>Today Deals</th>
						<th>Today Visits</th>
						<th>Current Location</th>
						<th>Map</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($users as $user) {
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
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $user->employee_id; ?></td>
                                <td><?php echo $user->s_display_name;echo '&nbsp;';echo $user->last_name; ?></td>
								<td><?php echo $user->user_role;?></td>
								<td><?php echo $user->sale_region;?></td>
                                <!--<td><?php echo $user->visit_date;?></td>                                     
                                <td><?php echo $user->visit_time;?></td>-->
								<td><?php echo $user->check_in;?></td>                                     
                                <td><?php echo $user->check_out;?></td>
								<td><?php echo $user->total;?></td>
								<td><?php echo $user->t_enq;?></td>
								<td><?php echo $user->t_deal;?></td>
								<td><?php echo $user->t_vis;?></td>
								<td><?php echo $location;?></td>
								<td><a href="javascript:void(0)" onclick="get_modal_content(<?=$user->pk_i_admin_id?>)" class="btn btn-sm btn-success"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td>
								<!--<td><a href="<?php echo base_url('visits/visit_live/').$user->pk_i_admin_id; ?>" class="btn btn-sm btn-success"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td>-->
                            </tr>                                
                            <?php $sl++; ?>
                        <?php } ?> 
                    <?php } ?> 
                </tbody>
            </table>  <!-- /.table-responsive -->
        </div>
    </div>
</div>
<button type="button" id='modal_btn' data-toggle="modal" data-target="#myModal" style="visibility: hidden;"></button>
<script type="text/javascript">
// Basic example
$(document).ready(function () {
  $('#vistbl').DataTable({
    "paging": true // false to disable pagination (or any other option)
  });
  $('.dataTables_length').addClass('bs-select');
});

    $("#att_date").on('change',function(){
        var date_f = $(this).val();
        $.ajax({
            url: '<?=base_url()?>attendance/att_set_filters_session',
            type: 'post',
            data: {'date':date_f},
            success: function(responseData){
                location.reload();
            }
        });        
    });

</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width: 70%">
      <div class="modal-content" id="map_model_content">
        
      </div>
    </div>
</div>
 <script type="text/javascript">
      function get_modal_content(id){        
            $.ajax({
            url: "<?=base_url().'visits/visit_live/'?>"+id,
            type: 'post',
            dataType: 'json',
            data:{'id':id},
            success: function(responseData){
                if(responseData.status == true){
                    $("#map_model_content").html(responseData.data);
                    $("#modal_btn").click();
                }else{
                    $("#map_model_content").html('');                    
                    alert(responseData.msg);
                }
            }
        });
      }
     $(".chosen-select").chosen({
      no_results_text: "Oops, nothing found!"
    });
  </script>