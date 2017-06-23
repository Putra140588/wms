<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
        </div>
        <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel panel-info">
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                             <?php $this->load->view('bo/v_date_range')?>
                                <table class="table table-striped table-bordered table-hover ss-tables" cellspacing="0" width="100%">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Kode Bahan Baku</th>     
                                        	<th>Nama Produk</th>                                   	
                                        	<th>Kategori</th>                                        	
                                        	<th>Lebar (mm)</th>                                        	     
                                        	<th>Tinggi (meter)</th>                                        	
                                        	<th>Qty Roll</th>
                                        	<th>Keterangan</th>
                                        	<th>Warehouse</th> 
                                        	<th>Date Add</th>
                                        	<th>Add By</th>                            	                                        	                                     	                       	
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
</script>        