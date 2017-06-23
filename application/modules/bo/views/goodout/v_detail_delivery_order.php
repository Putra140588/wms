<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
        </div>
        <div class="row">
                <div class="col-lg-12"> 
                <div class="panel panel-info">                       
                         <div class="panel-body">               
                    <?php                          			    				          		
          		echo '<form id="frmorder" method="post">					     
							  	<div class="row">							     	
							  		<div class="col-lg-4">
								  		<div class="panel panel-default">
								  			<div class="panel-heading">
								  				Alamat Pengiriman
								  			</div>
								  			<div class="panel-body">
								  			 <b>'.$sql[0]->nama_customer.'</b><br/>
								  				'.$sql[0]->alamat.'<br/>
								  				'.$sql[0]->phone.'	
								  			</div>
								  		</div>
								  	</div>
							  		<div class="col-lg-3">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Delivery No : <b>'.$sql[0]->do_number.'</b>
								  			</div>
								  		</div>
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Tgl.Pengiriman : <b>'.short_date($sql[0]->date_add).'</b>
								  			</div>
								  		</div>								  		
								  	</div>
								  	<div class="col-lg-3">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				PO No : <b>'.$sql[0]->po_number.'</b>
								  			</div>
								  		</div>
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Shipp Via : <b>'.$sql[0]->nama_courier.'</b>
								  			</div>
								  		</div>								  		
								  	</div>
							  	</div><!-- /row -->
								
								<div class="table-responsive">
								  		<table class="table table-striped table-bordered table-hover default-table">
											<thead>
								  				<tr>
								  					<th>#</th>
								  					<th>Kode Bahan Jadi</th>								  					
								  					<th>Deskripsi</th>
								  					<th>Qty</th>
								  					<th>Serial Number</th>
								  				</tr>
								  			</thead>
								  			<tbody>';
											$no=1;
								  			foreach ($order_detail as $row){								  												  				
								  				if ($row->id_form_order == 'LBL'){
								  					//label
								  					$deskripsi = '<b>'.strtoupper($row->nama_kategori).'</b><br/>								  							   	   
								  							   		Ukuran : (L) '.number_format($row->lebar_bj,0).'mm x (T) '.number_format($row->tinggi_bj,0).'mm<br/>';								  							   		
								  					$qty = qty_format($row->qty_delivery).' PCS';
								  				}else if ($row->id_form_order == 'CRB'){
								  					//carbon
								  					$deskripsi = '<b>'.strtoupper($row->nama_kategori).'</b><br/>								  							   		
								  							   		Ukuran : (L) '.number_format($row->lebar_bj,0).'mm x (P) '.number_format($row->tinggi_bj,0).'meter<br/>';								  							   		
								  					$qty = qty_format($row->qty_delivery).' Roll';
								  				}else{
								  					//hardware
								  					$deskripsi = '<b>'.strtoupper($row->nama_kategori).'</b><br/>';							  							   		
								  					$qty = qty_format($row->qty_delivery).' Roll';
								  				}
								  				
								  				echo '<tr><td>'.$no++.'</td>
								  						   <td>'.$row->label_code_bj.'</td>
								  						   <td>'.$deskripsi.'</td>		
								  						   <td>'.$qty.'</td>						  							   	
								  						   <td>'.$row->serial_number.'</td>								  							   
								  						  
								  					</tr>';
								  			}									
								 
								 
								 echo '<tr><td colspan=5><b>Description : </b></td></tr>';
								 echo '<tr><td colspan=5>'.$sql[0]->description.'</td></tr>';
								 				
								 			  
								 			  
								echo '</tbody>								  			
								  		</table>
								 </form>';?>
					
				    	<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button> 	
				   </div>				
                   
                </div>
            </div>
              </div>
     </div>
</div>    
