<?php if (!defined('BASEPATH')) exit("No direct script access allowed");
class Mpstockbb extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'STBB';	
	
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/stock/v_index_stock_bb' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['page_title'] = "Stok Bahan Baku";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.id_stock_bb',//default order sort
				1 => 'A.label_code_bb',
				2 => 'B.nama_product',
				3 => 'C.nama_kategori',
				4 => 'D.nama_supplier',
				5 => 'A.lebar_bb',
				6 => 'A.tinggi_awal_bb',	
				7 => 'A.tinggi_terpakai_bb',			
				8 => 'A.tinggi_tersedia_bb',
				9 => 'A.luasan_awal_bb',	
				10 => 'A.luasan_terpakai_bb',			
			    11 => 'A.luasan_tersedia_bb',	
				12 => 'E.nama_warehouse'			
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
		$total = count($this->m_master->get_stock_bb());
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
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_stock_bb('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
		
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_stock_bb());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {			
			//label
			$kat = 'bb';
			$tinggi_tersedia = ($row->tinggi_tersedia_bb > 0) ? '<span class="badge badge-success">'.$row->tinggi_tersedia_bb.'</span>' : '<span class="badge badge-important">'.$row->tinggi_tersedia_bb.'</span>';
			$luasan_tersedia = ($row->luasan_tersedia_bb > 0) ? '<span class="badge badge-success">'.$row->luasan_tersedia_bb.'</span>' : '<span class="badge badge-important">'.$row->luasan_tersedia_bb.'</span>';
			$output['data'][]=array($nomor_urut,
						$row->label_code_bb,
						$row->nama_product,
						$row->nama_kategori,
						$row->nama_supplier,
						$row->lebar_bb,
						$row->tinggi_awal_bb,
						$row->tinggi_terpakai_bb,
						$tinggi_tersedia,
						$row->luasan_awal_bb,
						$row->luasan_terpakai_bb,
						$luasan_tersedia,
						$row->nama_warehouse,
						'<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_stock_bb).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>
						<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->label_code_bb.'\',\'tes\')"><i class="icon-trash"></i></button>
						<button onclick="openWin(\''.$row->label_code_bb.'\',\'bb\')" title="Cetak Barcode" class="btn btn-success btn-circle"><i class="icon-qrcode"></i></button>'			
										
			);
			$nomor_urut++;
		}
		echo json_encode($output);	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/stock/v_crud_bb';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Stok Bahan Baku";
			$sql = $this->m_master->get_stock_bb(array('id_stock_bb'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Stok Bahan Baku";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['class'] = $this->class;		
		$data['produk'] = $this->m_master->get_product();
		$data['movement'] = $this->m_master->get_table_column('*','tb_status_movement',array('deleted'=>1));
		$data['warehouse'] = $this->m_master->get_table_column(array('id_warehouse','nama_warehouse'),'tb_warehouse',array('deleted'=>1));
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function show_move_detail(){
		$id = $this->input->post('value');
		$sql = $this->m_master->get_table_column('*','tb_status_movement_detail',array('id_status_move'=>$id));
		if (count($sql) > 0){
			foreach ($sql as $row){
				echo '<option value="'.$row->id_status_movement_detail.'">'.$row->keterangan_move.'</option>';
			}
		}
	}
	function proses(){
		$this->db->trans_start();
		$id = $this->input->post('id');
		$expl = explode("#",$this->input->post('idproduk',true));
		$id_product = $expl[0];
		$id_kategori = $expl[1];
		$id_supplier = $expl[2];
		$lebar = $this->input->post('lebar');
		$tinggi = $this->input->post('tinggi');
		if ($id == ""){
			$qty_roll = $this->input->post('qty');
			for ($i=1; $i <= $qty_roll; $i++){
				$qty = 1;
				$code = $this->m_master->generate_labelcode_bb($id_kategori,$id_supplier,$i);
				$inputs['id_product'] = $id_product;
				$inputs['label_code_bb'] = $code;
				$inputs['id_warehouse'] = $this->input->post('warehouse');
				$inputs['lebar_bb'] = $lebar;
				$inputs['tinggi_awal_bb'] = $tinggi;
				$inputs['tinggi_tersedia_bb'] = $tinggi;
				$inputs['luasan_awal_bb'] = $lebar; //luasan = qty_m2 / lebar
				$inputs['luasan_tersedia_bb'] = $lebar;//luasan = qty_m2 / lebar
				$inputs['qty_pcs_awal'] = $qty;//hanya berlaku untuk produk hardware
				$inputs['qty_pcs_tersedia'] = $qty;//hanya berlaku untuk produk hardware
				$inputs['add_by'] = $this->addby;
				$inputs['update_by'] = $this->addby;
				$input_merge[] = array_merge($inputs);
					
				$id_status = 1;//tambah stock default
				$res = $this->m_master->insert_stock_move_bb($code,$lebar,$tinggi,$qty,$id_status);
			}
			/*input table stock_bb*/
			$res = $this->db->insert_batch('tb_stock_bb',$input_merge);
			$msg = 'Tambah Stok bahan baku berhasil';
		}else{
			//ubah data stock bahan baku
			$label_code_bb = $this->input->post('label_code_bb');
			$tinggi_terpakai = $this->input->post('tinggiused');	
			$tinggi_tersedia = $tinggi - $tinggi_terpakai;
			$luasan_terpakai  = ($lebar / $tinggi) * $tinggi_terpakai;			
			$luasan_tersedia = $lebar - $luasan_terpakai;
			
			$new['id_warehouse'] = $this->input->post('warehouse');
			$new['lebar_bb'] = $lebar;
			$new['tinggi_awal_bb'] = $tinggi;
			$new['tinggi_terpakai_bb'] = $tinggi_terpakai;
			$new['tinggi_tersedia_bb'] = $tinggi_tersedia;
			$new['luasan_awal_bb'] = $lebar;
			$new['luasan_terpakai_bb'] = $luasan_terpakai;
			$new['luasan_tersedia_bb'] = $luasan_tersedia;		
			$new['update_by'] = $this->addby;
			$res = $this->m_master->updatedata('tb_stock_bb',$new,array('id_stock_bb'=>$id));
			$msg = 'Ubah data stock bahan baku berhasil';
			
			/*
			 * insert stock_movement_bj
			*/
			$id_status =  12;//ubah data default from database
			$res = $this->m_master->insert_stock_move_bb($label_code_bb,0,0,0,$id_status);
		}		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
			$this->session->set_flashdata('success',$msg);
			redirect('bo/'.$this->class);
		}
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->updatedata('tb_stock_bb',array('deleted'=>0,'update_by'=>$this->addby),array('label_code_bb'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus stock bahan baku berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
	function export($to){
		$sql = $this->m_master->ex_stock_bb();
		$filename = 'stok_bahanbaku-'.short_date($this->datenow);
		$title = 'Stok Bahan Baku';
		$column_header = array(
				'no'=>'No',
				'label_code_bb'=>'Kode Produk',
				'nama_product'=>'Nama Produk',
				'nama_kategori'=>'Kategori',
				'nama_supplier'=>'Supplier',
				'lebar_bb'=>'Lebar (mm)',
				'tinggi_awal_bb'=>'Tinggi Awal (m)',
				'tinggi_terpakai_bb'=>'Tinggi Terpakai (m)',
				'tinggi_tersedia_bb'=>'Tinggi Tersedia (m)',
				'luasan_awal_bb'=>'Luasan Awal (m2)',
				'luasan_terpakai_bb'=>'Luasan Terpakai (m2)',
				'luasan_tersedia_bb'=>'Luasan Tersedia (m2)',
				'nama_warehouse'=>'Warehouse',				
	
		);
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);
	}
}
