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
     			<input type="hidden" name="id" value="<?php echo isset($id_karyawan) ? $id_karyawan : ''?>">
     			<?php $select='';?>
     				<div class="row">     				
     					<div class="col-lg-6">
	     					<div class="form-group">
	     						<label> NIK <span class="required">*</span></label>
	     						<div class="controls">	     						
	     							<input class="form-control" type="text" name="nik" placeholder="NIK" onchange="ajaxcall('<?php echo base_url('bo/mpkaryawan/cek_nik')?>',this.value,'nik')" value="<?php echo isset($nik) ? $nik : ''?>"  required>
	     							<span id="nik" class="required"></span>
	     						</div>
	     					</div>
	     					<div class="form-group">
	     						<label>Jenis Kelamin <span class="required">*</span></label>
	     						<select name="jenkel" class="form-control">
	     							<option value="" selected>Pilih Jenis Kelamin</option>
	     							<?php $jen_array = array('Laki-laki','Perempuan');	     							
	     							foreach ($jen_array as $row){
	     								if ($id_karyawan != ''){
	     									$select = ($jenkel == $row) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row.'" '.$select.'>'.$row.'</option>';
	     							}?>
	     							
	     						</select>
	     					</div>
	     					<div class="form-group">
	     						<label>Nama Depan <span class="required">*</span></label>
	     						<div class="controls">
	     							<input class="form-control" type="text" name="namadepan" placeholder="Nama Depan" value="<?php echo isset($nama_depan) ? $nama_depan : ''?>" required>
	     						</div>
	     					</div>
	     					<div class="form-group">
	     						<label>Nama Belakang </label>
	     						<div class="controls">
	     							<input class="form-control" type="text" name="namabelakang" placeholder="Nama Belakang" value="<?php echo isset($nama_belakang) ? $nama_belakang : ''?>">
	     						</div>
	     					</div>
	     					<div class="form-group">
	     						<label>Email <span class="required">*</span></label>
	     						<input class="form-control" type="text" name="email" placeholder="Email" onchange="ajaxcall('<?php echo base_url('bo/mpkaryawan/cek_email')?>',this.value,'email')" required value="<?php echo isset($email) ? $email : ''?>">
	     						<span id="email" class="required"></span>
	     					</div>
	     					
     					</div>
     					<div class="col-lg-6">
     						<div class="form-group">
	     						<label>Password <span class="required">*</span></label>
	     						<input class="form-control" type="password" name="password" placeholder="Password" onchange="ajaxcall('<?php echo base_url('bo/mpkaryawan/lengt_pass')?>',this.value,'pass')">
	     						( Minimal 8 Karater )<br>
	     						<span id="pass" class="required"></span>
	     					</div>
     						<div class="form-group">
	     						<label>Phone</label>
	     						<input class="form-control" type="text" name="phone" placeholder="Phone" value="<?php echo isset($phone) ? $phone : ''?>">
	     					</div>
     						<div class="form-group">
	     						<label>Jabatan <span class="required">*</span></label>
	     						<select class="form-control" name="jabatan" required>
	     							<option value="" selected>Pilih Jabatan</option>
	     							<?php foreach ($jabatan->result() as $row){
	     								if ($id_karyawan != ''){
	     									$select = ($id_jabatan == $row->id_jabatan) ? 'selected' : ''; 
	     								}
	     								echo '<option value="'.$row->id_jabatan.'" '.$select.'>'.$row->nama_jabatan.'</option>';
	     							}?>
	     						</select>
	     					</div>
     						<div class="form-group">
     							<label>Bagian <span class="required">*</span></label>
     							<select class="form-control" name="bagian" required>
	     							<option value="" selected>Pilih Bagian</option>	
	     							<?php foreach ($bagian->result() as $row){
	     								if ($id_karyawan != ''){
	     									$select = ($id_bagian == $row->id_bagian) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row->id_bagian.'" '.$select.'>'.$row->nama_bagian.'</option>';
	     							}?>
	     						</select>
     						</div>
     						<div class="form-group">
     							<label>Group <span class="required">*</span></label>
     							<select class="form-control" name="group" required>
	     							<option value="" selected>Pilih Group</option>	
	     							<?php foreach ($group->result() as $row){
	     								if ($id_karyawan != ''){
	     									$select = ($kd_group == $row->kd_group) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row->kd_group.'" '.$select.'>'.$row->nama_group.'</option>';
	     							}?>
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
       