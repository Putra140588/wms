<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpkaryawan extends CI_Controller{
	public function __construct(){
		parent::__construct();	
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'DKRY';
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	function index(){		
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/karyawan/v_index_karyawan' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Data Karyawan";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/karyawan/v_crud_karyawan';
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
				$data['page_title'] = "Detail Karyawan";
				$view = 'bo/karyawan/v_detail_karyawan';
			}
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Karyawan";			
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['class'] = $this->class;
		$data['jabatan'] = $this->m_master->get_table('tb_jabatan');
		$data['bagian'] = $this->m_master->get_table('tb_bagian');
		$data['group'] = $this->m_master->get_akses_all();
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function proses(){		
		$post = $this->input->post();
		$id= $post['id'];//for update 
		$nik = $post['nik'];
		$put['nik'] = $nik;
		$put['nama_depan'] = $post['namadepan'];
		$put['nama_belakang'] = $post['namabelakang'];
		$put['phone'] = $post['phone'];
		$put['id_jabatan'] = $post['jabatan'];
		$put['id_bagian'] = $post['bagian'];
		$put['email'] = $post['email'];		
		$put['date_update'] = $this->datenow;
		$put['add_update'] = $this->addby;
		$put['kd_group'] = $post['group'];
		$put['jenkel'] = $post['jenkel'];
		if ($id != ''){
			//edit proses
			if ($post['password'] != ''){
				$put['password'] = hash_password($post['password']);
			}
			$res = $this->m_master->updatedata('tb_karyawan',$put,array('id_karyawan'=>$id));			
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah data karyawan berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{
			//input proses		
			
			$cek = $this->m_master->get_karyawan(array('nik'=>$nik));
			if (count($cek) > 0){
				$this->session->set_flashdata('warning','NIK Sudah terdaftar, tidak dapat input data');
				redirect('bo/'.$this->class);
			}else{				
				$put['password'] = hash_password($post['password']);
				$put['add_by'] = $this->addby;
				$put['date_add'] = $this->datenow;
				$res = $this->m_master->insertdata('tb_karyawan',$put);					
				if ($res > 0){
					$this->session->set_flashdata('success','Tambah data karyawan berhasil.');
					redirect('bo/'.$this->class);
				}
			}
		}
	}
	function cek_nik(){
		$val = $this->input->post('value');
		$cek = $this->m_master->get_karyawan(array('nik'=>$val));
		if (count($cek) > 0){
			echo 'Nik Sudah terdaftar, masukan nik yang berbeda';
		}
		
	}
	function cek_email(){
		$val = $this->input->post('value');
		$cek = $this->m_master->get_karyawan(array('email'=>$val));
		if (count($cek) > 0){
			echo 'Email Sudah terdaftar, masukan email yang berbeda';
		}	
	}
	
	function lengt_pass(){
		$val = $this->input->post('value');
		if (strlen($val) < 8){
			echo 'Panjang password minimal harus 8 karakter';
		}
	}
	
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'nik',
				2 => 'nama_depan',
				3 => 'nama_belakang',
				4 => 'email',
				5 => 'nama_jabatan',
				6 => 'nama_bagian',		
				7 => 'nama_group',
				8 => 'A.date_add',
				
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
		$total = count($this->m_master->get_karyawan());
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
		$query = $this->m_master->get_karyawan('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
		
		
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		 yang mengandung keyword tertentu
		 */
		if($request['keyword'] !=""){	
			$this->m_master->search_like($request['keyword'],$this->column());				
			$total = count($this->m_master->get_karyawan());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_karyawan());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_karyawan());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}		
		
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$edit = ($row->kd_group == 'SA') ? '' : '<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_karyawan).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>'; 
			$delete = ($row->kd_group == 'SA') ? '' : '<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_karyawan.'\',\'tes\')"><i class="icon-trash"></i></button>';
			$output['data'][]=array($nomor_urut,
									$row->nik,
									$row->nama_depan,
									$row->nama_belakang,
									$row->email,
									$row->nama_jabatan,
									$row->nama_bagian,	
									$row->nama_group,
									$row->date_add,												
									$delete.' 
									'.$edit.'
									<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_karyawan.'/1').'" title="'.$this->config->config['detail'].'" class="btn btn-warning btn-circle"><i class="icon-search"></i></a>'
			);									
			$nomor_urut++;
		}		
		echo json_encode($output);
		
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->updatedata('tb_karyawan',array('deleted'=>0),array('id_karyawan'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus data NIK '.$val.' berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
		
	}
	function export($to){
		$sql = $this->m_master->ex_karyawan();
		$filename = 'data_karyawan-'.short_date($this->datenow);
		$title = 'Data Karyawan';
		$column_header = array(
						'no'=>'No',
						'nik'=>'NIK',
						'nama_depan'=>'Nama Depan',
						'nama_belakang'=>'Nama Belakang',
						'jenkel'=>'Jenkel',
						'phone'=>'Phone',
						'email'=>'Email',
						'nama_jabatan'=>'Jabatan',
						'nama_bagian'=>'Bagian',
						'nama_group'=>'Group',
						'date_add'=>'Date Add'
		);
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);	
	}
}
