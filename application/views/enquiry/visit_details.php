<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
  <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
          <?php  if(user_access('1020'))  { ?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a> 
          <?php  }  ?>  
           
        </div>
        <?php if(user_access('1020'))  {  ?>
  <div class="col-md-4 col-sm-4 col-xs-4"> 
  </div>
  <div class="col-md-4 col-sm-4 col-xs-4 ">   
  <button  style="float:right; margin-right:10px;"  data-toggle="modal" data-target="#update_remarks"  class="btn btn-primary">Update  Remarks</button>
  </div>
  <?php } ?>
</div>
<br>
<p class="text-center" style="font-size:24px;">Purpose - <?=ucfirst($details->m_purpose)?></p>
<script type="text/javascript">
$("select").select2();
</script>
<div class="row">
<div class="row">
    <?php
        $visittable=$this->db->where(array('visit_id'=>$details->visit_id))->get('visit_details')->result();



          //   $waypoints=json_decode($details->way_points);
          //   if(!empty($waypoints)){
          //   $totalpoints=count($waypoints);
          //   $newpoints=array();
          //   // print_r($totalpoints);
          //   $cuts=$totalpoints/23;
          //   for ($i=0; $i < $totalpoints; $i+=$cuts) { 
          //     array_push($newpoints,$waypoints[$i]);
          //   }
          //    $lastKey = key(array_slice($newpoints, -1, 1, true));
          //   $firstpoint=$newpoints[0];
          //   $secondpoint=$newpoints[$lastKey];
          //   function twopoints_on_earth($latitudeFrom, $longitudeFrom,$latitudeTo,$longitudeTo) 
          //   { 
          //   $long1 = deg2rad($longitudeFrom); 
          //   $long2 = deg2rad($longitudeTo); 
          //   $lat1 = deg2rad($latitudeFrom); 
          //   $lat2 = deg2rad($latitudeTo); 
          //   //Haversine Formula 
          //   $dlong = $long2 - $long1; 
          //   $dlati = $lat2 - $lat1; 
          //   $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2); 
          //   $res = 2 * asin(sqrt($val)); 
          //   $radius = 3963.1906; 
          //   return ($res*$radius); 
          //   } 
          //   // latitude and longitude of Two Points 
          //   $latitudeFrom = $firstpoint[0]; 
          //   $longitudeFrom =  $firstpoint[1];
          //   $latitudeTo = $secondpoint[0]; 
          //   $longitudeTo = $secondpoint[1]; 
          //   // Distance between Mumbai and New York 
          //   $inmiles=twopoints_on_earth( $latitudeFrom, $longitudeFrom,  
          //   $latitudeTo,  $longitudeTo); 
          //   $km=$inmiles* 1.60934;
          //   $x=$waypoints;
          //   $sum=0;
          //        function points_on_earth($p1,$p2,$l1,$l2)
          //       {
          //           $inmiles=twopoints_on_earth( $p1, $p2, $l1,  $l2); 
          //            return  $inmiles * 1.60934;
          //       }
          //    for ($i=0; $i <count($x)-2; $i++) { 
          //        $sum +=  points_on_earth($x[$i][0],$x[$i][1],$x[$i+1][0],$x[$i+1][1]);
          //    }
          //     $sum;
          //     $kmamount=10;
          //     $totalpay=$kmamount*$km;

          //     $actualamt=$sum*$kmamount;
          //   //   find difference bectween
          //   function abs_diff($v1, $v2) {
          //       $diff = $v1 - $v2;
          //       return $diff < 0 ? (-1) * $diff : $diff;
          //   }
            
          //  $dif= abs_diff($actualamt,$totalpay);
          //  $percentChange=0;
          //  if($actualamt > 0 && $totalpay > 0){
          //  $dif= abs_diff($actualamt,$totalpay);
          //       $percentChange = (($totalpay - $actualamt) / $actualamt)*100;
          //         }else{
          //                 $actualamt=0;
          //                 $totalpay=0;
          //         }
               
           

    ?>
<div class="col-md-12">
<br>
<table class="table table-responseive table-stripped">
<thead >
<tr>
<th> S. No</th>
<th>Travel Start </th>
<th>Travel End </th>
<th>In Hours </th>
<th>Meeting Start </th>
<th>Meeting End </th>
<th>In Hours </th>

</tr>
</thead>
<tbody>
<?php 
$i=1;
$waypoints=[];
foreach ($visittable as $key => $value) { ?>
<tr>
  <td><?= $i++ ?></td>
<td>
<?php
 if(($value->visit_start)!=NULL){ echo date("F jS, Y, g:i a", strtotime($value->visit_start)); } ?>
</td>
<td>
  <?php  if(($value->visit_end)!=NULL ){ echo date("F jS, Y, g:i a ", strtotime($value->visit_end)); } ?>
</td>
<td>
<?php if(($value->visit_end!=NULL AND $value->visit_start!=NULL)){
 $minutes= round(abs(strtotime($value->visit_start) - strtotime($value->visit_end))/60);
  echo $hours = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60); }else{
    echo'N/A';

  } ?>
</td>
<td>
<?php  if(($value->start_time)!=NULL){ echo date("F jS, Y, g:i a", strtotime($value->start_time)); } ?>
</td>
<td>
<?php  if(($value->end_time)!=NULL){ echo date("F jS, Y, g:i a", strtotime($value->end_time)); } ?>
</td>
<td>
<?php if(($value->start_time!=NULL AND $value->end_time!=NULL)){
 $minutes= round(abs(strtotime($value->start_time) - strtotime($value->end_time))/60);
  echo $hours = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60); }else{
    echo'N/A';
  } ?>
</td>
</tr>
<?php
//$waypoints[]=$value->way_points;
//  array_push(, json_decode($value->way_points));  
// print_r($way_points);
}
//new line
$waypoints[]=$details->all_waypoints; 
//new line end
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
</tbody>
</table>
</div>
<div class="row">
<br>
<br>
<div class="col-md-12">
<hr>
<center>Expenses</center>
<?php if(user_access('1024'))  {  ?>
&nbsp;&nbsp;<button class="btn btn-primary  " style="float:right;" data-toggle="modal" data-target="#approve_expense">Action</button> 
<?php 
} ?>
<button class="btn btn-success  " style="float:right; margin-right: 20px;" data-toggle="modal" data-target="#add_approve">Send For Approval</button>
<button class="btn btn-success  " style="float:right; margin-right: 20px;" data-toggle="modal" data-target="#add_expense">Add Expense</button>
</div>
<div class="col-md-12">
<br>
<table class="table table-responseive table-stripped">
<thead >
<tr>
<th><INPUT type="checkbox" onchange="checkAll(this)" name="chk[]" /> S. No.</th>
<th>Expense Type</th>
<th>Title</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php 
    $user_id=$this->session->user_id;
    $comp_id=$this->session->companey_id;
$i=2;
$totalexp=0;
//$expense=$this->db->select('tbl_expense.id as ids,tbl_expense.*,tbl_expenseMaster.*')->where(array('tbl_expense.visit_id'=>$details->visit_id,'tbl_expense.created_by'=>$user_id,'tbl_expense.comp_id'=>$comp_id))->join('tbl_expenseMaster','tbl_expenseMaster.id=tbl_expense.expense','left')->get('tbl_expense')->result();
$expense=$this->db->select('tbl_expense.id as ids,tbl_expense.created_by as ct_id,tbl_expense.*,tbl_expenseMaster.*')->where(array('tbl_expense.visit_id'=>$details->id,'tbl_expense.comp_id'=>$comp_id))->join('tbl_expenseMaster','tbl_expenseMaster.id=tbl_expense.expense','left')->get('tbl_expense')->result();
foreach ($expense as $key => $value) { 
 $tamount= $value->amount
  ?>
<tr>
<td>
  <?php
  if($value->ct_id!=$user_id){
    echo'<input  type="checkbox" name="approve[]" class="checkbox1" value="'.$value->ids.'">';
  }
  ?> 
  <?= $i++; ?>
</td>
<td><?php if($value->type==1){
  echo'Travel Expense';
}else{
  echo'Other Expense';

} ?></td>
<td><?php if($value->type==1){
  echo'Travel Expense';
}else{
  echo $value->title;
} ?></td>
<td><?= $value->amount ?> ₹</td>
<td><?php  if($value->approve_status==0){echo'<span >Pending</span>';}elseif($value->approve_status==2){
echo'<span style="color:green">Accepted'.' ( '.$value->remarks.' ) </span>';
}elseif($value->approve_status==1){
  echo'<span style="color:red">Rejected'.' ( '.$value->remarks.' ) </span>';
} ?></td>
<td> <?php if($value->file!=''){ ?><a href="<?= base_url('assets/images/user/'.$value->file) ?>" style="btn btn-xs btn-success" target="_BLANK"><i class="fa fa-file"></i></a> <?php } ?>
  <?php if($value->approve_status==0){ ?> 
    <?php if($value->type!=1){ ?>
      <a  class="btn btn-xs  btn-danger" href="<?= base_url('visit-expense/delete/'.$value->ids) ?>"><i class="fa fa-trash"></i></a>
 <?php 
}  ?>
    <?php } ?>
    </td>
</tr>
<?php
if($value->approve_status==2){
$totalexp += $tamount;
}
} ?>
</tbody>

</table>
<div class="col-md-12">
<b>Total Payable Expense: <?= $totalexp; ?> ₹</b></div>
</div>
</div>
<br>
<br>

<hr>

<div id="map" style="width: 100%; height: 800px;"></div>

<script>
      
       
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
 
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

<form id="visit_create_form" action="<?=base_url('enquiry/add_visit')?>" class="form-inner" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
          <div class="row">
                <div class="form-group col-md-12">
                    <label>Select Visit Type</label>
                    <div class="form-check">
                        <label class="radio-inline">
                        <input name="type"  name="type" value="1" type="radio" checked onclick="handleClick(this);">Current Visit</label>
                        <label class="radio-inline">
                        <input type="radio" name="type" value="2" onclick="handleClick(this);">Future Visit</label>
                    </div>
                  </div>
                </div>
						
				        <div class="form-group col-md-6 visit-time col-md-6">     
                    <label>Purpose of meeting <span class="text-danger">*</span></label>
                    <input type="text" name="m_purpose" id="m_purpose" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label style="width:100%;">Company <span class="text-danger">*</span>
                      <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                        <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
                      </a>
                    </label>
                    <select class="form-control" name="company" onchange="filter_related_to(this.value)" required>
                      <option value="-1">Select</option>
                      <?php
                      if(!empty($company_list))
                      {
                        foreach ($company_list as $key =>  $row)
                        {
                          echo '<option value="'.$row->id.'">'.$row->company_name.'</option>';
                        }
                      }
                      ?>
                    </select>
                </div>

               <div class="form-group col-md-6">
                  <label style="width: 100%">Client Name <span class="text-danger">*</span>
                     <a href="<?=base_url('enquiry/create?status=1&red=visits')?>">
                        <span style="float: right; color:gray;"><i class="fa fa-plus"></i></span>
                      </a>
                    </label>
                  <select class="form-control" name="enq_id" required>
                    <option value="">Select</option>
                  </select>
               </div>

                <div class="form-group col-md-6">
                  <label style="width: 100%">Contact Name <span class="text-danger">*</span>
                    <span style="float: right; color:gray;" onclick="add_contact()">
                    <i class="fa fa-plus"></i>
                    </span>
                  </label>
                  <select class="form-control" name="contact_id" required>
                    <option value="">Select</option>
                  </select>
               </div>

                <div class="form-group col-md-6 visit-date col-md-6">     
          <label>Visit Date</label>
          <input type="date" name="visit_date" id="vdate" disabled class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" id="vtime" disabled class="form-control" value="<?= date('H:i') ?>" required>
        </div>
     
        <input type="hidden" name="visit_notification_id" value="">
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input id="visit_create_btn" type="submit" name="submit_only" class="btn btn-primary" value="Save">
            </div>
         </div>

</form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" id="close_visit" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>   
<div id="add_approve" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Send For Approval</h4>
         </div>
         <form action="<?= base_url('enquiry/notify_rmanager') ?>" method="POST">
         <div class="modal-body">
          <input name="visit_id" class="form-control"  value="<?= $details->id ?>" hidden>
            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" name="remarks" type="text" ></textarea>
            </div>
            </div>
          
               <br>
               <button class="btn btn-sm btn-success" type="submit" >
              Send </button>                    
               <br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
         </form>
      </div>
      </div>
      </div>
<div id="update_remarks" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Remarks</h4>
         </div>
         <form action="<?= base_url('client/updateVisit_remarks') ?>" method="POST">
         <div class="modal-body">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
            <label>Rating</label>
       	<select class="form-control v_filter" name="rating">
              <option value="">Select</option>
              <option value="1 star" <?php if($details->rating=="1 star"){echo'selected';} ?>> 1 star</option>
              <option value="2 star" <?php if($details->rating=="2 star"){echo'selected';} ?>> 2 star</option>
              <option value="3 star" <?php if($details->rating=="3 star"){echo'selected';} ?>> 3 star</option>
              <option value="4 star" <?php if($details->rating=="4 star"){echo'selected';} ?>> 4 star</option>
              <option value="5 star" <?php if($details->rating=="5 star"){echo'selected';} ?>> 5 star</option>
            </select>
            </div>
            </div>
          <input name="visit_id" class="form-control"  value="<?= $details->id ?>" hidden>
            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>

            <textarea class="form-control" name="remarks" type="text" value="<?= $details->remarks ?>"><?= $details->remarks ?> </textarea>
            </div>
            </div>
            <!-- <div class="col-md-12">
            <div class="form-group">
            <label>Travelled Type</label>

            <input class="form-control" name="travelledtype" type="text" value="<?= $details->remarks ?>">
            </div>
            </div> -->
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit" >
              Update</button>                    
               <br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
<div id="approve_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title"></h4>
         </div>
         <div class="modal-body">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
              <label>Status</label>
             <select id="approve_status" name="approve_status" class="form-control">
               <option value="2">Approve</option>
               <option value="1">Reject</option>
             </select>
            </div>
            </div>
          <input id="visit_id" class="form-control"  value="<?= $details->id ?>" hidden>

            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>

            <textarea class="form-control" name="remarks" id="remarks" cols="4"></textarea>
            </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit" onclick="expense_status();">
              Submit</button>                    
               <br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="add_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Add Expense</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>client/add_expense" method="POST" enctype='multipart/form-data'>  
            <div class="row">
          <input name="visit_id" class="form-control" value="<?= $details->id ?>" hidden>

            <table class="table table-responsive">
                  <thead>
                  <th>Title</th>
                  <th>Amount</th>
                  <th>File(if any)</th>
                  <th><input type="button" value="+ " id="add" class="btn btn-primary"></th>
                  </thead>
                  <tbody class="detail">
                  <tr>
                  <td width="30%">
           <select name="expense[]" class="form-control">
           <?php 
           $expenselist=$this->db->where(array('comp_id'=>$this->session->companey_id,'status'=>1))->get('tbl_expenseMaster')->result();
           foreach ($expenselist as $key => $value) { ?>
            <option value="<?= $value->id ?>"> <?= $value->title ?></option>
          <?php } ?>
           </select> 
          </td>
                  <td width="30%">
                  <input name="amount[]" class="form-control amount" onkeyup="total()" id="amount" value="0"  >
                  </td>
                  <td width="30%">
                  <input name="imagefile[]"  class="form-control" onchange="Filevalidation(this)"  type="file"  >
                  </td>
                  <td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                  </tr>
                  </tbody>
            <tfoot>
            <tr>
            
            <th style="text-align:right">Total: </th>
            <th id="total" class="total"></th><th></th>
            <th></th></tr></tfoot>
            </table>
          
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit">
              Add Expense</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
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