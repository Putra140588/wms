<div class="panel panel-info">
	<div class="panel-heading">
		<h4><i class="icon-bar-chart"></i> <?php echo $title?></h4>
	</div>
	<div class="panel-body">
		<div id="chart3"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#chart3').highcharts({
        title: {
            text: '<?php echo $title?>'
        },
        subtitle: {
            text: 'Source: <?php echo base_url(); ?>'
        },
        xAxis: {
        	categories: [<?php echo $kategori?>],        	                   	         
        	crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Luasan (m2)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:9px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                         '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        }, 
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            type: 'column',
            name: 'Luasan Awal (m2)',
            data: [<?php echo $awal?>]
        }, {
            type: 'column',
            name: 'Luasan Terpakai (m2)',
            data: [<?php echo $terpakai?>]
        },{
            type: 'column',
            name: 'Luasan Tersedia (m2)',
            data: [<?php echo $tersedia?>]
        }],
    });
});
		</script>