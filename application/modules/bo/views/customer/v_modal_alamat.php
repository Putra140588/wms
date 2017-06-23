<div class="modal-dialog">
     <div class="modal-content">
           <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title" id="myModalLabel"><?php echo $title?></h4>
           </div>
        <form id="frmalamat" method="post" action="<?php echo base_url('bo/'.$class.'/proses_alamat')?>">
     	<div class="modal-body">         
	     	  <input type="hidden" name="id" value="<?php echo isset($id_alamat) ? $id_alamat : ''?>">
	     	  <input type="hidden" name="idcust" value="<?php echo $id_customer?>">
	     	  <div class="form-group">
		     	<label> Nama Customer <span class="required">*</span></label>
		     	<div class="controls">	     						
		     		<input class="form-control" type="text" name="nama" placeholder="Nama Customer" value="<?php echo isset($nama_customer) ? $nama_customer : ''?>"  required>	     							
		     	</div>
		     </div>	
	     	  <div class="form-group">
		     	<label> Phone</label>
		     	<div class="controls">	     						
		     		<input class="form-control" type="text" name="phone" placeholder="Phone" value="<?php echo isset($phone) ? $phone : ''?>" >	     							
		     	</div>
		     </div>    
		     <div class="form-group">
		     	<label> Alamat</label>
		     	<div class="controls">	     						
		     		<textarea cols="3" name="alamat" rows="3" class="form-control"><?php echo isset($alamat) ? $alamat : ''?></textarea>
		     	</div>
		     </div>	     
	     
     </div>
     <div class="modal-footer">
           <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
           <button type="submit" class="btn btn-primary">Simpan</button>
     </div>
      </form>
    </div>
</div>