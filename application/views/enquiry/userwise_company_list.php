<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
        <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>       
        </div>
</div>

<div class="row p-5" style="margin-top: 20px;">
	<div class="col-lg-12">
		<div class="panel panel-success">
			<div class="panel-body">
        <table style="width: 100%" id="companyTable" class=" table table-bordered table-response">
             <thead>
                 <tr>
                   <th>&nbsp; # &nbsp;</th>
                   <th onclick="dosort()"><?=display('company_name')?></th>
                   <th>Created By</th>
                   <th>Region</th>
                   <th>Created Date</th> 
                 </tr>
             </thead>
             <tbody>
               
             </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var specific_list='';
var c='';
$(document).ready(function(){
  $('#companyTable').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'client/userwise_company_load_data'?>",
              "type": "POST",
              "data":function(d){

                     d.view_all=true;
                     d.specific_list = specific_list;
                     TempData = d;

                      if(c && c!='')
                      d.allow_cols = c;

                     console.log(JSON.stringify(d));
                   return d;
             }
         },
          columnDefs: [
                       { orderable: false, targets: -1 }
                    ]
 });
});

</script>