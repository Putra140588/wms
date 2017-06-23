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
     					<div class="col-lg-5">
	     					<div class="form-group">
	     						<label>Pilih Supplier <span class="required">*</span></label>
	     						<select name="supplier" class="form-control"  required>
	     							<option value="" selected disabled>Pilih Supplier</option>
	     							<?php foreach ($supplier->result() as $row){
	     								echo '<option value="'.$row->id_supplier.'">'.$row->nama_supplier.'</option>';
	     							}?>
	     						</select>
	     					</div>		  
	     					 <div class="form-group">
	     						<label>PO Number</label>
	     						<input type="text" name="po" class="form-control" placeholder="PO Number">
	     					</div>				
	     					 <div class="form-group">
	     						<label>Tanggal PO</label>
	     						<input type="text" name="podate" id="datepicker" class="form-control" placeholder="Tanggal PO">
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
	     						<label>Keterangan</label>
	     						<textarea cols="3" rows="3" class="form-control" name="desc"></textarea>
	     					</div>	   					
     					</div>     	
     					<div class="col-lg-7">
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
     					</div>     											
     				</div>
     				<div class="row">
     					<div class="col-lg-12">
     						<div class="form-group">
     							<label>Keranjang Bahan</label>     							
     								<div class="table-responsive">
		     							  <table class="table table-striped table-bordered table-hover default-table">
		     							  	<thead>
		     							   <tr>
		     							   	   <th>Nama Produk</th>
											   <th>Deskripsi</th>											   
											   <th>Lebar (mm)</th>
											   <th>Tinggi (m)</th>											   
											   <th>Qty Roll/Pcs</th>											   
											   <th>Type</th>
										       <th>Keterangan</th>
										       <th>Actions</th>
										    </tr>
		     							  	</thead>
											<tbody id="splcart">											
												<?php echo $this->m_content->load_cart_spl()?>
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