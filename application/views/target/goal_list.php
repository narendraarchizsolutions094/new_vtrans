<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<style type="text/css">
	#graph1,#graph2,#graph3,#graph4
	{
		height: 500px;
	}
	table span:hover
	{
		color: black;
		font-weight: 800;
	}
</style>
<script type="text/javascript">
var Ignore = new Array();
</script>

<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
          <?php
      if(user_access('260'))
      {
          if(!empty($this->session->process) && (is_array($this->session->process)?(count($this->session->process)==1?true:false):true))
			{
				?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#add_goal" title="Add Goal"></a>    

          <?php
          }
      }
          ?>  
        </div>

<?php
if(empty($_COOKIE['goal_filter_setting'])) {
	$variable=[];
} else {
$variable=explode(',',$_COOKIE['goal_filter_setting']);
}
?>
		<div class="col-md-2" style="float: right;">
		<div class="btn-group dropdown-filter">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by <span class="caret"></span>
              </button>              
              <ul class="filter-dropdown-menu dropdown-menu">   
                    <li>
                      <label>
                      <input type="checkbox" value="date" id="datecheckbox" name="filter_checkbox" <?php if(in_array('date',$variable)){echo'checked';} ?>> Date </label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="role" id="rolecheckbox" name="filter_checkbox" <?php if(in_array('role',$variable)){echo'checked';} ?>> For Role</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="user" id="usercheckbox" name="filter_checkbox" <?php if(in_array('user',$variable)){echo'checked';} ?>> For User</label>
                    </li>                
                    <li>
                      <label>
                      <input type="checkbox" value="matric" id="matriccheckbox" name="filter_checkbox" <?php if(in_array('matric',$variable)){echo'checked';} ?>> Matric</label>
                    </li>  


					<li>
                      <label>
                      <input type="checkbox" value="booking_type" id="booking_type_checkbox" name="filter_checkbox" <?php if(in_array('booking_type',$variable)){echo'checked';} ?>> Booking Type</label>
                    </li>  
					<li>
                      <label>
                      <input type="checkbox" value="deal_type" id="deal_type_checkbox" name="filter_checkbox" <?php if(in_array('deal_type',$variable)){echo'checked';} ?>> Deal Type</label>
                    </li>  
					<li>
                      <label>
                      <input type="checkbox" value="business_type" id="business_type_checkbox" name="filter_checkbox" <?php if(in_array('business_type',$variable)){echo'checked';} ?>> Business Type</label>
                    </li>  


                  <!--  <li>
                      <label>
                      <input type="checkbox" value="goals" id="goalcheckbox" name="filter_checkbox" <?php if(in_array('goals',$variable)){echo'checked';} ?>> Goal Analytics</label>
                    </li> --> 
                   
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>

		</div>
</div>


<div class="row" style="margin: 15px; <?php if(empty($_COOKIE['goal_filter_setting'])){ echo'display:none'; }  ?>" id="filter_pannel">

<form id="filter_form">
	<div class="row"  id="datefilter" style="<?php if(!in_array('date',$variable)){echo'display:none';} ?>">
			<div class="col-md-3">
			<div class="form-group">
			<label>Start From</label>
				<input   name="start_date_from" class="form-control form-date" >
			</div>
			</div>
		<!-- <div class="col-md-3">
			<div class="form-group">
			<label>Start To</label>
					<input   name="start_date_to" class="form-control form-date" >
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>End From </label>
					<input   name="end_ate_from" class="form-control form-date" >
			</div>
		</div> -->
		<div class="col-md-3">
			<div class="form-group">
			<label>End To</label>
					<input  name="end_date_to" class="form-control form-date" >
			</div>
		</div>

	<div class="col-md-3" id="rolefilter" style="<?php if(!in_array('role',$variable)){echo'display:none';} ?>">
		<div class="form-group">
			<label>For Role</label>
			<select class="form-control" name="role_for">
				<option value="">Select</option>
				<?php
				if(!empty($roles))
				{
					$rol = json_decode($roles);
					foreach ($rol as $row)
					{
						echo'<option value="'.$row->id.'">'.$row->role_name.'</option>';
					}
				}
				?>
			</select>
		</div>
		
	</div>
	
	<div class="col-md-3" id="userfilter" style="<?php if(!in_array('user',$variable)){echo'display:none';} ?>">
		<!-- <div class="form-group">
			<label>For User &nbsp;<small><input type="checkbox" name="fetch_type" value="1" checked> Hierarchy wise</small></label>
			<select class="form-control" name="user_for">
				<?php
				if(!empty($users))
				{
					$rol = json_decode($users);
					foreach ($rol as $row)
					{
						echo'<option value="'.$row->id.'" '.($row->id==$this->session->user_id?'selected':'').'>'.$row->user_name.'</option>';
					}
				}
				?>
			</select>
		</div> -->
		
		<label>For &nbsp;<small><input type="checkbox" name="fetch_type" value="2" checked > Hierarchy wise</small></label>
			<select class="form-control" name="user_for">
		<?php
		// $res = $this->db->select('pk_i_admin_id,designation,CONCAT(s_username," ",last_name) as name')->from('tbl_admin')->where('companey_id',$this->session->companey_id)->where('designation is not null and CHAR_LENGTH(designation)>0')->group_by('designation')->get()->result();

		// $res = $this->db->select('admin.pk_i_admin_id,CONCAT(admin.s_display_name," ",admin.last_name) as name, admin.designation, role.user_role')->from('tbl_admin admin')->join('tbl_user_role role','role.use_id=admin.user_type')->where('admin.companey_id',$this->session->companey_id)->where('admin.report_to="'.$this->session->user_id.'" or admin.pk_i_admin_id='.$this->session->user_id)->get()->result();
		// 		if(!empty($res))
		// 		{
		// 			foreach ($res as $key => $row)
		// 			{
		// 				echo'<option value="'.$row->pk_i_admin_id.'" '.($row->pk_i_admin_id==$this->session->user_id?'selected':'').'>'.$row->name.' ('.$row->user_role.')</option>';
		// 			}
		// 		}

				foreach (json_decode($users) as $key => $value)
				{
					echo'<option value="'.$value->id.'">'.$value->user_name.'</option>';
				}

				?>

			</select>
		</div>
	</div>
	<div class="col-md-3" id="matricfilter" style="<?php if(!in_array('matric',$variable)){echo'display:none';} ?>">
		<div class="form-group">
			<label>Metric</label>
			<select class="form-control" name="metric_type">
				<option value="">Select</option>
				<option value="freight">Freight</option>
				<option value="weight">Weight</option>
			</select>
		</div>
	</div>

	<div class="col-md-3" id="booking_type" style="<?php if(!in_array('booking_type',$variable)){echo'display:none';} ?>">
		<div class="form-group">
			<label> Booking Type </label>
			<select class="form-control" name="booking_type">
				<option value="">Select</option>
				<option value="sundry">Sundry</option>
				<option value="ftl">FTL</option>
			</select>
		</div>
	</div>

	<div class="col-md-3" id="deal_type" style="<?php if(!in_array('deal_type',$variable)){echo'display:none';} ?>">
		<div class="form-group">
			<label>Deal Type</label>
			<select class="form-control" name="deal_type">
				<option value="">Select</option>
				<option value="domestic">Domestic</option>
				<option value="saarc">SAARC</option>
			</select>
		</div>
	</div>

	<div class="col-md-3" id="business_type" style="<?php if(!in_array('business_type',$variable)){echo'display:none';} ?>">
		<div class="form-group">
			<label>Business Type</label>
			<select class="form-control" name="business_type">
				<option value="">Select</option>
				<option value="inward">Inward</option>
				<option value="outward">Outward</option>
			</select>
		</div>
	</div>

	<div class="col-md-3" id="goalfilter" style="<?php if(!in_array('goals',$variable)){echo'display:none';} ?>">
	<div class="form-group" ><br>
			<label><input type="checkbox" name="graph" value="1"> Goal Analytics</label>
		</div>
		</div>
</form>
</div>
<script>
  $(document).ready(function(){
	 $("#save_advance_filters").on('click',function(e){
	  e.preventDefault();
	  var arr = Array();  
	  $("input[name='filter_checkbox']:checked").each(function(){
		arr.push($(this).val());
	  });        
	  setCookie('goal_filter_setting',arr,365);      
	  // alert('Your custom filters saved successfully.');
	  Swal.fire({
	position: 'top-end',
	icon: 'success',
	title: 'Your custom filters saved successfully.',
	showConfirmButton: false,
	timer: 1000
  });
	});


});
$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#rolecheckbox').is(":checked")||$('#usercheckbox').is(":checked")||
  $('#golecheckbox').is(":checked")||$('#matriccheckbox').is(":checked")||$('#booking_type_checkbox').is(":checked")||$('#deal_type_checkbox').is(":checked")||$('#business_type_checkbox').is(":checked")){ 
    $('#filter_pannel').show();
  }else{
    $('#filter_pannel').hide();
  }
});
$('input[name="filter_checkbox"]').click(function(){              
        if($('#datecheckbox').is(":checked")){
         $('#datefilter').show();
        }
        else{
           $('#datefilter').hide();
             }
      
		if($('#rolecheckbox').is(":checked")){
        $('#rolefilter').show();
            }
        else{
          $('#rolefilter').hide();
		}
		if($('#matriccheckbox').is(":checked")){
        $('#matricfilter').show();
            }
        else{
          $('#matricfilter').hide();
		}
		if($('#usercheckbox').is(":checked")){
        $('#userfilter').show();
            }
        else{
          $('#userfilter').hide();
		}
		if($('#goalcheckbox').is(":checked")){
        $('#goalfilter').show();
            }
        else{
          $('#goalfilter').hide();
		}

		if($('#booking_type_checkbox').is(":checked")){
        	$('#booking_type').show();
        }
        else{
          $('#booking_type').hide();
		}


		if($('#deal_type_checkbox').is(":checked")){
        	$('#deal_type').show();
        }
        else{
          $('#deal_type').hide();
		}

		if($('#business_type_checkbox').is(":checked")){
        	$('#business_type').show();
        }
        else{
          $('#business_type').hide();
		}


		






		


		
});
		

</script>
<div class="panel" id="goal_graph" style="display: none;">
	<div class="panel-body" >
		<div class="row">
			<center><label>Metric Type : Deal Value</label></center>
			<div class="col-sm-6">
				<div id="graph1"></div>
			</div>
			<div class="col-sm-6">
				<div id="graph3"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<center><label>Metric Type :Won Deals</label></center>
			<div class="col-sm-6">
				<div id="graph2"></div>
			</div>
			<div class="col-sm-6">
				<div id="graph4"></div>
			</div>
		</div>
	</div>
</div>
<div class="panel">
	<div class="row" align="right">
		<div class="col-md-3 pull-right">
			<!-- <div class="input-group" > -->
			<input type="search" name="" class="form-control" style="padding-top:0px; padding-bottom: 0px; width: 100%;" placeholder="Search by name" id="keyword" onkeyup="load_table()">
			
		<!-- </div> -->
		</div>
	</div>
	<div class="panel-body" id="goal_table">

	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		load_table();
	});
$("#filter_form").change(function(){
			load_table();
		});
	function load_table()
	{
		var custom_filter = $("#filter_form").serialize();
		custom_filter+="&keyword="+$("#keyword").val();
		//alert(custom_filter);
		$.ajax({
			url:"<?=base_url('Target/load_goal_table')?>",
			type:'post',
			data:custom_filter,
			beforeSend:function(){
				$("#goal_table,#graph1,#graph2,#graph3,#graph4").html('<center><i class="fa fa-spinner fa-spin" style="font-size:77px;"></i><center>');
			},
			success:function(res){
				//alert(res);
				var p = JSON.parse(res);
				$("#goal_graph").hide();
				$("#goal_table").html(p.code);
				//console.log(p.count_goals);
				if(p.graph)
				{	
					$("#goal_graph").show();
					res = p.gval;
				am4core.ready(function() {
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end
			var chart = am4core.create('graph1', am4charts.XYChart)

			chart.colors.step = 2;

			chart.legend = new am4charts.Legend()
			chart.legend.position = 'top'
			chart.legend.paddingBottom = 20
			chart.legend.labels.template.maxWidth = 95

			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
			xAxis.dataFields.category = 'category'
			xAxis.renderer.cellStartLocation = 0.1
			xAxis.renderer.cellEndLocation = 0.9
			xAxis.renderer.grid.template.location = 0;

			var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
			yAxis.min = 0;

			function createSeries(value, name) {
			    var series = chart.series.push(new am4charts.ColumnSeries())
			    series.dataFields.valueY = value
			    series.dataFields.categoryX = 'category'
			    series.name = name

			    series.events.on("hidden", arrangeColumns);
			    series.events.on("shown", arrangeColumns);

			    var bullet = series.bullets.push(new am4charts.LabelBullet())
			    bullet.interactionsEnabled = false
			    bullet.dy = 30;
			    bullet.label.text = '{valueY}'
			    bullet.label.fill = am4core.color('#ffffff')

			    return series;
			}

			chart.data = [
			    {
			        category: 'Deal Values',
			        first:res.deal.target,
			        second: res.deal.forecast.value,
			        third: res.deal.achieved.value,
			    },
			]


			createSeries('first', 'Target');
			createSeries('second', 'Forecast');
			createSeries('third', 'Achieved');

			function arrangeColumns() {

			    var series = chart.series.getIndex(0);

			    var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
			    if (series.dataItems.length > 1) {
			        var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
			        var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
			        var delta = ((x1 - x0) / chart.series.length) * w;
			        if (am4core.isNumber(delta)) {
			            var middle = chart.series.length / 2;

			            var newIndex = 0;
			            chart.series.each(function(series) {
			                if (!series.isHidden && !series.isHiding) {
			                    series.dummyData = newIndex;
			                    newIndex++;
			                }
			                else {
			                    series.dummyData = chart.series.indexOf(series);
			                }
			            })
			            var visibleCount = newIndex;
			            var newMiddle = visibleCount / 2;

			            chart.series.each(function(series) {
			                var trueIndex = chart.series.indexOf(series);
			                var newIndex = series.dummyData;

			                var dx = (newIndex - trueIndex + middle - newMiddle) * delta

			                series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
			                series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
			            })
			        }
			    }
			}
			//================================
			var chart = am4core.create('graph2', am4charts.XYChart)

			chart.colors.step = 2;

			chart.legend = new am4charts.Legend()
			chart.legend.position = 'top'
			chart.legend.paddingBottom = 20
			chart.legend.labels.template.maxWidth = 95

			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
			xAxis.dataFields.category = 'category'
			xAxis.renderer.cellStartLocation = 0.1
			xAxis.renderer.cellEndLocation = 0.9
			xAxis.renderer.grid.template.location = 0;

			var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
			yAxis.min = 0;


			chart.data = [
			    {
			        category: 'Won Values',
			        first:res.won.target,
			        second: res.won.forecast.value,
			        third: res.won.achieved.value,
			    },
			]


			createSeries('first', 'Target');
			createSeries('second', 'Forecast');
			createSeries('third', 'Achieved');

			//================= 3rd graph


// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("graph3", am4charts.XYChart);

// Add percent sign to all numbers
chart.numberFormatter.numberFormat = "#";

// Add data
chart.data = [{
    "country": "Deals",
    "forecast": res.deal.forecast.info_count,
    "achieved": res.deal.achieved.info_count
}, {
    "country": "<?=display('lead')?>",
    "forecast": res.deal.forecast.enq_count,
    "achieved": res.deal.achieved.enq_count
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "country";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Forecast vs Achieved";
valueAxis.title.fontWeight = 800;
valueAxis.min = 0;
// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "forecast";
series.dataFields.categoryX = "country";
series.clustered = false;
series.tooltipText = "Forecast {categoryX}: [bold]{valueY}[/]";
series.columns.template.fill = am4core.color("#6771dc");

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "achieved";
series2.dataFields.categoryX = "country";
series2.clustered = false;
series2.columns.template.width = am4core.percent(50);
series2.tooltipText = "Achieved {categoryX}: [bold]{valueY}[/]";
series2.columns.template.fill = am4core.color("#a367dc");
series2.columns.template.stroke = am4core.color("#ffffff");

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;


//============chart 4



// Create chart instance
var chart = am4core.create("graph4", am4charts.XYChart);

// Add percent sign to all numbers
chart.numberFormatter.numberFormat = "#";

// Add data
chart.data = [{
    "country": "Deals",
    "forecast": res.won.forecast.info_count,
    "achieved": res.won.achieved.info_count
}, {
    "country": "<?=display('lead')?>",
    "forecast": res.won.forecast.enq_count,
    "achieved": res.won.achieved.enq_count
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "country";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Forecast vs Achieved";
valueAxis.title.fontWeight = 800;
valueAxis.min = 0;
// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "forecast";
series.dataFields.categoryX = "country";
series.clustered = false;
series.tooltipText = "Forecast {categoryX}: [bold]{valueY}[/]";
series.columns.template.fill = am4core.color("#6771dc");


var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "achieved";
series2.dataFields.categoryX = "country";
series2.clustered = false;
series2.columns.template.width = am4core.percent(50);
series2.tooltipText = "Achieved {categoryX}: [bold]{valueY}[/]";
series2.columns.template.fill = am4core.color("#a367dc");
series2.columns.template.stroke = am4core.color("#ffffff");

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;


			// ===========================

		}); // end am4core.ready()

					// ==================================================
				}

			},
			error:function(u,v,w)
			{
				alert(w);
			}
		});
	}
</script>



<!-- ================================== Modal -=-================================= -->
<div id="add_goal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <form onsubmit="return validate_form()" action="<?=base_url('target/save_goal')?>" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD GOAL</h4>
      </div>
      <div class="modal-body">  
      	<div class="row">     
      		<div class="form-group col-sm-12" style="padding: 4px;">  
	            <label>Goal Name<font color="red">*</font></label>       
	            <input type="text" name="goal_name" class="form-control" required>
	        </div>

            <div class="form-group col-sm-4" style="padding: 4px;">  
	            <label>Goal Period <font color="red">*</font></label>       
	            <select class="form-control"  name="goal_period" onchange="load_range(this.value)">
	            	<option value="weekly">Weekly</option>
	            	<option value="monthly">Monthly</option>
	            	<option value="quarterly">Quarterly</option>
	            	<option value="yearly" selected>Yearly</option>
	            </select>         
		    </div>  
		    <div class="col-sm-8" style="padding: 4px">
				<label>Booking Type</label>
				<select name="products[]" class="select2" multiple>
					<option value="sundry">Sundry</option>
					<option value="ftl">FTL</option>
					<?php
					// if(!empty($product_list))
					// {
					// 	foreach ($product_list as $row)
					// 	{
					// 		echo'<option value="'.$row->id.'">'.$row->country_name.'</option>';
					// 	}
					// }	
					?>
				</select>
			</div>
			</div>
		<div class="row" >
			<div class="col-sm-4" style="padding: 4px">
				<label>Deal Type</label>
				<select name="deal_type[]" class="select2" multiple>
					<option value="domestic">Domestic</option>
					<option value="saarc">SAARC</option>					
				</select>
			</div>
			<div class="col-sm-8" style="padding: 4px">
				<label>Business Type</label>
				<select name="business_type[]" class="select2" multiple>
					<option value="inward">Inward</option>
					<option value="outward">Outward</option>					
				</select>
			</div>
		</div>
		<div class="row">
            <div class="form-group">
  				<div class="col-sm-4" style="padding: 4px;">
  					<label>Time Range <font color="red">*</font></label>      
					<select class="form-control"  name="time_range" onchange="setTimeRange()" required>
		        	</select>
		        </div>
		        <div class="col-sm-4" style="padding: 4px;">
					<label>From <font color="red">*</font></label>
					<input type="date" name="period_from" class="form-control" readonly required>
				</div>
				 <div class="col-sm-4" style="padding: 4px;">
					<label>To <font color="red">*</font></label>
					<input type="date" name="period_to" class="form-control" readonly required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4" style="padding: 4px;">
				<label>Goal Type <font color="red">*</font></label>
				<select class="form-control"  name="goal_type" onchange="load_values(this.value)" required>
	            	<option value="user">User Goal</option>
	            	<option value="team">Team Goal</option>
	        	</select>
			</div>
			<div class="col-sm-8" style="padding: 4px;">
				<label><span id="goal_type_title"></span> <font color="red">*</font></label>
				<select name="target_list[]" class="form-control" onchange="Ignore = [],viewTeamTable();" required>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4" style="padding: 4px;">
				<label>Metric <font color="red">*</font></label>
				<select name="metric_type" class="form-control" required>
					<option value="">--- Select ---</option>
					<option value="freight">Freight</option>
					<option value="weight">Weight</option>
				</select>
			</div>
			<div class="col-sm-8" style="padding: 4px;">		
				<label>Target <font color="red">*</font><span id='target_metric'></span></label>

					<input type="number" name="target_value" class="form-control" onchange="viewTeamTable()" required>
			</div>
		</div>

		<div class="row TeamTableBox" style="display: none; padding:15px 0px;">
			<div class="form-group">
				<label>TARGETS BY TEAM MEMBERS</label>
			</div>
			<div class="TeamTable">
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="submit" >Add Goal</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </form>
    </div>
  </div>
</div>

<div id="edit_goal" class="modal" role="dialog">
  <div class="modal-dialog">
  </div>
</div>
<button id="editGoal" data-toggle="modal" data-target="#edit_goal" style="display: none;"></button>
<script type="text/javascript">
$("select[name='metric_type']").on('change',function(){
	if($(this).val() == 'freight'){
		$("#target_metric").text(' (In Rupees)');
	}else if($(this).val() == 'weight'){
		$("#target_metric").text(' (In Tons)');
	}
});
$(document).ready(function(){
	$(".select2").select2();
});	


load_range('yearly');
function load_range(v)
{
	var range_list = '';
		if(v=='weekly')
		{
			range_list+="<option value='1'>This Week </option><option value='2'>Next Week</option><option value='custom'>Custom period</option>";
			//<option value='3'>All weeks this month </option><option value='4'>All weeks this quarter</option>
		}
		else if(v=='monthly')
		{
			range_list+="<option value='1'>This Month </option><option value='2'>Next Month</option><option value='custom'>Custom period</option>";
			//<option value='3'>All month this quarter </option><option value='4'>All months this year</option>

		}
		else if(v=='quarterly')
		{
			range_list+="<option value='1'>This Quarter </option><option value='2'>Next Quarter</option><option value='custom'>Custom period</option>";
			//<option value='3'>All quarter this year </option><option value='4'>All weeks this quarter</option>
		}
		else if(v=='yearly')
		{
			range_list+="<option value='1'>This Year </option>";
			//<option value='2'>Next Year</option><option value='custom'>Custom period</option>
		}

	$("select[name=time_range]").html(range_list);
	setTimeRange();
}

var users = <?=$users?>;
var teams = <?=$roles?>;
function load_values(v)
{
	if(v=='user')
	{
		var sel = $("select[name='target_list[]']");
		$(sel).html('');
		$("#goal_type_title").html('Users');
		
		$(sel).attr('multiple','multiple');

		$(users).each(function(k,v){
			$(sel).append('<option value="'+v.id+'" '+(k==0?'selected':'')+'>'+v.user_name+'</option>');
		});
		$(sel).select2("destroy").select2();
	}
	else if(v=='team')
	{
		var sel = $("select[name='target_list[]']");
		$(sel).html('');
		$("#goal_type_title").html('Team');
		
		$(sel).removeAttr('multiple');

		$(teams).each(function(k,v){
			$(sel).append('<option value="'+v.id+'">'+v.role_name+'</option>');
		});
		$(sel).select2("destroy").select2();

		
	}

	viewTeamTable();
}

var temp='user';
load_values(temp);

function viewTeamTable()
{ 
	if($("select[name=goal_type]").val()=='team')
	{
		var target_for = $("select[name='target_list[]']").val();
		var target_value = $("input[name=target_value]").val();

		$.ajax({
			url:'<?=base_url('Target/divide_target')?>',
			type:'post',
			data:{'role_id':target_for,'target_value':target_value,'ignore':Ignore.toString()},
			success:function(q)
			{
				$(".TeamTableBox").show();
				$(".TeamTable").html(q);
				$('input[type=checkbox]').bootstrapToggle();
				$(document).on('change','input[type=checkbox]',function(){

					if(this.checked)
					{
						if(Ignore.indexOf(this.value) > -1)
							Ignore.splice(Ignore.indexOf(this.value),1);
					}
					else
					{
						if(Ignore.indexOf(this.value) == -1)
							Ignore.push(this.value);
					}
					viewTeamTable();
					//alert(Ignore.toString());
				});
			},
		});
	}
	else
	{
		$(".TeamTableBox").hide();
		$(".TeamTable").html('');
	}
}

function validate_form()
{
	if($("select[name=goal_type]").val()=='team')
	{
		var list = $(".target_value_input");
		var sum = 0;
		$(list).each(function(k,v){
			sum += parseInt($(v).val());
		});

		var tvalue = $("input[name=target_value]").val();
		//alert(tvalue+' '+sum);
		if(tvalue!=sum)
		{
			alert("Target Value not matched with sum of Team Members Target.")
			return false;
		}
	}
	//return false;
}

function setTimeRange()
{
	var time_range = $('select[name=time_range]').val();
	var period = $('select[name=goal_period]').val();
	
	var date_from;
	var date_to;

	var month,day,year;

	var d = new Date();

	if(time_range=='custom')
	{
		$("input[name=period_from]").val('').removeAttr('readonly');
		$("input[name=period_to]").val('').removeAttr('readonly');
		return;
	}

	if(period=='weekly')
	{
		if(time_range=='1')
		{	
			var from = manageDate(d,0,0,0);
			var to = manageDate(d,7,0,0);
		}
		else if(time_range=='2')
		{	
			d.setDate(d.getDate()+7);

			var from = manageDate(d,0,0,0);
			var to = manageDate(d,7,0,0);
		}
	}
	else if(period=='monthly')
	{
		if(time_range=='1')
		{	d.setDate(1);
			var from = manageDate(d,0,0,0);
			d.setDate(0);
			var to = manageDate(d,0,1,0);
		}
		else if(time_range=='2')
		{	
			d.setMonth(d.getMonth()+1);
			d.setDate(1);
			var from = manageDate(d,0,0,0);
			d.setDate(0)
			var to = manageDate(d,0,1,0);
		}
	}
	else if(period=='quarterly')
	{
		if(time_range=='1')
		{	d.setDate(1);
			var from = manageDate(d,0,0,0);
			d.setDate(0);
			var to = manageDate(d,0,4,0);
		}
		else if(time_range=='2')
		{	
			d.setMonth(d.getMonth()+4);
			d.setDate(1);
			var from = manageDate(d,0,0,0);
			d.setDate(0)
			var to = manageDate(d,0,4,0);
		}
	}
	else if(period=='yearly')
	{
		if(time_range=='1')
		{	d.setDate(1);
			d.setMonth(0);
			var from = manageDate(d,0,0,0);
			d.setDate(0);
			var to = manageDate(d,0,0,1);
		}
		else if(time_range=='2')
		{	
			d.setDate(1);
			d.setMonth(0);
			d.setFullYear(d.getFullYear()+1);
			var from = manageDate(d,0,0,0);
			d.setDate(0);
			var to = manageDate(d,0,0,1);
		}
	}

		$("input[name=period_from]").val(from).attr('readonly','readonly');
		$("input[name=period_to]").val(to).attr('readonly','readonly');
}

function manageDate(cur_date,day,month,year)
{
	var d = new Date(cur_date);
		d.setDate(d.getDate()+day);
		d.setMonth(d.getMonth()+month);
		d.setFullYear(d.getFullYear()+year);

		month = '' + (d.getMonth() + 1);
        day = '' + d.getDate();
        year = d.getFullYear();

        if (month.length < 2) 
	        month = '0' + month;
	    if (day.length < 2) 
	        day = '0' + day;

		return [year,month,day].join('-');
}

function view_source(t)
{
	var list = $(t).data('ids');
	if(list!='')
	{
		var url ="<?=base_url('client/deals/')?>"+btoa(list);
		window.open(url,'_blank');
	}
}


</script>