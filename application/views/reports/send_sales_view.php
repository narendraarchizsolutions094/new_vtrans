<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sales data</title>
        <!--<link rel="shortcut icon" href="<?= base_url() ?>">-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="<?= base_url() ?>" sizes="32x32" />
    <!-- Font Awesome 4.7.0 -->
    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- semantic css -->
    <link href="<?= base_url() ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css" />
    <!-- sliderAccess css -->
    <link href="<?= base_url() ?>assets/css/jquery-ui-timepicker-addon.min.css" rel="stylesheet"
        type="text/css" />
    <!-- slider  -->
    <link href="<?= base_url() ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables CSS -->
    <link href="<?= base_url() ?>assets/datatables/css/dataTables.min.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- pe-icon-7-stroke -->
    <link href="<?= base_url() ?>assets/css/pe-icon-7-stroke.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- themify icon css -->
    <link href="<?= base_url() ?>assets/css/themify-icons.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- Pace css -->
    <link href="<?= base_url() ?>assets/css/flash.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?= base_url() ?>assets/css/custom.css?v=1.0" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <!-- jQuery  -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js?v=1.0" type="text/javascript"></script>
<script type="text/javascript">
  window.getCookie = function(name) {
  var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  if (match) return match[2];
  else return false;
}
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="https://code.highcharts.com/modules/funnel3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>
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
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!------ Filter Div ---------->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
          
            <div class="panel-body">
                <div class="widget-title text-center">             
                    <?php
                    echo " Sales Report of ".$from;
                    ?>       
                </div>
                <hr>
             
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
             
                <div class="form-group col-md-12 table-responsive" id="showResult">
                    <table id="example" class=" table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <?php
                                  if (!empty($report_columns)) {
                                    foreach ($report_columns as $value) { ?>
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
 
            generate_pie_graph('source_chart','Source Wise');
            generate_pie_graph('process_chart','Process Wise');
            generate_pie_graph('stage_chart','Sales Stage Wise');
            generate_pie_graph('user_chart','Employee Wise Assigned Data');    
            generate_pie_graph('product_chart','Product/Service Wise');
            funnel_chart('status_chart','Sales Pipeline Wise');

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
            [-1],
            ["All"]
        ],
        "ajax": {
            "url": "<?=base_url().'report/all_report_filterdata'?>",
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

});

</script>
<script>
    $('#something').click(function() {
        //alert('hi');  
        //document.location = '';
    });
    </script>
    <!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-database.js"></script>

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
    <script src="<?= base_url() ?>assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="<?= base_url() ?>assets/js/tableHeadFixer.js?v=1.0" type="text/javascript"></script>
    <!-- Admin Script -->
    <script src="<?= base_url() ?>assets/js/frame.js?v=1.0?v=1.0" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url() ?>assets/js/custom.js?v=1.0.1" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
</body>

</html>
