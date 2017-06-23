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
								  				Alamat Tagihan
								  			</div>
								  			<div class="panel-body">
								  			<b>'.$bill[0]->nama_customer.'</b><br/>
								  				'.$bill[0]->alamat.'<br/>
								  				'.$bill[0]->phone.'								  				
								  			</div>
								  		</div>
								  	</div>
							  		<div class="col-lg-4">
								  		<div class="panel panel-default">
								  			<div class="panel-heading">
								  				Alamat Pengiriman
								  			</div>
								  			<div class="panel-body">
								  				<b>'.$shipp[0]->nama_customer.'</b><br/>
								  				'.$shipp[0]->alamat.'<br/>
								  				'.$shipp[0]->phone.'	
								  			</div>
								  		</div>
								  	</div>
							  		<div class="col-lg-4">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Salesman : <b>'.$sql[0]->nama_depan.' '.$sql[0]->nama_belakang.'</b>
								  			</div>
								  		</div>
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Tgl.Order : <b>'.short_date($sql[0]->date_add).'</b>
								  			</div>
								  		</div>
								  		
								  	</div>
							  	</div><!-- /row -->
								<div class="row">
								  	<div class="col-lg-3">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Customer : <b>'.$sql[0]->nama_customer.'</b>
								  			</div>
								  		</div>
								  	</div>
								  	<div class="col-lg-2">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Terms : <b>'.$sql[0]->terms.'</b>
								  			</div>
								  		</div>
								  	</div>
								  	<div class="col-lg-2">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				PO No : <b>'.$sql[0]->po_number.'</b>
								  			</div>
								  		</div>
								  	</div>	
								  	<div class="col-lg-2">
								  		<div class="panel panel-default">								  			
								  			<div class="panel-body">	
								  				Tgl.Shipp : <b>'.short_date($sql[0]->date_shipp).'</b>
								  			</div>
								  		</div>
								  	</div>	
								  	<div class="col-lg-3">
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
								  					<th>Deskripsi</th>								  					
								  					<th>Qty</th>
								  					<th>Harga</th>
								  					<th>Amount</th>
								  				</tr>
								  			</thead>
								  			<tbody>';
											$no=1;
								  			foreach ($order_detail as $row){
								  				$subtotal = $row->harga * $row->qty_pcs;
								  				
								  				if ($row->id_form_order == 'LBL'){
								  					//label
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Ukuran : (L) '.number_format($row->lebar,0).'mm x (T) '.number_format($row->tinggi,0).'mm<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = number_format($row->qty_pcs,0,',','.').' PCS / '.number_format($row->qty_roll,0,',','.').' Roll';
								  				}else if ($row->id_form_order == 'CRB'){
								  					//carbon
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Ukuran : (L) '.number_format($row->lebar,0).'mm x (P) '.number_format($row->tinggi,0).'meter<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = number_format($row->qty_roll,0,',','.').' Roll';
								  				}else{
								  					//hardware
								  					$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Keterangan : '.$row->keterangan.'';
								  					$qty = number_format($row->qty_pcs,0,',','.').' PCS';
								  				}								  				
								  				echo '<tr><td>'.$no++.'</td>
								  						   <td>'.$deskripsi.'</td>		
								  						   <td>'.$qty.'</td>						  							   	
								  						   <td>'.$_SESSION['iso'].' '.number_format($row->harga,0).'</td>								  							   
								  						   <td>'.$_SESSION['iso'].' '.number_format($subtotal,0).'</td>
								  					</tr>';
								  			}	
								 echo '<tr><td colspan=3><b>Terbilang :</b> '.ucwords(terbilang($sql[0]->total_amount)).' '.$_SESSION['iso_code'].'</td>
								 				<td><b>Sub Total</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->subtotal,0).'</td>
								 			  </tr>';	
								 echo '<tr><td colspan=3><b>Description : </b></td>
								 				<td><b>Discount</b></td>
								 				<td></td>
								 			  </tr>';
								 echo '<tr><td colspan=3 rowspan=2>'.$sql[0]->description.'</td>
								 				<td><b>VAT '.$sql[0]->tax_value.' %</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->tax_amount,0).'</td>
								 			  </tr>';
								 echo '<tr>
								 				<td><b>Total Amount</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->total_amount,0).'</td>
								 			</tr>';
								echo '</tbody>								  			
								  		</table>
								 </form>';?>
					<div class="panel panel-default">
						<div class="panel-heading">
							History data approval
						</div>
						<div class="panel-body">
							<div class="table table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Approve BY</th>
											<th>Status Approved</th>
											<th>Tanggal</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($approval->num_rows() > 0){
											$no=1;
											foreach ($approval->result() as $row){
												if ($row->status_approve == 1){
													$approved = '<span class="label label-info">Approved 1</span>';
												}elseif ($row->status_approve == 2){
													$approved = '<span class="label label-success">Approved 2</span>';
												}elseif ($row->status_approve == 3){
													$approved = '<span class="label label-danger">No Approved</span>';
												}else{
													$approved = '<span class="label label-warning">Waiting</span>';
												}
												echo '<tr><td>'.$no++.'</td>
              											  <td>'.$row->add_by.'</td>
              											  <td>'.$approved.'</td>
								  						  <td>'.$row->date_add.'</td>
              										 </tr>';
											}
										}?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				    	<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button> 	
				    	<a href="<?php echo base_url('bo/'.$class.'/approve/'.$sql[0]->id_order)?>" class="btn btn-info">Approve</a>
				    	<a href="<?php echo base_url('bo/'.$class.'/approve/'.$sql[0]->id_order.'/1')?>" class="btn btn-danger">Not Approve</a>
				   </div>				
                   
                </div>
            </div>
              </div>
     </div>
</div>    
