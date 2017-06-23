<div class="title-letter">PRODUCTION WORK SHEET</div>
<div class="invoice-date"><?php echo date('d M Y')?></div>
	 <div id="details" class="clearfix">
	 	<div id="client">
	 		 <div class="to">CUSTOMER</div>
	 		 <h2 class="name"><?php echo $nama_customer?></h2>
	 		 ID : <?php echo $id_customer?><br>
	 		 Tgl.Order : <?php echo date('d M Y',strtotime($date_add))?><br>
	 		 Tgl.Kirim : <?php echo date('d M Y',strtotime($date_shipp))?>
	 	</div>
	 	<div id="invoice">
	 		<h1>WS No: <?php echo $ws_number;?></h1>
	 		SO No : <?php echo $id_order?><br>
	 		Salesman : <?php echo $nama_depan.' '.$nama_belakang?><br>
	 		Create By : <?php echo $add_by?>
	 	</div>
	 </div>
     <!-- description content -->
     <h3>Material Information</h3>
     <table>			
        <tbody>	
		<tr><td class="no">Nama Produk</td>
			<td class="desc"><?php echo $nama_product?></td>
			<td class="no">Hook</td>			
			<td class="desc"><?php echo $hook?></td>
		</tr>
		<tr><td class="no">Kategori</td>
			<td class="desc"><?php echo $nama_kategory?></td>
			<td class="no">Line</td>			
			<td class="desc"><?php echo $line?></td>			
		</tr>
		<tr><td class="no">Deskripsi</td>
			<td class="desc"><?php echo $deskripsi?></td>
			<td class="no">Gap Samping</td>			
			<td class="desc"><?php echo $gap_samping?></td>			
		</tr>
		<tr><td class="no">Lebar (mm)</td>
			<td class="desc"><?php echo $lebar?></td>
			<td class="no">Gap Atas</td>			
			<td class="desc"><?php echo $gap_atas?></td>
		</tr>
		<tr><td class="no">Tinggi (mm)</td>
			<td class="desc"><?php echo $tinggi?></td>
			<td class="no">Material Size</td>			
			<td class="desc"><?php echo $material_size?></td>			
		</tr>
		<tr><td class="no">Qty Order</td>
			<td class="desc"><?php echo $qty_pcs?></td>
			<td class="no">Security Cut</td>			
			<td class="desc"><?php echo $security_cut?></td>
		</tr>
		<tr><td class="no">Qty Roll</td>
			<td class="desc"><?php echo $qty_roll?></td>
			<td class="no">Perforation</td>			
			<td class="desc"><?php echo $perforation?></td>			
		</tr>
		<tr><td class="no">PC/Roll</td>
			<td class="desc"><?php echo $pcsroll?></td>
			<td class="no">Colour</td>			
			<td class="desc"><?php echo $colour?></td>
		</tr>
		<tr><td class="no">Supplier</td>
			<td class="desc"><?php echo $nama_supplier?></td>
			<td class="no">Colour Key</td>			
			<td class="desc"><?php echo $colour_key?></td>			
		</tr>
	</tbody>
	</table>	
	<h3>Packing Instruction</h3>
	 <table>			
        <tbody>	
        <tr><td class="no">Packing</td>
			<td class="desc"><?php echo $packing?></td>
			
		</tr>
		<tr>
			<td class="no">Core Size</td>			
			<td class="desc"><?php echo $core_size?></td>
		</tr>
		<tr><td class="no">Cartoon Box</td>
			<td class="desc"><?php echo $cartoon_box?></td>			
		</tr>
		<tr>
			<td class="no">Cutter Sketch</td>			
			<td class="pic"><?php echo $nama_sketch.' ('.$cutter_sketch_tinggi.' m)'?><br><img src="<?php echo base_url($image)?>" style="width:100px"><br>(<?php echo $cutter_sketch_lebar?> mm)</td>           		
		</tr>
        </tbody>
     </table>
	<h3>Operator</h3>
	<table>
		<thead>
		  <tr>
		    <th class="no">#</th>
		    <th class="no">Kd Produk</th>	
		    <th class="no">Kategori</th>	   
		    <th class="no">Terpakai(mm)</th>
		    <th class="no">Sisa(mm)</th>
		    <th class="no">Qty Prd</th>
		    <th class="no">Keterangan</th>
		    <th class="no">Operator</th>
		    <th class="no">Tanggal</th>		   
		  </tr>
		 </thead>
		 <tbody>
		 <?php $report = $this->m_master->get_table_produksi($ws_number);
		if ($report->num_rows() > 0){
			$no=1;
			foreach ($report->result() as $row){
				echo '<tr>
					    	<td class="unit">'.$no++.'</td>
							<td class="desc">'.$row->label_code_bb.'</td>	
	 		 				<td class="desc">'.$row->nama_kategori.'</td>							
							<td class="desc">'.(int)$row->tinggi_terpakai.'</td>
							<td class="desc">'.(int)$row->tinggi_sisa.'</td>
							<td class="desc">'.(int)$row->qty_produksi.'</td>
							<td class="desc">'.$row->nama_status.'</td>
							<td class="desc">'.$row->nama_depan.'</td>
							<td class="desc">'.$row->date_report.'</td>
							
							
					  </tr>';
			}
		}else{
			echo '<tr><td colspan=11><center> -- Data Operator Kosong -- </td></tr>';
		}?>			 
		 </tbody>	  
	</table>
	<h3>Slitter</h3>
	<table>
		<thead>
		  <tr>
		    <th class="no">#</th>
		    <th class="no">Kd Produk</th>	
		    <th class="no">Kategori</th>	   
		    <th class="no">Lebar(mm)</th>
		    <th class="no">Tinggi(mm)</th>
		    <th class="no">Qty Roll</th>
		    <th class="no">Qty pcs</th>
		    <th class="no">Sliter</th>
		    <th class="no">Tanggal</th>
		   
		  </tr>
		 </thead>
		 <tbody>
		 <?php $sliting = $this->m_master->get_sliting(array('A.ws_number'=>$ws_number));
		if (count($sliting) > 0){
			$no=1;
			foreach ($sliting as $row){
				echo '<tr>
					    	<td class="unit">'.$no++.'</td>
							<td class="desc">'.$row->label_code_bb.'</td>	
	 		 				<td class="desc">'.$row->nama_kategori.'</td>								
							<td class="desc">'.(int)$row->lebar_sliting.'</td>
							<td class="desc">'.(int)$row->tinggi_sliting.'</td>
							<td class="desc">'.(int)$row->qty_roll_sliting.'</td>
							<td class="desc">'.$row->qty_pcs_sliting.'</td>
							<td class="desc">'.$row->nama_depan.'</td>
							<td class="desc">'.$row->date_sliting.'</td>						
							
					  </tr>';
			}
		}else{
			echo '<tr><td colspan=11><center> -- Data Sliter Kosong -- </td></tr>';
		}?>			 
		 </tbody>	  
	</table>

<div class="notice-set">
    	<div>NOTICE:</div> 	    
	    <div class="notice">Worksheet was created on a computer and is valid without the signature and seal.</div>
	    
    </div>
	



		

