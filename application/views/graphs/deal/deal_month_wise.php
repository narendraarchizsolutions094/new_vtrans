<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>
.highcharts-figure, .highcharts-data-table table {
  min-width: 310px; 
  /* max-width: 800px; */
  margin: 1em auto;
}

#container {
  height: 400px;
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
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}
</style>
<figure class="highcharts-figure">
  <div id="container"></div>
  <p class="highcharts-description">
   
  </p>
</figure>

<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Deal'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Values'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    credits: {
      enabled: false
    },
    plotOptions: {
        stacking: 'normal',
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: <?=$feed?>
});


  // Highcharts.chart('container', {

  // chart: {
  //   type: 'column'
  // },

  // title: {
  //   text: 'Monthly Graph'
  // },

  // xAxis: {
  //   categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
  // },

  // yAxis: {
  //   allowDecimals: false,
  //   min: 0,
  //   title: {
  //     text: 'Value'
  //   }
  // },

  // tooltip: {
  //   formatter: function () {
  //     return '<b>' + this.x + '</b><br/>' +
  //       this.series.name + ': ' + this.y + '<br/>' +
  //       'Total: ' + this.point.stackTotal;
  //   }
  // },

  // plotOptions: {
  //   column: {
  //     stacking: 'normal'
  //   }
  // },
  // series: <?=$feed?>
  // });
</script>