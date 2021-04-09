<?php
//print_r($goal);
?><link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
var Ignore = new Array();
<?php
if($goal->goal_type=='team')
{
	$x = json_decode($goal->custom_target);
	foreach ($x as $key => $value)
	{
		if($value=='0')
			echo'Ignore.push('.$key.');';
	}
}

?>

</script>
<div class="container-fluid">
<div class="col-sm-10 col-sm-offset-1">
<form onsubmit="return validate_form()" action="<?=base_url('target/update_goal')?>" method="post">
	<input type="hidden" name="goal_id" value="<?=$goal->goal_id?>">
      	<div class="row">      
            <div class="form-group col-sm-4" style="padding: 4px;">  
	            <label>Goal Period <font color="red">*</font></label>       
	            <select class="form-control"  name="goal_period" onchange="load_range(this.value)">
	            	<option value="weekly" <?=$goal->goal_period=='weekly'?'selected':''?>>Weekly</option>
	            	<option value="monthly" <?=$goal->goal_period=='monthly'?'selected':''?>>Monthly</option>
	            	<option value="quarterly" <?=$goal->goal_period=='quarterly'?'selected':''?>>Quarterly</option>
	            	<option value="yearly" <?=$goal->goal_period=='yearly'?'selected':''?>>Yearly</option>
	            </select>         
		    </div>  
		    <div class="col-sm-8" style="padding: 4px">
				<label>Products</label>
				<?php
				$cur = explode(',', $goal->products);

				?>
				<select name="products[]" class="select2" multiple>
					<option value="sundry" <?=in_array('sundry',$cur)?'selected':''?>>Sundry</option>
					<option value="ftl" <?=in_array('ftl',$cur)?'selected':''?>>FTL</option>
					<?php
					// if(!empty($product_list))
					// {	$cur = explode(',', $goal->products);
					// 	foreach ($product_list as $row)
					// 	{
					// 		echo'<option value="'.$row->id.'" '.(in_array($row->id,$cur)?'selected':'').'>'.$row->country_name.'</option>';
					// 	}
					// }	
					?>
				</select>
			</div>
		</div>
		<div class="row" >
			<div class="col-sm-4" style="padding: 4px">
				<label>Booking Type</label>
				<?php
				$cur = explode(',', $goal->deal_type);
				?>
				<select name="deal_type[]" class="select2" multiple>
					<option value="domestic" <?=in_array('domestic',$cur)?'selected':''?>>Domestic</option>
					<option value="saarc" <?=in_array('saarc',$cur)?'selected':''?>>SAARC</option>					
				</select>
			</div>
			<div class="col-sm-8" style="padding: 4px">
				<label>Business Type</label>
				<?php
				$cur = explode(',', $goal->business_type);
				?>
				<select name="business_type[]" class="select2" multiple>
					<option value="inward" <?=in_array('inward',$cur)?'selected':''?>>Inward</option>
					<option value="outward" <?=in_array('outward',$cur)?'selected':''?>>Outward</option>					
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
					<input type="date" name="period_from" class="form-control" readonly required value="<?=$goal->date_from?>">
				</div>
				 <div class="col-sm-4" style="padding: 4px;">
					<label>To <font color="red">*</font></label>
					<input type="date" name="period_to" class="form-control" readonly required value="<?=$goal->date_to?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4" style="padding: 4px;">
				<label>Goal Type <font color="red">*</font></label>
				<select class="form-control"  name="goal_type" onchange="load_values(this.value)" required>
	            	<option value="user" <?=$goal->goal_type=='user'?'selected':''?>>User Goal</option>
	            	<option value="team" <?=$goal->goal_type=='team'?'selected':''?>>Team Goal</option>
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
					<option value="freight" <?=$goal->metric_type=='freight'?'selected':''?>>Freight</option>
					<option value="weight" <?=$goal->metric_type=='weight'?'selected':''?>>Weight</option>
				</select>
			</div>
			<div class="col-sm-8" style="padding: 4px;">
				<label>Target <font color="red">*</font></label>

					<input type="number" name="target_value" class="form-control" onchange="viewTeamTable()" value="<?=$goal->target_value?>" required>
			</div>
		</div>

		<div class="row TeamTableBox" style="display: none; padding:15px 0px;">
			<div class="form-group">
				<label>TARGETS BY TEAM MEMBERS</label>
			</div>
			<div class="TeamTable">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
	        <button class="btn btn-success" type="submit" >Save Goal</button> 
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
		</div>
</form>
</div>
</div>
<script type="text/javascript">
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
			range_list+="<option value='1'>This Year </option><option value='2'>Next Year</option><option value='custom'>Custom period</option>";
		}

	$("select[name=time_range]").html(range_list);
	//setTimeRange();
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
			var r = [0];
			var al = 0;
			if(<?=$goal->team_id?>==0)
			{
				r = "<?=$goal->goal_for?>";
				r = r.split(',');
				al = v.id;
			}
	
			$(sel).append('<option value="'+v.id+'" '+(r.includes(al)?'selected':'')+'>'+v.user_name+'</option>');
		});
		try{
		$(sel).select2("destroy").select2();
		}catch(e){alert(e);}
	}
	else if(v=='team')
	{
		var sel = $("select[name='target_list[]']");
		$(sel).html('');
		$("#goal_type_title").html('Team');
		
		$(sel).removeAttr('multiple');
		
		$(teams).each(function(k,v){

			$(sel).append('<option value="'+v.id+'" '+(<?=$goal->team_id?>==v.id?'selected':'')+'>'+v.role_name+'</option>');

		});
		try{
			$(sel).select2("destroy").select2();
		}catch(e){alert(e);}
	}
	viewTeamTable();
}

function viewTeamTable()
{
	if($("select[name=goal_type]").val()=='team')
	{
		var target_for = $("select[name='target_list[]']").val();
		var target_value = $("input[name=target_value]").val();
		//alert(target_for);
		try{
		$.ajax({
			url:'<?=base_url('Target/divide_target')?>',
			type:'post',
			data:{'role_id':target_for,'target_value':target_value,'ignore':Ignore.toString()},
			success:function(q)
			{
				$(".TeamTableBox").show();
				$(".TeamTable").html(q);
				$('input[type=checkbox]').bootstrapToggle();
				$('input[type=checkbox]').on('change',function(){
				
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
			error:function(u,v,w)
			{
				alert(w);
			}
		});
		}catch(e){alert(e);}
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
load_range("<?=$goal->goal_period?>");
$(document).ready(function(){
	$("select").select2();
	var temp='<?=$goal->goal_type?>';
	load_values(temp);
	
});	
</script>