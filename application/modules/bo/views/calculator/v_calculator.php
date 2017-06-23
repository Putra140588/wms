<html>
	<head>
		<title>Calculator</title>
		<link href="<?php echo base_url()?>assets/bo/css/jquery-ui.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/css/bootstrap.min.css" rel="stylesheet" />
     <link href="<?php echo base_url()?>assets/bo/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/css/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/css/style.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/css/main-style.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/bo/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <script src="<?php echo base_url()?>assets/bo/js/jquery-1.12.4.js"></script>
     <script src="<?php echo base_url()?>assets/bo/js/jquery-ui.min.js"></script>
	</head>
	<body>
		<div style="margin-left:300px;margin-top:20px">
		<?php 
			
			for ($i=1; $i < 10; $i++){				
						echo '<button id="klik'.$i.'" value="'.$i.'" class="btn btn-small btn-info">'.$i.'</button>';
					}	
			echo '<br>';				
			echo '<button id="tambah" value="+" class="btn btn-small btn-danger">+</button>';	
			echo '<div style="margin-top:10px">
     				Hitung = <span id="no1"></span><span id="plus"></span><span id="no2"></span><span id="no3"></span>					
     			 </div>';
			?>
			
		</div>
	</body>
<script src="<?php echo base_url()?>assets/bo/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url()?>assets/bo/js/jquery.metisMenu.js"></script>
  <script src="<?php echo base_url()?>assets/bo/js/pace.js"></script>
  <script src="<?php echo base_url()?>assets/bo/js/siminta.js"></script> 
  <script src="<?php echo base_url()?>assets/bo/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url()?>assets/bo/datatables/media/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo base_url()?>assets/bo/js/function.js"></script>  
</html>