<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Mpgoodin extends CI_Controller{
	public function  __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'BRMS';		
		$this->id_karyawan_by = $this->session->userdata('id_karyawan');
		
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_karyawan_by;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/stock/v_index_stock_in' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Barang Masuk";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.id_receive_items',
				2 => 'A.id_receive_items',
				3 => 'B.nama_supplier',
				4 => 'A.date_receive',				
				5 => 'A.keterangan',
				6 => 'A.date_add',
				7 => 'A.add_by'
		);
		return $column_array;
	}
	function get_records(){
		/*Mempersiapkan array tempat kita akan menampung semua data
		 yang nantinya akan server kirimkan ke client*/
		$output=array();
		/*data request dari client*/
		$request = $this->m_master->request_datatable();
	
		/*Token yang dikrimkan client, akan dikirim balik ke client*/
		$output['draw'] = $request['draw'];
		$where = array('deleted'=>1);
		/*
		 $output['recordsTotal'] adalah total data sebelum difilter
		$output['recordsFiltered'] adalah total data ketika difilter
		Biasanya kedua duanya bernilai sama pada saat load default(Tanpa filter), maka kita assignment
		keduaduanya dengan nilai dari $total
		*/
		/*Menghitung total record didalam database*/
		$total = count($this->m_master->get_receive_item());
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
	
		/*disini nantinya akan memuat data yang akan kita tampilkan
		 pada table client*/
		$output['data'] = array();
	
		/*
		 * jika keyword tidak kosong, maka menjalankan fungsi search
		* untuk ditampilkan di datable
		* */
		if($request['keyword'] !=""){
			/*menjalankan fungsi filter or_like*/
			$this->m_master->search_like($request['keyword'],$this->column());
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_receive_item('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_receive_item());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_receive_item());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_receive_item());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html			
			$output['data'][]=array($nomor_urut,
					$row->id_receive_items,			
					$this->m_master->total_po_receive($row->id_receive_items),
					$row->nama_supplier,					
					$row->date_receive,					
					$row->keterangan,
					$row->date_add,
					$row->add_by,
					'<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_receive_items).'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'									
									 				
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id=''){
		$this->m_master->get_login();
		$view = 'bo/stock/v_receive_items';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Detail Barang Masuk";
			$view = 'bo/stock/v_detail_receive_items';
			$data['id'] = $id;
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Terima Barang";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['supplier'] =  $this->m_master->get_table('tb_supplier');
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function show_po(){
		$res='';
		$val = $this->input->post('value');
		//tidak tampil lagi jika status received 1 = complete
		$where = array('id_supplier'=>$val,'deleted'=>1,'state_received !='=>1,'active'=>1);
		$sql = $this->m_master->get_table_column(array('id_cart_spl','po_number','po_date'),'tb_supplier_order',$where);
		if (count($sql) > 0){
			$no=1;			
			foreach ($sql as $row){						
					$res .= '<tr><td>'.$no++.'</td>
								  <td><input type="checkbox" name="check[]" value="'.$row->id_cart_spl.'"></td>
								  <td>'.$row->po_number.'</td>
								  <td>'.$row->po_date.'</td>
							</tr>';
				
			}	
			
			$res .= '<tr><td colspan=4><button type="button" id="btnform" value="'.base_url('bo/mpgoodin/showcart').'" name="showcart" class="btn btn-success btn-xs">Tampilkan Barang</button></td></tr>';	
		}else{
			$res .= '<tr><td colspan=4><center> --Tidak ada PO Number-- </center></td></tr>';
		}
		echo $res;
	}
	function showcart(){
		$result='';
		$check = $this->input->post('check');	
		$this->db->where('deleted',1);
		$warehouse = $this->m_master->get_table('tb_warehouse');	
		if (empty($check)){
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Po Number belum dipilih'));
		}else{
			foreach ($check as $id){
				$id_cart_spl[] = $id;
			}
			
			$sql = $this->m_master->get_supplier_order_det($id_cart_spl);
			if (count($sql) > 0){
				$no=1;
				foreach ($sql as $row){
					$readonly = ($row->id_form_order == 'HDW') ? 'readonly' : '';
					$result .='<tr>
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_form_order]" value="'.$row->id_form_order.'">
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_cart_spl]" value="'.$row->id_cart_spl.'">
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_supplier_order_det]" value="'.$row->id_supplier_order_det.'">
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_kategori]" value="'.$row->id_kategori.'">
								<td>'.$no++.'</td>
								<td><input type="checkbox" name="chkitem[]" value="'.$row->id_spl_ord_cart_det.'"></td>
							    <td>'.$row->nama_product.'</td>
							   	<td>'.$row->deskripsi.'</td>
							   	<td>'.$row->type.'</td>
							   	<td>'.$row->keterangan.'</td>
							   	<td>'.number_format($row->lebar,0,".",",").'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][lebar]" '.$readonly.' onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>
							   	<td>'.number_format($row->tinggi,0,".",",").'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][tinggi]" '.$readonly.' onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>
							   	<td>'.$row->qty_roll.'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][qty_roll]" onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>	
							   	<td>'.number_format($row->qty_mp,0,',','.').'<input style="width:70px" type="text" id="qtymp'.$row->id_spl_ord_cart_det.'" name="data['.$row->id_spl_ord_cart_det.'][qty_mp]" '.$readonly.' onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>			   	
								<td>-<br><select name="data['.$row->id_spl_ord_cart_det.'][id_warehouse]" style="width:100" class="form-control">
							   			<option value="" selected disabled>--</option>';
										foreach ($warehouse->result() as $val){
											$result .='<option value="'.$val->id_warehouse.'">'.$val->nama_warehouse.'</option>';
										}
								$result .='</select>
							   	</td>
							   	<td>-<br><input type="text" name="data['.$row->id_spl_ord_cart_det.'][keterangan_det]" class="form-control" placeholder="..."></td>							   	
					     </tr>';
				}
			}else{
				$result .='<tr>
						 		<td colspan=12><center> -- Barang tidak ditemukan -- </center></td>
					   	  </tr>';
			}
			echo json_encode(array('error'=>0,'type'=>'cari','content'=>$result));
		}		
	}
	/*
	 * input receive item
	 */
	function proses(){			
		$data = $this->input->post('data');
		$check = $this->input->post('chkitem');//id_spl_ord_cart_det		
		if (!empty($check)){
			$this->db->trans_start();
			$id_receive = $this->m_master->random_ref('RI');
			$id_supplier = $this->input->post('supplier');
			$input['id_receive_items'] = $id_receive;
			$input['id_supplier'] = $id_supplier;
			$input['date_receive'] = $this->input->post('receivedate');
			$input['id_karyawan'] = $this->id_karyawan_by;
			$input['keterangan'] = $this->input->post('desc');
			$input['add_by'] = $this->addby;
			$input['date_add'] = $this->datenow;
			$res = $this->m_master->insertdata('tb_receive_items',$input);			
			foreach ($check as $id){
				$datax = $data[$id];		
				$id_supplier_order_det = $datax['id_supplier_order_det'];	
				$id_kategori = $datax['id_kategori'];
				$id_form_order = $datax['id_form_order'];		
				$lebar = $datax['lebar'];
				$tinggi = $datax['tinggi'];
				$id_warehouse = $datax['id_warehouse'];
				$qty_pcs = $datax['qty_roll'];
				$id_receive_items_detail = $this->m_master->random_ref('I');
				$in['id_receive_items_detail'] = $id_receive_items_detail;
				$in['id_receive_items'] = $id_receive;
				$in['id_supplier_order_det'] = $id_supplier_order_det;
				$in['id_cart_spl'] = $datax['id_cart_spl'];
				$in['lebar'] = $lebar;
				$in['tinggi_mm'] = meter_to_mm($datax['tinggi']);
				$in['tinggi_m'] = $tinggi;
				$in['qty_roll'] = $qty_pcs;
				$in['qty_mp'] = $datax['qty_mp'];//qty meter persegi
				$in['id_warehouse'] = $id_warehouse;
				$in['keterangan_det'] = $datax['keterangan_det'];			
				$merge_array = array_merge($in);
				$res = $this->m_master->insertdata('tb_receive_items_detail',$merge_array);
				$res=1;
				if ($res){					
					/*melakukan update pengurangan qty yang diorder jika diterima*/
					$this->db->set('qty_roll','qty_roll-'.$datax['qty_roll'],false);
					if ($datax['id_form_order'] == 'LBL'){
						//jika barang adalah label melakukan pengurangan qty meter persegi
						$this->db->set('qty_mp','qty_mp-'.$datax['qty_mp'],false);
					}					
					$this->db->where('id_supplier_order_det',$id_supplier_order_det);
					$res = $this->db->update('tb_supplier_order_det');
					
					/*melakukan cek qty yang diorder apakah sudah diterima semua*/
					$cek_qty = $this->m_master->get_table_column('qty_roll','tb_supplier_order_det',array('id_supplier_order_det'=>$id_supplier_order_det));
					if ($cek_qty[0]->qty_roll == 0){
						//jika sudah diterima semua maka update state_received_det
						$update['state_received_det'] = 1;
						$res = $this->m_master->updatedata('tb_supplier_order_det',$update,array('id_supplier_order_det'=>$id_supplier_order_det));
					}					
					
					/*updata status received jika sudah diterima semua no po tidak akan tampil kembali*/
					if ($this->m_master->po_complete($datax['id_cart_spl']) == 0){
						//jika sudah diterima semua maka update state_received_det
						$updates['state_received'] = 1;//complete						
					}else{
						//update status jika masih ada yg belum diterima
						$updates['state_received'] = 2;//not complete
					}
					$res = $this->m_master->updatedata('tb_supplier_order',$updates,array('id_cart_spl'=>$datax['id_cart_spl']));
					
					//jika barang adalah label maka akan membuat stock bahan baku
					if ($id_form_order == 'LBL'){
						//generate kode bahan baku dan stock jika barang yang diterima adalah label
						$res = $this->generate_stock_code($in);
					}else{
						//jika barang selain label maka akan membuat stock bahan jadi
						$id_status = 13;//tambah dari supplier
						$res = $this->m_master->generate_stock_bj($id_kategori,$lebar,$tinggi,$qty_pcs,$id_warehouse,$id_status);					
					}					
				}
			}
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				if ($res){
					$this->session->set_flashdata('success','Barang diterima berhasil disimpan');
					echo json_encode(array('error'=>0,'type'=>'save','redirect'=>base_url('bo/mpgoodin')));
				}				
			}
		}else{
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Barang yang diterima belum dipilih!'));
		}		
	}
	
	/*
	 * pembuatan stock bahan baku dari barang yang diterima oleh supplier
	 * Stock bahan baku tidak ada penambahan jika ada barang baru yang diterima
	 * setiap bahan baku yang diterima akan membuat barcode yang baru dan stock yang baru
	 * stock bahan baku akan berkurang jika digunakan untuk produksi dan sliting
	 * jika ada kategori, ukuran, jenis bahan baku yang sama pada saat penerimaan barang, tidak menambah stock melainkan membuat stock baru dan barcode yang baru
	 */
	function generate_stock_code($data){		
		$id_supplier_order_det = $data['id_supplier_order_det'];
		$datenow = date('ymd');	
		/*
		 * melakukan generate code bahan baku sejumlah qty roll yang dimasukan
		 */	
		for ($i=1; $i <= $data['qty_roll']; $i++){
			$sql  = $this->m_master->get_supplier_order_detail($id_supplier_order_det);
			if ($sql->num_rows() > 0){
				foreach ($sql->result() as $row){
					$id_kategori  = $row->id_kategori;
					$id_supplier = $row->id_supplier;
					$id_product = $row->id_product;
					/*
					 * 1.kode kategori
					 * 2.kode supplier
					 * 3.tahun bulan tanggal
					 * 4.microtime + no urut
					 * 5.No Urut
					 */
					$qty = 1;
					$code = $this->m_master->generate_labelcode_bb($id_kategori,$id_supplier,$i);					
					$inputs['id_product'] = $id_product;
					$inputs['label_code_bb'] = $code;
					$inputs['id_receive_items_detail'] = $data['id_receive_items_detail'];
					$inputs['id_warehouse'] = $data['id_warehouse'];
					$inputs['lebar_bb'] = $data['lebar'];
					$inputs['tinggi_awal_bb'] = $data['tinggi_m'];
					$inputs['tinggi_tersedia_bb'] = $data['tinggi_m'];
					$inputs['luasan_awal_bb'] = $data['lebar']; //luasan = qty_m2 / lebar
					$inputs['luasan_tersedia_bb'] = $data['lebar'];//luasan = qty_m2 / lebar
					$inputs['qty_pcs_awal'] = $qty;//hanya berlaku untuk produk hardware
					$inputs['qty_pcs_tersedia'] = $qty;//hanya berlaku untuk produk hardware
					$inputs['add_by'] = $this->addby;
					$inputs['update_by'] = $this->addby;
					$input_merge[] = array_merge($inputs);		
									
					$id_status = 13;//tambah dari supplier
					$res = $this->m_master->insert_stock_move_bb($code,$data['lebar'],$data['tinggi_m'],$qty,$id_status);
				}
			}
		}		
		/*input table stock_bb*/
		return $this->db->insert_batch('tb_stock_bb',$input_merge);
	}
	function column_receive(){
		$column_array = array(
				0 => 'A.id_receive_items_detail',//default order sort
				1 => 'B.nama_product',
				2 => 'B.deskripsi',
				3 => 'B.nama_kategory',
				4 => 'A.lebar',
				5 => 'A.tinggi_m',
				6 => 'A.qty_roll',
				7 => 'A.qty_mp',
				8 => 'C.nama_warehouse',
				9 => 'A.keterangan_det',				
		);
		return $column_array;
	}
	function get_records_detail_receive($id){
		/*Mempersiapkan array tempat kita akan menampung semua data
		 yang nantinya akan server kirimkan ke client*/
		$output=array();
		/*data request dari client*/
		$request = $this->m_master->request_datatable();
		
		/*Token yang dikrimkan client, akan dikirim balik ke client*/
		$output['draw'] = $request['draw'];
		
		$where = array('A.id_receive_items'=>$id);
		/*
		 $output['recordsTotal'] adalah total data sebelum difilter
		$output['recordsFiltered'] adalah total data ketika difilter
		Biasanya kedua duanya bernilai sama pada saat load default(Tanpa filter), maka kita assignment
		keduaduanya dengan nilai dari $total
		*/
		/*Menghitung total record didalam database*/
		$total = count($this->m_master->get_receive_detail($where));
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		
		/*disini nantinya akan memuat data yang akan kita tampilkan
		 pada table client*/
		$output['data'] = array();
		
		/*
		 * jika keyword tidak kosong, maka menjalankan fungsi search
		* untuk ditampilkan di datable
		* */
		if($request['keyword'] != ""){
			/*menjalankan fungsi filter or_like*/
			$this->m_master->search_like($request['keyword'],$this->column_receive());
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_receive_detail($where,$this->column_receive()[$request['column']],$request['sorting'],$request['length'],$request['start']);
		
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column_receive());
			$total = count($this->m_master->get_receive_detail($where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->nama_product,
					$row->deskripsi,
					$row->nama_kategory,
					$row->lebar,
					$row->tinggi_m,
					$row->qty_roll,
					$row->qty_mp,
					$row->nama_warehouse,
					$row->keterangan_det,								
					'<button onclick="openWinMultiple(\''.base_url('bo/barcodetest/cetak_multiple/'.$row->id_receive_items_detail.'/'.$row->id_form_order).'\')" title="Cetak Barcode" class="btn btn-success btn-circle"><i class="icon-qrcode"></i></button>'		
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	}
	
}
