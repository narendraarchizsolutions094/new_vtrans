<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript">
$("select").select2();
</script>
<div class="row">
    <div class="col-md-12">        
        <div class="panel-body">
            <form action="" method="post">
            <div class="row ">
                <div class="col-md-3 ">
                    <label>From Date<i class="text-danger">*</i></label>                    
                    <?php
                    if (set_value('date_from')) {
                        $from =   set_value('date_from');                     
                        $to =   set_value('date_to');                     
                    }else{
                        $from =  date('Y-m-d');
                        $to =  date('Y-m-d');
                    }                                        
                    ?>
                    <input type="date" name="date_from" class="form-control" style="width: 80%;padding-top:0px;" value="<?=date("Y-m-d", strtotime($from));?>" required>
                </div>
                <div class="col-md-3 ">
                    <label>To Date<i class="text-danger">*</i></label>                    
                    <input type="date" name="date_to" class="form-control" style="width: 80%;padding-top:0px;" value="<?=date("Y-m-d", strtotime($to));?>" required>
                </div>
                <div class="col-md-3 ">
                  <label for="inputPassword4"><?php echo display("employee"); ?><i class="text-danger">*</i></label>
                  <select data-placeholder="Begin typing a name to filter..."  class="form-control chosen-select" name="employee" id="employee" required>
                  <option value="0">--Select--</option>
                       <?php foreach ($employee as $user) {?>
                            <option value="<?=$user->pk_i_admin_id?>" <?php if(!empty(set_value('employee'))){if ($user->pk_i_admin_id==set_value('employee')) {echo 'selected';}}?>><?=$user->s_display_name . " " . $user->last_name?></option>
                        <?php }?>
                  </select>
                </div>
                
                <br>

                <input type="submit" name="submit" value="Filter" class="btn btn-primary" >
            </div>
            </form>
        </div>
    </div>
</div>
<hr>
<div class="row">

    <!--  table area -->
        <div class="panel-body">
        <div class="col-md-12">
        <div class="col-md-3"></div>
        <div class="col-md-3">
                <label>Minimum Differnce</label>
            <input type="text" class="form-control" id="min" name="min">
        </div>
        <div class="col-md-3">
        <label>Maximum Differnce</label>
            <input type="text" class="form-control" id="max" name="max">
        </div>
            </div>
        <div class="row">
        <div class="col-md-12" style="max-width: 100%; overflow-x:auto;">

            <table class=" table table-striped table-bordered" cellspacing="0"  id="datatable">
                <thead>
                    <tr>
                        <th><?php echo display('serial') ?></th>
                        <th>Company</th>
                        <th>Travel Start Time</th>
                        <th>Meeting Start Time</th>
                        <th>Meeting End Time</th>
                        <th>Travel End Time</th>
                        <th>Travelled Distance </th>
                        <th>Actual Distance</th>
                        <th>Actual Cost</th>
                        <th>Travelled Cost</th>
                        <th>Difference </th>
                        <th>Other Expense </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // print_r($reports);
                    $totalactualamt =0;
                    $totalpayamt =0;
                    $Totalexp =0;
                    if (!empty($reports)) { ?>
                        <?php $sl = 1; ?>
                        <?php 
                         $totalactualamt=0;
                         $totalpayamt =0;
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
                         function points_on_earth($p1,$p2,$l1,$l2)
                         {  
                             $inmiles=twopoints_on_earth( $p1, $p2, $l1,  $l2); 
                              return  $inmiles * 1.60934;
                          }
                          function abs_diff($v1, $v2) {
                            $diff = $v1 - $v2;
                            return $diff < 0 ? (-1) * $diff : $diff;
                        }
                        foreach ($reports as $report) {
                          
                            
                              $waypoints=json_decode($report->way_points);
                              $totalpoints=count($waypoints);
                              $newpoints=array();
                              $cuts=$totalpoints/23;
                              for ($i=0; $i < $totalpoints; $i+=$cuts) { 
                                array_push($newpoints,$waypoints[$i]);
                              }
                               $lastKey = key(array_slice($newpoints, -1, 1, true));
                              $firstpoint=$newpoints[0];
                              $secondpoint=$newpoints[$lastKey];
                             
                              // latitude and longitude of Two Points 
                              $latitudeFrom = $firstpoint[0]; 
                              $longitudeFrom =  $firstpoint[1];
                              $latitudeTo = $secondpoint[0]; 
                              $longitudeTo = $secondpoint[1]; 
                              // Distance between Mumbai and New York 
                              $inmiles=twopoints_on_earth( $latitudeFrom, $longitudeFrom,  
                              $latitudeTo,  $longitudeTo); 
                              $km=$inmiles* 1.60934;
                              $x=$waypoints;
                              $sum=0;
                                 
                               for ($i=0; $i <count($x)-2; $i++) { 
                                   $sum +=  points_on_earth($x[$i][0],$x[$i][1],$x[$i+1][0],$x[$i+1][1]);
                                }
                                $sum;
                                $kmamount=10;
                                $totalpay=$kmamount*$km;
                                $actualamt=$sum*$kmamount;
                                
                                $percentChange=0;
                             if($actualamt > 0 && $totalpay > 0){
                             $dif= abs_diff($actualamt,$totalpay);
                                  $percentChange = (($totalpay - $actualamt) / $actualamt)*100;
                                    }else{
                                            $actualamt=0;
                                            $totalpay=0;
                                    }
                    $totalactualamt += $actualamt;
                    $totalpayamt += $totalpay;
                    $Totalexp += $report->visit_expSum
                            ?>
                        
                            <tr >
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $report->company ?></td>
                                <!-- <td><?php echo $report->s_display_name; echo '&nbsp;';echo $report->last_name; ?></td> -->
                                <td><?php echo $report->visit_start;?></td>                                     
                                <td><?php echo $report->start_time;?></td>                                     
                                <td><?php echo $report->end_time;?></td>
                                <td><?php echo $report->visit_end;?></td>
                                <td>
                                   <?php if(!empty($sum)){echo round($sum,2).' Km';}else{ echo'N/A';} ?>
                                </td>
                                <td>
                                <?php if(!empty($km)){echo round($km,2).' Km';}else{ echo'N/A';} ?>
                                </td>
                                <td><?php if(!empty($actualamt)){echo round($actualamt,0).' ₹';}else{ echo '0'.' ₹';}  ?></td>
                                <td><?php if(!empty($totalpay)){echo round($totalpay,0).' ₹';}else{ echo '0'.' ₹';}  ?></td>
                                <td  style="<?php 
                                    if(abs($percentChange)>20){
                                    echo  'border:1px solid red;background-color: #eae0e0;';
                                    }
                                       ?>"><?php if(!empty($percentChange)){echo abs(round($percentChange,0));}else{ echo'0'; }  ?></td>
                                
                                <td><?php echo $report->visit_expSum;?></td>

                                <td><a class="btn btn-primary btn-sm" target="_blank" href="<?=base_url().'visits/visit_details/'.$report->visit_id ?>">View</a></td>
                            </tr>                                
                            <?php $sl++; ?>
                        
                    <?php }  }?> 
                </tbody>
            </table>  <!-- /.table-responsive -->
        </div>
        </div>
        <br>
            <div class="col-md-12">
            <div class="col-md-4" ></div>
            <div class="col-md-4" ></div>
            <div class="col-md-4" >
            
            <table class="table table-responsive table-bordered" >
            <tbody>
            <tr>
            <td width="50%"><b>Total Actual Cost:</b></td><td><?= round($totalactualamt) ?> ₹</td>
            </tr>
            <tr><td width="50%"><b>Total Travelled Cost:</b> </td><td><?= round($totalpayamt) ?> ₹</td>
            </tr>
            <tr><td width="50%"><b>Total other Expense:</b></td><td><?= round($Totalexp) ?> ₹</td>
            </tr></tbody>
            </table>
            </div>
            </div>
            
    </div>

<script>
$(document).ready(function(){
/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[10] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);

$(document).ready(function() {
    var table = $('#datatable').DataTable();
     
    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );
} );

});</script>