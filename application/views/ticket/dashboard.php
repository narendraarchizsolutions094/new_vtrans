<br>
<br>
<div class="col-md-12">
    <form action="<?= base_url('ticket/dashboard') ?>" method="POST">
        <div class="col-md-4">
            <label>From</label>
            <input name="fromdate"
                value="<?php if($this->input->post('fromdate')){echo $this->input->post('fromdate');} ?>"
                class="form-control form-date">
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input name="todate" value="<?php if($this->input->post('todate')){echo $this->input->post('todate');} ?>"
                class="form-control form-date">
        </div>
        <div class="col-md-4"><button style="margin-top:22px;" type="submit" class="form-control">Submit</button></div>
    </form>
</div>
<br>
<br>
<style>
.card-graph {
    min-height: 250px;
    max-height: 400px;
    border: 1px solid;
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}

.card-graph_full {
    min-height: 250px;
    max-height: 510px;
    border: 1px solid;
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}

.card-graph_full2 {
    border: 1px solid;
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
    padding: 20px;
}

.card-graph_full:hover {
    box-shadow: 0 0 11px rgba(33, 33, 33, .2);
}

.card-graph:hover {
    box-shadow: 0 0 11px rgba(33, 33, 33, .2);
}

.card-graph_full2:hover {
    box-shadow: 0 0 11px rgba(33, 33, 33, .2);

}

/* @media (min-width: 992px){
    .card-graph {
        width:32%;
    }
} */
.pd-10 {
    padding: 10px;
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

#chartdiv7 {
    width: 100%;
    height: 600px;
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
    height: 550px;
    margin-left: auto;
}
</style>
<!-- Resources -->
<br>
<br>
<br>
<div class="row pd-20" style="width:100%;">
    <div class="col-md-12" style="padding:10px;">
        <div class="card card-graph_full2">
            <div id="chartdiv_datewise"></div>
        </div>
    </div>
</div>
<br>
<div class="row pd-20" style="width:100%;">
    <div class="col-md-12 pd-20">
        <div class="card card-graph_full2">
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
    <div class="col-md-6 pd-20">
        <div class="card card-graph">
            <br>
            <center>
                <h3>Priority Wise</h3>
            </center>
            <div id="chartdiv"></div>
        </div>
    </div>
    <div class="col-md-6 pd-20">
        <div class="card card-graph">
            <br>
            <center>
                <h3>Type Wise</h3>
            </center>
            <div id="chartdiv2"></div>
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
    <div class="col-md-6 pd-20">
        <div class="card card-graph">
            <br>
            <center>
                <h3>Product/Service Wise Ticket </h3>
            </center>
            <div id="product_Ticket"></div>
        </div>
    </div>
</div>
<br>
<br><!-- HTML -->

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
                series.columns.template.showTooltipOn = "always";
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
                series.columns.template.showTooltipOn = "always";
                series.tooltip.pointerOrientation = "vertical";
                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity =
                    0.8; // on hover, make corner radiuses bigger
                var hoverState = series.columns.template.column.states.create(
                    "hover");
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
                var chart = am4core.create("chartdiv", am4charts
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
                var chart = am4core.create("chartdiv2", am4charts
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
                var chart = am4core.create("chartdiv6", am4charts
                    .PieChart); // Add data
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
                pieSeries.labels.template.text = "{name}: {value}";
                pieSeries.slices.template.tooltipText = "{name}: {value}";
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


<!-- failure point chart -->

<script>
$(document).ready(function() {
    $.ajax({
        url: "<?=base_url('ticket/failurepoint_ticketJson/'.$fromdate.'/'.$todate.'')?>",
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
                series.columns.template.showTooltipOn = "always";
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
                    .XYChart
                ); //                // Increase contrast by taking evey second color
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
                    //series.columns.template.showTooltipOn = "always";
                    series.tensionX = 0.8;
                    series.showOnInit = true;
                    var interfaceColors = new am4core.InterfaceColorSet();
                    switch (bullet) {
                        case "triangle":
                            var bullet = series.bullets.push(new am4charts
                                .Bullet());
                            bullet.width = 12;
                            bullet.height = 12;
                            bullet.horizontalCenter = "middle";
                            bullet.verticalCenter = "middle";
                            bullet.showTooltipOn = "always";
                            var triangle = bullet.createChild(am4core.Triangle);
                            triangle.stroke = interfaceColors.getFor("background");
                            triangle.strokeWidth = 2;
                            triangle.direction = "top";
                            triangle.width = 12;
                            triangle.height = 12;
                            break;
                        case "rectangle":
                            var bullet = series.bullets.push(new am4charts
                                .Bullet());
                            bullet.width = 10;
                            bullet.height = 10;
                            bullet.horizontalCenter = "middle";
                            bullet.verticalCenter = "middle";
                            bullet.showTooltipOn = "always";
                            var rectangle = bullet.createChild(am4core.Rectangle);
                            rectangle.stroke = interfaceColors.getFor("background");
                            rectangle.strokeWidth = 2;
                            rectangle.width = 10;
                            rectangle.height = 10;
                            break;
                        default:
                            var bullet = series.bullets.push(new am4charts
                                .CircleBullet());
                            bullet.circle.stroke = interfaceColors.getFor(
                                "background");
                            bullet.circle.strokeWidth = 2;
                            bullet.showTooltipOn = "always";
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
                createAxisAndSeries("hits", "Query Type", true,
                    "rectangle"); // Add legend
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
            am4core.ready(function() {

                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart instance
                var chart = am4core.create("chartdiv5", am4charts.XYChart);
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
                series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
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
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>