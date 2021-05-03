<div class="row pd-20" style="width:100%;">
    <div class="col-md-12" style="padding:10px;">
        <div class="card card-graph_full2">
            <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div> 
  <div class="row">
    <div class="col-sm-12">
      <div  class="panel panel-default thumbnail">
        <div class="panel-heading no-print">
            <div class="btn-group"> 
               <b> CUSTOMER FEEDBACK DASHBOARD </b>  
            </div>
        </div>
        <div class="panel-body">
          <table width="100%" class="datatable1 table table-striped table-bordered table-hover ">
            <thead>
                <tr>
                  <th>CUSTOMER FEEDBACK</th>
                  <th>COUNT</th>
                  <th>%AGE</th>
                </tr>
            </thead>
            <tbody>
			<?php foreach($tableone as $one){ ?>
                <tr>
                  <td><?php echo $one->feed_name; ?></td>
                  <td><?php echo $one->ttlcount; ?></td>
				  <td><?php echo $average = ($one->ttlcount*100) / $one->ttlfeed; ?> %</td>
                </tr>
			<?php } ?>
            </tbody>            
          </table>
        </div>
      </div>
    </div>
  </div>
</div>    
        </div>
    </div>
	
	<div class="col-md-12" style="padding:10px;">
        <div class="card card-graph_full2">
<div> 
  <div class="row">
    <div class="col-sm-12">
      <div  class="panel panel-default thumbnail">
        <div class="panel-heading no-print">
            <div class="btn-group"> 
               <b> REGION WISE DASHBOARD </b> 
            </div>
        </div>
        <div class="panel-body">
          <table width="100%" class="datatable1 table table-striped table-bordered table-hover ">
            <thead>
                <tr>
                  <th>BOOKING REGION</th>
                  <th>Satisfied</th>
                  <th>No response</th>
				  <th>Not a Concern Person</th>
				  <th>Wrong number</th>
				  <th>Service Concern</th>
				  <th>Not ready to give feedback</th>
				  <th>Rate concern</th>
				  <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
			<?php foreach($tabletwo as $two){ ?>
                <tr>
                  <td><?php echo $two->region; ?></td>
                  <td><?php echo $two->satisfied; ?></td>
				  <td><?php echo $two->no_responce; ?></td>
				  <td><?php echo $two->no_contact; ?></td>
                  <td><?php echo $two->wrong_no; ?></td>
				  <td><?php echo $two->service_consern; ?></td>
				  <td><?php echo $two->not_ready; ?></td>
                  <td><?php echo $two->rate_consern; ?></td>
				  <td><?php echo $total = $two->satisfied + $two->no_responce + $two->no_contact + $two->wrong_no + $two->service_consern + $two->not_ready + $two->rate_consern; ?></td>
              </tr>
			<?php } ?>
            </tbody>            
          </table>
        </div>
      </div>
    </div>
  </div>
</div>    
        </div>
    </div>
</div>