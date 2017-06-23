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
     			<form method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<input type="hidden" name="id" value="<?php echo isset($id_kategori) ? $id_kategori : ''?>">
     			<?php $disabled = isset($id_kategori) ? 'readonly' : '';?>
     				<div class="row">     				
     					<div class="col-lg-6">
     					<div class="form-group">
     						<label>ID Kategori</label>
     						<input type="text" name="idkategori" <?php echo $disabled?> class="form-control" value="<?php echo isset($id_kategori) ? $id_kategori : ''?>" placeholder="ID Kategori">
     					</div>
	     					<div class="form-group">
	     						<label> Nama Kategori <span class="required">*</span></label>
	     						<div class="controls">	     						
	     							<input class="form-control" type="text" name="namakategori" placeholder="Nama Kategori" value="<?php echo isset($nama_kategori) ? $nama_kategori : ''?>"  required>
	     							
	     						</div>
	     					</div>	
	     					<div class="form-group">
	     						<label>Form Order <span class="required">*</span></label>
	     						<select name="formorder" class="form-control" required>
	     							<option value="" selected disabled>-Pilih Form Order-</option>
	     							<?php foreach ($formorder as $row){
	     								$select='';
	     							if (isset($id_kategori)){
										$select = ($row->id_form_order == $id_form_order) ? 'selected' : '';}?>
	     								<option value="<?php echo $row->id_form_order?>" <?php echo $select?>><?php echo $row->nama_form?></option>
	     							<?php }?>
	     						</select>
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
       