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
     			<form id="frmkaryawan" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<input type="hidden" name="id" value="<?php echo isset($id_product) ? $id_product : ''?>">
     			<?php $select='';?>
     				<div class="row">     				
     					<div class="col-lg-6">
	     					<div class="form-group">
	     						<label> Nama Produk <span class="required">*</span></label>
	     						<div class="controls">	     						
	     							<input class="form-control" type="text" name="nama" placeholder="Nama Produk" value="<?php echo isset($nama_product) ? $nama_product : ''?>"  required>	     							
	     						</div>
	     					</div>
	     					<div class="form-group">
	     						<label>Kategori <span class="required">*</span></label>
	     						<select name="kategori" class="form-control">
	     							<option value="" selected disabled>Pilih Kategori</option>
	     							<?php   							
	     							foreach ($kategori->result() as  $row){
	     								if ($id_product != ''){
	     									$select = ($id_kategori == $row->id_kategori) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row->id_kategori.'" '.$select.'>'.$row->nama_kategori.'</option>';
	     							}?>
	     							
	     						</select>
	     					</div>
	     					<div class="form-group">
	     						<label>Supplier <span class="required">*</span></label>
	     						<select name="supplier" class="form-control">
	     							<option value="" selected disabled>Pilih Supplier</option>
	     							<?php   							
	     							foreach ($supplier->result() as  $row){
	     								if ($id_product != ''){
	     									$select = ($id_supplier == $row->id_supplier) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row->id_supplier.'" '.$select.'>'.$row->nama_supplier.'</option>';
	     							}?>
	     							
	     						</select>
	     					</div>	      					
	     					
	     					<div class="form-group">
	     						<label>Deskripsi</label>
	     						<div class="controls">	   
	     						<textarea rows="4" cols="5" name="deskripsi" class="form-control" placeholder="Deskripsi"><?php echo isset($deskripsi) ? $deskripsi : ''?></textarea>
	     						</div>
	     					</div>	
     					</div>     						
     				</div>
     				<span class="required">*</span> Wajib diisi.
     				<div class="cleaner_h10"></div>
     				<button type="submit" class="btn btn-primary">Simpan</button>
     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>
     				</form>			
     			</div>
     		</div>
     	</div>
     </div>
</div>
       