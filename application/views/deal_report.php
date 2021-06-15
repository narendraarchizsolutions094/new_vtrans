<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="https://code.highcharts.com/modules/funnel3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
.card-graph{
    min-height:250px;
    max-height:400px;
    border:1px solid;    
    margin:2px;
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
@media (min-width: 992px){
    .card-graph {
        width:32%;
    }
}
.hide_graph{
   display:none !important;    
 }
</style>
<!------ Filter Div ---------->
<div class="row" id="filter_pannel">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading no-print">
                <div class="btn-group">
                    <a class="btn btn-primary" href="<?php echo base_url("report/deal_report") ?>"> <i class="fa fa-list"></i>
                        <?php echo display('reports_list') ?> </a>                    
                </div> 
            </div>
            <div class="panel-body">
                <div class="widget-title">
                    <h3><?=$title?></h3>
                </div>
                <hr>
                <form method="post" class="lead-form" id="filter_and_save_form"
                    action="<?php echo base_url('Report/deal_report_panel') ?>">
<input name="type" id="type" value="1" hidden>
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-3">
                            <label for="inputEmail4"><?php echo display("from_date"); ?></label>
                            <input  class="form-control form-dates" id="from-date"
                                value="<?php if (!empty(set_value('from_exp'))) {echo set_value('from_exp');}?>"
                                name="from_exp" style="padding-top:0px;" type="date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("to_date"); ?></label>
                            <input   class="form-control form-dates" id="to-date"
                                value="<?php if (!empty(set_value('to_exp'))) {echo set_value('to_exp');}?>"
                                name="to_exp" style="padding-top:0px;" type="date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputEmail4"><?php echo display("update_from_date"); ?></label>
                            <input  class="form-control form-dates" id="from-date"
                                value="<?php if (!empty(set_value('updated_from_exp'))) {echo set_value('updated_from_exp');}?>"
                                name="updated_from_exp" style="padding-top:0px;" type="date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("update_to_date"); ?></label>
                            <input  class="form-control form-dates" id="to-date"
                                value="<?php if (!empty(set_value('updated_to_exp'))) {echo set_value('updated_to_exp');}?>"
                                name="updated_to_exp" style="padding-top:0px;" type="date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("employee"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="employee[]" id="employee">

                                <option><?= 'Select Here';?></option>
                                <?php foreach ($employee as $user) {?>
                                <option value="<?=$user->pk_i_admin_id?>"
                                    <?php if(!empty(set_value('employee'))){if (in_array($user->pk_i_admin_id,set_value('employee'))) {echo 'selected';}}?>>
                                    <?=$user->s_display_name . " " . $user->last_name;?> -
                                    <?=$user->s_user_email?$user->s_user_email:$user->s_phoneno;?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("status"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                            class="form-control chosen-select" name="state[]" id="state">
							
							<option><?= 'Select Here';?></option>

                                <option value="1"
                                    <?php if(!empty(set_value('state'))){ if (in_array('1',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('enquiry')?></option>
                                <option value="2"
                                    <?php if(!empty(set_value('state'))){ if (in_array('2',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('lead')?></option>
                                <option value="3"
                                    <?php if(!empty(set_value('state'))){ if (in_array('3',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('client')?></option>
<?php
    $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
    if (!empty($enquiry_separation)) {
    $enquiry_separation = json_decode($enquiry_separation, true);
    foreach ($enquiry_separation as $key => $value) {
    $ctitle = $enquiry_separation[$key]['title'];            
?>
                                <option value="<?=$key?>" <?php if(!empty(set_value('state'))){ if (in_array($key,set_value('state'))) {echo 'selected';}}?>>
								<?= $ctitle ?></option>
<?php }} ?>         
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">

                        <?php
                        $report_columns = 
                                        array(
                                              'S.No',
											  'Quatation No',
											  'Quatation Amt',
                                              'Name',
                                              'Client Name',
                                              'Business Type',
                                              'Booking Type',
											  'Deal Type',
											  'Deal Insurance',
											  'Deal Stage',
											  'Deal Status',
											  'Created By',
											  'Created Date',
											  'Updated Date',
											  'Edit Remark'
                                            );             
                        ?>
                        <div class="form-group col-md-12">

                            <label for="enq_product"><?php echo display("report_columns"); ?><label class="required"
                                    style="color:red">*</label></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select"
                                name="report_columns[]"  >

                                <option
                                    <?php if(!empty(set_value('report_columns'))){ if (in_array('S.No',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>S.No</option>
								<option
                                    <?php if(!empty(set_value('report_columns'))){ if (in_array('Quatation No',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Quatation No</option>
								<option
                                    <?php if(!empty(set_value('report_columns'))){ if (in_array('Quatation Amt',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Quatation Amt</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Name',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Name</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Client Name',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Client Name</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Business Type',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Business Type</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Booking Type',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Booking Type</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Deal Type',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Deal Type</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Deal Insurance',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Deal Insurance</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Deal Stage',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Deal Stage</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Deal Status',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Deal Status</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Created By',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Created By</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Created Date',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Created Date</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Updated Date',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Updated Date</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Edit Remark',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Edit Remark</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success"
                                id="filter_report"><?php echo display("filter"); ?></button>
                            <button type="submit" class="btn btn-primary"
                                id="filter_and_save"><?php echo display("filter_and_save"); ?></button>
                            <input type=button class="btn btn-warning"
                                onClick="location.href='<?php echo base_url('report/deal_report_panel'); ?>'" value='Reset'>
                        </div>
                    </div>
                    <br>
                </form>
                <?php
                if(!empty($post_report_columns)){ ?>
                <div style="float:right;">
                    <!--<a class='btn btn-xs  btn-primary' href='javascript:void(0)' id='show_analytics' title='Show report analytics'><i class='fa fa-bar-chart'></i></a>-->
                </div>
                <?php
                }
                ?>
                <div class='show_graphs hide_graph'>
                    <div class='row'>
                        
                        <div class='col-md-4 card-graph'>
                            <div id='source_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='process_chart' >
                            </div>                
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='stage_chart' >
                            </div>                        
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4 card-graph'>
                            <div id='status_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='user_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='product_chart' >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-md-12 table-responsive" id="showResult">
                    <table id="example" class=" table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <?php
                                  if (!empty($post_report_columns)) {
                                    foreach ($post_report_columns as $value) { ?>
                                <th><?=ucfirst($value)?></th>
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
    </div>
</div>
</div>

<!---------------------------->
<script type="text/javascript">
    $("#show_analytics").on('click',function(){
        $(this).hide();
        $(".show_graphs").removeClass('hide_graph');
        show_report_graph();
    });
    function show_report_graph(){
        if(!$(".show_graphs").hasClass('hide_graph')){    
            generate_pie_graph('source_chart','Source Wise');
            generate_pie_graph('process_chart','Process Wise');
            generate_pie_graph('stage_chart','Sales Stage Wise');
            generate_pie_graph('user_chart','Employee Wise Assigned Data');    
            generate_pie_graph('product_chart','Product/Service Wise');
            funnel_chart('status_chart','Sales Pipeline Wise');
        }    
    }
    function generate_pie_graph(elm,title){
        var url = "<?=base_url().'report/report_analitics/'?>"+elm;
        $.ajax({
            url: url,
            type: 'POST',                    
            success: function(result) {      
                result = JSON.parse(result);

                Highcharts.chart(elm, {            
                    chart: {
                        type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45                        
                            },
                        margin: [0, 0, 0, 0],
                        spacingTop: 0,
                        spacingBottom: 0,
                        spacingLeft: 0,
                        spacingRight: 0
                    },
                    title: {
                            text: ''
                        },
                    exporting: {
                        buttons: {
                            contextButton: {
                                menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
                            }
                        }
                    },
                    subtitle: {
                        text: title
                    },
                    plotOptions: {            
                        pie: {
                            //size:'40%',
                            // dataLabels: {
                            //     enabled: false
                            // },
                            innerSize: 50,
                            depth: 45
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Count',                
                        data: result
                    }]
                });
            }
        });
    }

function funnel_chart(elm,title){
    var url = "<?=base_url().'report/report_analitics_pipeline/'?>"+elm;
        $.ajax({
            url: url,
            type: 'POST',                    
            success: function(result) {      
                result = JSON.parse(result);
                Highcharts.chart(elm, {
                chart: {
                    type: 'funnel3d',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        depth: 50,
                        viewDistance: 50
                    },
                    margin: [0, 0, 0, 0],
                    spacingTop: 0,
                    spacingBottom: 0,
                    spacingLeft: 0,
                    spacingRight: 0
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: title
                },
                exporting: {
                    buttons: {
                        contextButton: {
                            menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y:,.0f})',
                        allowOverlap: false,
                        y: 10
                    },
                    neckWidth: '30%',
                    neckHeight: '25%',
                    width: '80%',
                    height: '80%'
                    }
                },
                series: [{
                    name: 'Count',
                    data: result
                }]
                });
            }
        });
}

$("#filter_and_save_form").on('submit', function(e) {
    //alert($("input[name='hier_wise']").is(":checked"));
    if ($("input[name='hier_wise']").is(":checked") && $("#employee").select2('data').length != 1) {
        alert("please select one employee for hierarchy wise report");
        e.preventDefault();
    }
});

$(document).ready(function() {
    //$("#selected-col").select2();
    $(".chosen-select").select2();
});

$(document).ready(function() {
    var d = new Date($.now());
    var report_name = 'Report_' + d.getDate() + "-" + (d.getMonth() + 1) + "-" + d.getFullYear() + " " + d
        .getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
    $('#example').DataTable({
        "processing": true,
        "scrollX": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 30, 50, 100, 500, 1000, -1],
            [10, 30, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "<?=base_url().'report/deal_report_filterdata'?>",
            "type": "POST",
        },
        "columnDefs": [{
            "orderable": false,
            "targets": 0
        }],
        "order": [
            [1, "desc"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'copyHtml5',
                title: report_name
            },
            {
                extend: 'csvHtml5',
                title: report_name
            },
            {
                extend: 'excelHtml5',
                title: report_name
            }
        ]
    });




    $("#filter_and_save").on("click", function(e) {
        // alert( $("#filter_and_save_form").serialize());
        e.preventDefault();
        var title = window.prompt("Enter Report Name");
        if (title) {
            var url = "<?=base_url().'report/create_report'?>";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'filters': $("#filter_and_save_form").serialize(),
                    'report_name': title,
                    'type': '8'
                },
                success: function(result) {
                    result = JSON.parse(result);
                    if (result.status) {
                        $("#filter_and_save_form").submit();
                    } else {
                        alert(result.msg);
                    }
                }
            });
        } else {
            alert("Report not saved");
        }
    });   

});
jQuery(function($){ //on document.ready
        $('.form-dates').datepicker({ dateFormat: 'yy-mm-dd' });
      })
</script>