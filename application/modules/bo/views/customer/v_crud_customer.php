<div id="page-wrapper">     
 <div class="cleaner_h50"></div>
     <div class="row">
     	<div class="col-lg-12">
     	<?php $this->load->view('bo/v_alert_notif');?> 		
     		<div class="panel panel-info">
     			<div class="panel-heading">
     				<h4><?php echo $page_title?></h4>         						        
     			</div>
     			<div class="panel-body">     			
     			<form method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
     			<?php $id_customer = isset($id_customer) ? $id_customer : ''?>
	     			<input type="hidden" name="id" value="<?php echo $id_customer?>">
	     			<?php $select='';?>
	     				<div class="row">     				
	     					<div class="col-lg-6">     						   
		     					<div class="form-group">
		     						<label> Nama Customer <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="nama" placeholder="Nama Customer" value="<?php echo isset($nama_customer) ? $nama_customer : ''?>"  required>	     							
		     						</div>
		     					</div>	
		     					<div class="form-group">
		     						<label> Email </label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo isset($email) ? $email : ''?>" >	     							
		     						</div>
		     					</div>	
		     				<?php if ($id_customer == ''){?>
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
     	<?php if ($id_customer != ''){?>
     	<div class="col-lg-12">
     		<div class="panel panel-info">
     			<div class="panel-heading">     				
     				<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modalShow('<?php echo base_url('bo/'.$class.'/alamat/'.$id_customer)?>','','myModal')">Tambah Alamat Baru</button>    				 
     			</div>
     			<div class="panel-body">
     				 <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover ss-tables">
                                    <thead>
                                        <tr><th>#</th>                                        	
                                        	<th>Nama Customer</th>                                        	
                                        	<th>Phone</th>
                                        	<th>Alamat</th>                                        	
                                        	<th>Date Add</th>
                                        	<th>Add By</th>                                       	                                      	                                        	                                     	
                                        	<th class="no-sort">Actions</th>
                                        </tr>                                      	              	
                                    </thead>     
                                    <tbody>
                                    	<?php $no=1;
                                    	foreach ($alamat->result() as $row){
											echo '<tr>
     												<td>'.$no++.'</td>
     												<td>'.$row->nama_customer.'</td>
     												<td>'.$row->phone.'</td>
     												<td>'.$row->alamat.'</td>
     												<td>'.short_date($row->date_add).'</td>
     												<td>'.$row->add_by.'</td>
     												<td>
     													<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_alamat.'\',\'alamat\')"><i class="icon-trash"></i></button>
	     						 						<button title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" onclick="modalShow(\''.base_url('bo/'.$class.'/alamat/'.$id_customer).'\',\''.$row->id_alamat.'\',\'myModal\')"><i class="icon-edit"></i></a>
									 				</td>
     				     						 </tr>'; 											
										}?>
                                    </tbody>                               
                                </table>
                            </div>
     			</div>
     		</div>
     	</div>
     	<?php }?>
     </div>
</div>
       