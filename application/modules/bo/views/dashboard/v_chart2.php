<div class="panel panel-info">
	<div class="panel-heading">
		<h4><i class="icon-bar-chart"></i> <?php echo $title?></h4>
	</div>
	<div class="panel-body">
		<div id="chart2"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#chart2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '<?php echo $title; ?>'
        },
        subtitle: {
            text: 'Source: <?php echo base_url(); ?>'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Sales Order Approve'
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            pointFormat: 'Total SO : <b> {point.y:.2f}</b>'
        },
        series: [{
            name: 'Population',
            data: [ <?php echo $data; ?> ],
            dataLabels: {
                enabled: false,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '12px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
});
</script>