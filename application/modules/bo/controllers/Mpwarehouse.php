<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpwarehouse extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'WRHS';
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/warehouse/v_index_warehouse' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['page_title'] = "Warehouse";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'id_warehouse',//default order sort
				1 => 'nama_warehouse',
				2 => 'phone',
				3 => 'alamat',				
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
		$total = count($this->m_master->get_table_filter('tb_warehouse',$where));
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
		$query = $this->m_master->get_table_filter('tb_warehouse',$where,$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		 yang mengandung keyword tertentu
		 */
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_table_filter('tb_warehouse',$where));
			//echo '<pre>';print_r($total);die;
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
									$row->nama_warehouse,
									$row->phone,
									$row->alamat,																										
									'<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_warehouse.'\',\'tes\')"><i class="icon-trash"></i></button>
									 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_warehouse).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>'
									
			);					
			$nomor_urut++;
		}
		
		echo json_encode($output);
	
	}
	function form($id=''){
		$this->m_master->get_login();
		$view = 'bo/warehouse/v_crud_warehouse';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Warehouse";
			$sql = $this->m_master->get_table_filter('tb_warehouse',array('id_warehouse'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
					$data[$key] = $val;
			}			
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Warehouse";
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
		$put['nama_warehouse'] = $post['nama'];		
		$put['alamat'] = $post['alamat'];
		$put['phone'] = $post['phone'];
		$put['add_update'] = $this->addby;
		$put['date_update'] = $this->datenow;
		if ($id != ''){
			//edit proses
			$res = $this->m_master->updatedata('tb_warehouse',$put,array('id_warehouse'=>$id));
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah data warehouse berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{
			$put['add_by'] = $this->addby;
			$put['date_add'] = $this->datenow;
			$res = $this->m_master->insertdata('tb_warehouse',$put);
			if ($res > 0){
				$this->session->set_flashdata('success','Tambah warehouse berhasil.');
				redirect('bo/'.$this->class);
			}
		}
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->updatedata('tb_warehouse',array('deleted'=>0),array('id_warehouse'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus data warehouse berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
}
