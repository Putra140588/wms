<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpproduksi extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'PDKS';		
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
		$main_page = (empty($priv)) ? 'bo/produksi/v_index_worksheet' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['page_title'] = "Production Work Sheet";
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
				2 => 'A.ws_number',
				3 => 'B.nama_customer',
				4 => 'A.nama_kategory',
				5 => 'A.lebar',
				6 => 'A.tinggi',
				7 => 'A.qty_pcs',
				8 => 'A.qty_roll',
				9 => 'A.qty_roll',
				10 => 'A.status_work',
				11 => 'A.add_by',
				12 => 'A.date_add',
				
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
		$total = count($this->m_master->get_worksheet());
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
		$query = $this->m_master->get_worksheet('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_worksheet());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_worksheet());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_worksheet());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			
			$qty_pcs = $row->qty_pcs;
			$qty_roll = $row->qty_roll;
			$pcsroll=0;//tak terhingga
			/*
			 * bilangan lain tidak bisa dibagi dengan 0 karena nilai tak terhingga (division zero)
			 */
			if ($qty_roll != 0){
				$pcsroll = floor($qty_pcs/$qty_roll);
			}			
			if ($row->nama_status == 1){
				
			}
			$statework = isset($row->nama_status) ? '<span class="'.$row->label_alert.'">'.$row->nama_status.'</span>' : '<span class="label label-warning">Waiting</span>';
			$output['data'][]=array($nomor_urut,
					$row->id_order,
					$row->ws_number,
					$row->nama_customer,
					$row->nama_kategory,
					$row->lebar,
					$row->tinggi,
					qty_format($row->qty_pcs),
					qty_format($row->qty_roll),
					qty_format($pcsroll),	
					$statework,
					$row->add_by,
					$row->date_add,
					'<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_order_detail.'/2').'" title="Print Worksheet" target="_blank" class="btn btn-success btn-circle"><i class="icon-file"></i></a>
					 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_order_detail.'/1').'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/order/v_new_order';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Data Produksi";					
			if ($detail != ''){								
				$sql = $this->m_master->get_worksheet(array('id_order_detail'=>$id));
				foreach ($sql as $row){
					foreach ($row as $key=>$val){
						$data[$key]=$val;
					}
					$qty_pcs = $row->qty_pcs;
					$qty_roll = $row->qty_roll;
					$pcsroll=0;//tak terhingga
					/*
					 * bilangan lain tidak bisa dibagi dengan 0 karena nilai tak terhingga (division zero)
					*/
					if ($qty_roll != 0){
						$pcsroll = floor($qty_pcs/$qty_roll);
					}
				}
				$data['deliv'] = $this->m_master->get_alamat_order($row->id_alamat_pengiriman);
				$data['bill'] = $this->m_master->get_alamat_order($row->id_alamat_tagihan);
				$data['pcsroll'] = $pcsroll;
				$data['karyawan'] = $this->m_master->get_karyawan();
				$data['status'] = $this->m_master->get_table('tb_status_produksi');
				$data['status_work'] = $this->m_master->get_table('tb_status_work');
				$data['produksi'] = $this->m_content->table_produksi($row->ws_number);
				
				if ($detail == 1){
					//show view detail
					$action = 'view';
					$data['page_title'] = "Production Worksheet Details";
					$view = 'bo/produksi/v_detail_worksheet';					
				
				}else{					
					//show view print worksheet
					$this->print_worksheet($data);
					return false;
				}
				
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
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function enter_code($id_kategori){
		$value = $this->input->post('value');
		echo $this->m_content->table_stock_info($value,$id_kategori);
	}
	function create_produksi(){
		$this->db->trans_start();
		$lebar_bb = isset($_POST['lebar_bb']) ? $_POST['lebar_bb'] : '';
		$label_code = isset($_POST['label_code']) ? $_POST['label_code'] : '';
		if ($label_code != ''){
			$ws_number= isset($_POST['ws_number']) ? $_POST['ws_number'] : '';
			$id_status_work = isset($_POST['progresstate']) ? $_POST['progresstate'] : '';
			$tinggi_terpakai = isset($_POST['panjangterpakai']) ? $_POST['panjangterpakai'] : '';
			$input['id_order'] = isset($_POST['id_order']) ? $_POST['id_order'] : '';
			$input['label_code_bb'] = $label_code;
			$input['ws_number'] = $ws_number;
			$input['id_status_produksi'] = isset($_POST['status']) ? $_POST['status'] : '';
			$input['id_status_work'] = $id_status_work;
			$input['date_report'] = isset($_POST['dateproduksi']) ? $_POST['dateproduksi'] : '';
			$input['id_karyawan'] = isset($_POST['operator']) ? $_POST['operator'] : '';
			$input['tinggi_terpakai'] = $tinggi_terpakai;
			$input['tinggi_sisa'] = isset($_POST['panjangsisa']) ? $_POST['panjangsisa'] : '';
			$input['qty_produksi'] = isset($_POST['qty']) ? $_POST['qty'] : '';
			$input['catatan'] = isset($_POST['catatan']) ? $_POST['catatan'] : '';
			$input['date_add'] = $this->datenow;
			$input['add_by'] = $this->addby;
			$res = $this->m_master->insertdata('tb_produksi',$input);
			
			$this->db->set('status_work',$id_status_work);
			$this->db->where('ws_number',$ws_number);
			$this->db->update('tb_order_detail');
			//jika stock bahan baku digunakan
			if ($tinggi_terpakai != ''){
				//potong stock bahan baku
				$res = $this->potong_stock_bb($input);
				
				//insert stock movement BB
				$id_status = 15;//produksi
				$res = $this->m_master->insert_stock_move_bb($label_code,$lebar_bb,$tinggi_terpakai,1,$id_status);
			}
			if ($this->db->trans_status() === FALSE){
				$this->db->roll_back();
			}else{
				$this->db->trans_complete();
				if ($res){						
					$table = $this->m_content->table_produksi($ws_number);
					echo json_encode(array('error'=>0,'msg'=>'Simpan data produksi berhasil!','modal'=>$table));
				}
			}
			
			
		}else{
			echo json_encode(array('error'=>1,'msg'=>'Kode Produk belum di input!'));
		}
	}
	
	function potong_stock_bb($data){
		/*
		 * luasan Terpakai = 160 / 1000 = 0.16 x tinggi terpakai(500) = 80 m2
		 */
		$label_code = $data['label_code_bb'];
		$tinggi_terpakai = $data['tinggi_terpakai'];		
		$get_stock = $this->db->select('lebar_bb,tinggi_awal_bb')
							  ->from('tb_stock_bb')
							  ->where ('label_code_bb',$label_code)
							  ->get()->result();
		
		$lebar = $get_stock[0]->lebar_bb;
		$tinggi_awal = $get_stock[0]->tinggi_awal_bb;				
		$luasan_terpakai  = ($lebar / $tinggi_awal) * $tinggi_terpakai;	
		
		$this->db->set('tinggi_tersedia_bb','tinggi_tersedia_bb-'.$tinggi_terpakai,false);
		$this->db->set('luasan_tersedia_bb','luasan_tersedia_bb-'.$luasan_terpakai,false);
		$this->db->set('tinggi_terpakai_bb','tinggi_terpakai_bb+'.$tinggi_terpakai,false);
		$this->db->set('luasan_terpakai_bb','luasan_terpakai_bb+'.$luasan_terpakai,false);
		$this->db->where ('label_code_bb',$label_code);
		$res = $this->db->update('tb_stock_bb');
		return $res;
	}
	function rollback_stock_bb($data){
		/*
		 * luasan Terpakai = 160 / 1000 = 0.16 x tinggi terpakai(500) = 80 m2
		*/
		$label_code = $data['label_code_bb'];
		$tinggi_terpakai = $data['tinggi_terpakai'];
		$get_stock = $this->db->select('lebar_bb,tinggi_awal_bb')
							->from('tb_stock_bb')
							->where ('label_code_bb',$label_code)
							->get()->result();
	
		$lebar = $get_stock[0]->lebar_bb;
		$tinggi_awal = $get_stock[0]->tinggi_awal_bb;
		$luasan_terpakai  = ($lebar / $tinggi_awal) * $tinggi_terpakai;
		//ubah stock bb
		$this->db->set('tinggi_tersedia_bb','tinggi_tersedia_bb+'.$tinggi_terpakai,false);
		$this->db->set('luasan_tersedia_bb','luasan_tersedia_bb+'.$luasan_terpakai,false);
		$this->db->set('tinggi_terpakai_bb','tinggi_terpakai_bb-'.$tinggi_terpakai,false);
		$this->db->set('luasan_terpakai_bb','luasan_terpakai_bb-'.$luasan_terpakai,false);
		$this->db->where ('label_code_bb',$label_code);
		$res = $this->db->update('tb_stock_bb');
		
		//insert stock movement BB
		$id_status = 14;//rollback stock
		$res = $this->m_master->insert_stock_move_bb($label_code,$lebar,$tinggi_terpakai,1,$id_status);
		return $res;
	}
	function print_worksheet($data){		
		$data['title'] = 'Worksheet No - '.$data['ws_number'];		
		$this->load->view('letter_format/v_navbar_button');
		$this->load->view('letter_format/v_header_letter',$data);
		$this->load->view('letter_format/v_content_worksheet');
		//$this->load->view('letter_format/v_footer_letter');
	}
	
	function delete($val){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){			
			$cek = $this->m_master->get_table_column(array('tinggi_terpakai','label_code_bb','ws_number'),'tb_produksi',array('id_produksi'=>$val));
			foreach ($cek as $row){
				$tinggi_terpakai = $row->tinggi_terpakai;				
				//jika menghapus data yg sudah ada proses produksi dan pemakaian stock bahan baku
				if ($tinggi_terpakai > 0 ){
					$data['label_code_bb'] = $row->label_code_bb;
					$data['tinggi_terpakai'] = $tinggi_terpakai;
					//proses rollback kembali stock yg telah terpakai
					$this->rollback_stock_bb($data);		
				}
				$this->db->set('status_work',1);//default InProgress
				$this->db->where('ws_number',$row->ws_number);
				$this->db->update('tb_order_detail');
			}
			$res = $this->m_master->updatedata('tb_produksi',array('deleted'=>0),array('id_produksi'=>$val));
			if ($res){
				$this->session->set_flashdata('success','Hapus data produksi berhasil');
				$url = $_SERVER['HTTP_REFERER'];
				redirect($url);//redirect current url
				
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
}
