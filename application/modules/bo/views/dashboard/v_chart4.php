<div class="panel panel-info">
	<div class="panel-heading">
		<h4><i class="icon-bar-chart"></i> <?php echo $title?></h4>
	</div>
	<div class="panel-body">
		<div id="chart4"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#chart4').highcharts({
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
                text: 'Total Qty (pcs)'
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
            name: 'Qty Awal (pcs)',
            data: [<?php echo $awal?>]
        }, {
            type: 'column',
            name: 'Qty Terpakai (pcs)',
            data: [<?php echo $terpakai?>]
        },{
            type: 'column',
            name: 'Qty Tersedia (pcs)',
            data: [<?php echo $tersedia?>]
        }],
    });
});
		</script>