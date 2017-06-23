<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mpsplorder extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'SPORD';
		$this->id_cart_spl = $this->session->userdata('id_cart_spl');
		$this->id_karyawan_by = $this->session->userdata('id_karyawan');
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_cart_spl;
	var $id_karyawan_by;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/order/v_index_spl_order' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Supplier Order";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.po_number',
				2 => 'A.po_date',
				3 => 'B.nama_supplier',		
				4 => 'A.state_received',	
				5 => 'A.active',	
				6 => 'A.add_by',
				7 => 'A.date_add',
		);
		return $column_array;
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/order/v_new_spl_order';
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
				$data['page_title'] = "Detail Supplier Order";
				$view = 'bo/order/v_detail_supplier_order';
				$data['sql'] = $this->m_master->get_supplier_order(array('id_supplier_order'=>$id));	
				$id_cart_spl = $data['sql'][0]->id_cart_spl;					
				$data['order_detail'] = $this->m_master->get_table_column('*','tb_supplier_order_det',array('id_cart_spl'=>$id_cart_spl));
			}
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Supplier Order";
				
			//jika id_cart_ord belum terset
			if ($this->session->userdata('id_cart_spl') == ''){
				//create sess cart
				$this->session->set_userdata('id_cart_spl',$this->m_master->random_ref($this->acces_code));
			}
				
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['supplier'] =  $this->m_master->get_table('tb_supplier');	
		$data['courier'] = $this->m_master->get_table('tb_courier');
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
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
		$total = count($this->m_master->get_supplier_order());
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
		$query = $this->m_master->get_supplier_order('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_supplier_order());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			if ($row->state_received == 1){
				$status = '<span class="label label-success">Complete</span>';				
			}else if ($row->state_received == 2){
				$status = '<span class="label label-warning">Not Complete</span>';
			}else{
				$status = '<span class="label label-default">Order</span>';
			}
			//show in html			
			$active = ($row->active == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Dibatalkan</span>';
			$output['data'][]=array($nomor_urut,
					$row->po_number,
					$row->po_date,
					$row->nama_supplier,
					$status,
					$active,
					$row->add_by,
					$row->date_add,
					'<a href="'.base_url('bo/'.$this->class.'/cancel/'.$row->id_supplier_order).'" onclick="return confirm(\'Anda yakin ingin membatalkan order ?\')" title="Batalkan" class="btn btn-danger btn-circle"><i class="icon-ban-circle"></i></a>									
					 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_supplier_order.'/1').'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
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
					'<button type="button" class="btn btn-success btn-circle" title="Pilih Bahan" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_bahan').'\',\''.$row->id_product.'#'.$row->nama_product.'#'.$row->nama_kategori.'#'.$row->id_form_order.'\',\'splcart\')"><i class="icon-download-alt"></i></button>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
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
	function pilih_bahan(){
		$val = explode('#', $this->input->post('value'));
		$id_product = $val[0];
		$nama_product = $val[1];
		$nama_kategori = $val[2];
		$id_form_order = $val[3];
		$post['id_cart_spl'] = $this->id_cart_spl;
		$post['nama_product'] = $nama_product;
		$post['nama_kategory'] = $nama_kategori;
		$post['id_form_order'] = $id_form_order;
		$post['id_product'] = $id_product;
		$post['date_add'] = $this->datenow;
		$post['add_by'] = $this->addby;
		$res = $this->m_master->insertdata('tb_spl_ord_cart_det',$post);
		echo $this->m_content->load_cart_spl();
	
	}
	function delete_cart(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->deletedata('tb_spl_ord_cart_det',array('id_spl_ord_cart_det'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus Item cart berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
	/*
	 * create summary cart
	 */
	function proses(){
		$id_spl_ord_cart_det = $this->input->post('id');
		$data = $this->input->post('data');		
		if (empty($data)){
			echo json_encode(array('error'=>1,'msg'=>'Bahan belum dipilih'));
		}else{	
			//update order cart detail		
			foreach ($id_spl_ord_cart_det as $id){
				$datax = $data[$id];	
				$qty_roll = $datax['qty_roll'];
				$lebar = $datax['lebar'];
				$input['deskripsi'] = $datax['deskripsi'];
				$input['lebar'] = 	$lebar;
				$input['tinggi'] = $datax['tinggi'];
				$input['qty_roll'] = $qty_roll;
				$input['type'] = $datax['type'];
				$input['keterangan'] = $datax['keterangan'];
				$input['qty_mp'] = $qty_roll * $lebar;//hitung meter persegi
				$res = $this->m_master->updatedata('tb_spl_ord_cart_det',$input,array('id_spl_ord_cart_det'=>$id));
			}	
			
			//tb_spl_order_cart			
			$cart['id_supplier'] = isset($_POST['supplier']) ? $_POST['supplier'] : '';			
			$cart['id_karyawan_by'] = $this->id_karyawan_by;					
			$cart['po_number'] = isset($_POST['po']) ? $_POST['po'] : '';
			$cart['po_date'] = $this->input->post('podate');
			$cart['id_courier'] = $this->input->post('courier');
			$cart['keterangan'] = isset($_POST['desc']) ? $_POST['desc'] : '';
			$cart['add_by'] = $this->addby;
			$cart['date_add'] = $this->datenow;
				
			//cek tb_product_cart
			$cek = $this->m_master->get_table_column(array('id_cart_spl'),'tb_spl_ord_cart',array('id_cart_spl'=>$this->id_cart_spl));
			if (count($cek) > 0){				
				$res = $this->m_master->updatedata('tb_spl_ord_cart',$cart,array('id_cart_spl'=>$this->id_cart_spl));
			}else{
				$cart['id_cart_spl'] = $this->id_cart_spl;
				/*
				 * insert new tb_order_cart
				*/
				$res = $this->m_master->insertdata('tb_spl_ord_cart',$cart);
			}				
			if ($res){
				//show modal summary
				echo json_encode(array('error'=>0,'msg'=>'Create summary berhasil','modal'=>$this->m_content->modal_summary_spl_ord()));
			}
		}
	
	}
	function create_order(){
		$this->db->trans_start();
		$order_cart = $this->m_master->get_table_column('*','tb_spl_ord_cart',array('id_cart_spl'=>$this->id_cart_spl));
		if (count($order_cart) > 0){			
			foreach ($order_cart as $row){				
				$insert['id_cart_spl'] = $row->id_cart_spl;
				$insert['po_number'] = $row->po_number;
				$insert['id_supplier'] = $row->id_supplier;
				$insert['id_karyawan_by'] = $row->id_karyawan_by;
				$insert['po_date'] = $row->po_date;				
				$insert['date_add'] = $row->date_add;
				$insert['add_by'] = $row->add_by;
				$insert['keterangan'] = $row->keterangan;
				$insert['id_courier'] = $row->id_courier;
				$res =  $this->m_master->insertdata('tb_supplier_order',$insert);
				foreach ($this->m_master->get_table_column('*','tb_spl_ord_cart_det',array('id_cart_spl'=>$row->id_cart_spl)) as $val){
					$post['id_spl_ord_cart_det'] = $val->id_spl_ord_cart_det;
					$post['id_cart_spl'] = $val->id_cart_spl;					
					$post['id_product'] = $val->id_product;
					$post['nama_product'] = $val->nama_product;
					$post['nama_kategory'] = $val->nama_kategory;
					$post['id_form_order'] = $val->id_form_order;
					$post['deskripsi'] = $val->deskripsi;					
					$post['lebar'] = $val->lebar;
					$post['tinggi'] = $val->tinggi;					
					$post['qty_roll'] = $val->qty_roll;//qty yang akan dipotong keteika received item
					$post['qty_order'] = $val->qty_roll;
					$post['qty_mp'] = $val->qty_mp;//akan dipotong ketika received item
					$post['qty_mp_order'] = $val->qty_mp;
					$post['type'] = $val->type;
					$post['keterangan'] = $val->keterangan;
					$post['date_add'] = $val->date_add;
					$post['add_by'] = $val->add_by;
					$res =  $this->m_master->insertdata('tb_supplier_order_det',$post);
					
					//insert notif order
					$res = $this->m_master->insert_notif_transaksi($row->id_cart_spl,$this->acces_code);
				}
			}
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				$this->session->unset_userdata('id_cart_spl');
				$this->session->set_flashdata('success','Supplier Order berhasil dibuat');
				echo json_encode(array('error'=>0,'type'=>'save','redirect'=>base_url('bo/mpsplorder')));
			}
		}else{
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Create supplier order tidak berhasil, session id cart supplier order sudah berakhir'));
		}
	
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->updatedata('tb_supplier_order',array('deleted'=>0),array('id_supplier_order'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus data supplier order berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
	function confirm_notif_order($id='',$id_transaksi=''){
		if ($id != ''){
			//singgle confirm
			$this->db->set('status_confirm',1);
			$this->db->where('id_transaksi',$id_transaksi);
			$this->db->where('id_karyawan',$this->id_karyawan_by);
			$this->db->update('tb_notif_transaksi');
			$this->form($id,1);
		}else{
			//cinfirm all notif
			//singgle confirm
			$this->db->set('status_confirm',1);
			$this->db->where('id_karyawan',$this->id_karyawan_by);
			$this->db->where('akses_code','SPORD');
			$this->db->update('tb_notif_transaksi');
			redirect('bo/'.$this->class);
		}
	
	}
	function cancel($id){
		$priv = $this->m_master->get_priv($this->acces_code,'edit');
		if (empty($priv)){
			$status = $this->db->select('active')->from('tb_supplier_order')->where('id_supplier_order',$id)->get()->result();
			if ($status[0]->active == 1){
				$res = $this->m_master->updatedata('tb_supplier_order',array('active'=>0),array('id_supplier_order'=>$id));
				if ($res){
					$this->session->set_flashdata('success','Supplier Order berhasil dibatalkan');
					redirect('bo/'.$this->class);
				}
			}else{
				$this->session->set_flashdata('danger','Supplier sudah dibatalkan,tidak dapat dibatalkan kembali');
				redirect('bo/'.$this->class);
			}
		}else{
			$this->session->set_flashdata('danger',$priv['notif']);
			redirect('bo/'.$this->class);
		}
	}
}
