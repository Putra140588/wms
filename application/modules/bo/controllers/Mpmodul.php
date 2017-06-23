<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpmodul extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'MODL';
		$this->table = 'tb_modul';
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/modul/v_index_modul' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Moduls";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'nama_modul',//default order sort
				1 => 'akses_code',
				2 => 'nama_modul',				
				3 => 'level',
				4 => 'link',
				5 => 'icon'			
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
		$total = count($this->m_master->get_table_filter($this->table));
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
		$query = $this->m_master->get_table_filter($this->table,'',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		 yang mengandung keyword tertentu
		 */
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_table_filter($this->table));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
									$row->akses_code,
									$row->nama_modul,							
									$row->level,
									$row->link,
									$row->icon		
									
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id=''){
		$this->m_master->get_login();
		$view = 'bo/modul/v_crud_modul';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah modul";
			$sql = $this->m_master->get_table_filter($this->table,array('id_modul'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
					$data[$key] = $val;
			}			
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah modul";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['modul'] = $this->m_master->get_table('tb_modul');
		$data['class'] = $this->class;		
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function proses(){
		$post = $this->input->post();
		$id= $post['id'];//for update		
		$put['nama_modul'] = $post['namamodul'];	
		$put['akses_code'] = $post['aksescode'];
		$put['id_modul_parent'] = $post['parent'];
		$put['level'] = $post['level'];
		$put['link'] = $post['link'];
		$put['icon'] = $post['icon'];	
		$put['sort'] = $post['sort'];
		if ($id != ''){
			//edit proses			
			$res = $this->m_master->updatedata($this->table,$put,array('id_modul'=>$id));
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah data modul berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{
			//input proses								
			$res = $this->m_master->insertdata($this->table,$put);
			$id_modul = $this->m_master->get_id_max('id_modul',$this->table);
			//input akses
			$sql = $this->m_master->get_table_column(array('kd_group'),'tb_group');
			foreach ($sql as $row){
				$in = array('kd_group'=>$row->kd_group,
						'id_modul'=>$id_modul,
						'id_modul_parent'=>$post['parent'],
						'add_by'=>$this->addby,
						'add_update'=>$this->addby,
						'date_add'=>$this->datenow,
						'date_update'=>$this->datenow,
				);
				$merge = array_merge($in);
				$res = $this->m_master->insertdata('tb_akses',$merge);
			}
			if ($res > 0){
				$this->session->set_flashdata('success','Tambah modul berhasil.');
				redirect('bo/'.$this->class);
			}
		}
	}
	
}
