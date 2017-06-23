<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mporder extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'SORD';
		$this->id_cart_ord = $this->session->userdata('id_cart_ord');
		$this->id_karyawan_by = $this->session->userdata('id_karyawan');			
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_cart_ord;
	var $id_karyawan_by;
	function index(){
		$this->m_master->get_login();		
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/order/v_index_order' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];		
		$data['page_title'] = "Sales Order";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.id_order',
				2 => 'B.nama_customer',
				3 => 'C.nama_depan',
				4 => 'A.approved',
				5 => 'A.active',
				6 => 'A.add_by',		
				7 => 'A.date_add'		
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
		/*Menghitung total desa didalam database*/
		$total = count($this->m_master->get_order());
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
		$query = $this->m_master->get_order('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			if ($row->approved == 1){
				$approved = '<span class="label label-info">Approved 1</span>';
			}elseif ($row->approved == 2){
				$approved = '<span class="label label-success">Approved 2</span>';
			}elseif ($row->approved == 3){
				$approved = '<span class="label label-danger">No Approved</span>';
			}else{
				$approved = '<span class="label label-warning">Waiting</span>';
			}
			
			$status = ($row->active == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Dibatalkan</span>';
			$output['data'][]=array($nomor_urut,
					$row->id_order,
					$row->nama_customer,
					$row->nama_depan,		
					$approved,
					$status,
					$row->add_by,						
					$row->date_add,					
					'<a href="'.base_url('bo/'.$this->class.'/cancel/'.$row->id_order).'" onclick="return confirm(\'Anda yakin ingin membatalkan order ?\')" title="Batalkan" class="btn btn-danger btn-circle"><i class="icon-ban-circle"></i></a>				
					 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_order.'/1').'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'									 
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function delete_cart(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->deletedata('tb_product_cart',array('id_product_cart'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus Item cart berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$this->session->unset_userdata('id_customer');
		$view = 'bo/order/v_new_order';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Karyawan";
			$sql = $this->m_master->get_karyawan(array('id_karyawan'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			if ($detail != ''){
				//show view detail
				$action = 'view';
				$data['page_title'] = "Detail Sales Order #".$id;
				$view = 'bo/order/v_detail_order';				
				$data['sql'] = $this->m_master->get_order(array('id_order'=>$id));
				$data['bill'] = $this->m_master->get_alamat_order($data['sql'][0]->id_alamat_tagihan);
				$data['shipp'] = $this->m_master->get_alamat_order($data['sql'][0]->id_alamat_pengiriman);
				$data['order_detail'] = $this->m_master->get_table_column('*','tb_order_detail',array('id_order'=>$id));
				$data['approval'] = $this->db->where('id_order',$id)->get('tb_order_approval');
			}
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Sales Order";
			
			//jika id_cart_ord belum terset			
			if ($this->session->userdata('id_cart_ord') == ''){
				//create sess cart
				$this->session->set_userdata('id_cart_ord',$this->m_master->random_ref($this->acces_code));
			}
			
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['customer'] =  $this->m_master->get_table('tb_customer');
		$data['terms'] =  $this->m_master->get_table('tb_terms');
		$data['courier'] = $this->m_master->get_table('tb_courier');
		$data['salesman'] =  $this->m_master->get_table_column(array('id_karyawan','nik','nama_depan','nama_belakang'),'tb_karyawan',array('deleted'=>1,'active'=>1));
		$data['class'] = $this->class;		
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function pilih_alamat(){
		$id = $this->input->post('value');
		$get = $this->m_master->get_alamat(array('id_customer'=>$id));		
		$this->session->set_userdata('id_customer',$id);		
		foreach ($get->result() as $row){
			echo '<option value="'.$row->id_alamat.'">'.$row->alamat.'</option>';
		}
	}
	function column_bahan(){
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.nama_product',
				2 => 'C.nama_kategori',
				3 => 'B.nama_supplier',				
		);
		return $column_array;
	}
	function get_records_bahan(){
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
		$total = count($this->m_master->get_jenis_bahan());
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
			$this->m_master->search_like($request['keyword'],$this->column_bahan());
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_jenis_bahan('',$this->column_bahan()[$request['column']],$request['sorting'],$request['length'],$request['start']);
		
		
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column_bahan());
			$total = count($this->m_master->get_jenis_bahan());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		
		
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->nama_product,
					$row->nama_kategori,
					$row->nama_supplier,					
					'<button type="button" class="btn btn-success btn-circle" title="Pilih Bahan" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_bahan').'\',\''.$row->id_product.'#'.$row->nama_product.'#'.$row->nama_kategori.'#'.$row->id_form_order.'\',\'cartproduct\')"><i class="icon-download-alt"></i></button>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	}
	function pilih_bahan(){
		$val = explode('#', $this->input->post('value'));
		$id_product = $val[0];
		$nama_product = $val[1];
		$nama_kategori = $val[2];
		$id_form_order = $val[3];
		$post['id_cart_ord'] = $this->id_cart_ord;
		$post['nama_product'] = $nama_product;
		$post['nama_kategory'] = $nama_kategori;
		$post['id_form_order'] = $id_form_order;
		$post['id_product'] = $id_product;
		$post['date_add'] = $this->datenow;
		$post['add_by'] = $this->addby;
		$res = $this->m_master->insertdata('tb_product_cart',$post);
		echo $this->m_content->load_cart();
		
	}
	/*
	 * create summary cart
	 */
	function proses(){		
		$id_product_cart = $this->input->post('id');
		$data = $this->input->post('data');
		
		if (empty($data)){
			echo json_encode(array('error'=>1,'msg'=>'Bahan belum dipilih'));
		}else{		
			/*
			 * update tb_product_cart
			*
			*/
			foreach ($id_product_cart as $id){
				$datax = $data[$id];
				$harga = $datax['harga'];				
				//jika jenis carbon dikalikan qty_roll
				$qty = ($datax['id_form_order'] == 'CRB') ? $datax['qty_roll'] : $datax['qty_pcs'];
				$total_harga = $harga * $qty;
				$datax['total_harga'] = $total_harga;
				
				$res = $this->m_master->updatedata('tb_product_cart',$datax,array('id_product_cart'=>$id));
			}

			//tb_order_cart
			$subtotal = $this->m_master->get_total_harga();
			$tax_value = $_SESSION['tax'];
			$tax_amount = ($subtotal * $tax_value) / 100;
			$total_amount = $subtotal + $tax_amount;
			$id_customer = $this->session->userdata('id_customer');
			$cart['id_customer'] = isset($id_customer) ? $id_customer : '';
			$cart['id_karyawan'] = isset($_POST['salesman']) ? $_POST['salesman'] : '';
			$cart['id_karyawan_by'] = $this->id_karyawan_by;
			$cart['id_alamat_pengiriman'] = isset($_POST['shippaddress']) ? $_POST['shippaddress'] : '';
			$cart['id_alamat_tagihan'] = isset($_POST['billaddress']) ? $_POST['billaddress'] : '';
			$cart['id_terms'] = isset($_POST['terms']) ? $_POST['terms'] : '';
			$cart['po_number'] = isset($_POST['po']) ? $_POST['po'] : '';
			$cart['date_shipp'] = $this->input->post('shippdate');
			$cart['description'] = isset($_POST['desc']) ? $_POST['desc'] : '';
			$cart['add_by'] = $this->addby;
			$cart['date_add'] = $this->datenow;
			$cart['subtotal'] = $subtotal;
			$cart['tax_value'] = $tax_value;
			$cart['tax_amount'] = $tax_amount;
			$cart['total_amount'] = $total_amount;
			$cart['id_courier'] = $this->input->post('courier');
			
			//cek tb_product_cart
			$cek = $this->m_master->get_table_column(array('id_cart_ord'),'tb_order_cart',array('id_cart_ord'=>$this->id_cart_ord));
			if (count($cek) > 0){
				/*
				 * Jika session id_caret_ord masih ada
				 * update tb_order_cart
				 */
				$res = $this->m_master->updatedata('tb_order_cart',$cart,array('id_cart_ord'=>$this->id_cart_ord));
			}else{
				$cart['id_cart_ord'] = $this->id_cart_ord;
				/*
				 * insert new tb_order_cart
				 */
				$res = $this->m_master->insertdata('tb_order_cart',$cart);					
			}			
			
			if ($res){
				//show modal summary
				echo json_encode(array('error'=>0,'msg'=>'Create summary berhasil','modal'=>$this->m_content->modal_summary_order()));
			}
		}		
	}
	
	function create_order(){
		$this->db->trans_start();		
		$order_cart = $this->m_master->get_table_column('*','tb_order_cart',array('id_cart_ord'=>$this->id_cart_ord));
		if (count($order_cart) > 0){
			$id_order = $this->m_master->so_refnumber('SO');
			foreach ($order_cart as $row){				
				$insert['id_order'] = $id_order;
				$insert['id_cart_ord'] = $row->id_cart_ord;
				$insert['id_customer'] = $row->id_customer;
				$insert['id_karyawan'] = $row->id_karyawan;
				$insert['id_karyawan_by'] = $row->id_karyawan_by;
				$insert['id_alamat_pengiriman'] = $row->id_alamat_pengiriman;
				$insert['id_alamat_tagihan'] = $row->id_alamat_tagihan;
				$insert['id_terms'] = $row->id_terms;
				$insert['po_number'] = $row->po_number;
				$insert['date_shipp'] = $row->date_shipp;
				$insert['tax_value'] = $row->tax_value;
				$insert['tax_amount'] = $row->tax_amount;
				$insert['subtotal'] = $row->subtotal;
				$insert['total_amount'] = $row->total_amount;
				$insert['description'] = $row->description;
				$insert['date_add'] = $row->date_add;
				$insert['add_by'] = $row->add_by;		
				$insert['id_courier'] = $row->id_courier;
				$res =  $this->m_master->insertdata('tb_order',$insert);
				foreach ($this->m_master->get_product_cart() as $val){					
					$post['id_order'] = $id_order;
					$post['id_product_cart'] = $val->id_product_cart;
					$post['id_cart_ord'] = $val->id_cart_ord;
					$post['id_product'] = $val->id_product;
					$post['id_form_order'] = $val->id_form_order;
					$post['ws_number'] = $this->m_master->random_ref('WS');//number akan tergenerate berbeda setiap item
					$post['nama_kategory'] = $val->nama_kategory;
					$post['nama_product'] = $val->nama_product;
					$post['deskripsi'] = $val->deskripsi;
					$post['harga'] = $val->harga;
					$post['lebar'] = $val->lebar;
					$post['tinggi'] = $val->tinggi;
					$post['qty_pcs'] = $val->qty_pcs;
					$post['qty_roll'] = $val->qty_roll;
					$post['total_harga'] = $val->total_harga;
					$post['hook'] = $val->hook;
					$post['line'] = $val->line;
					$post['gap_samping'] = $val->gap_samping;
					$post['gap_atas'] = $val->gap_atas;
					$post['material_size'] = $val->material_size;
					$post['security_cut'] = $val->security_cut;
					$post['perforation'] = $val->perforation;
					$post['colour'] = $val->colour;
					$post['colour_key'] = $val->colour_key;
					$post['packing'] = $val->packing;
					$post['packing_qty'] = $val->packing_qty;
					$post['core_size'] = $val->core_size;
					$post['cartoon_box'] = $val->cartoon_box;
					$post['cutter_sketch'] = $val->cutter_sketch;
					$post['cutter_sketch_tinggi'] = $val->cutter_sketch_tinggi;
					$post['cutter_sketch_lebar'] = $val->cutter_sketch_lebar;
					$post['keterangan'] = $val->keterangan;					
					$post['date_add'] = $val->date_add;
					$post['add_by'] = $val->add_by;
					$res =  $this->m_master->insertdata('tb_order_detail',$post);
					
					//insert notif order
					$res = $this->m_master->insert_notif_transaksi($id_order,$this->acces_code);
				}								
			}			
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				$this->session->unset_userdata('id_cart_ord');
				$this->session->set_flashdata('success','Sales Order berhasil dibuat');
				echo json_encode(array('error'=>0,'type'=>'save','redirect'=>base_url('bo/mporder')));
			}
		}else{
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Create order tidak berhasil, session id cart order sudah berakhir'));
		}		
	}
	function confirm_notif_order($id=''){
		if ($id != ''){
			//singgle confirm
			$this->db->set('status_confirm',1);
			$this->db->where('id_transaksi',$id);
			$this->db->where('id_karyawan',$this->id_karyawan_by);
			$this->db->update('tb_notif_transaksi');
			$this->form($id,1);
		}else{
			//cinfirm all notif
			//singgle confirm
			$this->db->set('status_confirm',1);			
			$this->db->where('id_karyawan',$this->id_karyawan_by);
			$this->db->where('akses_code','SORD');
			$this->db->update('tb_notif_transaksi');
			redirect('bo/'.$this->class);
		}
		
	}
	function approve($id,$method=""){
		if ($method != ""){
			//not approved
			$status = 3;
			$this->db->set('approved',3);//not approved
			$this->db->where('id_order',$id);
			$this->db->update('tb_order');				
			$notif = 'Sales order berhasil tidak di approve';
					
		}else{
			$cek = $this->db->select('approved')
							->from('tb_order')
							->where('id_order',$id)
							->get()->result();
			$approved = $cek[0]->approved;
			if ($approved > 0){
				//jika sudah di approve ke 1
				if ($approved == 1){
					//melakukan approval ke 2
					$status = 2;
					$this->db->set('approved',$status);
				}else{					
					$this->session->set_flashdata('danger','Tidak dapat melakukan approve, sales order sudah diapprove semua');
					redirect('bo/'.$this->class);
					return false;
				}
			}else{
				//jika belum di approve melakukan approval ke 1
				$status = 1;
				$this->db->set('approved',$status);					
			}
			$this->db->where('id_order',$id);
			$this->db->update('tb_order');
			
			//insert tb_order_approval
			$input['id_karyawan_approve'] = $this->id_karyawan_by;
			$input['id_order'] = $id;
			$input['status_approve'] = $status;
			$notif = 'Sales order berhasil diapprove';
			
		}
		//insert tb_order_approval
		$input['id_karyawan_approve'] = $this->id_karyawan_by;
		$input['id_order'] = $id;
		$input['status_approve'] = $status;
		$input['date_add'] = $this->datenow;
		$input['add_by'] = $this->addby;
		$res = $this->m_master->insertdata('tb_order_approval',$input);
		$this->session->set_flashdata('success',$notif);
		redirect('bo/'.$this->class);		
	}
	function cancel($id){
		$priv = $this->m_master->get_priv($this->acces_code,'edit');
		if (empty($priv)){
			$res = $this->m_master->updatedata('tb_order',array('active'=>0),array('id_order'=>$id));
			if ($res){
				$this->session->set_flashdata('success','Order berhasil dibatalkan');
				redirect('bo/'.$this->class);
			}
		}else{
			$this->session->set_flashdata('danger',$priv['notif']);
			redirect('bo/'.$this->class);
		}
	}
	function column_customer(){
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.nama_customer',				
		);
		return $column_array;
	}
	function get_records_customer(){
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
		$total = count($this->m_master->get_customer());
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
			$this->m_master->search_like($request['keyword'],$this->column_customer());
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_customer('',$this->column_customer()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column_customer());
			$total = count($this->m_master->get_customer());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->nama_customer,					
					'<button type="button" class="btn btn-success btn-circle" title="Pilih Customer" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_alamat').'\',\''.$row->id_customer.'\',\'address\')"><i class="icon-download-alt"></i></button>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	}
}
