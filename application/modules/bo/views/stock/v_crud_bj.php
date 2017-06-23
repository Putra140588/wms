<div id="page-wrapper">     
 <div class="cleaner_h50"></div>
     <div class="row">
     	<div class="col-lg-12">
     		<div class="panel panel-info">
     			<div class="panel-heading">
     				<h4><?php echo $page_title?> <?php echo isset($label_code_bj) ? '<b>('.$label_code_bj.')</b>' : ''.'</b>'?></h4>
     			</div>
     			<div class="panel-body">
     			<?php $this->load->view('bo/v_alert_notif');?>
     			<form method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<input type="hidden" name="id" value="<?php echo isset($id_stock_bj) ? $id_stock_bj : ''?>">
     			<input type="hidden" name="label_code_bj" value="<?php echo isset($label_code_bj) ? $label_code_bj : ''?>">
     			<?php $disabled= isset($id_stock_bj) ? 'disabled' : '';?>
     				<div class="row">     				
     					<div class="col-lg-6">	     					
	     					<div class="form-group">
	     						<label>Kategori <span class="required">*</span></label>
	     						<select name="kategori" class="form-control" onchange="show_text(this.value)" <?php echo $disabled?>>
	     							<option value="" selected disabled>Pilih Kategori</option>
	     							<?php   							
	     							foreach ($kategori->result() as  $row){
	     								if ($id_stock_bj != ''){
	     									$select = ($id_kategori == $row->id_kategori) ? 'selected' : '';
	     								}
	     								echo '<option value="'.$row->id_kategori.'#'.$row->id_form_order.'" '.$select.'>'.$row->nama_kategori.'</option>';
	     							}?>	     							
	     						</select>
	     					</div>
	     					<?php if (!isset($id_stock_bj)){?>
	     					<div class="form-group">
	     						<label>Movement <span class="required">*</span></label>
	     						<select name="movement" class="form-control" onchange="ajaxcall('<?php echo base_url('bo/'.$class.'/show_move_detail')?>',this.value,'movedetail')" required>
	     							<option value="" selected disabled>--Pilih Movement--</option>
	     							<?php   							
	     							foreach ($movement as  $row){										     								
	     								echo '<option value="'.$row->id_status_move.'">'.$row->nama_status .'</option>';
	     							}?>	     							
	     						</select>
	     					</div>
	     					<div class="form-group">
	     						<label>Keterangan Movement <span class="required">*</span></label>
	     						<select name="movedetail" class="form-control" id="movedetail" required>
	     							<option value="" selected disabled>--Pilih Keterangan Movement--</option>	     							
	     						</select>
	     					</div>
	     					<div class="form-group">
	     						<label>Lebar (mm) <span class="required">*</span></label>
	     						<input type="text" id="lebar" name="lebar" class="form-control size" placeholder="Lebar" id="lebar" onkeypress="return decimals(event,this.id)" value="<?php echo isset($lebar_bj) ? number_format($lebar_bj,0) : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Tinggi (meter) <span class="required">*</span></label>
	     						<input type="text" id="tinggi" name="tinggi" class="form-control size" placeholder="Tinggi" id="tinggi" onkeypress="return decimals(event,this.id)" value="<?php echo isset($tinggi_bj) ? number_format($tinggi_bj,0) : '';?>" required>
	     					</div>
	     					<?php }?>
	     					
	     					<div class="form-group">
	     						<label>Qty Pcs Awal<span class="required">*</span></label>
	     						<input type="text" name="qty" class="form-control" placeholder="Qty Pcs" id="qty" onkeypress="return decimals(event,this.id)" value="<?php echo isset($qty_pcs_awal) ? $qty_pcs_awal : '';?>" required>
	     					</div>
	     					<?php if (isset($id_stock_bj)){?>
	     					<div class="form-group">
	     						<label>Qty Pcs Terpakai<span class="required">*</span></label>
	     						<input type="text" name="qtyterpakai" class="form-control" placeholder="Qty Pcs Terpakai" id="qtyterpakai" onkeypress="return decimals(event,this.id)" value="<?php echo isset($qty_pcs_terpakai) ? $qty_pcs_terpakai : '';?>" required>
	     					</div>
	     					<div class="form-group">
	     						<label>Qty Pcs Tersedia<span class="required">*</span></label>
	     						<input type="text" name="qtytersedia" class="form-control" placeholder="Qty Pcs Tersedia" id="qtytersedia" onkeypress="return decimals(event,this.id)" value="<?php echo isset($qty_pcs_tersedia) ? $qty_pcs_tersedia : '';?>" required>
	     					</div>
	     					<?php }?>
	     					<div class="form-group">
	     						<label>Warehouse <span class="required">*</span></label>
	     						<select name="warehouse" class="form-control" required>
	     							<option value="" selected disabled>--Pilih Warehouse--</option>
	     							<?php   							
	     							foreach ($warehouse as  $row){	     	
										if (isset($id_stock_bj)){
											$select = ($id_warehouse == $row->id_warehouse) ? 'selected' : '';
										}							
	     								echo '<option value="'.$row->id_warehouse.'" '.$select.'>'.$row->nama_warehouse .'</option>';
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
<script>
function show_text(value){	
	var spl = value.split("#");
	var id_form = spl[1];	
	if (id_form == "HDW"){
		$(".size").attr('readonly',true);
		clear_text();
	}else{
		$(".size").attr('readonly',false);
	}
	
}
function clear_text(){
	$("#lebar").val('');
	$("#tinggi").val('');	
}
</script>

       