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
        <!-- jQuery  -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js?v=1.0" type="text/javascript"></script>
<script type="text/javascript">
  window.getCookie = function(name) {
  var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  if (match) return match[2];
  else return false;
}
</script>

</head>
<style>
    td{
        text-align: center;
    }
    th{
        text-align: center;
    }
.card-graph{
    min-height:250px;
    max-height:800px;
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
.selected_region{
    padding:10px 10px;
}

</style>

<!------ Filter Div ---------->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
           
            <div class="panel-body">
                <div class="widget-title text-center">             
                    <?php
                    echo " Sales Report of ".$this->session->from1;
                    ?>       
                </div>
                <hr>
                
                <div class="form-group col-md-12 row">
                    <a href="<?=$fullURL?>" class="btn btn-primary btn-sm change-color" style="margin-bottom:4px"><div class="col-sm-1" style="font-size:8px;">All</div></a>
                    <?php foreach($emp_region as $key => $region){
                        $selected_class = '';
                        if(!empty($_GET['region']) && $region['region_id'] == $_GET['region']){
                            $selected_class = 'selected_region';
                        }
                        ?>

                        <a  style="margin-bottom:4px" href="<?=$fullURL.'?region='.$region['region_id'] ?>" class="btn btn-primary btn-sm <?=$selected_class?>"><div class="col-sm-1" style="font-size:8px;"><?= $region['name'];?></div></a>
                    <?php }?>
                </div> 



                <div class="form-group col-md-12 table-responsive visit_data" id="showResult1">
                    <table id="example1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Today</th>
                                <th>Yesterday</th>
                                <th>This Week</th>
                                <th>Last Week</th>
                                <th>This Month</th>
                                <th>Last Month</th>
                                <th>Total - Till Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Visits</th>
                                <th><?= round($visit_data['today_call']);?></th>
                                <th><?= round($visit_data['yesterday_call']);?></th>
                                <th><?= round($visit_data['this_week']);?></th>
                                <th><?= round($visit_data['last_week']);?></th>
                                <th><?= round($visit_data['this_month_call']);?></th>
                                <th><?= round($visit_data['last_month_call']);?></th>
                                <th><?= round($visit_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Lead</th>
                                <th><?= round($prospect_data['today_call']);?></th>
                                <th><?= round($prospect_data['yesterday_call']);?></th>
                                <th><?= round($prospect_data['this_week']);?></th>
                                <th><?= round($prospect_data['last_week']);?></th>
                                <th><?= round($prospect_data['this_month_call']);?></th>
                                <th><?= round($prospect_data['last_month_call']);?></th>
                                <th><?= round($prospect_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Approach</th>
                                <th><?= round($approach_data['today_call']);?></th>
                                <th><?= round($approach_data['yesterday_call']);?></th>
                                <th><?= round($approach_data['this_week']);?></th>
                                <th><?= round($approach_data['last_week']);?></th>
                                <th><?= round($approach_data['this_month_call']);?></th>
                                <th><?= round($approach_data['last_month_call']);?></th>
                                <th><?= round($approach_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Negotiation</th>
                                <th><?= round($negotiation_data['today_call']);?></th>
                                <th><?= round($negotiation_data['yesterday_call']);?></th>
                                <th><?= round($negotiation_data['this_week']);?></th>
                                <th><?= round($negotiation_data['last_week']);?></th>
                                <th><?= round($negotiation_data['this_month_call']);?></th>
                                <th><?= round($negotiation_data['last_month_call']);?></th>
                                <th><?= round($negotiation_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Closure</th>
                                <th><?= round($closure_data['today_call']);?></th>
                                <th><?= round($closure_data['yesterday_call']);?></th>
                                <th><?= round($closure_data['this_week']);?></th>
                                <th><?= round($closure_data['last_week']);?></th>
                                <th><?= round($closure_data['this_month_call']);?></th>
                                <th><?= round($closure_data['last_month_call']);?></th>
                                <th><?= round($closure_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Order</th>
                                <th><?= round($signings_data['today_call']);?></th>
                                <th><?= round($signings_data['yesterday_call']);?></th>
                                <th><?= round($signings_data['this_week']);?></th>
                                <th><?= round($signings_data['last_week']);?></th>
                                <th><?= round($signings_data['this_month_call']);?></th>
                                <th><?= round($signings_data['last_month_call']);?></th>
                                <th><?= round($signings_data['all_call']);?></th>
                            </tr>
                            <tr>
                                <th>Future opportunities</th>
                                <th><?= round($future_opp_data['today_call']);?></th>
                                <th><?= round($future_opp_data['yesterday_call']);?></th>
                                <th><?= round($future_opp_data['this_week']);?></th>
                                <th><?= round($future_opp_data['last_week']);?></th>
                                <th><?= round($future_opp_data['this_month_call']);?></th>
                                <th><?= round($future_opp_data['last_month_call']);?></th>
                                <th><?= round($future_opp_data['all_call']);?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                    <div class='row'>                        
                        <div class='col-md-4 card-graph' style="height: 400px; width: 50%;">
                            <div id='chartContainer2' style="height: 400px; width: 100%;">
                            </div>
                        </div>
                        <div class='col-md-4 card-graph' style="width: 49%;">
                            <div id='chartContainer1' style="height: 400px; width: 100%;">
                            </div>
                        </div>                        
                    </div>
                    <div class='row'>                        
                        <div class='col-md-4 card-graph' style="width: 100%;">
                            <div id='chartContainer4' style="height: 800px; width: 100%;">
                            </div>                        
                        </div>
                    </div>

                    
                </div>
                <div class='row'>
                    <div class="form-group col-md-12 table-responsive" id="new_data">                    
                    </div>
                    <div class="form-group col-md-12 table-responsive" id="new_data2">
                    </div>
                </div>
        </div>
    </div>
</div>
</div>

<!---------------------------->
<?php
$dataPoints1 = array( 
	array("Lead",$nad_count),
	array("Approach",$prospect_count),
	array("Negotiations",$approach_count),
	array("Closure",$negotiations_count),
	array("Order",$closure_count),
	array("Future Opportunities",$order_count)
	
);
?>

<?php
$lead_source = $this->db->get_where('lead_source')->result_array();
$dataPoints2_source = array();
$dataPoints2_value = array();
foreach($lead_source as $key => $source){
    if(!empty($get_ids)){                            
        $this->db->where_in('created_by',$get_ids);
    }
    $lead_data = $this->db->where(array('enquiry_source' => $source['lsid']))->from('enquiry')->count_all_results();
    array_push($dataPoints2_source,$source['lead_name']);
    array_push($dataPoints2_value,$lead_data);
}
    // echo "<pre>";
    // print_r($dataPoints2);
    // echo "</pre>";
?>

<?php
$ignore = array(4);
$this->db->where_not_in('stage_for',$ignore);
$lead_stage = $this->db->get_where('lead_stage')->result_array();
$dataPoints3 = array();
    foreach($lead_stage as $key => $stage){
        if(!empty($get_ids)){                            
            $this->db->where_in('created_by',$get_ids);
        }
        $lead_stage = $this->db->where(array('lead_stage' => $stage['stg_id']))->from('enquiry')->count_all_results();
        array_push($dataPoints3,array("label" => $stage['lead_stage_name'], "symbol" => substr($stage['lead_stage_name'], 0, 2),"y" => $lead_stage));
    }
?>


<?php
if(!empty($region_filter_id)){    
    $this->db->where('sales_region',$region_filter_id);
}
$user_data = $this->db->get_where('tbl_admin',array('dept_name'=>1,'b_status'=>1))->result_array();
$dataPoints4 = array();
    foreach($user_data as $key => $user){
        $lead_emp_data = $this->db->where('created_by',$user['pk_i_admin_id'])->or_where('aasign_to',$user['pk_i_admin_id'])->from('enquiry')->count_all_results();
        array_push($dataPoints4,array("name" => $user['s_display_name'].' '.$user['last_name'],"y" => (int)$lead_emp_data));
    }
    $dataPoints4[0]['sliced'] = 'true';
    $dataPoints4[0]['selected'] = 'true';
    // echo "<pre>";
    // print_r($dataPoints4);
    // echo "</pre>";
?>

<script>
 window.onload = function() {
// var chart1 = new CanvasJS.Chart("chartContainer1", {
// 	theme: "light",
// 	animationEnabled: true, 
//     animationDuration: 2000,  
// 	title: {
// 		text: "Sales Pipeline Wise",
//         fontSize: 12,
//         fontColor: "#666666",
//         fontFamily:"Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif",
// 	},
//     axisY: {
// 		title: "Total Leads"
// 	},
//     axisX: {
// 		title: "Data Source"
// 	},
//     exportFileName: "Sales Pipeline Wise",  //Give any name accordingly
// 	exportEnabled: true,
// 	data: [{
// 		type: "funnel",
// 		indexLabel: "{label} - {y}",
//         neckHeight: "0%",
//         neckWidth: "15%",
// 		yValueFormatString: "#,##0",
// 		showInLegend: true,
// 		legendText: "{label}",
// 		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
// 	}]
// });
// chart1.render();



Highcharts.chart('chartContainer1', {
    chart: {
        type: 'funnel'
    },
    title: {
        text: 'Sales funnel'
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y:,.0f})',
                softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '30%',
            neckHeight: '25%',
            width: '80%'
        }
    },
    legend: {
        enabled: false
    },
    credits: {
     enabled: false
    },
    series: [{
        name: 'Unique users',
        data: <?php echo json_encode($dataPoints1); ?>
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                plotOptions: {
                    series: {
                        dataLabels: {
                            inside: true
                        },
                        center: ['50%', '50%'],
                        width: '100%'
                    }
                }
            }
        }]
    }
});
           

// var chart2 = new CanvasJS.Chart("chartContainer2", {
// 	theme: "light",
// 	animationEnabled: true, 
//     animationDuration: 2000,  
// 	title: {
// 		text: "Source Wise Chart",
//         fontSize: 12,
//         fontColor: "#666666",
//         fontFamily:"Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif",
// 	},
   
//     exportFileName: "Source Wise Chart",  //Give any name accordingly
//     exportEnabled: true,
// 	data: [{
// 		type: "column",
// 		yValueFormatString: "#,##0.## leads",
//         neckHeight: "0%",
//         neckWidth: "100%",
// 		showInLegend: false,
// 		dataPoints: <?php// echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
// 	}]
// });
// chart2.render();



const chart = Highcharts.chart('chartContainer2', {
    title: {
        text: 'Source Wise Chart'
    },
    subtitle: {
        text: 'Plain'
    },
    xAxis: {
        categories: <?=json_encode($dataPoints2_source)?>
    },
    credits: {
     enabled: false
    },
    series: [{
        type: 'column',
        colorByPoint: true,
        data: <?=json_encode($dataPoints2_value)?>,
        showInLegend: false
    }]
});
// var chart3 = new CanvasJS.Chart("chartContainer3", {
// 	theme: "light2",
// 	animationEnabled: true,
// 	title: {
// 		text: "Stage Wise Data",
//         fontSize: 12,
//         fontColor: "#666666",
//         fontFamily:"Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif",
// 	},
//     exportFileName: "Source Wise Chart",  //Give any name accordingly
//     exportEnabled: true,
// 	data: [{
// 		type: "doughnut",
// 		indexLabel: "{symbol} - {y}",
// 		yValueFormatString: "#,##0\"\"",
//         neckHeight: "0%",
//         neckWidth: "100%",
// 		showInLegend: true,
// 		legendText: "{label} : {y}",
// 		dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
// 	}]
// });
// chart3.render();


// var chart4 = new CanvasJS.Chart("chartContainer4", {
// 	animationEnabled: true,
// 	title: {
// 		text: "Sales Employee Wise Data",
//         fontSize: 12,
//         fontColor: "#666666",
//         fontFamily:"Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif",
// 	},
//     exportFileName: "Source Wise Chart",  //Give any name accordingly
//     exportEnabled: true,
// 	data: [{
// 		type: "pie",
// 		yValueFormatString: "#,##0\"\"",
// 		indexLabel: "{label} ({y})",
//         neckHeight: "10%",
//         neckWidth: "100%",
// 		showInLegend: false,
//         legendText: "{label} : {y}",
// 		dataPoints: <?php echo json_encode($dataPoints4); ?>
// 	}]
// });
// chart4.render();





Highcharts.chart('chartContainer4', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Sales Employee Wise Data'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },    
    credits: {
     enabled: false
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y} '
            },
            showInLegend: true            
        }
    },
    series: [{
        name: 'Employees',
        colorByPoint: true,
        data: <?php echo json_encode($dataPoints4); ?>
    }]
});
              








}
</script>



<script type="text/javascript">
 
//             generate_pie_graph('source_chart','Source Wise');
//             generate_pie_graph('process_chart','Process Wise');
//             generate_pie_graph('stage_chart','Sales Stage Wise');
//             generate_pie_graph('user_chart','Employee Wise Assigned Data');    
//             generate_pie_graph('product_chart','Product/Service Wise');
//             funnel_chart('status_chart','Sales Pipeline Wise');

//     function generate_pie_graph(elm,title){
//         var url = "<?=base_url().'report/report_analitics/'?>"+elm;
//         $.ajax({
//             url: url,
//             type: 'POST',                    
//             success: function(result) {      
//                 result = JSON.parse(result);

//                 Highcharts.chart(elm, {            
//                     chart: {
//                         type: 'pie',
//                             options3d: {
//                                 enabled: true,
//                                 alpha: 45                        
//                             },
//                         margin: [0, 0, 0, 0],
//                         spacingTop: 0,
//                         spacingBottom: 0,
//                         spacingLeft: 0,
//                         spacingRight: 0
//                     },
//                     title: {
//                             text: ''
//                         },
//                     exporting: {
//                         buttons: {
//                             contextButton: {
//                                 menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
//                             }
//                         }
//                     },
//                     subtitle: {
//                         text: title
//                     },
//                     plotOptions: {            
//                         pie: {
//                             //size:'40%',
//                             // dataLabels: {
//                             //     enabled: false
//                             // },
//                             innerSize: 50,
//                             depth: 45
//                         }
//                     },
//                     credits: {
//                         enabled: false
//                     },
//                     series: [{
//                         name: 'Count',                
//                         data: result
//                     }]
//                 });
//             }
//         });
//     }

// function funnel_chart(elm,title){
//     var url = "<?=base_url().'report/report_analitics_pipeline/'?>"+elm;
//         $.ajax({
//             url: url,
//             type: 'POST',                    
//             success: function(result) {      
//                 result = JSON.parse(result);
//                 Highcharts.chart(elm, {
//                 chart: {
//                     type: 'funnel3d',
//                     options3d: {
//                         enabled: true,
//                         alpha: 10,
//                         depth: 50,
//                         viewDistance: 50
//                     },
//                     margin: [0, 0, 0, 0],
//                     spacingTop: 0,
//                     spacingBottom: 0,
//                     spacingLeft: 0,
//                     spacingRight: 0
//                 },
//                 title: {
//                     text: ''
//                 },
//                 subtitle: {
//                     text: title
//                 },
//                 exporting: {
//                     buttons: {
//                         contextButton: {
//                             menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
//                         }
//                     }
//                 },
//                 credits: {
//                     enabled: false
//                 },
//                 plotOptions: {
//                     series: {
//                     dataLabels: {
//                         enabled: true,
//                         format: '<b>{point.name}</b> ({point.y:,.0f})',
//                         allowOverlap: false,
//                         y: 10
//                     },
//                     neckWidth: '30%',
//                     neckHeight: '25%',
//                     width: '80%',
//                     height: '80%'
//                     }
//                 },
//                 series: [{
//                     name: 'Count',
//                     data: result
//                 }]
//                 });
//             }
//         });
// }

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

// $(document).ready(function() {
//     var d = new Date($.now());
//     var report_name = 'Report_' + d.getDate() + "-" + (d.getMonth() + 1) + "-" + d.getFullYear() + " " + d
//         .getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
//     var table = $('#example').DataTable({
//         "processing": true,
//         "scrollX": true,
//         "serverSide": true,
//         "lengthMenu": [
//                 [10, 30, 50, 100, 500, 1000, -1],
//                 [10, 30, 50, 100, 500, 1000, "All"]
//             ],       
//         "ajax": {
//             "url": "<?=base_url().'report/all_report_filterdata'?>",
//             "type": "POST",
//         },
//         "columnDefs": [{
//             "orderable": false,
//             "targets": 0
//         }],
//         "order": [
//             [1, "desc"]
//         ],
//         dom: 'lBfrtip',
//         buttons: [{
//                 extend: 'copyHtml5',
//                 title: report_name
//             },
//             {
//                 extend: 'csvHtml5',
//                 title: report_name
//             },
//             {
//                 extend: 'excelHtml5',
//                 title: report_name
//             }
//         ]
//     });

// });


$(document).ready(function(){
    $.ajax({
            url: '<?= base_url('report/get_last_month_data');?>',
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('#new_data').html(data);
                getSum();
            }
        });
  });

//   $(document).ready(function(){
//     $.ajax({
//             url: '<?= base_url('report/get_last_month_avg_data');?>',
//             type: 'POST',
//             dataType: 'html',
//             success: function (data) {
//                 $('#new_data2').html(data);
//                 getSum2();
//             }
//         });
//   });


  function getSum(){
      var sales_region = $("#sales_region").text();

    for (let index = 1; index <= 30; index++) {
        var visit = 0;
        var nad = 0;
        var sinings = 0;

        for (let sales = 1; sales <= sales_region; sales++) {
          visit += parseInt($("#visit"+sales+'_'+index).text());
          nad += parseInt($("#nad"+sales+'_'+index).text());
          sinings += parseInt($("#sinings"+sales+'_'+index).text());

        }
        $("#res_visit"+index).text(visit);
        $("#res_nad"+index).text(nad);
        $("#res_signings"+index).text(sinings);

    }
      
  }
  
  function getSum2(){
      var sales_region = $("#avg_sales_region").text();

    for (let index = 1; index <= 30; index++) {
        var visit = 0;
        var nad = 0;
        var sinings = 0;

        for (let sales = 1; sales <= sales_region; sales++) {
          visit += parseInt($("#avg_visit"+sales+'_'+index).text());
          nad += parseInt($("#avg_nad"+sales+'_'+index).text());
          sinings += parseInt($("#avg_sinings"+sales+'_'+index).text());

        }
        $("#avg_res_visit"+index).text(visit);
        $("#avg_res_nad"+index).text(nad);
        $("#avg_res_signings"+index).text(sinings);

    }
      
  }
  

function get_region_wise_data(region_id){
    $.ajax({
            url: '<?= base_url('report/get_region_wise_data/');?>'+region_id,
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("a").removeClass("change-color");
                $("#change-color-"+region_id).addClass("change-color");
                var obj = JSON.parse(data);
                $('.visit_data').html(obj.html1);
                $('.signings').html(obj.html2);
                $('.nad').html(obj.html3);

            }
        });
}

</script>
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/funnel.js"></script>

    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</body>

</html>
