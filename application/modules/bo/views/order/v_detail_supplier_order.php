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
								  				Supplier
								  			</div>
								  			<div class="panel-body">
								  			<b>'.$sql[0]->nama_supplier.'</b><br/>
								  				'.$sql[0]->alamat.'<br/>
								  				'.$sql[0]->phone.'
								  			</div>
								  		</div>
								  	</div>		
							  		<div class="col-lg-4">
								  		<div class="panel panel-default">
								  			<div class="panel-body">
								  				PO Date : <b>'.long_date($sql[0]->po_date).'</b>
								  			</div>
								  		</div>
								  		<div class="panel panel-default">
								  			<div class="panel-body">
								  				PO Number : <b>'.$sql[0]->po_number.'</b>
								  			</div>
								  		</div>	
								  	</div>
							  	</div><!-- /row -->								
								<div class="table-responsive">
								  		<table class="table table-striped table-bordered table-hover default-table">
											<thead>
								  				<tr>
								  					<th>#</th>								  					
								  					<th>Deskripsi</th>
								  					<th>Qty</th>
								  				</tr>
								  			</thead>
								  			<tbody>';
											$no=1;
								  			foreach ($order_detail as $row){	
								  				if ($row->id_form_order == 'LBL'){
								  					//label
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Ukuran : (L) '.number_format($row->lebar,0).'mm x (T) '.number_format($row->tinggi,0).'mm<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = qty_format($row->qty_order).' Roll / '.qty_format($row->qty_mp_order,0).' m2';
								  				}else if ($row->id_form_order == 'CRB'){
								  					//carbon
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Ukuran : (L) '.number_format($row->lebar,0).'mm x (P) '.number_format($row->tinggi,0).'meter<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = qty_format($row->qty_order).' Roll';
								  				}else{
								  					//hardware
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = qty_format($row->qty_order).' PCS';
								  				}							  				
								  				echo '<tr><td>'.$no++.'</td>																	
									  					<td>'.$deskripsi.'</td>								  							   										  							   									  							   
									  					<td>'.$qty.'</td>									  							   								  							   	
								  				   </tr>';
								  			}	
								echo '<tr>
											<td colspan=3><b>Keterangan : </b></td>
								 	  </tr>';
							echo '<tr>
								 		<td colspan=3>'.$sql[0]->keterangan.'</td>
								 	</tr>';
								
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
