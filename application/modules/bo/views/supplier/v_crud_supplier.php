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
     			<input type="hidden" name="id" value="<?php echo isset($id_supplier) ? $id_supplier : ''?>">  			
     			<?php $readonly= isset($id_supplier) ? 'readonly' : '' ;?>
     				<div class="row">     				
     					<div class="col-lg-6">
     						<div class="form-group">
	     						<label> ID Supplier <span class="required">*</span></label>
	     						<div class="controls">	     						
	     							<input class="form-control" <?php echo $readonly?> type="text" name="id_supplier" placeholder="ID Supplier" value="<?php echo isset($id_supplier) ? $id_supplier : ''?>"  required>
	     							
	     						</div>
	     					</div>	   
	     					<div class="form-group">
	     						<label> Nama Supplier <span class="required">*</span></label>
	     						<div class="controls">	     						
	     							<input class="form-control" type="text" name="namasupplier" placeholder="Nama Supplier" value="<?php echo isset($nama_supplier) ? $nama_supplier : ''?>"  required>	     							
	     						</div>
	     					</div>	
	     					<div class="form-group">
	     						<label> Alamat</label>
	     						<div class="controls">	     						
	     							<textarea cols="3" name="alamat" rows="3" class="form-control"><?php echo isset($alamat) ? $alamat : ''?></textarea>
	     						</div>
	     					</div>	
	     					<div class="form-group">
	     						<label> Phone</label>
	     						<div class="controls">	     						
	     							<input class="form-control" type="text" name="phone" placeholder="Phone" value="<?php echo isset($phone) ? $phone : ''?>">	     							
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
       