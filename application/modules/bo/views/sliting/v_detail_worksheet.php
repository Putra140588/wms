<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2>               
        </div>
        <form id="form" method="post" action="<?php echo base_url('bo/mpproduksi/create_produksi')?>" class="tableproduksi">
        <input type="hidden" name="ws_number" value="<?php echo $ws_number?>">
        <input type="hidden" name="id_order" value="<?php echo $id_order?>">
        <input type="hidden" name="label_code" id="labelcode">
        <div class="row">
                <div class="col-lg-6">
                	<div class="panel panel-default">
                		<div class="panel-heading">
                			Information Work Sheet No <b># <?php echo $ws_number?></b>
                		</div>
                		<div class="panel-body">
                			<div class="table-responsive">
                				<table class="table table-striped">
                					<tbody>
                						<tr><td><b>SO Number</b></td><td>:</td><td><?php echo $id_order?></td></tr>
                						<tr><td><b>ID Customer</b><td>:</td><td><?php echo $id_customer?></td></tr>
                						<tr><td><b>Customer</b></td><td>:</td><td><?php echo $nama_customer?></td></tr>
                						<tr><td><b>Alamat Pengiriman</b></td><td>:</td><td><?php echo $id_order?></td></tr>
                						<tr><td><b>Alamat Penagihan</b></td><td>:</td><td><?php echo $id_order?></td></tr>
                						<tr><td><b>Tgl Order</b></td><td>:</td><td><?php echo $date_add?></td></tr>
                						<tr><td><b>Tgl Kirim</b></td><td>:</td><td><?php echo $date_shipp?></td></tr>
                						<tr><td><b>Salesman</b></td><td>:</td><td><?php echo $nama_depan.' '.$nama_belakang?></td></tr>
                						<tr><td><b>Add By</b></td><td>:</td><td><?php echo $add_by?></td></tr>
                					</tbody>
                				</table>
                			</div>
                		</div>
                	</div>                    
                </div>
                 <div class="col-lg-6">
                	<div class="panel panel-default">
                		<div class="panel-heading">
                			Production Material Information Order
                		</div>
                		<div class="panel-body">
                			<div class="table-responsive">
                				<table class="table table-striped">
                					<tbody>
                						<tr>
                							<td><b>Nama Produk</b></td><td>:</td><td><?php echo $nama_product?></td>
                							<td><b>Hook</b></td><td>:</td><td><?php echo $hook?></td>
                						</tr>
                						<tr>
                								<td><b>Kategori</b><td>:</td><td><?php echo $nama_kategory?></td>
                								<td><b>Line</b></td><td>:</td><td><?php echo $line?></td>
                						</tr>
                						<tr>
                								<td><b>Deskripsi</b></td><td>:</td><td><?php echo $deskripsi?></td>
                								<td><b>Gap Samping</b></td><td>:</td><td><?php echo $gap_samping?></td>
                						</tr>
                						<tr>
                								<td><b>Lebar (mm)</b></td><td>:</td><td><?php echo $lebar?></td>
                								<td><b>Gap Atas</b></td><td>:</td><td><?php echo $gap_atas?></td>
                						</tr>
                						<tr>
                							<td><b>Tinggi (mm)</b></td><td>:</td><td><?php echo $tinggi?></td>
                							<td><b>Material Size</b></td><td>:</td><td><?php echo $material_size?></td>
                						</tr>
                						<tr>
                							<td><b>Qty Order</b></td><td>:</td><td><?php echo $qty_pcs?></td>
                							<td><b>Security Cut</b></td><td>:</td><td><?php echo $security_cut?></td>
                						</tr>
                						<tr>
                							<td><b>Qty Roll</b></td><td>:</td><td><?php echo $qty_roll?></td>
                							<td><b>Perforation</b></td><td>:</td><td><?php echo $perforation?></td>
                						</tr>
                						<tr>
                							<td><b>PC/Roll</b></td><td>:</td><td><?php echo $pcsroll?></td>
                							<td><b>Colour</b></td><td>:</td><td><?php echo $colour?></td>
                						</tr>
                						<tr>
                							<td><b>Supplier</b></td><td>:</td><td><?php echo $nama_supplier?></td>
                							<td><b>Colour Key</b></td><td>:</td><td><?php echo $colour_key?></td>
                						</tr>
                					</tbody>
                				</table>
                			</div>
                		</div>
                	</div>                    
                   
                </div>
         </div>              
         <div class="row">
         <div class="col-lg-6">
         <?php $this->load->view('bo/v_alert_notif');?>             
         	<div class="panel panel-default">
         		<div class="panel-heading">
         			Operator Report
         		</div>
         		<div class="panel-body">         			
         				<div class="form-group">
         					<label>Kode Produk</label>
         					<input type="text" class="form-control" name="<?php echo base_url('bo/mpproduksi/enter_code')?>"  id="keycode" placeholder="Entry / Scan Barcode" autocomplete="off">
         				</div>
         				<div class="form-group">
         					<div id="produkstock"></div>
         				</div>
         				<div class="form-group">
         					<label>Keterangan <span class="required">*</span></label>
         					<select name="status" class="form-control" onchange="show_produksi(this.value)" required>
         						<option value="" selected disabled> -- Pilih Keterangan -- </option>
         						<?php foreach ($status->result() as $row){
         						 echo '<option value="'.$row->id_status_produksi.'">'.$row->nama_status.'</option>';
         						}?>
         					</select>
         				</div>
         				<div class="form-group produksi">
         					<label>Tinggi Terpakai (m)</label>
         					<input type="text" name="panjangterpakai" id="Tterpakai" class="form-control" placeholder="Tinggi Terpakai" onkeypress="return decimals(event,this.id)">
         				</div>
         				<div class="form-group produksi">
         					<label>Tinggi Sisa (m)</label>
         					<input type="text" name="panjangsisa" id="Psisa" class="form-control" placeholder="Tinggi Sisa" onkeypress="return decimals(event,this.id)">
         				</div>
         				<div class="form-group produksi">
         					<label>Qty Produksi</label>
         					<input type="text" name="qty" id="qty" class="form-control" placeholder="Qty Produksi" onkeypress="return decimals(event,this.id)">
         				</div>
         				<div class="form-group">
         					<label>Operator <span class="required">*</span></label>
         					<select name="operator" class="form-control" required>
         						<option value="" selected disabled> -- Pilih Operator -- </option>
         						<?php foreach ($karyawan as $row){
         						 echo '<option value="'.$row->id_karyawan.'">'.$row->nama_depan.' '.$row->nama_belakang.'</option>';
         						}?>
         					</select>
         				</div>
         				<div class="form-group">
         					<label>Tanggal <span class="required">*</span></label>
         					<input type="text" id="datepicker" name="dateproduksi" placeholder="Tanggal" class="form-control" required>
         				</div> 
         				<div class="form-group">
         					<label>Progress Status <span class="required">*</span></label>
         					<select name="progresstate" class="form-control" required>
         						<option value="" selected disabled> -- Pilih Status -- </option>
         						<?php foreach ($status_work->result() as $row){
         						 echo '<option value="'.$row->id_status_work.'">'.$row->nama_status.'</option>';
         						}?>
         					</select>
         				</div>
         				<div class="form-group">
         					<label>Catatan</label>
         					<textarea rows="3" cols="3" name="catatan" class="form-control" placeholder="Catatan"></textarea>
         				</div>  
         				<span class="required">*</span> Wajib diisi.
     				<div class="cleaner_h10"></div>
     				<button type="submit" class="btn btn-primary">Simpan</button>
     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>       				
         		</div>
         	</div>
         </div>
         	<div class="col-lg-6">
         		<div class="panel panel-default">
                		<div class="panel-heading">
                			Packing Instruction
                		</div>
                		<div class="panel-body">
                			<div class="table-responsive">
                				<table class="table table-striped">
                					<tbody>
                						<tr><td><b>Packing</b></td><td>:</td><td><?php echo $packing?></td></tr>
                						<tr><td><b>Core Size</b><td>:</td><td><?php echo $core_size?>"</td></tr>
                						<tr><td><b>Cartoon Box</b></td><td>:</td><td><?php echo $cartoon_box?></td></tr>
                						<tr><td><b>Cutter Sketch</b></td><td>:</td><td><?php echo $nama_sketch.' ('.$cutter_sketch_tinggi.' m)'?><br><img src="<?php echo base_url($image)?>"><br>(<?php echo $cutter_sketch_lebar?> mm)</td></tr>                						
                					</tbody>
                				</table>
                			</div>
                		</div>
                	</div>                    
         	</div>
         </div>
         <div class="row">
         	<div class="col-lg-12">
         		<div class="panel panel-default">
         			<div class="panel-heading">
         				History Data Produksi
         			</div>
         			<div class="panel-body">
         				<div class="table-responsive">
		         			<table class="table table-striped table-bordered">
		         				<thead>
		         					<tr>
		         					<th>#</th>
		         					<th>Kode Produk</th>
		         					<th>Nama Produk</th>
		         					<th>Kategori</th>
		         					<th>Supplier</th>
		         					<th>Panjang Terpakai (m)</th>
		         					<th>Panjang Sisa (m)</th>
		         					<th>Qty Produksi</th>
		         					<th>Keterangan</th>
		         					<th>Operator</th>
		         					<th>Tanggal</th>
		         					<th>Catatan</th>
		         					<th>Date Add</th>
		         					<th>Add By</th>
		         					</tr>
		         				</thead>
		         				<tbody id="tableproduksi">
		         					<?php echo $produksi?>
		         				</tbody>
		         			</table>
		         		</div>
         			</div>
         		</div>         		
         	</div>
         </div>
        </form>
     </div>
</div>    
<script type="text/javascript">
 $(document).ready(function(){
	 $(".produksi").hide();
	 clear_text();
 });
function show_produksi(value){
	if (value == 1){
		$(".produksi").hide();
	}else{
		$(".produksi").toggle();
	}
	clear_text();
}
function clear_text(){
	$("#qty").val('');
	$("#Tterpakai").val('');
	$("#Psisa").val('');
}
</script>
 