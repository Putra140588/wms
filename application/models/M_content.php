<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class M_content extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);		
		$this->id_cart_ord = $this->session->userdata('id_cart_ord');
		$this->id_cart_spl = $this->session->userdata('id_cart_spl');
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_cart_ord;
	var $id_cart_spl;
	function load_cart(){
		$result='';
		$sql = $this->m_master->get_product_cart();
		$no=1;
		foreach ($sql as $row){		
			
			$result .='<tr>
						<input type="hidden" name="id[]" value="'.$row->id_product_cart.'">
						<input type="hidden" name="data['.$row->id_product_cart.'][id_form_order]" value="'.$row->id_form_order.'">
						<td>'.$no++.'</td>
						<td><input type="text" style="width:150px" disabled value="'.$row->nama_product.'" class="form-control"></td>		
						<td><input type="text" style="width:150px" name="data['.$row->id_product_cart.'][deskripsi]" placeholder="..." class="form-control"></td>		   		
				        <td><input type="text" style="width:80px" id="price'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][harga]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>
				        <td><input type="text" style="width:60px" id="lebar'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][lebar]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
				        <td><input type="text" style="width:60px" id="tinggi'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][tinggi]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
				        <td><input type="text" style="width:60px" id="qtypcs'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][qty_pcs]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>
				        <td><input type="text" style="width:60px" id="qtyroll'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][qty_roll]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>
				        <td><input type="text" style="width:60px" name="data['.$row->id_product_cart.'][hook]" placeholder="..." class="form-control"></td>
			            <td><input type="text" style="width:60px" id="line'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][line]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>
			            <td><input type="text" style="width:60px" id="gap_samping'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][gap_samping]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
			            <td><input type="text" style="width:60px" id="gap_atas'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][gap_atas]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
			            <td><input type="text" style="width:60px" id="material_size'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][material_size]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
			            <td><select class="form-control" style="width:75px" name="data['.$row->id_product_cart.'][security_cut]"><option value="" selected >-</option><option value="Yes">Yes</option><option value="No">No</option></select></td>
			           	<td><select class="form-control" style="width:75px" name="data['.$row->id_product_cart.'][perforation]"><option value="" selected >-</option><option value="Yes">Yes</option><option value="No">No</option><option value="Hole">Hole</option></select></td>
			            <td><select class="form-control" style="width:75px" name="data['.$row->id_product_cart.'][colour]"><option value="" selected >-</option><option value="No">No</option><option value="1C">1C</option><option value="2C">2C</option><option value="3C">3C</option><option value="4C">4C</option><option value="B-mark">B-mark</option></select></td>			            
			            <td><input type="text" style="width:60px" name="data['.$row->id_product_cart.'][colour_key]" placeholder="..." class="form-control"></td>
			            <td><input type="text" style="width:60px" id="packing_qty'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][packing_qty]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>			            
			            <td><select class="form-control" style="width:90px" name="data['.$row->id_product_cart.'][packing]"><option value="" selected >-</option><option value="Sheet">/Sheet</option><option value="Fan">/Fan</option><option value="Roll">/Roll</option><option value="Pack">/Pack</option></select></td>
			            <td><select class="form-control" style="width:75px" name="data['.$row->id_product_cart.'][core_size]"><option value="" selected >-</option><option value="0.5">0.5"</option><option value="1">1"</option><option value="1.5">1.5"</option><option value="3">3"</option></select></td>
			            <td><select class="form-control" style="width:90px" name="data['.$row->id_product_cart.'][cartoon_box]"><option value="" selected >-</option><option value="Blank">Blank</option><option value="Logo">Logo</option></select></td>
			            <td><select class="form-control" style="width:100px" name="data['.$row->id_product_cart.'][cutter_sketch]"><option value="" selected >-</option><option value="1">Roll In</option><option value="2">Roll Out</option></select></td>
						<td><input type="text" style="width:60px" id="cutter_sketch_lebar'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][cutter_sketch_lebar]" onkeypress="return decimals(event,this.id)" placeholder="...mm" class="form-control"></td>
			            <td><input type="text" style="width:60px" id="cutter_sketch_tinggi'.$row->id_product_cart.'" name="data['.$row->id_product_cart.'][cutter_sketch_tinggi]" onkeypress="return decimals(event,this.id)" placeholder="...m" class="form-control"></td>		
			            <td><input type="text" style="width:150px" name="data['.$row->id_product_cart.'][keterangan]" placeholder="..." class="form-control"></td>			            	            
			            <td>
							<center><button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/mporder/delete_cart').'\',\''.$row->id_product_cart.'\',\'cartproduct\')"><i class="icon-trash"></i></button></center>
						</td>
				 </tr>';
	
			$result .='';
		}
		return $result;
	}
	function modal_summary_order(){
		$result='';
		$sql = $this->m_master->get_order_cart(array('id_cart_ord'=>$this->id_cart_ord));
		
		$bill = $this->m_master->get_alamat_order($sql[0]->id_alamat_tagihan);
		$shipp = $this->m_master->get_alamat_order($sql[0]->id_alamat_pengiriman);
		$prod_cart = $this->m_master->get_product_cart();
		$result .='<div class="modal-dialog" style="width:65%">
				     <div class="modal-content">
				           <div class="modal-header">
				           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				               <h4 class="modal-title" id="myModalLabel">Summary Order</h4>
							   <div class="success" style="display:none">'.$this->session->flashdata('success').'</div>
							   <div class="danger" style="display:none">'.$this->session->flashdata('danger').'</div>	
				           </div>
				        <form id="frmorder" method="post">
					     	<div class="modal-body">
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
								  				Tgl.Pengiriman : <b>'.short_date($sql[0]->date_shipp).'</b>
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
								  			foreach ($prod_cart as $row){
								  				$qty = ($row->id_form_order == 'CRB') ? $row->qty_roll : $row->qty_pcs;
								  				$subtotal = $row->harga * $qty;
								  				
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
								  				$result .='<tr><td>'.$no++.'</td>
								  							   <td>'.$deskripsi.'</td>		
								  							   	<td>'.$qty.'</td>						  							   	
								  							   	<td>'.$_SESSION['iso'].' '.number_format($row->harga,0).'</td>								  							   
								  							   	<td>'.$_SESSION['iso'].' '.number_format($subtotal,0).'</td>
								  							</tr>';
								  			}	
								 $result .='<tr><td colspan=3><b>Terbilang :</b> '.ucwords(terbilang($sql[0]->total_amount)).' '.$_SESSION['iso_code'].'</td>
								 				<td><b>Sub Total</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->subtotal,0).'</td>
								 			  </tr>';	
								 $result .='<tr><td colspan=3><b>Description : </b></td>
								 				<td><b>Discount</b></td>
								 				<td></td>
								 			  </tr>';
								 $result .='<tr><td colspan=3 rowspan=2>'.$sql[0]->description.'</td>
								 				<td><b>VAT '.$sql[0]->tax_value.' %</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->tax_amount,0).'</td>
								 			  </tr>';
								 $result .='<tr>
								 				<td><b>Total Amount</b></td>
								 				<td>'.$_SESSION['iso'].' '.number_format($sql[0]->total_amount,0).'</td>
								 			</tr>';
								$result .='</tbody>								  			
								  		</table>
								  </div>';
					 $result .='</div><!-- /body -->
						     <div class="modal-footer">
						           <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
						           <button type="button" class="btn btn-primary" onclick="addform(\''.base_url("bo/mporder/create_order").'\',\'tes\',\'tes\')">Create Order</button>
						     </div>
				     	 </form>
				    </div>
				</div>';
		return $result;
	}
	function modal_summary_spl_ord(){
		$result='';
		$sql = $this->m_master->get_spl_cart(array('id_cart_spl'=>$this->id_cart_spl));		
		$spl_cart_detail = $this->m_master->get_table_column('*','tb_spl_ord_cart_det',array('id_cart_spl'=>$this->id_cart_spl));
		$result .='<div class="modal-dialog" style="width:65%">
				     <div class="modal-content">
				           <div class="modal-header">
				           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				               <h4 class="modal-title" id="myModalLabel">Summary Supplier Order</h4>
							   <div class="success" style="display:none">'.$this->session->flashdata('success').'</div>
							   <div class="danger" style="display:none">'.$this->session->flashdata('danger').'</div>
				           </div>
				        <form id="frmorder" method="post">
					     	<div class="modal-body">
							  	<div class="row">
							     	<div class="col-lg-4">
								  		<div class="panel panel-primary">
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
								  		<div class="panel panel-primary">
								  			<div class="panel-body">
								  				PO Date : <b>'.long_date($sql[0]->po_date).'</b>
								  			</div>
								  		</div>
								  		<div class="panel panel-primary">
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
									foreach ($spl_cart_detail as $row){	
										if ($row->id_form_order == 'LBL'){
											//label
											$deskripsi = '<b>'.strtoupper($row->deskripsi).'</b><br/>
								  							   		Type : '.$row->nama_kategory.'<br/>
								  							   		Ukuran : (L) '.number_format($row->lebar,0).'mm x (T) '.number_format($row->tinggi,0).'mm<br/>
								  							   		Keterangan : '.$row->keterangan.'';
											$qty = number_format($row->qty_roll,0,',','.').' Roll / '.number_format($row->qty_mp,0,',','.').' m2';
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
											$qty = number_format($row->qty_roll,0,',','.').' PCS';
										}											
										$result .='<tr><td>'.$no++.'</td>																	
									  					<td>'.$deskripsi.'</td>								  							   										  							   									  							   
									  					<td>'.$qty.'</td>									  							   								  							   	
								  				   </tr>';
										}		
								   $result .='<tr>
												<td colspan=3><b>Keterangan : </b></td>								 				
								 			  </tr>';
								    $result .='<tr>
								 				<td colspan=5>'.$sql[0]->keterangan.'</td>								 				
								 			  </tr>';
											$result .='</tbody>
															</table>
														</div>';
								$result .='</div><!-- /body -->
						     <div class="modal-footer">
						           <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
						           <button type="button" class="btn btn-primary" onclick="addform(\''.base_url("bo/mpsplorder/create_order").'\',\'tes\',\'tes\')">Create Order</button>
						     </div>
				     	 </form>
				    </div>
				</div>';
		return $result;
	}
	function load_cart_spl(){
		$result='';
		$sql = $this->m_master->get_table_column('*','tb_spl_ord_cart_det',array('id_cart_spl'=>$this->id_cart_spl));
		foreach ($sql as $row){
			$disabled = ($row->id_form_order == 'HDW') ? 'readonly':'';
			$result .='<tr>
						<input type="hidden" name="id[]" value="'.$row->id_spl_ord_cart_det.'">
						<td>'.$row->nama_product.'</td>
				   		<td><input type="text" name="data['.$row->id_spl_ord_cart_det.'][deskripsi]" placeholder="..." class="form-control"></td>				        
				        <td><input type="text" '.$disabled.' id="lebar'.$row->id_spl_ord_cart_det.'" name="data['.$row->id_spl_ord_cart_det.'][lebar]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>
				        <td><input type="text" '.$disabled.' id="tinggi'.$row->id_spl_ord_cart_det.'" name="data['.$row->id_spl_ord_cart_det.'][tinggi]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>				       
				        <td><input type="text" id="qtyroll'.$row->id_spl_ord_cart_det.'" name="data['.$row->id_spl_ord_cart_det.'][qty_roll]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>				        
				        <td><input type="text" name="data['.$row->id_spl_ord_cart_det.'][type]" placeholder="..." class="form-control"></td>
			            <td><input type="text" name="data['.$row->id_spl_ord_cart_det.'][keterangan]" placeholder="..." class="form-control"></td>
						<td>
							<center><button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/mpsplorder/delete_cart').'\',\''.$row->id_spl_ord_cart_det.'\',\'splcart\')"><i class="icon-trash"></i></button></center>
						</td>
				 </tr>';	
			$result .='';
		}
		return $result;
	}
	function table_stock_info($value,$id_kategori){
		$result='';		
		$sql = $this->m_master->get_stock_bb(array('label_code_bb'=>$value));
		if (count($sql) > 0){
			foreach ($sql as $row){
				//jika kategori sama dengan yang dipesan customer
				if ($row->id_kategori == $id_kategori){
					$result .='<input type="hidden" name="lebar_bb" value="'.$row->lebar_bb.'">';
					$result .='<input type="hidden" name="label_code" value="'.$row->label_code_bb.'">';
					$result .='<div class="table-responsive">
                				<table class="table table-striped">
                					<tbody>
                						<tr>
											<td><b>Kode Produk</b></td><td>:</td><td>'.$row->label_code_bb.'</td>
											<td><b>Tinggi Terpakai (m)</b><td>:</td><td>'.(int)$row->tinggi_terpakai_bb.'</td>        
											               								
										</tr>
                						<tr>
                							<td><b>Nama Produk</b></td><td>:</td><td>'.$row->nama_product.'</td>  
                							<td><b>Tinggi Tersedia (m)</b><td>:</td><td>'.(int)$row->tinggi_tersedia_bb.'</td> 
                							                								              							
                						</tr>          
                						<tr>
                							<td><b>Kategori</b><td>:</td><td>'.$row->nama_kategori.'</td>   
                							<td><b>Luasan Awal (m<sup>2</sup>)</b><td>:</td><td>'.(int)$row->luasan_awal_bb.'</td> 
                							   								
                						</tr>  
                						<tr>
                							<td><b>Supplier</b><td>:</td><td>'.$row->nama_supplier.'</td>     
                							<td><b>Luasan Terpakai (m<sup>2</sup>)</b><td>:</td><td>'.(int)$row->luasan_terpakai_bb.'</td>                 
                							    								
                						</tr>
                						<tr>
                							<td><b>Lebar (mm)</b><td>:</td><td>'.(int)$row->lebar_bb.'</td> 
                							<td><b>Luasan Tersedia (m<sup>2</sup>)</b><td>:</td><td>'.(int)$row->luasan_tersedia_bb.'</td>               								
                						</tr>
                						<tr>
                							<td><b>Tinggi Awal (m)</b><td>:</td><td>'.(int)$row->tinggi_awal_bb.'</td>
                							<td></td><td></td><td></td>             								
                						</tr>
                						  						
                					</tbody>
                				</table>
                			</div>';
				}else{
					$result.='<div class="alert alert-danger">Kategori produk tidak sesuai yang dipesan!</div>';
				}
			}
		}else{
			$result.='<div class="alert alert-danger">Kode Produk tidak terdaftar!</div>';
		}
		return $result;
	}
	function table_produksi($id){
		$result='';
		$sql = $this->m_master->get_table_produksi($id);
		if ($sql->num_rows() > 0){
			$no=1;
			foreach ($sql->result() as $row){
				$result .='<tr>
							<td>'.$no++.'</td>
							<td>'.$row->label_code_bb.'</td>
							<td>'.$row->nama_product.'</td>
							<td>'.$row->nama_kategori.'</td>
							<td>'.$row->nama_supplier.'</td>
							<td>'.(int)$row->tinggi_terpakai.'</td>
							<td>'.(int)$row->tinggi_sisa.'</td>
							<td>'.(int)$row->qty_produksi.'</td>
							<td>'.$row->nama_status.'</td>
							<td>'.$row->nama_depan.' '.$row->nama_belakang.'</td>
							<td>'.$row->date_report.'</td>
							<td>'.$row->catatan.'</td>
							<td>'.$row->date_add.'</td>
							<td>'.$row->add_by.'</td>
							<td><a onclick="return confirm(\'Anda yakin ingin menghapus data produksi ?\')" title="'.$this->config->config['delete'].'" id="delete" class="btn btn-danger btn-circle" href="'.base_url('bo/mpproduksi/delete/'.$row->id_produksi).'"><i class="icon-trash"></i></a></td>
							</tr>';
			}
			$sum = $this->m_master->get_tinggi_terpakai($id);			
			$result .='<tr><td colspan=11></td>
							<td colspan=3>Total Tinggi Terpakai (m): </td>
						   <td>'.$sum[0]->tinggi_terpakai.'</td>
					  </tr>';			
			$result .='<tr><td colspan=11></td>
							<td colspan=3>Qty Produksi (pcs): </td>
						   <td>'.$sum[0]->qty_produksi.'</td>
					</tr>';
		}else{
			$result .='<tr><td colspan=15> <center> -- History data produksi kosong -- </td></tr>';
		}
		return $result;
	}
	function load_form_splitter(){
		$result='';
		$karyawan = $this->m_master->get_karyawan();//only show operator
		$warehouse = $this->m_master->get_table_column(array('id_warehouse','nama_warehouse'),'tb_warehouse',array('deleted'=>1));
		$where = array('deleted'=>1,'saving'=>0);
		$sql = $this->m_master->get_table_column('*','tb_sliting',$where);
		$no=1;
		if (count($sql) > 0){
			foreach ($sql as $row){
				$result .='';
				$result .='<tr><input type="hidden" name="id[]" value="'.$row->id_sliting.'">
							  <td>'.$no++.'</td>
							   <td><input type="text" style="width:150px" name="data['.$row->id_sliting.'][ws_number]" class="form-control" value="'.$row->ws_number.'" readonly></td>
							   <td><input type="text" style="width:205px" name="data['.$row->id_sliting.'][label_code_bb]" class="form-control" value="'.$row->label_code_bb.'" readonly></td>	
							   <td><input type="text" name="data['.$row->id_sliting.'][date_sliting]" class="form-control date-form"></td>			
							   <td><select name="data['.$row->id_sliting.'][id_karyawan]" class="form-control" style="width:150px">	
										<option value="" selected disabled> -- Pilih Slitter -- </option>';
									foreach ($karyawan as $val){
										$result .='<option value="'.$val->id_karyawan.'">'.$val->nama_depan.' '.$val->nama_belakang.'</option>';
									}	
								$result .='</select></td>';	
					$result .='<td><input type="text" id="lebar_sliting.'.$row->id_sliting.'" name="data['.$row->id_sliting.'][lebar_sliting]" class="form-control" onkeypress="return decimals(event,this.id)"></td>
							   <td><input type="text" id="tinggi_sliting.'.$row->id_sliting.'" name="data['.$row->id_sliting.'][tinggi_sliting]" class="form-control" onkeypress="return decimals(event,this.id)"></td>
							   <td><input type="text" id="qty_roll.'.$row->id_sliting.'" name="data['.$row->id_sliting.'][qty_roll_sliting]" class="form-control" onkeypress="return decimals(event,this.id)"></td>
							   <td><input type="text" id="qty_sliting.'.$row->id_sliting.'" name="data['.$row->id_sliting.'][qty_pcs_sliting]" class="form-control" onkeypress="return decimals(event,this.id)"></td>';
					$result .='<td><select name="data['.$row->id_sliting.'][id_warehouse]" class="form-control" style="width:150px">
									<option value="" selected disabled>--Pilih Warehouse--</option>';
									foreach ($warehouse as $w){
										$result .='<option value="'.$w->id_warehouse.'">'.$w->nama_warehouse.'</option>';
									}
					$result .='</select></td>';
					$result .='<td>
									<center><button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/mpsliting/delete_form').'\',\''.$row->id_sliting.'\',\'frmsliter\')"><i class="icon-trash"></i></button></center>
						       </td>
							</tr>';
			}
		}else{
			$result .='<tr><td colspan=11><center>-- Form Slitter Kosong --</td></tr>';
		}
		return $result;		
	}
	function delivery_order_detail($id_order){
		$res='';
		$sql = $this->m_master->get_sales_order(array('A.id_order'=>$id_order));
		$no=1;
		foreach ($sql as $row){
			$res .= '<tr><td>'.$no++.'</td>
						<td>'.$row->po_number.'</td>
						<td>'.$row->nama_product.'</td>
						<td>'.$row->deskripsi.'</td>
						<td>'.$row->nama_kategory.'</td>
						<td>'.$row->lebar.'</td>
						<td>'.$row->tinggi.'</td>
						<td>'.qty_format($row->qty_pcs).'</td>
					</tr>';
		}
		return $res;
	}
	function delivery_cart_content(){
		$res='';
		$content_cart = $this->cart->contents();
		$no=1;
		foreach ($content_cart as $val){
			$res .='<tr>
					<input type="hidden" name="rowid[]" value="'.$val['id'].'">					
					<td>'.$no++.'</td>
					<td>'.$val['id'].'</td>
					<td>'.$val['name'].'</td>
					<td>'.$val['lebar'].'</td>
					<td>'.$val['tinggi'].'</td>
					<td><input type="text" name="data['.$val['id'].'][qty_delivery]" placeholder="Qty" class="form-control" style="width:100px"></td>
					<td><input type="text" name="data['.$val['id'].'][serial_number]" placeholder="Serial Number" class="form-control"></td>
					<td>
						<center><button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/mpgoodout/delete_cart').'\',\''.$val['rowid'].'\',\'cartproduct\')"><i class="icon-trash"></i></button></center>
					</td>
				</tr>';
		}
		return $res;
	}
	
}