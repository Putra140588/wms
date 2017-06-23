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
                            Barang Masuk No <b>#<?php echo $id?></b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover ss-tables" cellspacing="0" width="100%">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Nama Produk</th>
                                        	<th>Deskripsi</th>                                        	
                                        	<th>Kategori</th>
                                        	<th>Lebar (mm)</th>                                        	
                                        	<th>Tinggi (m)</th> 
                                        	<th>Qty Roll/Pcs</th>
                                        	<th>Qty m<sup>2</sup>                                       	     
                                        	<th>Warehouse</th>
                                        	<th>Catatan</th>                                        	                                 	                                        	                                     	
                                        	<th class="no-sort">Actions</th>
                                        </tr>                                      	              	
                                    </thead>                                    
                                </table>
                            </div>      
                            <div class="cleaner_h20"></div>
                            <button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>                      
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>              
     </div>
</div>    
<input type="hidden" id="url-dt" value="<?php echo base_url('bo/'.$class.'/get_records_detail_receive/'.$id)?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		loaddatatable();		
	});
</script>    