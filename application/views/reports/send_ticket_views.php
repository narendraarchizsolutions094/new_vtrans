<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Data</title>
    <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="<?= base_url() ?>" sizes="32x32" />
    <!-- Font Awesome 4.7.0 -->
    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- semantic css -->
    <link href="<?= base_url() ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/datatables/css/dataTables.min.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- pe-icon-7-stroke -->
    <link href="<?= base_url() ?>assets/css/pe-icon-7-stroke.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- themify icon css -->
    <link href="<?= base_url() ?>assets/css/themify-icons.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- Pace css -->
    <link href="<?= base_url() ?>assets/css/flash.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?= base_url() ?>assets/css/custom.css?v=1.0" rel="stylesheet" type="text/css" />
    <script src="<?= base_url() ?>assets/js/jquery.min.js?v=1.0" type="text/javascript"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/cylinder.js"></script>
    <script src="https://code.highcharts.com/modules/funnel3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
    #chartdiv_substage {
        width: 100%;
        height: 550px;
        margin-left: auto;
    }

    .card-graph {
        min-height: 250px;
        max-height: 400px;
        border: 1px solid;
        margin: 2px;
        box-shadow: 0px 0px 7px -1px;
        border-radius: 6px;
        border-color: transparent;
        overflow: hidden;
    }

    @media (min-width: 992px) {
        .card-graph {
            width: 32%;
        }
    }

    .hide_graph {
        display: none !important;
    }
    </style>
    <!-- Styles -->
    <style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <a target="_BLANK" class="btn btn-primary"
                    href="<?=base_url().'ticket/daily_summary/141?date='.date('Y-m-d',strtotime('-1 days'))?>"> <i
                        class="fa fa-arrow-right"></i> Ticket Summary </a>
            </div>
            <div class="row text-center">
                <?php 
        echo " Ticket Report of  <b>".$fromdate.' </b>';
        ?>
            </div>
            <div class="row">
                <div class='' style=" margin-top:50px; padding: 10px;">
                    <div class='row'>

                        <div class='col-md-4 card-graph'>
                            <div id='source_chart'>
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='process_chart'>
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='stage_chart'>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4 card-graph'>
                            <div id='user_chart'>
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='product_chart'>
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='container'>
                            </div>
                        </div>



                    </div>
                    <div class="row pd-20" style="width:100%;">
                        <div class="col-md-12">
                            <div class="card card-graph_full2"><br>
                                <center>
                                    <h3>Failure Point Wise Ticket</h3>
                                </center>
                                <div id="chartdiv7"></div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <center>
                        <h3>Substage Wise Ticket</h3>
                    </center>

                    <div class="row pd-20" style="width:100%;">
                        <div class="col-md-12 pd-20" style="padding: 10px;">
                            <div id="chartdiv_substage"></div>
                        </div>
                    </div>

                    <br>
                </div>
                <div class="">
                    <div class="panel-body">
                        <?php


        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
 ?>
                        <!-- Filter Panel End -->
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-12">
                                <table id="ticket_table" class=" table table-striped table-bordered"
                                    style="width:100%;">
                                    <thead>
                                        <th class="noExport sorting_disabled">
                                            <input type='checkbox' class="checked_all1" value="check all">
                                        </th>
                                        <th>S.No.</th>
                                        <?=($showall or in_array(1,$acolarr))?'<th>Ticket</th>':''?>

                                        <?php
                      if($this->session->comp_id==65)
                      {
                      ?>
                                        <?=($showall or in_array(15,$acolarr))?'<th>'.display('tracking_no').'</th>':''?>
                                        <?php
                      }
                      ?>
                                        <?=($showall or in_array(7,$acolarr))?'<th>Created By</th>':''?>
                                        <?=($showall or in_array(9,$acolarr))?'<th>Created Date</th>':''?>
                                        <?=($showall or in_array(18,$acolarr))?'<th>'.display('last_updated').'</th>':''?>
                                        <?=($showall or in_array(2,$acolarr))?'<th>Client</th>':''?>
                                        <?=($showall or in_array(3,$acolarr))?'<th>Email</th>':''?>
                                        <?=($showall or in_array(4,$acolarr))?'<th>Phone</th>':''?>
                                        <?=($showall or in_array(5,$acolarr))?'<th>Product</th>':''?>
                                        <?=($showall or in_array(6,$acolarr))?'<th>Assign To</th>':''?>
                                        <?=($showall or in_array(17,$acolarr))?'<th>Assign By</th>':''?>
                                        <?=($showall or in_array(8,$acolarr))?'<th>Priority</th>':''?>
                                        <?=($showall or in_array(19,$acolarr))?'<th>'.display('ticket_problem').'</th>':''?>
                                        <?=($showall or in_array(10,$acolarr))?'<th>Referred By</th>':''?>
                                        <?=($showall or in_array(11,$acolarr))?'<th>'.display('data_source').'</th>':''?>
                                        <?=($showall or in_array(12,$acolarr))?'<th>'.display('stage').'</th>':''?>
                                        <?=($showall or in_array(13,$acolarr))?'<th>Sub Stage</th>':''?>
                                        <?=($showall or in_array(14,$acolarr))?'<th>Review</th>':''?>
                                        <?=($showall or in_array(16,$acolarr))?'<th>Status</th>':''?>
                                        <?php 
                      if(!empty($dacolarr) and !empty($dfields))
                      {
                        foreach($dfields as $ind => $flds)
                        {                
                          if(!empty(in_array($flds->input_id, $dacolarr )))
                          {            
                          ?><th><?php echo $flds->input_label; ?></th><?php 
                          }
                        }
                       } ?>

                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </form>
                        <!--<?php echo form_close(); ?>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------TABLE COLOUMN CONFIG----------------------------------------------->
    <script type="text/javascript">
    generate_pie_graph('source_chart', 'Source Wise');
    generate_pie_graph('process_chart', 'Process Wise');
    generate_pie_graph('stage_chart', 'Stage Wise');
    generate_pie_graph('user_chart', 'Employee Wise Assigned Data');
    generate_pie_graph('product_chart', 'Product/Service Wise');
    var send_data = '<?php 
             $filters['from_created']=$fromdate;
             $filters['to_created']=$fromdate;
             echo json_encode($filters);
            ?>';

    function generate_pie_graph(elm, title) {
        var send_data = '<?php 
             $filters['from_created']=$fromdate;
             $filters['to_created']=$fromdate;
             echo json_encode($filters);
            ?>';
        // $form_data= serialize;
        var url = "<?=base_url().'report/ticket_report_analitics/'?>" + elm;
        $.ajax({
            url: url,
            type: 'POST',
            data: send_data,
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
    // function prgenerate_pie_graph(elm,title){
    // $form_data= serialize;
    var url = "<?=base_url().'report/prticket_report_analitics/'?>";
    $.ajax({
        url: url,
        type: 'POST',
        data: send_data,
        success: function(result) {
            result = JSON.parse(result);

            Highcharts.chart('container', {
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
                    text: 'Priority Wise'
                },
                plotOptions: {
                    pie: {
                       innerSize: 50,
                        depth: 45
                    }
                },
                series: [{
                    name: 'Priority Wise',
                    colorByPoint: true,
                    data: result
                }]
            });
        }
    });
    // }
    </script>
    <script>
    function select_all() {
        var select_all = document.getElementById("selectall"); //select all checkbox
        var checkboxes = document.getElementsByClassName("choose-col"); //checkbox items
        var dcheckboxes = document.getElementsByClassName("dchoose-col"); //checkbox items
        //select all checkboxes
        select_all.addEventListener("change", function(e) {
            for (i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = select_all.checked;
            }
            for (i = 0; i < dcheckboxes.length; i++) {
                dcheckboxes[i].checked = select_all.checked;
            }
        });
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function(e) {
                if (this.checked == false) {
                    select_all.checked = false;
                }
                if (document.querySelectorAll('.choose-col:checked').length == checkboxes.length) {
                    select_all.checked = true;
                }
            });
        }

        for (var i = 0; i < dcheckboxes.length; i++) {

            dcheckboxes[i].addEventListener('change', function(e) { //".checkbox" change 
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) {
                    select_all.checked = false;
                }
                //check "select all" if all checkbox items are checked
                if (document.querySelectorAll('.dchoose-col:checked').length == dcheckboxes.length) {
                    select_all.checked = true;
                }
            });
        }
    }
    </script>
    <script type="text/javascript">
    $(document).on("click", ".set-col-table", function(e) {
        e.preventDefault();
        if ($(".choose-col:checked").length == 0 && $(".dchoose-col:checked").length == 0) {
            return false;
        }
        var chkval = "";
        $(".choose-col:checked").each(function() {
            chkval += $(this).val() + ",";
        });
        var dchkval = "";
        $(".dchoose-col:checked").each(function() {
            dchkval += $(this).val() + ",";
        });
        document.cookie = "ticket_allowcols=" + chkval + "; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
        document.cookie = "ticket_dallowcols=" + dchkval + "; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
        location.reload();
    });




    function reset_input() {
        $('input:checkbox').removeAttr('checked');
    }
    </script>

    <script type="text/javascript">
    <?php  
  $_POST=$filters;
  if(!empty($_POST)){
  ?>
    $(document).ready(function() {
        $('#ticket_table').DataTable({
            "processing": true,
            "scrollX": true,
            "scrollY": 800,
            "serverSide": true,
            "lengthMenu": [
                [10, 30, 50, 100, 500, 1000, -1],
                [10, 30, 50, 100, 500, 1000, "All"]
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": 0
            }],
            "order": [
                [1, "desc"]
            ],
            "ajax": {
                "url": "<?=base_url().'Ticket/report_ticket_load_data'?>",
                "type": "POST",
                error: function(u, v, w) {
                    alert(w);
                }
            },
            <?php if(user_access(317)) { ?>
            dom: "<'row text-center'<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp",
            buttons: [{
                    extend: 'copy',
                    className: 'btn-xs btn',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'csv',
                    title: 'list<?=date("Y-m-d H:i:s")?>',
                    className: 'btn-xs btn',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'excel',
                    title: 'list<?=date("Y-m-d H:i:s")?>',
                    className: 'btn-xs btn',
                    title: 'exportTitle',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'pdf',
                    title: 'list<?=date("Y-m-d H:i:s")?>',
                    className: 'btn-xs btn',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'print',
                    className: 'btn-xs btn',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ] <?php  } ?>
        });
    });
    <?php
  }
  ?>


    $(document).ready(function() {
        $.ajax({
            url: "<?=base_url('ticket/send_failurepoint_ticketJson/'.$fromdate.'/'.$fromdate.'')?>",
            type: "post",
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                am4core.ready(function() {

                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("chartdiv7", am4charts.XYChart);
                    chart.scrollbarX = new am4core.Scrollbar();

                    // Add data
                    chart.data = response;

                    // Create axes
                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "name";
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.renderer.minGridDistance = 30;
                    categoryAxis.renderer.labels.template.horizontalCenter = "right";
                    categoryAxis.renderer.labels.template.verticalCenter = "middle";
                    categoryAxis.renderer.labels.template.rotation = 270;
                    categoryAxis.tooltip.disabled = true;
                    categoryAxis.renderer.minHeight = 110;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.renderer.minWidth = 50;

                    // Create series
                    var series = chart.series.push(new am4charts.ColumnSeries());
                    series.sequencedInterpolation = true;
                    series.dataFields.valueY = "value";
                    series.dataFields.categoryX = "name";
                    series.tooltipText = "[{name}: bold]{value}[/]";
                    series.columns.template.strokeWidth = 0;

                    series.tooltip.pointerOrientation = "vertical";

                    series.columns.template.column.cornerRadiusTopLeft = 10;
                    series.columns.template.column.cornerRadiusTopRight = 10;
                    series.columns.template.column.fillOpacity = 0.8;

                    // on hover, make corner radiuses bigger
                    var hoverState = series.columns.template.column.states.create(
                        "hover");
                    hoverState.properties.cornerRadiusTopLeft = 0;
                    hoverState.properties.cornerRadiusTopRight = 0;
                    hoverState.properties.fillOpacity = 1;

                    series.columns.template.adapter.add("fill", function(fill, target) {
                        return chart.colors.getIndex(target.dataItem.index);
                    });

                    // Cursor
                    chart.cursor = new am4charts.XYCursor();

                }); // end am4core.ready()
            }
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: "<?=base_url('ticket/send_subsource_typeJson/'.$fromdate.'/'.$todate.'')?>",
            type: "post",
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                am4core.ready(function() {

                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("chartdiv_substage", am4charts.XYChart);

                    // Add data
                    chart.data = response.result;
                    console.log(response.result);
                    // Create axes
                    var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
                    yAxis.dataFields.category = "state";
                    yAxis.renderer.grid.template.location = 0;
                    yAxis.renderer.labels.template.fontSize = 10;
                    yAxis.renderer.minGridDistance = 6;

                    var xAxis = chart.xAxes.push(new am4charts.ValueAxis());

                    // Create series
                    var series = chart.series.push(new am4charts.ColumnSeries());
                    series.dataFields.valueX = "sales";
                    series.dataFields.categoryY = "state";
                    series.columns.template.tooltipText =
                        "{categoryY}: [bold]{valueX}[/]";
                    series.columns.template.strokeWidth = 0;
                    series.columns.template.adapter.add("fill", function(fill, target) {
                        if (target.dataItem) {
                            return chart.colors.getIndex(1);
                        }
                        return fill;
                    });

                    var axisBreaks = {};
                    var legendData = [];

                    // Add ranges
                    function addRange(label, start, end, color) {
                        var range = yAxis.axisRanges.create();
                        range.category = start;
                        range.endCategory = end;
                        range.label.text = label;
                        range.label.disabled = false;
                        range.label.fill = color;
                        range.label.location = 0;
                        range.label.dx = -200;
                        range.label.dy = 12;
                        range.label.fontWeight = "bold";
                        range.label.fontSize = 10;
                        range.label.horizontalCenter = "left"
                        range.label.inside = true;

                        range.grid.stroke = am4core.color("#396478");
                        range.grid.strokeOpacity = 1;
                        range.tick.length = 200;
                        range.tick.disabled = false;
                        range.tick.strokeOpacity = 0.6;
                        range.tick.stroke = am4core.color("#396478");
                        range.tick.location = 0;

                        range.locations.category = 1;
                        var axisBreak = yAxis.axisBreaks.create();
                        axisBreak.startCategory = start;
                        axisBreak.endCategory = end;
                        axisBreak.breakSize = 1;
                        axisBreak.fillShape.disabled = true;
                        axisBreak.startLine.disabled = true;
                        axisBreak.endLine.disabled = true;
                        axisBreaks[label] = axisBreak;
                        legendData.push({
                            name: label,
                            fill: color
                        });
                    }
                    group = response.group;
                    console.log(group);
                    $.each(group, function(i, item) {
                        addRange(group[i][0], group[i][2], group[i][1], chart
                            .colors.getIndex(i));
                        console.log(group[i]);
                        console.log(group[i][0]);
                    });
                    // addRange("East", "New York", "West Virginia", chart.colors.getIndex(1));
                    // addRange("South", "Florida", "South Carolina", chart.colors.getIndex(2));
                    // addRange("West", "California", "Wyoming", chart.colors.getIndex(3));
                    chart.cursor = new am4charts.XYCursor();
                    var legend = new am4charts.Legend();
                    legend.position = "right";
                    legend.scrollable = true;
                    legend.valign = "top";
                    legend.reverseOrder = true;
                    chart.legend = legend;
                    legend.data = legendData;
                    legend.itemContainers.template.events.on("toggled", function(
                        event) {
                        var name = event.target.dataItem.dataContext.name;
                        var axisBreak = axisBreaks[name];
                        if (event.target.isActive) {
                            axisBreak.animate({
                                property: "breakSize",
                                to: 0
                            }, 1000, am4core.ease.cubicOut);
                            yAxis.dataItems.each(function(dataItem) {
                                if (dataItem.dataContext.region ==
                                    name) {
                                    dataItem.hide(1000, 500);
                                }
                            })
                            series.dataItems.each(function(dataItem) {
                                if (dataItem.dataContext.region ==
                                    name) {
                                    dataItem.hide(1000, 0, 0, [
                                        "valueX"
                                    ]);
                                }
                            })
                        } else {
                            axisBreak.animate({
                                property: "breakSize",
                                to: 1
                            }, 1000, am4core.ease.cubicOut);
                            yAxis.dataItems.each(function(dataItem) {
                                if (dataItem.dataContext.region ==
                                    name) {
                                    dataItem.show(1000);
                                }
                            })
                            series.dataItems.each(function(dataItem) {
                                if (dataItem.dataContext.region ==
                                    name) {
                                    dataItem.show(1000, 0, ["valueX"]);
                                }
                            })
                        }
                    })
                }); // end am4core.ready()
            }
        });
    });
    </script>
    <!-- jquery-ui js -->
    <script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>
    <!-- DataTables JavaScript -->
    <script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>
   
    <script>
    $('#something').click(function() {
        //alert('hi');  
        //document.location = '';
    });
    </script>

    <script src="<?= base_url() ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <!---- new js file added by pp ------------->
    <script src="<?= base_url() ?>assets/js/dashboard_js.js" type="text/javascript"></script>
    <!----------------------------------------------------------------------------------------------->
    <!-- bootstrap js -->
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- bootstrap timepicker -->
    <script src="<?= base_url() ?>assets/js/jquery-ui-sliderAccess.js?v=1.0" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/select2.min.js?v=1.0" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/sparkline.min.js?v=1.0" type="text/javascript"></script>

    <!-- ChartJs JavaScript -->
    <script src="<?= base_url() ?>assets/js/Chart.min.js?v=1.0" type="text/javascript"></script>
    <!-- semantic js -->
    <script src="<?= base_url() ?>assets/js/semantic.min.js?v=1.0" type="text/javascript"></script>
    <!-- DataTables JavaScript -->
    <script src="<?= base_url() ?>assets/datatables/js/dataTables.min.js?v=1.0"></script>
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>     -->
    <script src="<?= base_url() ?>assets/js/tableHeadFixer.js?v=1.0" type="text/javascript"></script>
    <!-- Admin Script -->
    <script src="<?= base_url() ?>assets/js/frame.js?v=1.0?v=1.0" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url() ?>assets/js/custom.js?v=1.0.1" type="text/javascript"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    </body>

</html>