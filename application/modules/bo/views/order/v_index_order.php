<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
        </div>
        <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel panel-info">
                        <div class="panel-heading">
                             <a class="btn btn-success btn-sm" href="<?php echo base_url('bo/'.$class.'/form')?>" >Tambah</a>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                             <?php $this->load->view('bo/v_date_range')?>
                                <table class="table table-striped table-bordered table-hover ss-tables" cellspacing="0" width="100%">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>SO Number</th>
                                        	<th>Customer</th>                                        	                                     	                                       	
                                        	<th>Sales By</th> 
                                        	<th>Approved</th>  
                                        	<th>Status</th>                                    	                                          	
                                        	<th>Add By</th>                                        	
                                        	<th>Date Add</th>                                	                                        	                                     	
                                        	<th class="no-sort">Actions</th>
                                        </tr>                                      	              	
                                    </thead>                                    
                                </table>
                            </div>                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
         </div>              
     </div>
</div>    
<input type="hidden" id="url-dt" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_records')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		primari_table(".ss-tables");	
	});

	//barcode view
	function openWin() {
		var windowWidth = 600;
	    var windowHeight = 800;
	    var xPos = (screen.width/2) - (windowWidth/2);
	    var yPos = (screen.height/2) - (windowHeight/2);
	    window.open("barcodetest","POPUP","width=" 
	    + windowWidth+",height="+windowHeight +",left="+xPos+",top="+yPos);
	   // myWindow = window.open("barcodetest", "", "width=600, height=1000");  
	    //window.print();
	}
</script>   
    