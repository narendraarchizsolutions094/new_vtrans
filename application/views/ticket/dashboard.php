<br>
<br>
<div class="col-md-12">
    <form action="<?= base_url('ticket/dashboard') ?>" method="POST">
        <div class="col-md-4">
            <label>From</label>
            <input name="fromdate"
                value="<?php if($this->input->post('fromdate')){echo $this->input->post('fromdate');} ?>"
                class="form-control form-date" >
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input name="todate" value="<?php if($this->input->post('todate')){echo $this->input->post('todate');} ?>"
                class="form-control form-date"  >
        </div>
        <div class="col-md-4"><button style="margin-top:22px;" type="submit" class="form-control">Submit</button></div>
    </form>
</div>
<br>
<br>
<style>
    .card-graph{
    min-height:250px;
    max-height:400px;
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
.card-graph_full{
    min-height:250px;
    max-height:510px;
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
.card-graph_full2{
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
    padding: 20px;
}
.card-graph_full:hover{
    box-shadow: 0 0 11px rgba(33,33,33,.2); 
}
.card-graph:hover {
  box-shadow: 0 0 11px rgba(33,33,33,.2); 
}
.card-graph_full2:hover{
    box-shadow: 0 0 11px rgba(33,33,33,.2); 

}

/* @media (min-width: 992px){
    .card-graph {
        width:32%;
    }
} */
.pd-10{
    padding:10px;
}

#chartdiv {
    width: 100%;
    height: 250px;
}

#chartdiv2 {
    width: 100%;
    height: 250px;
}

#chartdiv1 {
    width: 100%;
    height: 500px;
}

.highcharts-figure,
.highcharts-data-table table {
    min-width: 100%;
    max-width: 800px;
    margin: 1em auto;
}

#chartdiv6 {
    width: 100%;
    height: 250px;
}
#chartdiv5 {
    width: 100%;
    height: 600px;
}
#product_Ticket {
    width: 100%;
    height: 250px;
}
.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

#chartdiv_date {
    width: 100%;
    height: 500px;
}

#chartdiv_datewise {
    width: 100%;
    height: 500px;
}

#chartdiv_substage {
    width: 100%;
    height: 1000px;
}
</style>
<!-- Resources -->
<br>
<br>
<br>
<div class="row pd-20" style="width:100%;">
                <div class="col-md-12" style="padding:10px;">
                    <div class="card card-graph_full2" >
<div id="chartdiv_datewise"></div>
                    </div>
                </div>
                </div>
<br>
<div class="row pd-20" style="width:100%;">
                <div class="col-md-12 pd-20">
                    <div class="card card-graph_full2" >
                    <br>
<center>
    <h3>Referred By</h3>
</center>
<div id="chartdiv1"></div>
<br>
                    </div>
                </div>
</div>
<div class="row pd-20" style="width:100%;">
                <div class="col-md-6 pd-20" >
                    <div class="card card-graph" >
                    <br>
        <center>
            <h3>Priority Wise</h3>
        </center>
        <div id="chartdiv"></div>
    </div>
    </div>
                <div class="col-md-6 pd-20">
                    <div class="card card-graph" >
                    <br>
        <center>
            <h3>Type Wise</h3>
        </center>
        <div id="chartdiv2"></div>
    </div>
    </div>
</div>
 
<br>
<!-- <center>
    <h3>Substage Wise Ticket</h3>
</center> -->
<!-- HTML -->
<!-- <div id="chartdiv_substage"></div> -->
<br>
<br>
<div class="row pd-20" style="width:100%;">

<div class="col-md-12 pd-20" style="padding: 10px;">
<div class="card card-graph_full2">
<br>
<center>
    <h3>Stage Wise Ticket</h3>
</center>
<!-- HTML -->
<div id="chartdiv5"></div>
</div>
</div>
</div>
<br>
<br><!-- HTML -->

<div class="row pd-20" style="width:100%;">

    <div class="col-md-6 pd-20">
        <div class="card card-graph"><br>
            <center>
                <h3>Source Wise Ticket</h3>
            </center>
            <div id="chartdiv6"></div>
        </div>
    </div>
    <div class="col-md-6 pd-20" >
        <div class="card card-graph">
            <br>
    <center>
        <h3>Product/Service Wise Ticket </h3>
    </center>
    <div id="product_Ticket"></div>
</div>
    </div>
    </div>
<br><!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/subsource_typeJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv_substage", am4charts.XYChart);
                chart.scrollbarX = new am4core.Scrollbar(); // Add data
                chart.data = response // Create axes
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
                valueAxis.min = 0;
                valueAxis.renderer.minWidth = 50; // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "name";
                let labelTemplate = categoryAxis.renderer.labels.template;
                labelTemplate.inside =    false;
                series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                series.columns.template.strokeWidth = 0;
                series.tooltip.pointerOrientation = "vertical";
                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity =
                0.8; // on hover, make corner radiuses bigger
                var hoverState = series.columns.template.column.states.create("hover");
                hoverState.properties.cornerRadiusTopLeft = 0;
                hoverState.properties.cornerRadiusTopRight = 0;
                hoverState.properties.fillOpacity = 1;
                series.columns.template.adapter.add("fill", function(fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                }); // Cursor
                chart.cursor = new am4charts.XYCursor();
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/referred_byJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv1", am4charts.XYChart);
                chart.scrollbarX = new am4core.Scrollbar(); // Add data
                chart.data = response // Create axes
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
                valueAxis.renderer.minWidth = 50; // Create series
                valueAxis.min = 0;
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "name";
                series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                series.columns.template.strokeWidth = 0;
                series.tooltip.pointerOrientation = "vertical";
                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity =
                0.8; // on hover, make corner radiuses bigger
                var hoverState = series.columns.template.column.states.create("hover");
                hoverState.properties.cornerRadiusTopLeft = 0;
                hoverState.properties.cornerRadiusTopRight = 0;
                hoverState.properties.fillOpacity = 1;
                series.columns.template.adapter.add("fill", function(fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                }); // Cursor
                chart.cursor = new am4charts.XYCursor();
            });
        }
    });
});
</script>
<!-- //priority wise -->
<!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/priority_wiseJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv", am4charts.PieChart); // Add data
                chart.data = response // Set inner radius
                chart.innerRadius = am4core.percent(50); // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity =
                1; // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;
                pieSeries.colors.list = [
                    new am4core.color('#b8182b'),
                    new am4core.color('#FBC02D'),
                    new am4core.color('#388E3C'),
                ]
            });
        }
    });
});
</script><!-- HTML -->
<!-- Resources -->
<!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/complaint_typeJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv2", am4charts.PieChart); // Add data
                chart.data = response // Set inner radius
                chart.innerRadius = am4core.percent(50); // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity =
                1; // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;
                pieSeries.colors.list = [
                    new am4core.color('#b8182b'),
                    new am4core.color('#388E3C'),
                ]
            });
        }
    });
});
</script><!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/source_typeJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv6", am4charts.PieChart); // Add data
                chart.data = response; // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity =
                1; // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;
            }); // end am4core.ready()
        }
    });
});
</script>
<!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/product_ticketJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("product_Ticket", am4charts
                .PieChart); // Add data
                chart.data = response // Set inner radius
                chart.innerRadius = am4core.percent(50); // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "name";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity =
                1; // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;
                pieSeries.colors.list = [
                    new am4core.color('#b8182b'),
                    new am4core.color('#FBC02D'),
                    new am4core.color('#388E3C'),
                ]
            });
        }
    });
});
</script>
<!-- HTML -->
<!-- Chart code -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/createddatewise/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv_datewise", am4charts
                .XYChart); //                // Increase contrast by taking evey second color
                chart.colors.step = 2; // Add data
                chart.data = response; // Create axes
                chart.numberFormatter.numberFormat = "#";

                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.minGridDistance = 50; // Create series
                function createAxisAndSeries(field, name, opposite, bullet) {
                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.min = 0;
                    if (chart.yAxes.indexOf(valueAxis) != 0) {
                        valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
                    }
                    var series = chart.series.push(new am4charts.LineSeries());
                    series.dataFields.valueY = field;
                    series.dataFields.dateX = "date";
                    series.strokeWidth = 2;
                    series.yAxis = valueAxis;
                    series.name = name;
                    series.tooltipText = "{name}: [bold]{valueY}[/]";
                    series.tensionX = 0.8;
                    series.showOnInit = true;
                    var interfaceColors = new am4core.InterfaceColorSet();
                    switch (bullet) {
                        case "triangle":
                            var bullet = series.bullets.push(new am4charts.Bullet());
                            bullet.width = 12;
                            bullet.height = 12;
                            bullet.horizontalCenter = "middle";
                            bullet.verticalCenter = "middle";
                            var triangle = bullet.createChild(am4core.Triangle);
                            triangle.stroke = interfaceColors.getFor("background");
                            triangle.strokeWidth = 2;
                            triangle.direction = "top";
                            triangle.width = 12;
                            triangle.height = 12;
                            break;
                        case "rectangle":
                            var bullet = series.bullets.push(new am4charts.Bullet());
                            bullet.width = 10;
                            bullet.height = 10;
                            bullet.horizontalCenter = "middle";
                            bullet.verticalCenter = "middle";
                            var rectangle = bullet.createChild(am4core.Rectangle);
                            rectangle.stroke = interfaceColors.getFor("background");
                            rectangle.strokeWidth = 2;
                            rectangle.width = 10;
                            rectangle.height = 10;
                            break;
                        default:
                            var bullet = series.bullets.push(new am4charts.CircleBullet());
                            bullet.circle.stroke = interfaceColors.getFor("background");
                            bullet.circle.strokeWidth = 2;
                            break;
                    }
                    valueAxis.renderer.line.strokeOpacity = 1;
                    valueAxis.renderer.line.strokeWidth = 2;
                    valueAxis.renderer.line.stroke = series.stroke;
                    valueAxis.renderer.labels.template.fill = series.stroke;
                    valueAxis.renderer.opposite = opposite;
                }
                createAxisAndSeries("visits", "Complaint Type", false, "circle");
                // createAxisAndSeries("views", "Query Type", true, "triangle");
                createAxisAndSeries("hits", "Query Type", true, "rectangle"); // Add legend
                chart.legend = new am4charts.Legend(); // Add cursor
                chart.cursor = new am4charts.XYCursor();
            }); // end am4core.ready() 
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/stage_typeJson/'.$fromdate.'/'.$todate.'')?>",
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            //alert('loaded');
            am4core.ready(function() { // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end                // Create chart instance
                var chart = am4core.create("chartdiv5", am4charts.XYChart3D);
                chart.paddingBottom = 30;
                chart.angle = 35; // Add data
                chart.data = response; // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "name";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 20;
                categoryAxis.renderer.inside = true;
                categoryAxis.renderer.grid.template.disabled = true;
                let labelTemplate = categoryAxis.renderer.labels.template;
                labelTemplate.rotation = -90;
                labelTemplate.horizontalCenter = "left";
                labelTemplate.verticalCenter = "middle";
                labelTemplate.dy = 10; // moves it a bit down;
                labelTemplate.inside =
                false; 
                // this is done to avoid settings which are not suitable when label is rotated                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.grid.template.disabled = true; // Create series
                var series = chart.series.push(new am4charts.ConeSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "name";
                series.tooltipText = "[{name}: bold]{value}[/]";
                var columnTemplate = series.columns.template;
                columnTemplate.adapter.add("fill", function(fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                });
                 columnTemplate.adapter.add("stroke", function(stroke, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                })
            }); // end am4core.ready()
        }
    });
});
</script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>