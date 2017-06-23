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
     			<?php $select='';?>
     				<div class="row">     				
     					<div class="col-lg-6">
     						<div class="form-group">
	     						<label>Tanggal Terima <span class="required">*</span></label>
	     						<input type="text" name="receivedate" id="datepicker" class="form-control" placeholder="Tanggal Terima" required>
	     					</div>	
	     					<div class="form-group">
	     						<label>Pilih Supplier <span class="required">*</span></label>
	     						<select name="supplier" class="form-control" onchange="ajaxcall('<?php echo base_url('bo/'.$class.'/show_po')?>',this.value,'ponumber')" required>
	     							<option value="" selected disabled>Pilih Supplier</option>
	     							<?php foreach ($supplier->result() as $row){
	     								echo '<option value="'.$row->id_supplier.'">'.$row->nama_supplier.'</option>';
	     							}?>
	     						</select>
	     					</div>		  
	     					 <div class="form-group">
	     						<label>PO Number</label>
	     						<table class="table table-striped table-bordered hover">
	     							<thead>
	     								<tr>
	     									<th>#</th>	 
	     									<th>Check</th> 
	     									<th>PO Number</th>   	
	     									<th>PO Tanggal</th>							
	     								</tr>
	     							</thead>
	     							<tbody id="ponumber"></tbody>     												
	     						</table>     							     						
	     					</div>				     										
     					</div>     	
     					<div class="col-lg-6">     						      					
	     					<div class="form-group">
	     						<label>Keterangan</label>
	     						<textarea cols="3" rows="8" class="form-control" name="desc" placeholder="Tulis keterangan"></textarea>
	     					</div>	   					
     					</div>     											
     				</div>
     				<div class="row">
     					<div class="col-lg-12">
     						<div class="form-group">
     							<label>Keranjang Barang</label>     							
     								<div class="table-responsive">
		     							  <table class="table table-striped table-bordered table-hover default-table">
		     							  	<thead>
		     							   <tr>
		     							   	   <th>#</th>
		     							   	   <th>Check</th>
		     							   	   <th>Nama Produk</th>
											   <th>Deskripsi</th>	
											   <th>Type</th>	
											   <th>Keterangan</th>									   
											   <th>Lebar (mm)</th>
											   <th>Tinggi (mm)/(m)</th>											   
											   <th>Qty Roll/Pcs</th>
											   <th>Qty m<sup>2</sup>
											   <th>Warehouse</th>											  
										       <th>Catatan</th>										       
										    </tr>
		     							  	</thead>
											<tbody id="showcart"></tbody> 																																   																
		     							</table>									
     								</div>     							
     						</div>
     					</div>
     				</div>
     				<span class="required">*</span> Wajib diisi.
     				<div class="cleaner_h20"></div>
     				<button type="submit" class="btn btn-primary">Simpan</button>
     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>
     				</form>			
     			</div>
     		</div>
     	</div>
     </div>
</div>

     