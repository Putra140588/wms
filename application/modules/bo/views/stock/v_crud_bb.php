<div id="page-wrapper">     
 <div class="cleaner_h50"></div>
     <div class="row">
     	<div class="col-lg-12">
     		<div class="panel panel-info">
     			<div class="panel-heading">
     				<h4><?php echo $page_title?> <?php echo isset($label_code_bb) ? '<b>('.$label_code_bb.')</b>' : ''.'</b>'?></h4>
     			</div>
     			<div class="panel-body">
     			<?php $this->load->view('bo/v_alert_notif');?>
     			<form method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<input type="hidden" name="id" value="<?php echo isset($id_stock_bb) ? $id_stock_bb : ''?>">
     			<input type="hidden" name="label_code_bb" value="<?php echo isset($label_code_bb) ? $label_code_bb : ''?>">
     			<?php $select=''; $disabled= isset($id_stock_bb) ? 'disabled' : '';?>
     				<div class="row">     				
     					<div class="col-lg-6">	  
     					<div class="form-group">
	     						<label>Bahan Baku / Kategori / Supplier <span class="required">*</span></label>
	     						<select name="idproduk" class="form-control" required <?php echo $disabled?>>
	     							<option value="" selected disabled>--Pilih Bahan Baku / Kategori / Supplier--</option>
	     							<?php   							
	     							foreach ($produk as  $row){												
										if (isset($id_stock_bb)){
											$val = $id_product.'#'.$id_kategori.'#'.$id_supplier;
											$id = $row->id_product.'#'.$row->id_kategori.'#'.$row->id_supplier;
											$select = ($id == $val) ? 'selected' : '';											
										}								     								
	     								echo '<option '.$select.'  value="'.$row->id_product.'#'.$row->id_kategori.'#'.$row->id_supplier.'">'.$row->nama_product.' / '.$row->nama_kategori.' / '.$row->nama_supplier.'</option>';
	     							}?>	     							
	     						</select>
	     				</div>     					
	     				<div class="form-group">
	     						<label>Warehouse <span class="required">*</span></label>
	     						<select name="warehouse" class="form-control" required>
	     							<option value="" selected disabled>--Pilih Warehouse--</option>
	     							<?php   							
	     							foreach ($warehouse as  $row){	     	
										if (isset($id_stock_bb)){
											$select = ($id_warehouse == $row->id_warehouse) ? 'selected' : '';
										}							
	     								echo '<option value="'.$row->id_warehouse.'" '.$select.'>'.$row->nama_warehouse .'</option>';
	     							}?>	     							
	     						</select>
	     					</div>
	     					<?php if (isset($id_stock_bb)){?>
	     					<div class="form-group">
	     						<label>Lebar (mm) <span class="required">*</span></label>
	     						<input type="text" id="lebar" name="lebar" class="form-control size" placeholder="Lebar"  onkeypress="return decimals(event,this.id)" value="<?php echo isset($lebar_bb) ? number_format($lebar_bb,0) : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Tinggi Awal (meter) <span class="required">*</span></label>
	     						<input type="text" id="tinggi" name="tinggi" class="form-control size" placeholder="Tinggi Awal"  onkeypress="return decimals(event,this.id)" value="<?php echo isset($tinggi_awal_bb) ? $tinggi_awal_bb : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Tinggi Terpakai (meter) <span class="required">*</span></label>
	     						<input type="text" id="tinggiused" name="tinggiused" class="form-control size" placeholder="Tinggi Terpakai" onkeypress="return decimals(event,this.id)" value="<?php echo isset($tinggi_terpakai_bb) ? $tinggi_terpakai_bb : '';?>" required>
	     					</div>
	     					   					
	     					<?php }else{?>
	     					<div class="form-group">
	     						<label>Lebar (mm) <span class="required">*</span></label>
	     						<input type="text" id="lebar" name="lebar" class="form-control size" placeholder="Lebar" id="lebar" onkeypress="return decimals(event,this.id)" value="<?php echo isset($lebar_bb) ? $lebar_bb : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Tinggi (meter) <span class="required">*</span></label>
	     						<input type="text" id="tinggi" name="tinggi" class="form-control size" placeholder="Tinggi" id="tinggi" onkeypress="return decimals(event,this.id)" value="<?php echo isset($tinggi_bb) ? $tinggi_bb : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Qty Roll<span class="required">*</span></label>
	     						<input type="text" name="qty" class="form-control" placeholder="Qty Roll" id="qty" onkeypress="return decimals(event,this.id)" value="<?php echo isset($qty_roll) ? $qty_roll : '';?>" required>
	     					</div>
	     					<?php }?>
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


       