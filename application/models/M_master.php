<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class M_master extends CI_Model{
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $id_cart_ord;
	var $id_karyawan_by;
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);		
		$this->id_cart_ord = $this->session->userdata('id_cart_ord');
		$this->id_karyawan_by = $this->session->userdata('id_karyawan');
	}	
	function insertdata($table,$post){
		$res = $this->db->insert($table,$post);
		return $res;
	}	
	function updatedata($table,$data,$where){
		$res = $this->db->update($table,$data,$where);
		return $res;
	}
	function deletedata($table,$where){
		$res = $this->db->delete($table,$where);
		return $res;
	}
	function cek_login($post){
		$sql = $this->db->select('A.*,B.nama_bagian,C.nama_jabatan,D.nama_group')
						->from	('tb_karyawan as A')
						->join	('tb_bagian as B','A.id_bagian = B.id_bagian','left')
						->join	('tb_jabatan as C','A.id_jabatan = C.id_jabatan','left')
						->join	('tb_group as D','A.kd_group = D.kd_group','left')
						->where	($post)
						->where	('deleted',1)
						->where	('active',1)
						->get()->result();
		return $sql;
	}
	function get_login(){
		if ($this->session->userdata('login_admin') == false){
			redirect('bo/mplogin');
		}
	}
	function get_akses_modul($where){
		$kd_group = $this->session->userdata('kd_group');
		$sql = $this->db->select('A.*,B.*')
					->from  ('tb_akses as A')
					->join	('tb_modul as B','A.id_modul = B.id_modul','left')
					->where	('A.kd_group',$kd_group)
					->where	('A.active',1)
					->where ($where)
					//->where	('B.id_modul_parent',0)
					//->where	('B.level',0)
					->order_by('B.sort','asc')
					->get();
		return $sql;
	}
	function get_table($table){
		return $this->db->get($table);
	}
	function get_table_column($column,$table,$where=''){
			$this->db->select($column);
			$this->db->from($table);
			($where != '') ? $this->db->where($where) : '';
		return $this->db->get()->result();
	}
	function get_table_filter($table,$where='',$column='',$sort='',$length='',$start=''){		
		$this->db->select('*');
		$this->db->from($table);
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql = $this->db->get()->result();		
		return $sql;
	}
	function get_akses_all(){
		//tidak menampilkan superadmin
		return $this->db->where('kd_group <> ','SA')->get('tb_group');
	}	
	function get_karyawan($where='',$column='',$sort='',$length='',$start=''){
			 $this->db->select('A.*,B.nama_jabatan,C.nama_bagian,D.nama_group');
			 $this->db->from	('tb_karyawan as A');
			 $this->db->join	('tb_jabatan as B','A.id_jabatan = B.id_jabatan','left');
			 $this->db->join	('tb_bagian as C','A.id_bagian = C.id_bagian','left');	
			 $this->db->join	('tb_group D','A.kd_group = D.kd_group','left');
			 $this->db->where	('A.deleted',1);
			 /*where digunakan untuk get by field*/
			 ($where !='') ? $this->db->where($where) : '';			 
			 $this->db->limit($length,$start);
			 $this->db->order_by($column,$sort);			
			 $sql =  $this->db->get()->result();
		return $sql;
	}
	
	function get_kategori($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_form');
		$this->db->from ('tb_kategori as A');
		$this->db->join	('tb_form_order as B','A.id_form_order = B.id_form_order','left');		
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	/*
	 * mengakftifkan mungsi search by keyword
	 */
	function search_like($keyword,$column){		
		//melakukan pengulangan column
		$this->db->group_start();
		foreach ($column as $val=>$row){
			$this->db->or_like($row,$keyword);
		}
		$this->db->group_end();
	}
	/*Menangkap semua data yang dikirimkan oleh client*/
	function request_datatable(){
		/*Offset yang akan digunakan untuk memberitahu database
		 dari baris mana data yang harus ditampilkan untuk masing masing page
		 */
		$start = $_REQUEST['start'];	
		/*Keyword yang diketikan oleh user pada field pencarian*/
		$keyword = $_REQUEST['search']["value"];
		/*Sebagai token yang yang dikrimkan oleh client, dan nantinya akan
		 server kirimkan balik. Gunanya untuk memastikan bahwa user mengklik paging
		 sesuai dengan urutan yang sebenarnya */
		$draw = $_REQUEST['draw'];	
		/*asc/desc yg direquest dari client*/
		$sorting = $_REQUEST['order'][0]['dir'];	
		/*index column yg direquest dari client*/
		$column = $_REQUEST['order'][0]['column'];	
		/*Jumlah baris yang akan ditampilkan pada setiap page*/
		$length = $_REQUEST['length'];
		/*value tanggal from terdapat di index 0 columns*/
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		/*value tanggal to terdapat di index 1 columns*/
		$date_to = $_REQUEST['columns'][1]['search']['value'];
		
		$output = array('start'=>$start,'keyword'=>$keyword,
					   'draw'=>$draw,'sorting'=>$sorting,
					   'column'=>$column,'length'=>$length,
						'date_from'=>$date_from,'date_to'=>$date_to				
		);
		return $output;
	}
	function get_modul_group($where){
		$sql = $this->db->select('A.nama_modul,A.link,A.akses_code,
								 B.id_akses,B.kd_group,B.id_modul,B.active,B.add,B.edit,B.delete,B.view')
						->from	('tb_modul as A')
						->join	('tb_akses as B','A.id_modul = B.id_modul','left')
						->where	($where)
						->get();
		return $sql;
	}
	function get_priv($ac,$action){
		$notif='';
		$alias_array  = array('view'=>'Menampilkan halaman','add'=>'Tambah baru',
							  'edit'=>'Ubah data','delete'=>'Hapus data','active'=>'Akses modul');
		$kd_group = $this->session->userdata('kd_group');
		$this->db->select ('A.active,A.add,A.edit,A.delete,A.view,B.nama_modul,B.akses_code');
		$this->db->from   ('tb_akses as A');
		$this->db->join	  ('tb_modul as B','A.id_modul = B.id_modul','left');
		$this->db->where  ('A.kd_group',$kd_group);
		$this->db->where  ('B.akses_code',$ac);
		$sql = $this->db->get()->result();
		foreach ($sql as $row)
			if ($row->$action != 1){			
			$data['notif'] = 'Anda tidak punya hak untuk '.$alias_array[$action].' '.$row->nama_modul;
			$data['error'] = 'v_access_denied';
			return $data;
		}		
	}
	function random_ref($pref){
		//random number
		$code = str_replace(array('.',' '), '', microtime());
		$acak = str_shuffle($code);
		$date = date('dmy');
		$ref = $pref.$date.substr($acak, 1,5);
		return $ref;
	}
	function get_product($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_kategori,C.nama_supplier');
		$this->db->from('tb_product as A');
		$this->db->join('tb_kategori as B','A.id_kategori = B.id_kategori','left');
		$this->db->join	('tb_supplier as C','A.id_supplier = C.id_supplier','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
		
	}
	function get_moduls($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.id_modul,A.nama_modul,A.level,A.akses_code,B.nama_modul as nama_parent');
		$this->db->from	('tb_modul as A');
		$this->db->join	('tb_modul as B','A.id_modul = B.id_modul_parent','left');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;		
	}
	function get_id_max($column,$table){
		$sql = $this->db->select_max($column)
						->from ($table)
						->limit(1)
						->get()->result();
		$id = $sql[0]->$column;
		return $id;
	}
	function get_order($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_customer,C.nama_depan,C.nama_belakang,D.terms,E.nama_courier');
		$this->db->from	('tb_order as A');
		$this->db->join	('tb_customer as B','A.id_customer = B.id_customer','left');
		$this->db->join ('tb_karyawan as C','A.id_karyawan = C.id_karyawan','left');
		$this->db->join	('tb_terms as D','A.id_terms = D.id_terms','left');
		$this->db->join('tb_courier as E','A.id_courier = E.id_courier','left');
		$this->db->where ('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_supplier_order($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_supplier,B.alamat,B.phone');
		$this->db->from	('tb_supplier_order as A');
		$this->db->join	('tb_supplier as B','A.id_supplier = B.id_supplier','inner');		
		$this->db->where ('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_customer($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.alamat,B.phone');		
		$this->db->from ('tb_customer as A');
		$this->db->join ('tb_alamat as B','A.id_customer = B.id_customer','left');
		$this->db->where ('A.deleted',1);
		$this->db->where ('B.default',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_alamat($where){
		$this->db->where('deleted',1);
		return $this->db->get_where('tb_alamat',$where);
	}
	function so_refnumber($pref){

		$getmaxnum = $this->getMaxId_order();
		//jika nilai true make increament number
		if (count($getmaxnum) > 0)
		{
			//get max for increment
			$number = $getmaxnum[0]->id_order;
				
		}else{
			//set default 0 + 1 if change year
			$number = 0;
		}
		$number++;//num + 1
		$datenow = date('ymd');
		$prefix = $pref.$datenow;
		$unique = str_pad($number, 5, "0", STR_PAD_LEFT);
		$ref_order = $prefix.$unique;
		return $ref_order;
	}
	public function getMaxId_order()
	{
		//9 = mengambil nilai yang ke 9
		//5 = panjang perhitungan 5 digit
	
		//mendapatkan id_order maks berdasarkan tahun sekarang
		$yearnow = date('Y');
		$sql = $this->db->query('SELECT MAX(substring(id_order,9,5))AS id_order FROM tb_order
								WHERE DATE_FORMAT(date_add,"%Y")="'.$yearnow.'" and deleted=1');
		return $sql->result();
	}
	function get_jenis_bahan($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.id_product,A.nama_product,B.nama_supplier,C.nama_kategori,C.id_form_order');
		$this->db->from ('tb_product as A');
		$this->db->join ('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->join	('tb_kategori as C','A.id_kategori = C.id_kategori','left');
		$this->db->where ('A.deleted',1);
		$this->db->where ('A.active',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_order_cart($where){
		$this->db->select	('A.*,B.nama_customer,C.nama_depan,C.nama_belakang,D.terms,E.nama_courier');
		$this->db->from		('tb_order_cart as A');
		$this->db->join		('tb_customer as B','A.id_customer = B.id_customer','left');
		$this->db->join		('tb_karyawan as C','A.id_karyawan = C.id_karyawan','left');
		$this->db->join		('tb_terms as D','A.id_terms = D.id_terms','left');
		$this->db->join		('tb_courier as E','A.id_courier = E.id_courier','left');
		$this->db->where($where);
		$this->db->limit(1);
		return $this->db->get()->result();
	}
	
	function get_spl_cart($where){
		$this->db->select	('A.*,B.*');
		$this->db->from		('tb_spl_ord_cart as A');
		$this->db->join		('tb_supplier as B','A.id_supplier = B.id_supplier','left');		
		$this->db->where($where);
		$this->db->limit(1);
		return $this->db->get()->result();
	}
	function get_alamat_order($id_alamat){
		$sql = $this->get_table_column(array('nama_customer','phone','alamat'),'tb_alamat',array('id_alamat'=>$id_alamat));
		return $sql;
	}
	function get_product_cart(){
		return $this->get_table_column('*', 'tb_product_cart',array('id_cart_ord'=>$this->id_cart_ord));
	}
	function get_total_harga(){
		$sql = $this->db->select_sum('total_harga')
						->from ('tb_product_cart')
						->where	('id_cart_ord',$this->id_cart_ord)
						->get()->result();
		return $sql[0]->total_harga;
	}
	function get_receive_item($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_supplier');
		$this->db->from ('tb_receive_items as A');
		$this->db->join	('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->where ('A.deleted',1);
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function po_complete($id_cart_spl){
		//menjumlahkan status received yang belum diterima
		/*
		 * jika belum diterima semua maka count > 0
		 * jika sudah diterima semua maka count == 0
		 */
		$sql = $this->db->select('count(state_received_det) as state_received_det')
						->from('tb_supplier_order_det')
						->where	('id_cart_spl',$id_cart_spl)
						->where ('state_received_det',0)
						->get()->result();
		return $sql[0]->state_received_det;;
	}
	function total_po_receive($id){
		$sql = $this->db->select('id_cart_spl')
					    ->from	('tb_receive_items_detail')
					    ->where	('id_receive_items',$id)
					    ->get()->result();
		foreach ($sql as $row){
			$id_cart_spl[] = $row->id_cart_spl;
		}
		
		$q = $this->db->select('po_number')
					  ->from ('tb_supplier_order')
					  ->where_in ('id_cart_spl',$id_cart_spl)
					  ->get()->result();
		foreach ($q as $val){
			$po[] = $val->po_number;
		}		
		return $po;
	}
	function get_worksheet($where='',$column='',$sort='',$length='',$start=''){
		/*
		 * dditampilkan hanya jenis label (LBL)
		 * hanya menampilkan order yg sudah diapproved yang ke 2 
		 * tidak ditampilkan jika order telah dibatalkan
		 */
		$this->db->select('A.*,B.id_customer,B.nama_customer,C.id_alamat_pengiriman,C.id_alamat_tagihan,C.date_shipp,D.nama_depan,D.nama_belakang,E.id_kategori,
				          F.nama_supplier,G.nama_sketch,G.image,H.nama_status,H.label_alert');
		$this->db->from  ('tb_order_detail as A');
		$this->db->join  ('tb_order as C','A.id_order = C.id_order','left');
		$this->db->join	 ('tb_customer as B','C.id_customer = B.id_customer','left');
		$this->db->join  ('tb_karyawan as D','C.id_karyawan = D.id_karyawan','left');
		$this->db->join	 ('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join	 ('tb_supplier as F','E.id_supplier = F.id_supplier','left');
		$this->db->join	 ('tb_cutter_sketch as G','A.cutter_sketch = G.id_cutter_sketch','left');
		$this->db->join	 ('tb_status_work as H','A.status_work = H.id_status_work','left');
		$this->db->where ('C.approved',2);
		$this->db->where ('C.deleted',1);
		$this->db->where ('C.active',1);
		$this->db->where ('A.id_form_order','LBL');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;		
	}
	function get_receive_detail($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_product,B.deskripsi,B.id_form_order,B.nama_kategory,C.nama_warehouse,D.id_kategori');
		$this->db->from ('tb_receive_items_detail as A');
		$this->db->join ('tb_supplier_order_det as B','A.id_supplier_order_det = B.id_supplier_order_det','left');
		$this->db->join ('tb_warehouse as C','A.id_warehouse = C.id_warehouse','left');
		$this->db->join ('tb_product as D','B.id_product = D.id_product','left');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_supplier_order_detail($id){
		$this->db->select('A.id_product,B.id_kategori,B.id_supplier');
		$this->db->from('tb_supplier_order_det as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','inner');
		$this->db->where_in('A.id_supplier_order_det',$id);
		$sql = $this->db->get();
		return $sql;
	}
	function get_stock_bb($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_product,B.id_kategori,B.id_supplier,
						   C.nama_kategori,C.id_form_order,D.nama_supplier,E.nama_warehouse');
		$this->db->from('tb_stock_bb as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_kategori as C','B.id_kategori = C.id_kategori','left');
		$this->db->join('tb_supplier as D','B.id_supplier = D.id_supplier','left');
		$this->db->join('tb_warehouse as E','A.id_warehouse = E.id_warehouse','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	/*
	 * menampilkan data history produksi
	 */
	function get_table_produksi($id){
		$this->db->select('A.*,C.nama_product,D.nama_kategori,E.nama_supplier,F.nama_depan,F.nama_belakang,G.nama_status');
		$this->db->from ('tb_produksi as A');
		$this->db->join	('tb_stock_bb as B','A.label_code_bb = B.label_code_bb','left');
		$this->db->join	('tb_product as C','B.id_product = C.id_product','left');
		$this->db->join	('tb_kategori as D','C.id_kategori = D.id_kategori','left');
		$this->db->join	('tb_supplier as E','C.id_supplier = E.id_supplier','left');
		$this->db->join	('tb_karyawan as F','A.id_karyawan = F.id_karyawan','left');
		$this->db->join	('tb_status_produksi as G','A.id_status_produksi = G.id_status_produksi','left');
		$this->db->where ('A.ws_number',$id);
		$this->db->where ('A.deleted',1);
		$this->db->order_by('A.date_add','desc');
		return $this->db->get();
	}
	function get_tinggi_terpakai($ws){
		$this->db->select('sum(tinggi_terpakai) as tinggi_terpakai');
		$this->db->select('sum(qty_produksi) as qty_produksi');		
		$this->db->from('tb_produksi');
		$this->db->where('ws_number',$ws);
		$this->db->where('deleted',1);
		//$this->db->where('label_code_bb',$code);
		return $sql = $this->db->get()->result();
	}
	function get_group_produksi($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.label_code_bb,A.ws_number,C.nama_product,D.nama_kategori');
		$this->db->select_sum('A.qty_produksi');
		$this->db->from('tb_produksi as A');
		$this->db->join('tb_stock_bb as B','A.label_code_bb = B.label_code_bb','left');
		$this->db->join('tb_product as C','B.id_product = C.id_product','left');
		$this->db->join('tb_kategori as D','C.id_kategori = D.id_kategori','left');		
		$this->db->where('A.status_sliting',0);
		$this->db->where('A.deleted',1);
		$this->db->group_by('A.label_code_bb,A.ws_number');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;		
	}
	function get_sliting($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,C.nama_product,D.nama_kategori,E.nama_depan,E.nama_belakang,F.nama_warehouse');
		$this->db->from('tb_sliting as A');
		$this->db->join('tb_stock_bb as B','A.label_code_bb = B.label_code_bb','left');
		$this->db->join('tb_product as C','B.id_product = C.id_product','left');
		$this->db->join('tb_kategori as D','C.id_kategori = D.id_kategori','left');
		$this->db->join('tb_karyawan as E','A.id_karyawan = E.id_karyawan','left');
		$this->db->join('tb_warehouse as F','A.id_warehouse = F.id_warehouse','left');
		$this->db->where('A.saving',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function cek_label_code_bj($where){
		$sql = $this->db->select('label_code_bj')
						->from ('tb_stock_bj')
						->where ($where)						
						->limit(1)
						->get()->result();															
		if (count($sql) > 0){
			return $sql[0]->label_code_bj;
		}else{
			//tidak tersedia
			return false;
		}		
	}
	function get_id_kategori($ws_number){
		$this->db->select('B.id_kategori');
		$this->db->from('tb_order_detail as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->where_in('ws_number',$ws_number);
		$sql = $this->db->get()->result();
		return $sql[0]->id_kategori;
	}
	function get_stock_bahan_jadi($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_kategori,B.id_form_order,C.nama_warehouse,D.nama_form');
		$this->db->from('tb_stock_bj as A');
		$this->db->join('tb_kategori as B','A.id_kategori = B.id_kategori','left');
		$this->db->join('tb_warehouse as C','A.id_warehouse = C.id_warehouse','left');
		$this->db->join('tb_form_order as D','B.id_form_order = D.id_form_order','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock_move_bj($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.lebar_bj,B.tinggi_bj,C.nama_kategori,D.keterangan_move,E.nama_warehouse,F.nama_status');
		$this->db->from('tb_stock_movement_bj as A');
		$this->db->join('tb_stock_bj as B','A.label_code_bj = B.label_code_bj','left');
		$this->db->join('tb_kategori as C','B.id_kategori = C.id_kategori','left');
		$this->db->join('tb_status_movement_detail as D','A.id_status_movement_detail = D.id_status_movement_detail','left');
		$this->db->join('tb_warehouse as E','B.id_warehouse = E.id_warehouse','left');
		$this->db->join('tb_status_movement as F','D.id_status_move = F.id_status_move','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock_move_bb($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,C.nama_kategori,D.keterangan_move,E.nama_warehouse,F.nama_status,G.nama_product');
		$this->db->from('tb_stock_movement_bb as A');
		$this->db->join('tb_stock_bb as B','A.label_code_bb = B.label_code_bb','left');
		$this->db->join('tb_product as G','B.id_product = G.id_product','left');
		$this->db->join('tb_kategori as C','G.id_kategori = C.id_kategori','left');
		$this->db->join('tb_status_movement_detail as D','A.id_status_movement_detail = D.id_status_movement_detail','left');
		$this->db->join('tb_warehouse as E','B.id_warehouse = E.id_warehouse','left');
		$this->db->join('tb_status_movement as F','D.id_status_move = F.id_status_move','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_sales_order($where){
		$this->db->select('A.nama_product,A.nama_kategory,A.deskripsi,A.lebar,A.tinggi,A.qty_pcs,B.po_number');						 
		$this->db->from  ('tb_order_detail as A');
		$this->db->join  ('tb_order as B','A.id_order = B.id_order','left');
		$this->db->where ($where);
		return $this->db->get()->result();
		
	}
	function get_delivery_order($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.po_number,C.nama_customer,C.alamat,C.phone,D.nama_courier');
		$this->db->from('tb_delivery_order as A');
		$this->db->join	('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join ('tb_alamat as C','B.id_alamat_pengiriman = C.id_alamat','left');
		$this->db->join ('tb_courier as D','B.id_courier = D.id_courier','left');
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_delivery_order_detail($id){
		$sql = $this->db->select('A.*,B.lebar_bj,B.tinggi_bj,C.nama_kategori,C.id_form_order,D.nama_warehouse')
						->from('tb_delivery_order_detail as A')
						->join('tb_stock_bj as B','A.label_code_bj = B.label_code_bj','left')
						->join('tb_kategori as C','B.id_kategori = C.id_kategori','left')
						->join('tb_warehouse as D','B.id_warehouse = D.id_warehouse','left')
						->where('A.id_delivery_order',$id)
						->get()->result();
		return $sql;
	}
	function stock_bj($label_code_bj){
		$sql = $this->db->select('qty_pcs_tersedia')
						->from('tb_stock_bj')
						->where('label_code_bj',$label_code_bj)
						->get()->result();
		return $sql[0]->qty_pcs_tersedia;
	}
	function get_supplier_order_det($id_cart_spl){
		$this->db->select('A.*,B.id_kategori');
		$this->db->from('tb_supplier_order_det as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->where_in('A.id_cart_spl',$id_cart_spl);
		$this->db->where('state_received_det',0);//tampilkan jika barang belum diterima
		return $this->db->get()->result();
	}
	/*
	 * pembuatan stock bahan jadi
	 * Jika ada penerimaan barang dengan kategori dan ukuran yang sama stock bahan jadi akan bertambah dan tidak membuat barcode baru
	 * 
	*/
	function generate_stock_bj($id_kategori,$lebar,$tinggi,$qty_pcs,$id_warehouse,$id_status){
		$lebars = ($lebar != '') ? $lebar : 0;
		$tinggis = ($tinggi != '') ? $tinggi : 0;
		/*
		 * melakukan cek stock bahan jadi apakah sudah tersedia, sesuai kategori, dan ukuran
		 */
		$where = array('id_kategori'=>$id_kategori,'lebar_bj'=>$lebars,'tinggi_bj'=>$tinggis);
		$label_code_bj = $this->m_master->cek_label_code_bj($where);
		if ($label_code_bj != false){
			//update stock jika label code bj tersedia
			$this->db->set('qty_pcs_awal','qty_pcs_awal+'.$qty_pcs,false);
			$this->db->set('qty_pcs_tersedia','qty_pcs_tersedia+'.$qty_pcs,false);
			$this->db->where($where);
			$res =  $this->db->update('tb_stock_bj');
		}else{
			//insert baru label code bj tidak tersedia
			$label_code_bj = $id_kategori.'-'.$lebars.'-'.$tinggis.'-'.date('is');
			$new['label_code_bj'] = $label_code_bj;			
			$new['id_kategori'] = $id_kategori;
			$new['id_warehouse'] = $id_warehouse;
			$new['lebar_bj'] = $lebars;
			$new['tinggi_bj'] = $tinggis;
			$new['qty_pcs_awal'] = $qty_pcs;
			$new['qty_pcs_tersedia'] = $qty_pcs;
			$new['add_by'] = $this->addby;
			$new['update_by'] = $this->addby;
			$res =  $this->m_master->insertdata('tb_stock_bj',$new);
		}
		$res = $this->m_master->insert_stock_move_bj($label_code_bj,$qty_pcs,$id_status);
		return $res;
	}
	
	function insert_stock_move_bj($label_code_bj,$qty_pcs,$id_status){
		/*
		 * insert stock_movement_bj
		*/
		$move['label_code_bj'] = $label_code_bj;
		$move['qty_pcs_awal'] = $qty_pcs;
		$move['id_status_movement_detail'] = $id_status;
		$move['date_add'] = $this->datenow;
		$move['add_by'] = $this->addby;
		return $this->m_master->insertdata('tb_stock_movement_bj',$move);
	}
	function insert_stock_move_bb($label_code_bb,$lebar_bb,$tinggi_bb,$qty_roll,$id_status){
		/*
		 * insert stock_movement_bb
		*/
		$move['label_code_bb'] = $label_code_bb;
		$move['lebar_bb'] = $lebar_bb;
		$move['tinggi_bb'] = $tinggi_bb;
		$move['qty_roll'] = $qty_roll;
		$move['id_status_movement_detail'] = $id_status;
		$move['date_add'] = $this->datenow;
		$move['add_by'] = $this->addby;
		$batch[] = $move;
		return $this->db->insert_batch('tb_stock_movement_bb',$batch);
	}
	function insert_notif_transaksi($id_transaksi,$akses_code){
		$this->db->select('id_karyawan');
		$this->db->where('deleted',1);
		$sql = $this->db->get('tb_karyawan');
		if ($sql->num_rows() > 0){
			foreach ($sql->result() as $row){
				$insert['id_karyawan'] = $row->id_karyawan;
				$insert['id_transaksi'] = $id_transaksi;
				$insert['akses_code'] = $akses_code;
				$batch[] = array_merge($insert);
			}
			$res = $this->db->insert_batch('tb_notif_transaksi',$batch);
		}
		return $res;
	}
	function get_notif_so(){
		$sql = $this->db->select('A.id_transaksi,B.date_add,C.nama_customer')
						->from('tb_notif_transaksi as A')
						->join('tb_order as B','A.id_transaksi = B.id_order','left')
						->join('tb_customer as C','B.id_customer = C.id_customer','left')
						->where('A.akses_code','SORD')//default code Sales Order
						->where('A.id_karyawan',$this->id_karyawan_by)
						->where('A.status_confirm',0)
						->order_by('B.date_add','desc')
						->get();
		return $sql;
	}
	function get_notif_splorder(){
		$sql = $this->db->select('A.id_transaksi,B.date_add,B.id_supplier_order,C.nama_supplier')
						->from('tb_notif_transaksi as A')
						->join('tb_supplier_order as B','A.id_transaksi = B.id_cart_spl','left')
						->join('tb_supplier as C','B.id_supplier = C.id_supplier','left')
						->where('A.akses_code','SPORD')//default code supplier order
						->where('A.id_karyawan',$this->id_karyawan_by)
						->where('A.status_confirm',0)
						->order_by('B.date_add','desc')
						->get();
		return $sql;
	}
	function generate_labelcode_bb($id_kategori,$id_supplier,$i){
		$datenow = date('ymd');
		$code = $id_kategori.'-'.$id_supplier.'-'.$datenow.'-'.str_replace('.', '', substr(microtime() + $i,0,5)).'-'.$i;
		return $code;
	}
	/*
	 * mendapatkan total grafik presentase data produksi dan sliting setiap bulan pertahun
	 */
	function get_sum_prod_slit($yearmonth,$periode){
		
		if ($periode == 'ym'){
			/*
			 * JIka periode adalah Tahun dan tanggal
			 */
			$this->db->where_in('DATE_FORMAT(date_add,"%Y-%m")',$yearmonth);
			$this->db->group_by('DATE_FORMAT(date_add,"%Y-%m")');
		}else{
			/*
			 * jika periode 1 tahun
			 * digunakan untuk grafik Pie mentotalkan keseluruhan
			 */
			
			$this->db->where_in('DATE_FORMAT(date_add,"%Y")',date('Y'));
			$this->db->group_by('DATE_FORMAT(date_add,"%Y")');
		}
		$this->db->select('SUM(qty_produksi) as qty_produksi,DATE_FORMAT(date_add,"%Y-%m") as yearmonth');
		$this->db->where('deleted',1);
		$this->db->from('tb_produksi');		
		$sql = $this->db->get();		
		//echo '<pre>';print_r($sql->result());die;
		if ($sql->num_rows() > 0){
			foreach ($sql->result() as $row){
				$qty_produksi = $row->qty_produksi;
				$qty_sliting  = $this->get_qty_sliting($row->yearmonth);
				$slitting = $qty_sliting / $qty_produksi * 100;
				$produksi = ($qty_produksi - $qty_sliting) / $qty_produksi * 100;	
				$data['yearmonth'][] = $row->yearmonth;				
				$data['slitting'][] = number_format($slitting,2,'.',',');
				$data['produksi'][] = number_format($produksi,2,'.',',');
				$data['rata'][] = number_format($slitting / 2,2,'.',',');
			}				
			
		}else{
			$data['yearmonth'][] = date('Y-m');
			$data['slitting'][] = 0;
			$data['produksi'][] = 0;
			$data['rata'][] = 0;
		}
		
		return $data;
	}
	function get_qty_sliting($yearmonth){
		/*
		 * mendapatkan jumlah sliting berdasarkan no worksheet dari table produksi
		 */
		$sql = $this->db->select('SUM(qty_pcs_sliting) as qty_sliting')
						->from('tb_sliting')
						->where_in('DATE_FORMAT(date_add,"%Y-%m")',$yearmonth)
						->group_by('DATE_FORMAT(date_add,"%Y-%m")')
						->get();
		if ($sql->num_rows() > 0){
			foreach ($sql->result() as $row){
				$qty_sliting = $row->qty_sliting;
			}
		}else{
			$qty_sliting = 0;
		}
		return $qty_sliting;
	}
	/*
	 * mendapatkan jumlah sales order dalam setahun
	 */
	function get_presentase_so($yearmonth){
		$this->db->select('count(*) as total,date_add');
		$this->db->from('tb_order');
		$this->db->where('deleted',1);
		$this->db->where('approved',2);
		$this->db->where('active',1);
		$this->db->where_in('DATE_FORMAT(date_add,"%Y-%m")',$yearmonth);
		$this->db->group_by('DATE_FORMAT(date_add,"%Y-%m")');
		$sql = $this->db->get();
		if ($sql->num_rows() > 0){
			foreach ($sql->result() as $row){
				//membentuk format string untuk diparsing ke grafik
				$data['datax'][] = "['".date('F Y',strtotime($row->date_add))."',".$row->total."],";				
			}
			
		}else{
			$data['datax'][] = "['".date('F Y')."',0],";
		}
		return $data;
	}
	function get_grafik_bb(){
		$this->db->select('SUM(A.luasan_awal_bb) as awal,SUM(A.luasan_terpakai_bb) as terpakai,SUM(A.luasan_tersedia_bb) as tersedia');
		$this->db->select('C.id_kategori,C.nama_kategori');
		$this->db->from('tb_stock_bb as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_kategori as C','B.id_kategori = C.id_kategori','left');
		$this->db->group_by('id_kategori');
		$sql = $this->db->get()->result();
		return $sql;	
	}
	function get_grafik_bj(){
		$this->db->select('SUM(A.qty_pcs_awal) as awal,
						   SUM(A.qty_pcs_terpakai) as terpakai,
						   SUM(A.qty_pcs_tersedia) as tersedia');
		$this->db->select('C.id_kategori,C.nama_kategori,C.id_form_order');
		$this->db->from('tb_stock_bj as A');		
		$this->db->join('tb_kategori as C','A.id_kategori = C.id_kategori','left');
		$this->db->group_by('id_kategori');
		$sql = $this->db->get()->result();
		return $sql;
	}
	function ex_karyawan(){
		 $this->db->select('A.nik,A.nama_depan,A.nama_belakang,A.jenkel,A.phone,A.email,B.nama_jabatan,C.nama_bagian,D.nama_group,A.date_add');
		 $this->db->from	('tb_karyawan as A');
		 $this->db->join	('tb_jabatan as B','A.id_jabatan = B.id_jabatan','left');
		 $this->db->join	('tb_bagian as C','A.id_bagian = C.id_bagian','left');	
		 $this->db->join	('tb_group D','A.kd_group = D.kd_group','left');
	     $this->db->where	('A.deleted',1);
	     $this->db->order_by('A.nama_depan','asc');
	     return $this->db->get();
	}
	function generate_export($to,$filename,$sql,$title,$column,$ort=''){
		if ($to == 'csv'){
			$this->load->dbutil(); // call db utility library
			$this->load->helper('download'); // call download helper
			$file_name = $filename.'.csv';
			$delimiter = ";";
			$newline = "\r\n";//baris baru
			$enclosure = '';//tanda kutip
			//remove firts line (headername)
			$convert = ltrim(strstr($this->dbutil->csv_from_result($sql,$delimiter,$newline,$enclosure), $newline));
			force_download($file_name, $convert);
		}else if ($to == 'excel'){
			$this->load->helper('to_excel');
			$file_name = $filename;
			to_excel_custom($sql,$file_name,$column);
		}else if ($to == 'pdf'){
			error_reporting(1);
			$parameters = array (
					'paper'=>'A4',
					'orientation'=>'landscape',
					'type'=>'',
					'options'=>'',
			);
			$this->load->library('Pdf', $parameters);
			//path font set
			$this->pdf->selectFont(APPPATH.'/third_party/pdf-php/fonts/FreeSerif.afm');
			$this->pdf->ezImage(base_url('assets/bo/images/logo/satuscan.jpg'), 0, 200, 'none', 'left');
			$this->pdf->ezText($title, 14, array('justification'=> 'centre'));
			$this->pdf->ezSetDy(-10);//spasi
			$this->pdf->ezText(short_date($this->datenow), 14, array('justification'=> 'centre'));
			$this->pdf->ezSetDy(-15);
			$no = 1;
			foreach ($sql->result_array() as $key=>$value){
				$data[$key] = $value;
				$data[$key]['no'] = $no++;	
			}
			$this->pdf->ezTable($data, $column);
			$file_name = $filename.'.pdf';
			$this->pdf->ezStream(array('Content-Disposition'=>$file_name));
		}else{
			echo 'Error export';
			return false;
		}
	}
	function ex_supplier(){
		$this->db->select('id_supplier,nama_supplier,alamat,phone,date_add');
		$this->db->from('tb_supplier');
		$this->db->where('deleted',1);
		return $this->db->get();
	}
	function ex_customer(){
		$this->db->select('A.id_customer,A.nama_customer,A.email,B.phone,B.alamat,A.date_add');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_alamat as B','A.id_customer = B.id_customer','left');
		$this->db->where('A.deleted',1);
		$this->db->where('B.default',1);
		$this->db->order_by('A.nama_customer','left');
		return $this->db->get();
	}
	function ex_stock_bb(){
		$this->db->select('A.label_code_bb,B.nama_product,
						   C.nama_kategori,D.nama_supplier,
						   A.lebar_bb,A.tinggi_awal_bb,A.tinggi_terpakai_bb,A.tinggi_tersedia_bb,
						   A.luasan_awal_bb,A.luasan_terpakai_bb,A.luasan_tersedia_bb,E.nama_warehouse');						  
		$this->db->from('tb_stock_bb as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_kategori as C','B.id_kategori = C.id_kategori','left');
		$this->db->join('tb_supplier as D','B.id_supplier = D.id_supplier','left');
		$this->db->join('tb_warehouse as E','A.id_warehouse = E.id_warehouse','left');
		$this->db->where('A.deleted',1);		
		return $this->db->get();
	}
	function ex_stock_bj(){
		$this->db->select('A.label_code_bj,B.nama_kategori,D.nama_form,A.lebar_bj,
						   A.tinggi_bj,A.qty_pcs_awal,A.qty_pcs_terpakai,A.qty_pcs_tersedia,
						   C.nama_warehouse');
		$this->db->from('tb_stock_bj as A');
		$this->db->join('tb_kategori as B','A.id_kategori = B.id_kategori','left');
		$this->db->join('tb_warehouse as C','A.id_warehouse = C.id_warehouse','left');
		$this->db->join('tb_form_order as D','B.id_form_order = D.id_form_order','left');
		$this->db->where('A.deleted',1);
		return $this->db->get();
	}
}
