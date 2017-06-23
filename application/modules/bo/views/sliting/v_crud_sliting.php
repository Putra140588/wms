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
     			<form id="form" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<div class="row">
     				<div class="col-lg-12">
     					<div class="form-group">
     						<label>Bahan Produksi</label>
     						<div class="table-responsive">
	     					<table class="table table-striped table-bordered table-hover ss-tables">
	     						<thead>
	     							<tr>
	     							<td>#</td>
	     							<td>Worksheet</td>
	     							<td>Kode Produk</td>
	     							<td>Nama Produk</td>
	     							<td>Kategori</td>
	     							<td>Qty Produksi</td>	     							
	     							<td>Action</td>
	     							</tr>
	     						</thead>
	     					</table>
     					 </div>     					 
     				</div>
     				<div class="form-group">
     						<label>Form Slitter</label>
     						<div class="table-responsive">
     							<table class="table table-striped table-bordered table-hover default-table">
     								<thead>
     									<tr><td>#</td>
     										<td>Worksheet</td>
     										<td>Kode Produk</td>
     										<td>Tanggal Selesai</td>
     										<td>Slitter</td>
     										<td>Lebar (mm)</td>
     										<td>Tinggi (mm)</td>
     										<td>Qty Roll</td>
     										<td>Qty Slitting</td>
     										<td>Warehouse</td>
     										<td>Actions</td>
     									</tr>
     								</thead>
     								<tbody id="frmsliter">
     									<?php echo $this->m_content->load_form_splitter()?>
     								</tbody>
     							</table>
     						</div>
     				</div>	
     					
     				<span class="required">*</span> Wajib diisi.
     				<div class="cleaner_h10"></div>
     				<button type="submit" class="btn btn-primary">Simpan</button>
     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>
     				</div>
     			</div>     				
     				</form>			
     			</div>
     		</div>
     	</div>
     </div>
</div>
 <input type="hidden" id="url-dt" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_records_sliting')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		primari_table(".ss-tables");	
	});
</script>           