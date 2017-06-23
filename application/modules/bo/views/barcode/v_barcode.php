<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cetak barcode</title>
    <script type="text/javascript" src="<?php echo base_url()?>assets/bo/js/connectcode-javascript-code128b.js"></script>
    <style type="text/css">    	
  		.barcode {font-weight: bold; font-style: normal; line-height:normal; sans-serif; font-size: 12pt; margin-right:830px;}
    </style>
</head>
<body style='margin-left:10px; width:100px;'>
<?php if ($page == 'single'){?>
<div id="barcodecontainer" style="width:15in">
	<?php 
	for($i=1; $i <= $qtyprint; $i++){
		echo '<div  class="barcode" id="barcode'.$i.'">'.$label_code.'</div>';			
	}?>
</div>
<script type="text/javascript">
  function get_object(id) {	 
	   var object = null;
	   if (document.layers) {
	    object = document.layers[id];
	   } else if (document.all) {
	    object = document.all[id];
	   } else if (document.getElementById) {
	    object = document.getElementById(id);
	   }
	   return object;
  }  
  for(var i=1; i <= <?php echo $qtyprint?>; i++){		
	get_object("barcode" + i).innerHTML=DrawHTMLBarcode_Code128B(get_object("barcode"+i).innerHTML,"yes","in",0,8,0.5,"bottom","center","font-size:16pt","black","white");
  }	
window.print();
</script>

<?php }else{?>
<div id="barcodecontainer" style="width:15in">
	<?php 
		$i=1;
		foreach ($sql as $row){
			for ($b=1; $b <= $qtyprint;$b++){
				$label_code = isset($row->label_code_bb) ? $row->label_code_bb : $row->label_code_bj;
				echo '<div  class="barcode" id="barcode'.$i++.'">'.$label_code.'</div>';
			}
	}?>
</div>
<script type="text/javascript">
  function get_object(id) {	 
	   var object = null;
	   if (document.layers) {
	    object = document.layers[id];
	   } else if (document.all) {
	    object = document.all[id];
	   } else if (document.getElementById) {
	    object = document.getElementById(id);
	   }
	   return object;
  }
  
  for(var i=1; i <= <?php echo $count*$qtyprint?>; i++){		
	get_object("barcode" + i).innerHTML=DrawHTMLBarcode_Code128B(get_object("barcode"+i).innerHTML,"yes","in",0,8,0.5,"bottom","center","font-size:16pt","black","white");
  }	
 window.print();
</script>
<?php }?>
</body>
</html>


