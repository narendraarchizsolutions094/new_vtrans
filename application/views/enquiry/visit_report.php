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
                <div class="col-md-1"></div>
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
                <input type="submit" name="submit" value="Filter" class="btn btn-primary">
            </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <!--  table area -->
        <div class="panel-body">
            <h2></h2>
            <table class="datatable table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">
                <thead>
                    <tr>
                        <th><?php echo display('serial') ?></th>
                        <th><?php echo ucfirst(display('disolay_name')) ?></th>
                        <th>Travel Start Time</th>
                        <th>Meeting Start Time</th>
                        <th>Meeting End Time</th>
                        <th>Travel End Time</th>
                        <th>Distance Travelled</th>
                        <th>Calculated Distance</th>
                        <th>Estimated Cost</th>
                        <th>Travelled Cost</th>
                        <th>Difference </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // print_r($reports);
                    $totalactualamt =0;
                    $totalpayamt =0;
                    if (!empty($reports)) { ?>
                        <?php $sl = 1; ?>
                        <?php 
                         $totalactualamt=0;
                         $totalpayamt =0;
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
                                   function points_on_earth($p1,$p2,$l1,$l2)
                                  {  $inmiles=twopoints_on_earth( $p1, $p2, $l1,  $l2); 
                                       return  $inmiles * 1.60934;
                                   }
                               for ($i=0; $i <count($x)-2; $i++) { 
                                   $sum +=  points_on_earth($x[$i][0],$x[$i][1],$x[$i+1][0],$x[$i+1][1]);
                                }
                                $sum;
                                $kmamount=10;
                                $totalpay=$kmamount*$km;
                                $actualamt=$sum*$kmamount;
                                
                              function abs_diff($v1, $v2) {
                                  $diff = $v1 - $v2;
                                  return $diff < 0 ? (-1) * $diff : $diff;
                              }
                             $dif= abs_diff($actualamt,$totalpay);
                                  $percentChange = (($totalpay - $actualamt) / $actualamt)*100;
                                    
                    $totalactualamt += $actualamt;
                    $totalpayamt += $totalpay;
                            ?>
                            <?php
                            
                            ?>
                            <tr>
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $report->s_display_name;echo '&nbsp;';echo $report->last_name; ?></td>
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
                                       ?>"><?php if(!empty($percentChange)){echo round($percentChange,0).' % ';}else{ echo '0'.' %';}  ?>(<?= round($dif,0) ?>)</td>
                                <td><a class="btn btn-primary btn-sm" target="_blank" href="<?=base_url().'visits/visit_details/'.$report->visit_id ?>">View</a></td>
                            </tr>                                
                            <?php $sl++; ?>
                        <?php 
                     
                    } ?> 
                    <?php } ?> 
                </tbody>
            </table>  <!-- /.table-responsive -->
            <div class="col-md-12">
            <div class="col-md-4"><span><b>Total Estimated Cost:</b> <?= round($totalactualamt) ?> ₹ </span></div>
            <div class="col-md-4"><span><b>Total Taravelled Cost:</b> <?= round($totalpayamt) ?> ₹</span></div>
            </div>
    </div>
</div>

<script>
$(document).ready(function(){

$('#datatable').DataTable({ 
})
});</script>