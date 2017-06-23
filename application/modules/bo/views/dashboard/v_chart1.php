<div class="panel panel-info">
	<div class="panel-heading">
		<h4><i class="icon-bar-chart"></i> <?php echo $title?></h4>
	</div>
	<div class="panel-body">
		<div id="chart1"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#chart1').highcharts({
        title: {
            text: 'Produksi Vs Slitting'
        },
        subtitle: {
            text: 'Source: <?php echo base_url(); ?>'
        },
        xAxis: {
        	categories: [<?php echo $month?>],
        	                   	         
        	         crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Presentase Values'
            }
        },
        labels: {
            items: [{
                html: 'Total Persentase (%)',
                style: {
                    left: '50px',
                    top: '18px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'white'
                }
            }]
        },
        series: [{
            type: 'column',
            name: 'Produksi (%)',
            data: [<?php echo $produksi?>]
        }, {
            type: 'column',
            name: 'Slitting (%)',
            data: [<?php echo $slitting?>]
        }, {
            type: 'spline',
            name: 'Rata-rata',
            data: [<?php echo $rata?>],
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }, {
            type: 'pie',
            name: 'Total Persentase (%)',
            data: [{
                name: 'Produksi',
                y: <?php echo $total_produksi?>,
                color: Highcharts.getOptions().colors[0] // John's color
            }, {
                name: 'Slitting',
                y: <?php echo $total_slitting?>,
                color: Highcharts.getOptions().colors[1] // Jane's color
            }],
            center: [100, 80],
            size: 100,
            showInLegend: false,
            dataLabels: {
                enabled: false
            }
        }]
    });
});
		</script>