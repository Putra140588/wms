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
     			<input type="hidden" name="id" value="<?php echo isset($id_order) ? $id_order : ''?>">
     			<?php $select='';?>
     				<div class="row">     				
     					<div class="col-lg-8">
	     					<div class="form-group">
	     						<label>Pilih Customer <span class="required">*</span></label>
	     						<div class="table-responsive">
		     							  <table class="table table-striped table-bordered table-hover second-tables" cellspacing="0" width="100%">
		     							  	<thead>
		     							  	 <tr>
		     							   	   <th>#</th>
		     							   	   <th>Customer</th>		     							   	   			   
										       <th class="no-sort">Actions</th>
										    </tr>
		     							  	</thead>											    																
		     							</table>									
     							</div>    			     						
	     					</div>	  	     					
     					</div>     	
     					<div class="col-lg-4">
     					<div class="form-group">
	     						<label>Pilih Alamat Tagihan <span class="required">*</span></label>
	     						<select name="billaddress" id="billaddress" class="form-control address" required>
	     							<option value="" selected disabled>Pilih Alamat Tagihan</option>	     							
	     						</select>
	     					</div>	   					
	     					<div class="form-group">
	     						<label>Pilih Alamat Pengiriman <span class="required">*</span></label>
	     						<select name="shippaddress" id="shipaddress" class="form-control address" required>
	     							<option value="" selected disabled>Pilih Alamat Pengiriman</option>	     							
	     						</select>
	     					</div>		  
	     					<div class="form-group">
	     						<label>Pengiriman Via <span class="required">*</span></label>
	     						<select name="courier" class="form-control" required>
	     							<option value="" selected disabled>Pilih Pengiriman Via</option>
	     							<?php foreach ($courier->result() as $row){
	     								echo '<option value="'.$row->id_courier.'">'.$row->nama_courier.'</option>';
	     							}?>
	     						</select>
	     					</div>   					
	     					 <div class="form-group">
	     						<label>Tanggal Pengiriman</label>
	     						<input type="text" name="shippdate" id="datepicker" class="form-control" placeholder="Tanggal Pengiriman">
	     					</div>
	     					<div class="form-group">
	     						<label>PO Number</label>
	     						<input type="text" name="po" class="form-control" placeholder="PO Number">
	     					</div>	
	     					<div class="form-group">
	     						<label>Terms</label>
	     						<select name="terms" class="form-control">
	     							<option value="" selected disabled>Pilih Terms</option>
	     							<?php foreach ($terms->result() as $row){
	     								echo '<option value="'.$row->id_terms.'">'.$row->terms.'</option>';
	     							}?>
	     						</select>
	     					</div>	
	     					<div class="form-group">
	     						<label>Salesman</label>
	     						<select name="salesman" class="form-control">
	     							<option value="" selected disabled>Pilih Salesman</option>
	     							<?php foreach ($salesman as $row){
	     								echo '<option value="'.$row->id_karyawan.'">NIK '.$row->nik.' - '.$row->nama_depan.' '.$row->nama_belakang.'</option>';
	     							}?>
	     						</select>
	     					</div>
	     					<div class="form-group">
	     						<label>Description</label>
	     						<textarea cols="3" rows="3" class="form-control" name="desc"></textarea>
	     					</div>  					   										
     					</div>     											
     				</div>
     				<!-- /#end customer description -->
     				
     				<div class="row">
     					<div class="col-lg-12">
     					<div class="form-group">
     							<label>Jenis Bahan</label>
     							<div class="table-responsive">
     							  <table class="table table-striped table-bordered table-hover ss-tables" cellspacing="0" width="100%">
									<thead>
										<tr><th>#</th>
											<th>Nama Produk</th>
											<th>Kategori</th>
											<th>Supplier</th>
											<th class="no-sort">Pilih</th>
										</tr>
									</thead>     							
     							</table>
     							</div>
     						</div>  
     						<div class="form-group">
     							<label>Keranjang Bahan</label>     							
     								<div class="table-responsive">
		     							  <table class="table table-striped table-bordered table-hover default-table">
		     							  	<thead>
		     							   <tr>
		     							   	   <th>#</th>
		     							   	   <th>Nama Produk</th>
		     							   	   <th>Deskripsi</th>										  
											   <th>Harga</th>
											   <th>Lebar (mm)</th>
											   <th>Tinggi (mm)</th>
											   <th>Qty Pcs</th>
											   <th>Qty Roll</th>
											   <th>Hook</th>
											   <th>Line</th>		
											   <th>Gap Samping</th>
											   <th>Gap Atas</th>
											   <th>Material Size</th>
											   <th>Security Cut</th>
											   <th>Perforation</th>
											   <th>Colour</th>
											   <th>Colour Key</th>
											   <th colspan="2"><center>Packing</center></th>
											   <th>Core Size</th>
											   <th>Cartoon Box</th>
											   <th colspan="3"><center>Cutter Sketch</center></th>	
											   <th>Keterangan</th>							   
										       <th>Actions</th>
										    </tr>
		     							  	</thead>
											<tbody id="cartproduct">											
												<?php echo $this->m_content->load_cart()?>
											</tbody>    																
		     							</table>									
     								</div>     							
     						</div>
     					</div>
     				</div>
     				<span class="required">*</span> Wajib diisi.
     				<div class="cleaner_h20"></div>
     				<button type="submit" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Create Summary</button>
     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>
     				</form>			
     			</div>
     		</div>
     	</div>
     </div>
</div>
<input type="hidden" id="url-dt" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_records_bahan')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		primari_table(".ss-tables");		
	});
</script>  
<input type="hidden" id="url-dt" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_records_customer')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		second_table(".second-tables");		
	});
</script>
    