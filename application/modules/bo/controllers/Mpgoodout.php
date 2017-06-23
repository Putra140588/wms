<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Mpgoodout extends CI_Controller{
	public function  __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'BRKL';		
		$this->id_karyawan_by = $this->session->userdata('id_karyawan');	
		$this->load->library('cart');
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_karyawan_by;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/goodout/v_index_delivery_order' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Delivery Order";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.do_number',
				2 => 'B.po_number',
				3 => 'C.nama_customer',
				4 => 'C.alamat',				
				5 => 'D.nama_courier',
				6 => 'A.date_shipp',				
				7 => 'A.active',
				8 => 'A.date_add',
				9 => 'A.add_by'
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
		
		/*
		 $output['recordsTotal'] adalah total data sebelum difilter
		$output['recordsFiltered'] adalah total data ketika difilter
		Biasanya kedua duanya bernilai sama pada saat load default(Tanpa filter), maka kita assignment
		keduaduanya dengan nilai dari $total
		*/
		/*Menghitung total record didalam database*/
		$total = count($this->m_master->get_delivery_order());
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
		$query = $this->m_master->get_delivery_order('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_delivery_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_delivery_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_delivery_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html			
			$status = ($row->active == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Dibatalkan</span>';
			$output['data'][]=array($nomor_urut,
					$row->do_number,			
					$row->po_number,
					$row->nama_customer,					
					$row->alamat,					
					$row->nama_courier,
					$row->date_shipp,					
					$status,
					$row->date_add,
					$row->add_by,
					'<a href="'.base_url('bo/'.$this->class.'/cancel/'.$row->id_delivery_order).'" onclick="return confirm(\'Anda yakin ingin membatalkan delivery order ?\')" title="Batalkan" class="btn btn-danger btn-circle"><i class="icon-ban-circle"></i></a>	
					 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_delivery_order.'/1').'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'					
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();		
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Detail Barang Masuk";
			$view = 'bo/stock/v_detail_receive_items';
			$data['id'] = $id;
			if ($detail != ''){
				//show view detail
				$action = 'view';
				$data['page_title'] = "Detail Delivery Order";
				$view = 'bo/goodout/v_detail_delivery_order';
				$data['sql'] = $this->m_master->get_delivery_order(array('id_delivery_order'=>$id));						
				$data['order_detail'] = $this->m_master->get_delivery_order_detail($id);
			}
		}else{
			//akses tambah
			$this->cart->destroy();
			$action = 'add';
			$data['page_title'] = "Tambah Delivery Order";
			$view = 'bo/goodout/v_new_delivery_order';
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$this->db->order_by('A.date_add','desc');
		$data['sales_order'] = $this->m_master->get_order();
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function show_po(){
		$res='';
		$val = $this->input->post('value');
		//tidak tampil lagi jika status received 1 = complete
		$where = array('id_supplier'=>$val,'deleted'=>1,'state_received !='=>1);
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
			
			$res .= '<tr><td colspan=4><button type="button" id="btnform" value="mpgoodin/showcart" name="showcart" class="btn btn-success btn-xs">Tampilkan Barang</button></td></tr>';	
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
			$this->db->where_in('id_cart_spl',$id_cart_spl);
			$this->db->where('state_received_det',0);//tampilkan jika barang belum diterima
			$sql = $this->m_master->get_table_column('*','tb_supplier_order_det');
			if (count($sql) > 0){
				$no=1;
				foreach ($sql as $row){
					$result .='<tr>
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_form_order]" value="'.$row->id_form_order.'">
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_cart_spl]" value="'.$row->id_cart_spl.'">
								<input type="hidden" name="data['.$row->id_spl_ord_cart_det.'][id_supplier_order_det]" value="'.$row->id_supplier_order_det.'">
								<td>'.$no++.'</td>
								<td><input type="checkbox" name="chkitem[]" value="'.$row->id_spl_ord_cart_det.'"></td>
							    <td>'.$row->nama_product.'</td>
							   	<td>'.$row->deskripsi.'</td>
							   	<td>'.$row->type.'</td>
							   	<td>'.$row->keterangan.'</td>
							   	<td>'.number_format($row->lebar,0,".",",").'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][lebar]" onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>
							   	<td>'.number_format($row->tinggi,0,".",",").'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][tinggi]" onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>
							   	<td>'.$row->qty_roll.'<br><input style="width:70px" type="text" name="data['.$row->id_spl_ord_cart_det.'][qty_roll]" onkeypress="return decimals(event,this.id)" class="form-control" placeholder="..."></td>	
							   	<td>'.number_format($row->qty_mp,0,',','.').'<input style="width:70px" type="text" id="qtymp'.$row->id_spl_ord_cart_det.'" name="data['.$row->id_spl_ord_cart_det.'][qty_mp]" onkeypress="return decimals(event,this.id)" placeholder="..." class="form-control"></td>			   	
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
	 * input deliveri order stok out
	 */
	function proses(){			
		$this->db->trans_start();
			$id_delivery = $this->m_master->random_ref('DO');
			$do_number = $this->input->post('donumber');
			$id_order = $this->input->post('salesorder');
			$shippdate = $this->input->post('shippdate');
			$desc = $this->input->post('desc');
			$insert['id_delivery_order'] = $id_delivery;
			$insert['do_number'] = $do_number;
			$insert['id_order'] = $id_order;
			$insert['date_shipp'] = $shippdate;
			$insert['description'] = $desc;
			$insert['date_add'] = $this->datenow;
			$insert['add_by'] = $this->addby;			
			$res = $this->m_master->insertdata('tb_delivery_order',$insert);		
						
			$rowid = $this->input->post('rowid');
			$data = $this->input->post('data');
			foreach ($rowid as $label_code_bj){
				$datax = $data[$label_code_bj];			
				$qty_pcs = $datax['qty_delivery'];
				$input['id_delivery_order'] = $id_delivery;
				$input['label_code_bj'] = $label_code_bj;
				$merge[] = array_merge($datax,$input);
				
				/*
				 * potong stok bahan jadi
				*/					
				$this->db->set('qty_pcs_tersedia','qty_pcs_tersedia-'.$qty_pcs,false);
				$this->db->set('qty_pcs_terpakai','qty_pcs_terpakai+'.$qty_pcs,false);
				$this->db->where('label_code_bj',$label_code_bj);
				$res = $this->db->update('tb_stock_bj');
				
				/*
				 * insert stock_movement_bj
				*/
				$id_status = 5;//order dari pelanggan
				$res = $this->m_master->insert_stock_move_bj($label_code_bj,$qty_pcs,$id_status);
			}
			//insert batch delivery order detail
			$res = $this->db->insert_batch('tb_delivery_order_detail',$merge);				
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
			if ($res){
				$this->session->set_flashdata('success','Delivery order berhasil disimpan');
				echo json_encode(array('error'=>0,'type'=>'save','redirect'=>base_url('bo/'.$this->class)));
			}
		}
	}		
	
	function pilih_so(){		
		$id_order = $this->input->post('value');
		echo $this->m_content->delivery_order_detail($id_order);
	}
	function column_bj(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.id_stock_bj',//default order sort
				1 => 'A.label_code_bj',
				2 => 'B.nama_kategori',
				3 => 'A.lebar_bj',
				4 => 'A.tinggi_bj',
				5 => 'A.qty_pcs_awal',
				6 => 'A.qty_pcs_terpakai',
				7 => 'A.qty_pcs_tersedia',
				8 => 'C.nama_warehouse',
		);
		return $column_array;
	}
	function get_records_bj(){
		/*Mempersiapkan array tempat kita akan menampung semua data
		 yang nantinya akan server kirimkan ke client*/
		$output=array();
		/*data request dari client*/
		$request = $this->m_master->request_datatable();
	
		/*Token yang dikrimkan client, akan dikirim balik ke client*/
		$output['draw'] = $request['draw'];
	
		/*
		 $output['recordsTotal'] adalah total data sebelum difilter
		$output['recordsFiltered'] adalah total data ketika difilter
		Biasanya kedua duanya bernilai sama pada saat load default(Tanpa filter), maka kita assignment
		keduaduanya dengan nilai dari $total
		*/
		/*Menghitung total desa didalam database*/
		$total = count($this->m_master->get_stock_bahan_jadi());
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
			$this->m_master->search_like($request['keyword'],$this->column_bj());
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_stock_bahan_jadi('',$this->column_bj()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column_bj());
			$total = count($this->m_master->get_stock_bahan_jadi());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$na = 'N/A';
			if ($row->id_form_order == 'HDW'){
				//hardware
				$output['data'][]=array($nomor_urut,
						$row->label_code_bj,
						$row->nama_kategori,
						$na,
						$na,
						(int)$row->qty_pcs_awal,
						(int)$row->qty_pcs_terpakai,
						(int)$row->qty_pcs_tersedia,
						$row->nama_warehouse,
						'<button type="button" class="btn btn-success btn-circle" title="Pilih Bahan" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_bahan').'\',\''.$row->label_code_bj.'\',\'cartproduct\')"><i class="icon-download-alt"></i></button>'
	
				);
			}else if ($row->id_form_order == 'HDW'){
				//carbon ribbon
	
			}else{
				//label
				$output['data'][]=array($nomor_urut,
						$row->label_code_bj,
						$row->nama_kategori,
						$row->lebar_bj,
						$row->tinggi_bj,
						qty_format($row->qty_pcs_awal),
						qty_format($row->qty_pcs_terpakai),
						qty_format($row->qty_pcs_tersedia),
						$row->nama_warehouse,
						'<button type="button" class="btn btn-success btn-circle" title="Pilih Bahan" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_bahan').'\',\''.$row->label_code_bj.'\',\'cartproduct\')"><i class="icon-download-alt"></i></button>'
				);
			}
			$nomor_urut++;
		}
		echo json_encode($output);	
	}
	function pilih_bahan(){		
		$res='';
		$label_code_bj = $this->input->post('value');
		$sql = $this->m_master->get_stock_bahan_jadi(array('label_code_bj'=>$label_code_bj));
		$no=1;
		$cek_stock = $this->m_master->stock_bj($label_code_bj);
		
		//jika stock tersedia > 0
		if ($cek_stock > 0){
			foreach ($sql as $row){
				$data = array(
						'id'      => $row->label_code_bj,
						'qty'     => 1,//not used
						'price'   => 0,//not used
						'name'    => $row->nama_kategori,
						'lebar'	  => $row->lebar_bj,
						'tinggi'  => $row->tinggi_bj,
						'id_kategori' =>$row->id_kategori,
						'id_warehouse' =>$row->id_warehouse
				);					
				$this->cart->insert($data);		
				echo $this->m_content->delivery_cart_content();
			}
		}else{
			//stock bhan jadi tidak tersedia			
			echo 'error_bj';
		}			
		
	}
	function delete_cart(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$rowid = $this->input->post('value');
			$data = array(
					'rowid'=>$rowid,
					'qty' =>0		
			);
			$res = $this->cart->update($data);
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus Material berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
	
	function cancel($id){
		$priv = $this->m_master->get_priv($this->acces_code,'edit');
		if (empty($priv)){
			$status = $this->db->select('active')->from('tb_delivery_order')->where('id_delivery_order',$id)->get()->result();
			//jika belum dibatalkan
			if ($status[0]->active == 1){
				$sql = $this->m_master->get_delivery_order_detail($id);
				foreach ($sql as $row){
					/*
					 * update active to 0
					 */
					$this->db->set('active',0);
					$this->db->where('id_delivery_order',$id);
					$res = $this->db->update('tb_delivery_order');
					
					/*
					 * rollback stok bahan jadi
					*/
					$this->db->set('qty_pcs_tersedia','qty_pcs_tersedia+'.$row->qty_delivery,false);
					$this->db->set('qty_pcs_terpakai','qty_pcs_terpakai-'.$row->qty_delivery,false);
					$this->db->where('label_code_bj',$row->label_code_bj);
					$res = $this->db->update('tb_stock_bj');
					
					/*
					 * insert stock_movement_bj
					*/
					$id_status = 14;//rollback stock
					$res = $this->m_master->insert_stock_move_bj($row->label_code_bj,$row->qty_delivery,$id_status);
				}
				if ($res){
					$this->session->set_flashdata('success','Delivery order berhasil dibatalkan');
					redirect('bo/'.$this->class);
				}
			}else{
				
					$this->session->set_flashdata('danger','Delivery order sudah dibatalkan, tidak dapat dibatalkan kembali!');
					redirect('bo/'.$this->class);
				
			}			
		}else{
			$this->session->set_flashdata('danger',$priv['notif']);
			redirect('bo/'.$this->class);
		}
	}
}
