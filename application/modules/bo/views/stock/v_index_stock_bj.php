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
                            <span class="pull-right">                             
	                             <a class="btn btn-primary btn-sm" href="<?php echo base_url('bo/'.$class.'/export/csv')?>">Export CSV</a>
	                             <a class="btn btn-info btn-sm" href="<?php echo base_url('bo/'.$class.'/export/excel')?>">Export Excel</a>
	                             <a class="btn btn-warning btn-sm" href="<?php echo base_url('bo/'.$class.'/export/pdf')?>" target="_blank">Export PDF</a>                        
                             </span>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover ss-tables" cellspacing="0" width="100%">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Kode Bahan Jadi</th>                                        	
                                        	<th>Kategori</th>       
                                        	<th>Jenis</th>                                 	
                                        	<th>Lebar (mm)</th>                                        	     
                                        	<th>Tinggi (meter)</th>                                        	
                                        	<th>Qty Pcs Awal</th>
                                        	<th>Qty Pcs Terpakai</th>
                                        	<th>Qty Pcs Tersedia</th> 
                                        	<th>Warehouse</th>  
                                        	<th>Actions</th>                           	                                        	                                     	                       	
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