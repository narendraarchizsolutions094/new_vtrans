<?php 

  if (!empty($repdetails)) {
    foreach($repdetails as $ind => $val){

      $enqno = $val->enquiry_id;
      if(!empty($fieldsval[$enqno])){
        $exptracol = $fieldsval[$enqno];
      }
    }
  }
 ?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading no-print">
            <div class="btn-group"> 
                <a class="btn btn-primary" href="<?=base_url()?>report/deal_report"> <i class="fa fa-list"></i>  All Reports  </a>  
            </div>
        </div>
        <div class="panel-body">
            <div class="widget-title">
              View Report
            </div>
            <hr>                   
            <div class="row">  
              <?php
                  echo form_open_multipart('report/deal_view/'.$rid.'/'.$this->uri->segment(4),array('class'=>"",'name'=>'search'));
              ?>
              <div class="col-sm-3">    
              </div>
              <div class="col-sm-3">
                <label>From</label>
                 <input  class="form-control form-date" name="from">
              </div>
              <div class="col-sm-3">
                 <label>To</label>
                 <input  class="form-control form-date" name="to">
              </div>
              <br>
              <div class="col-sm-2">
                <input type="submit" class="btn btn-primary" value="Filter">
             </div> 
              <?php echo form_close(); ?>  
          </div>
       </div>
    </div>
    <div style="overflow: scroll;">
      <table id="example"  class=" table table-striped table-bordered" style="width:100%;">
          <thead>
          <tr>
              <?php
              if (!empty($report_columns)) {
                foreach ($report_columns as $value) { ?>
                  <th><?=$value?></th>
                <?php
                }
              }
              ?>
          </tr>
          </thead>
           <tbody>
          </tbody>
      </table>
    </div>
  </div>
</div>
<?php 
  $name=base64_decode($this->uri->segment(4)); 
?>
</div>
<script>
    $(document).ready(function(){
      $('#example').DataTable({
        "processing": true,
        "scrollX": true,
        "serverSide": true,
        "lengthMenu": [[10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"]],
        "ajax": {
          "url":  "<?=base_url().'report/deal_report_view_data'?>",
          "type": "POST",
        },    
        "columnDefs": [{ "orderable": false, "targets": 0 }],
            "order": [[ 1, "desc" ]],
        dom: 'lBfrtip',
        buttons: [
        {
          extend: 'copyHtml5',
          title: '<?php echo $name; ?>'
        },
        {
          extend: 'csvHtml5',
          charset: 'UTF-8',         
          bom: true,
        },
        {
          extend: 'excelHtml5',
          title: '<?php echo $name; ?>'
        },
        {
          extend: 'pdfHtml5',
          title: '<?php echo $name; ?>'
        },
        {
          extend: 'print',
          title: '<?php echo $name; ?>'
        }
      ]
    });
});
</script>