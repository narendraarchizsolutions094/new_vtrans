<div class="row pd-20" style="width:100%;">
 <div class="row"  style="margin-top: 15px;">
    <form method="POST" action="<?php echo base_url('ticket/feedback_dash'); ?>">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="form-row" style="padding: 10px;">
	  <div class="col-lg-3">
        <div class="form-group">
          <label>From</label>
          <input  class="d_filter form-control form-date" name="from_date" id="from_date" value="<?php if(!empty($from_date)){echo $from_date;} ?>">
        
        </div>
      </div>

      <div class="col-lg-3">
        <div class="form-group">
          <label>To</label>
           <input  class="d_filter form-control form-date" name="to_date" value="<?php if(!empty($to_date)){echo $to_date;} ?>">
        </div>
      </div>
                     <div class=" col-lg-2">
                        <div class="form-group" style="padding:20px;">
                          <button name="submit" type="submit" class="btn btn-primary" >Filter</button>
                        </div>
                     </div>
                </div>
                      
            </div>
            
        </div>
        
    </form>
</div>
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
			<?php 
			$feedback = array();
			$percent = array();
			foreach($tableone as $one){ ?>
                <tr>
                  <td><?php echo $one->feed_name; ?></td>
                  <td><?php echo $one->ttlcount; ?></td>
				  <td><?php $average = ($one->ttlcount*100) / $one->ttlfeed; echo sprintf("%.2f", $average) ?> %</td>
                </tr>
			<?php 
			$feedback[] = $one->ttlcount;
			$percent[] = $average;
			} ?>
			<tfoot>
                <tr style="background:#CCE2FF;">
                <td><b>Grand Total</b></td>
				<td><b><?php echo array_sum($feedback); ?></b></td>
				<td><b><?php $tavg = $one->ttlfeed*100 / array_sum($feedback); echo sprintf("%.2f", $tavg) ?> %</b></td>
                </tr>
            </tfoot>
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
          <table width="100%" class="datatable1 table table-striped table-bordered table-hover">
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
				  <th style="background:#CCE2FF;">Grand Total</th>
                </tr>
            </thead>
            <tbody>
			<?php 
			$satisfied = array();
			$no_responce = array();
			$no_contact = array();
			$wrong_no = array();
			$service_consern = array();
			$not_ready = array();
			$rate_consern = array();

			foreach($tabletwo as $two){ ?>
                <tr>
                  <td><?php echo $two->region; ?></td>
                  <td><?php echo $two->satisfied; ?></td>
				  <td><?php echo $two->no_responce; ?></td>
				  <td><?php echo $two->no_contact; ?></td>
                  <td><?php echo $two->wrong_no; ?></td>
				  <td><?php echo $two->service_consern; ?></td>
				  <td><?php echo $two->not_ready; ?></td>
                  <td><?php echo $two->rate_consern; ?></td>
				  <td style="background:#CCE2FF;"><?php echo $total = $two->satisfied + $two->no_responce + $two->no_contact + $two->wrong_no + $two->service_consern + $two->not_ready + $two->rate_consern; ?></td>
              </tr>
			<?php 
			$satisfied[] = $two->satisfied;
			$no_responce[] = $two->no_responce;
			$no_contact[] = $two->no_contact;
			$wrong_no[] = $two->wrong_no;
			$service_consern[] = $two->service_consern;
			$not_ready[] = $two->not_ready;
			$rate_consern[] = $two->rate_consern;  
			} ?>
			<tfoot>
                <tr style="background:#CCE2FF;">
				  <td>Grand Total</td>
                  <td><b><?php echo array_sum($satisfied); ?></b></td>
                  <td><b><?php echo array_sum($no_responce); ?></b></td>
				  <td><b><?php echo array_sum($no_contact); ?></b></td>
				  <td><b><?php echo array_sum($wrong_no); ?></b></td>
                  <td><b><?php echo array_sum($service_consern); ?></b></td>
				  <td><b><?php echo array_sum($not_ready); ?></b></td>
				  <td><b><?php echo array_sum($rate_consern); ?></b></td>
				  <td><?php echo $gt = array_sum($satisfied)+array_sum($no_responce)+array_sum($no_contact)+array_sum($wrong_no)+array_sum($service_consern)+array_sum($not_ready)+array_sum($rate_consern); ?> - Total</td>
                </tr>
            </tfoot>
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
<script>
$("#add_feedback_form").click(function(e) {
	//alert('hi');
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var url =  '<?php echo base_url();?>ticket/add_feedback';
      $.ajax({
         type: "POST",
         url: url,
         data: $('#customer_feed').serialize(),		 // serializes the form's elements.
         success: function(data)
         {
if(data==1){			 
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Feedback Create successfully',
  showConfirmButton: false,
  timer: 1500
});
}else{
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Feedback Update successfully',
  showConfirmButton: false,
  timer: 1500
});
}
         }
       });
  });
</script>