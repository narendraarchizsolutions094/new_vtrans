<div class="row">
<div class="row">
    <?php
        $visittable=$this->db->where(array('visit_id'=>$details->visit_id))->get('visit_details')->result();
    ?>
<div class="col-md-12">
<?php 
$i=1;
$waypoints=[];
foreach ($visittable as $key => $value) { ?>
<?php
$waypoints[]=$value->way_points;
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
</div>

<br>
<br>

<hr>

<div id="map" style="width: 100%; height: 800px;"></div>

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

<script>
 document.getElementById("vdate").disabled = true;  
  document.getElementById("vtime").disabled = true; 
function handleClick(myRadio) {
  var valuer= myRadio.value;
  if(valuer==1){
  document.getElementById("vdate").disabled = true;  
  document.getElementById("vtime").disabled = true;  
  }else{
    document.getElementById("vdate").disabled = false;  
  document.getElementById("vtime").disabled = false;  
  } 
}

$(function() {
    $('#add').click(function() {
      addnewrow();
    });
    $('body').delegate('.remove', 'click', function() {
      $(this).parent().parent().remove();
    });
    $('body').delegate('.qtys,.price', 'keyup', function() {
      var tr = $(this).parent().parent();
    });
  });
//   $( "#amount" ).keypress(function() {
//     var t = 0;
//     $('#amount').each(function(i, e) {
//       var amount = $(this).val() - 0;
//       t += amount;
//     });
//     $('#total').html(t);
//     alert(t);
// });

  function total() {
    var t = 0;
    $('.amount').each(function(i, e) {
      var amount = $(this).val() - 0;
      t += amount;
    });
    $('.total').html(t);
  }
  function Filevalidation(t)  {
    var filesize =t.files[0].size;
    filesize=filesize/1024;
   var filesizeinkb= filesize.toFixed(0);
    // alert(filesizeinkb);

    if(filesizeinkb > 1024){
   alert('File Size not exceed ');
    }
	}
  function addnewrow() {
    var n = ($('.detail tr').length - 0) + 1;
    var s = n + 3
    var r = n + 1
    var tr = '<tr>' + '<td width="30%"><select name="expense[]" class="form-control"><?php foreach ($expenselist as $key => $value) { ?><option value="<?= $value->id ?>"><?= $value->title ?></option><?php } ?></select></td>'+'<td width="30%"><input id="amount'+n+'" class="form-control amount" name="amount[]"  onkeyup="total()"></td>'+'<td width="30%"><input name="imagefile[]" class="form-control " onchange="Filevalidation(this)"  id="file'+n+'" type="file"  ></td>'+'<td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>' + '</tr>';
    $('.detail').append(tr);
    // $.getScript("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
    document.getElementById('amount' + n).addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });
     

  }
  document.getElementById('amount').addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });

    function expense_status(){
      var x = new Array(); 
      
      $($(".checkbox1:checked")).each(function(k,v){
        x.push($(v).val());
      });
      //alert(x.toString()); return;
       approve_status = document.getElementById("approve_status").value;
       remarks = document.getElementById("remarks").value;
      $.ajax({
              type: 'POST',
              url: '<?= base_url('client/update_expense_status') ?>',
              data: {exp_ids:x,status:approve_status,remarks:remarks},
              success:function(data){
              //  alert(data);
               location.reload();
              } 
              });

    }
    function checkallexpense_status(){
      approve_status = document.getElementById("approve_status").value;
       remarks = document.getElementById("remarks").value;
       visit_id = document.getElementById("visit_id").value;
      $.ajax({
              type: 'POST',
              url: '<?= base_url('client/all_update_expense_status') ?>',
              data: {status:approve_status,remarks:remarks,visit_id:visit_id},
              success:function(data){
              //  alert(data);
               location.reload();
              } 
              });
    }
    function select_all(){

var select_all = document.getElementById("selectall"); //select all checkbox
var checkboxes = document.getElementsByClassName("choose-col"); //checkbox items

//select all checkboxes
select_all.addEventListener("change", function(e){
  for (i = 0; i < checkboxes.length; i++) { 
    checkboxes[i].checked = select_all.checked;
  }
});


for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener('change', function(e){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){
      select_all.checked = false;
    }
    //check "select all" if all checkbox items are checked
    if(document.querySelectorAll('.choose-col:checked').length == checkboxes.length){
      select_all.checked = true;
    }
  });
}

}
function filter_related_to(v)
{
      $.ajax({
            url:"<?=base_url('client/account_by_company')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $("select[name=enq_id]").html(q);
               $("select[name=enq_id]").trigger('change');
            }
      });
    
       $.ajax({
            url:"<?=base_url('client/contact_by')?>",
            type:'get',
            data:{key:v,by:'company'},
            success:function(q){
              $("select[name=contact_id]").html(q);
               $("select[name=contact_id]").trigger('change');
            }
      });
  }

  </script>