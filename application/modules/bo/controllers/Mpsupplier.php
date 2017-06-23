<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpsupplier extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'SPLR';
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/supplier/v_index_supplier' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['page_title'] = "Supplier";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'date_add',//default order sort
				1 => 'id_supplier',
				2 => 'nama_supplier',
				3 => 'alamat',
				4 => 'phone',
				5 => 'date_add',
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
		$total = count($this->m_master->get_table_filter('tb_supplier',$where));
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
			$this->db->like('date_add',$request['date_from']);
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(date_add,"%Y-%m-%d") <=',$request['date_to']);
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_table_filter('tb_supplier',$where,$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		 yang mengandung keyword tertentu
		 */
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_table_filter('tb_supplier',$where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('date_add',$request['date_from']);
			$total = count($this->m_master->get_table_filter('tb_supplier',$where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_table_filter('tb_supplier',$where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
									$row->id_supplier,
									$row->nama_supplier,
									$row->alamat,
									$row->phone,		
									$row->date_add,															
									'<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_supplier.'\',\'tes\')"><i class="icon-trash"></i></button>
									 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_supplier).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>'
									
			);					
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/supplier/v_crud_supplier';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Supplier";
			$sql = $this->m_master->get_table_filter('tb_supplier',array('id_supplier'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
					$data[$key] = $val;
			}			
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Supplier";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['class'] = $this->class;		
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function proses(){
		$post = $this->input->post();
		$id= $post['id'];//for update
		$put['id_supplier'] = $post['id_supplier'];
		$put['nama_supplier'] = $post['namasupplier'];
		$put['alamat'] = $post['alamat'];
		$put['phone'] = $post['phone'];
		$put['add_update'] = $this->addby;
		$put['date_update'] = $this->datenow;
		if ($id != ''){
			//edit proses
			$res = $this->m_master->updatedata('tb_supplier',$put,array('id_supplier'=>$id));
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah data supplier berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{
			$put['add_by'] = $this->addby;
			$put['date_add'] = $this->datenow;
			$res = $this->m_master->insertdata('tb_supplier',$put);
			if ($res > 0){
				$this->session->set_flashdata('success','Tambah supplier berhasil.');
				redirect('bo/'.$this->class);
			}
		}
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->updatedata('tb_supplier',array('deleted'=>0),array('id_supplier'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus data supplier berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
	function export($to){
		$sql = $this->m_master->ex_supplier();
		$filename = 'data_supplier-'.short_date($this->datenow);
		$title = 'Data Supplier';
		$column_header = array(
				'no'=>'No',
				'id_supplier'=>'ID Supplier',
				'nama_supplier'=>'Nama Supplier',
				'alamat'=>'Alamat',
				'phone'=>'Phone',
				'date_add'=>'Date Add',
				
		);
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);
	}
}
