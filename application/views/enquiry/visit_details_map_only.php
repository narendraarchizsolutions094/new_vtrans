<?php
    //$visittable=$this->db->where(array('visit_id'=>$details->visit_id))->get('visit_details')->result(); 
    $visittable=$this->db->where(array('id'=>$details->id))->get('tbl_visit')->result();     
?>

<?php 
$i=1;
$waypoints=[];
foreach ($visittable as $key => $value) { ?>
<?php
$waypoints[]=$value->all_waypoints;
//  array_push(, json_decode($value->way_points));  
// print_r($way_points);
} 
$arr_m=[];
foreach ($waypoints as $key => $value) {
foreach (json_decode($value) as $key => $values) {
   $arr_m[]=$values;
}
}
// print_r($arr_m);
// die();
 $totalpoints=count($arr_m);
$newpoints=array();
// print_r($totalpoints);
$cuts=$totalpoints/23;
for ($i=0; $i < $totalpoints; $i+=$cuts) { 
  array_push($newpoints,$arr_m[$i]);
}
 $lastKey = key(array_slice($newpoints, -1, 1, true));
$firstpoint=$newpoints[0];
$secondpoint=$newpoints[$lastKey];

?>
<style>
body{
	overflow: hidden;
    margin: 0px;
}
</style>

<div id="map" style="width: 100%; height: 100%;"></div>

<script> 
    var distance;
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsRenderer = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: {lat: 41.8781, lng: 87.6298}
        });
        directionsRenderer.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsRenderer);
        /*document.getElementById('submit').addEventListener('click', function() {
        });*/
         // The map, centered on Central Park
  // Locations of landmarks
  const dakota = {lat: <?=$firstpoint[0]?>, lng: <?=$firstpoint[1]?>};
  const frick = {lat: <?=$secondpoint[0]?>, lng: <?=$secondpoint[1]?>};
  // The markers for The Dakota and The Frick Collection
  var mk1 = new google.maps.Marker({position: dakota, map: map});
  var mk2 = new google.maps.Marker({position: frick, map: map});
   distance = haversine_distance(mk1,mk2);
  document.getElementById('msg').innerHTML = "Distance between markers: " + distance.toFixed(0)*1.60934 + " KM.";


      }
      var res = JSON.parse("<?=json_encode($newpoints)?>");

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
        var checkboxArray = JSON.parse("<?=json_encode($newpoints)?>");
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
            // var summaryPanel = document.getElementById('directions-panel');
            // summaryPanel.innerHTML = '';
            // // For each route, display summary information.
            // for (var i = 0; i < route.legs.length; i++) {
            //   var routeSegment = i + 1;
            //   summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
            //       '</b><br>';
            //   summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
            //   summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
            //   summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            // }
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }

      function haversine_distance(mk1, mk2) {
      var R = 3958.8; // Radius of the Earth in miles
      var rlat1 = mk1.position.lat() * (Math.PI/180); // Convert degrees to radians
      var rlat2 = mk2.position.lat() * (Math.PI/180); // Convert degrees to radians
      var difflat = rlat2-rlat1; // Radian difference (latitudes)
      var difflon = (mk2.position.lng()-mk1.position.lng()) * (Math.PI/180); // Radian difference (longitudes)

      var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat/2)*Math.sin(difflat/2)+Math.cos(rlat1)*Math.cos(rlat2)*Math.sin(difflon/2)*Math.sin(difflon/2)));
      return d;
    }
    
    </script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaoGdhDoXMMBy1fC_HeEiT7GXPiCC0p1s&callback=initMap"
  type="text/javascript"></script>
  
   

