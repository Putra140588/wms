<div id="page-wrapper">     
 <div class="cleaner_h50"></div>
     <div class="row">
     	<div class="col-lg-12">
     		<div class="panel panel-info">
     			<div class="panel-heading">
     				<h4><?php echo $page_title?></h4>
     			</div>
     			<div class="panel-body">
     			<?php $this->load->view('bo/v_alert_notif');?>
     			<form id="form" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>" class="myModal">
     			<input type="hidden" name="id" value="<?php echo isset($id_delivery_order) ? $id_delivery_order : ''?>">
     			<?php $select='';?>
     				<div class="row">     				
     					<div class="col-lg-6">
	     					<div class="form-group">
	     						<label>Pilih Sales Order <span class="required">*</span></label>
	     						<select name="salesorder" id="salesorder" class="form-control" onchange="ajaxcall('<?php echo base_url('bo/mpgoodout/pilih_so')?>',this.value,'sodetail')" required>
	     							<option value="" selected disabled>--Pilih Sales Order--</option>
	     							<?php foreach ($sales_order as $row){
	     								echo '<option value="'.$row->id_order.'">'.$row->id_order.' - '.$row->nama_customer.'</option>';
	     							}?>
	     						</select>
	     					</div>		
	     					<div class="form-group">
	     						<label>DO Number <span class="required">*</span></label>
	     						<input type="text" name="donumber" class="form-control" placeholder="DO Number" required>
	     					</div>  	     					  			
	     					 <div class="form-group">
	     						<label>Tanggal Pengiriman <span class="required">*</span></label>
	     						<input type="text" name="shippdate" id="datepicker" class="form-control" placeholder="Tanggal Pengiriman" required>
	     					</div>	   						     					     						     					
     					</div>  
     					<div class="col-lg-6">
     						<div class="form-group">
	     						<label>Description</label>
	     						<textarea cols="3" rows="3" class="form-control" name="desc" placeholder="Description"></textarea>
	     					</div>	
     					</div>      	     								 											
     				</div>   	
     				<div class="row" >
	     				<div class="col-lg-12">
		     				<div class="panel panel-info">
		     					<div class="panel-heading">
		     						Detail Order Material
		     					</div>
		     					<div class="panel-body">
		     						<div class="table-responsive">
			     						<table class="table table-striped table-bordered">
			     							<thead>
			     								<tr><th>#</th>
			     									<th>PO Number</th>
			     								    <th>Nama Product</th>
			     								    <th>Deskripsi</th>
			     								    <th>Kategory</th>
			     								    <th>Lebar</th>
			     								    <th>Tinggi</th>
			     								    <th>Qty</th>	     								   
			     								</tr>
			     							</thead>
			     							<tbody id="sodetail">
			     								
			     							</tbody>
			     						</table>
	     						   </div>
		     					</div>
		     				</div>	     
		     				<div class="panel panel-info">
		     					<div class="panel-heading">
		     						Stok Bahan Jadi
		     					</div>
		     					<div class="panel-body">
		     						<div class="table-responsive">
		     							<table class="table table-striped table-bordered table-hover ss-tables">
			     							<thead>
		                                        <tr><th>#</th>
		                                        	<th>Kode Bahan Jadi</th>                                        	
		                                        	<th>Kategori</th>                                        	
		                                        	<th>Lebar (mm)</th>                                        	     
		                                        	<th>Tinggi (mm)</th>                                        	
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
		     				<div class="panel panel-info">
		     					<div class="panel-heading">
		     						Keranjang Delivery Material
		     					</div>
		     					<div class="panel-body">
		     						<div class="table-responsive">
		     							 <table class="table table-striped table-bordered table-hover default-table">
			     							<thead>
		                                        <tr><th>#</th>
		                                        	<th>Kode Bahan Jadi</th>                                        	
		                                        	<th>Kategori</th>                                        	
		                                        	<th>Lebar (mm)</th>                                        	     
		                                        	<th>Tinggi (mm)</th>                                        	
		                                        	<th>Qty Pcs</th>	
		                                        	<th>Serial Number</th>	                                        	
		                                        	<th>Actions</th>                           	                                        	                                     	                       	
		                                        </tr>                                      	              	
	                                   		 </thead>
	                                   		 <tbody id="cartproduct">
	                                   		 		<?php echo $this->m_content->delivery_cart_content()?>
	                                   		 </tbody>
		     							</table>
		     						</div>
		     					</div>
		     				</div>			
	     				</div>
	     			</div>			
	     				<span class="required">*</span> Wajib diisi.
	     				<div class="cleaner_h20"></div>
	     				<button type="submit" class="btn btn-primary">Create DO</button>
	     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>
     				</form>			
     			</div>
     		</div>
     	</div>
     </div>
</div>
<input type="hidden" id="url-dt" value="<?php echo base_url('bo/'.$class.'/get_records_bj')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		loaddatatable();		
	});
</script>        