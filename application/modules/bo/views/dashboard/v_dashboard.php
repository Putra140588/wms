<!--  page-wrapper -->
<script src="<?php echo base_url();?>assets/bo/js/highcharts.js"></script>
<script src="<?php echo base_url();?>assets/bo/js/highcharts-more.js"></script>
<script src="<?php echo base_url();?>assets/bo/js/highcharts-3d.js"></script>
<script src="<?php echo base_url();?>assets/bo/js/exporting.js"></script>
        <div id="page-wrapper">
            <div class="row">
                <!-- Page Header -->
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>                   
                </div>
                <!--End Page Header -->
              
            </div>
			<div class="row">
				<div class="col-lg-6">
					<?php echo $chart1?>
				</div>
				<div class="col-lg-6">
					<?php echo $chart2?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<?php echo $chart3?>
				</div>
				<div class="col-lg-6">
					<?php echo $chart4?>
				</div>
			</div>
        </div>
        <!-- end page-wrapper -->