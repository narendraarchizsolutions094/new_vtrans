<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
  <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
          <?php
          if(user_access('1020'))
          {
          ?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a> 
          <?php
          }
          ?>   
        </div>
</div>

<br>
<script type="text/javascript">
$("select").select2();
</script>
<!-- ///area// -->
<div class="row">
    <div class="col-md-12">
    <div class="form-group col-md-3">
                <label>Travel Start Time</label>
                   <input value="<?= $details->visit_start ?>" disabled class="form-control">
                </div>
                <div class="form-group col-md-3">
                <label>Travel End Time</label>
                <input value="<?= $details->visit_end ?>" disabled class="form-control">
               </div>
               <div class="form-group col-md-3">
                <label>Meeting Start Time</label>
                <input value="<?= $details->start_time ?>" disabled class="form-control">
               </div>
               <div class="form-group col-md-3">
                <label>Meeting End Time</label>
                <input value="<?= $details->end_time ?>" disabled class="form-control">
               </div>
        
    </div>

</div>
<div class="row">
    waypoints
    <?php $waypoints=json_decode($details->way_points);
            unset($waypoints[2]);
            echo $lastKey = key(array_slice($waypoints, -1, 1, true));
           
            // print_r(json_encode($waypoints));

            function twopoints_on_earth($latitudeFrom, $longitudeFrom,$latitudeTo,$longitudeTo) 
            { 
            $long1 = deg2rad($longitudeFrom); 
            $long2 = deg2rad($longitudeTo); 
            $lat1 = deg2rad($latitudeFrom); 
            $lat2 = deg2rad($latitudeTo); 

            //Haversine Formula 
            $dlong = $long2 - $long1; 
            $dlati = $lat2 - $lat1; 

            $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2); 

            $res = 2 * asin(sqrt($val)); 

            $radius = 3958.756; 

            return ($res*$radius); 
            } 

            // latitude and longitude of Two Points 
            $latitudeFrom = 19.017656 ; 
            $longitudeFrom = 72.856178; 
            $latitudeTo = 40.7127; 
            $longitudeTo = -74.0059; 
            // Distance between Mumbai and New York 
            print_r(twopoints_on_earth( $latitudeFrom, $longitudeFrom,  
            $latitudeTo,  $longitudeTo).' '.'miles'); 


    ?>


</div>
<div id="map" style="width: 100%; height: 800px;"></div>

<script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsRenderer = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: {lat: 41.85, lng: -87.65}
        });
        directionsRenderer.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsRenderer);
        /*document.getElementById('submit').addEventListener('click', function() {
        });*/
      }
      var res = JSON.parse("<?=json_encode($waypoints)?>");

      this.origin = {
      	lat: Number(res[0][0]),
      	lng: Number(res[0][1])
      };     

	  this.destination = {
	  	lat: Number(res[res.length-1][0]),
		lng: Number(res[res.length-1][1])
	  };
      function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        var waypts = [];        
        var checkboxArray = JSON.parse("<?=json_encode($waypoints)?>");
        for (var i = 0; i < checkboxArray.length; i++) {        	        	
        	const waypointObject = new google.maps.LatLng(checkboxArray[i][0],checkboxArray[i][1]);
        	
        	/*console.log(checkboxArray[i][0]);
        	console.log(checkboxArray[i][1]);*/

            waypts.push({
              location: waypointObject,
              stopover: true
            });
        }

        //console.log(waypts);

        directionsService.route({
          origin: this.origin,
          destination: this.destination,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsRenderer.setDirections(response);
            var route = response.routes[0];
            /*var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';*/
            // For each route, display summary information.
            /*for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }*/
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDErpGSCUmRXuGkha8aTesCGpWhhQu_xYM&callback=initMap"
  type="text/javascript"></script>
<div id="Save_Visit" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Visits</h4>
         </div>
         <div class="modal-body">
            <div class="row" >

<form action="<?=base_url('enquiry/add_visit')?>" class="form-inner" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

                <div class="form-group col-md-6">
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
                  <label>Related To (Primary Contact)</label>
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

                <div class="form-group col-md-6 visit-date col-md-6">     
          <label>Visit Date</label>
          <input type="date" name="visit_date" class="form-control">
        </div>
    
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" class="form-control">
        </div>
    
        <div class="form-group col-md-6 distance-travelled col-md-6">     
        <label>Distance Travelled</label>
           <input type="text" name="travelled" class="form-control">
        </div>
    
        <div class="form-group col-md-6 distance-travelled-type col-md-6">      
        <label>DISTANCE TRAVELLED TYPE</label>
           <input type="text" name="travelled_type" class="form-control">
        </div>
    
        <div class="form-group col-md-6 customer-rating col-md-6">      
        <label>Customer Rating</label>
          <select class="form-control" name="rating">
              <option value="">Select</option>
              <option value="1 star">1 star</option>
              <option value="2 star"> 2 star</option>
              <option value="3 star"> 3 star</option>
              <option value="4 star"> 4 star</option>
              <option value="5 star"> 5 star</option>
            </select>
        </div>
        
         
      <div class="col-md-12">
      <label style="color:#283593;">Next Visit Information<i class="text-danger"></i></label>
       <hr>
      </div>
        
          <div class="form-group col-md-6 next-visit-date col-md-6">      
            <label>Next Visit Date</label>
             <input type="date" name="next_visit_date" class="form-control">
          </div>
      
          <div class="form-group col-md-6 next-visit-location col-md-6">      
           <label>Next Visit Location</label>
             <input type="text" name="next_location" class="form-control">
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
