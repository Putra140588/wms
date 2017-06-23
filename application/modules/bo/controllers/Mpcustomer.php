<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpcustomer extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'CSTM';
		$this->table = 'tb_customer';
		$id_customer = $this->uri->segment(4);
				
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
	
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/customer/v_index_customer' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['page_title'] = "Customer";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.id_customer',
				2 => 'A.nama_customer',
				3 => 'A.email',
				4 => 'B.phone',
				5 => 'B.alamat',
				6 => 'A.date_add',
				7 => 'A.add_by'
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
			$this->m_master->search_like($request['keyword'],$this->column());
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_customer('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		 yang mengandung keyword tertentu
		 */
		if($request['keyword'] != ""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_customer());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_customer());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_customer());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
									$row->id_customer,
									$row->nama_customer,
									$row->email,
									$row->phone,
									$row->alamat,
									$row->date_add,	
									$row->add_by,
									'<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_customer.'\',\'tes\')"><i class="icon-trash"></i></button>
									 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_customer).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>'
									
			);					
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/customer/v_crud_customer';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Customer";
			$sql = $this->m_master->get_table_filter('tb_customer',array('id_customer'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
					$data[$key] = $val;
			}			
			
			$data['alamat'] = $this->m_master->get_alamat(array('id_customer'=>$id));
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Customer";
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
		$id_customer = $this->m_master->random_ref('CS');
		$put['nama_customer'] = $post['nama'];
		$put['email'] = $post['email'];		
		$put['add_update'] = $this->addby;
		$put['date_update'] = $this->datenow;
		if ($id != ''){
			//edit proses
			$res = $this->m_master->updatedata('tb_customer',$put,array('id_customer'=>$id));
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah data customer berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{
			$put['id_customer'] = $id_customer;
			$put['add_by'] = $this->addby;
			$put['date_add'] = $this->datenow;
			$res = $this->m_master->insertdata('tb_customer',$put);
			
			$in['id_customer'] = $id_customer;	
			$in['nama_customer'] = $post['nama'];
			$in['alamat'] = $post['alamat'];
			$in['phone'] = $post['phone'];
			$in['add_update'] = $this->addby;
			$in['date_update'] = $this->datenow;
			$in['add_by'] = $this->addby;
			$in['date_add'] = $this->datenow;
			$in['default'] = 1;
			$res = $this->m_master->insertdata('tb_alamat',$in);
			
			if ($res > 0){
				$this->session->set_flashdata('success','Tambah customer berhasil.');
				redirect('bo/'.$this->class);
			}
		}
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			if ($this->input->post('div') == 'alamat'){
				$res = $this->m_master->updatedata('tb_alamat',array('deleted'=>0),array('id_alamat'=>$val));
				if ($res){
					echo json_encode(array('error'=>0,'msg'=>'Hapus alamat berhasil'));
				}				
			}else{
				$res = $this->m_master->updatedata('tb_customer',array('deleted'=>0),array('id_customer'=>$val));
				if ($res){
					echo json_encode(array('error'=>0,'msg'=>'Hapus data customer berhasil'));
				}
			}	
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}	
	}
	function alamat($id_customer){		
		$priv = $this->m_master->get_priv($this->acces_code,'add');
		if (empty($priv)){
			$val = $this->input->post('val');
			$data['class'] = $this->class;
			if ($val != ''){
				$data['id_alamat'] = $val;
				$data['title'] = 'Ubah Alamat';
				$sql = $this->m_master->get_alamat(array('id_alamat'=>$val));
				foreach ($sql->result() as $row)
					foreach ($row as $key=>$val){
					$data[$key] = $val;
				}
			}else{
				$data['title'] = 'Tambah Alamat Baru';
			}
			$data['id_customer'] = $id_customer;
			$this->load->view('bo/customer/v_modal_alamat',$data);
		}else{
			$data['title'] = 'Access Denied';
			$data['notif'] = '<div class="alert alert-danger">Anda tidak punya hak untuk menambah alamat</div>';
			$this->load->view('bo/v_modal_notif',$data);
		}
	}
	function proses_alamat(){
		$id_alamat = $this->input->post('id',true);
		$id_customer = $this->input->post('idcust',true);
		$post['nama_customer'] = $this->input->post('nama',true);
		$post['alamat'] = $this->input->post('alamat',true);
		$post['phone'] = $this->input->post('phone',true);
		$post['date_update'] = $this->datenow;
		$post['add_update'] = $this->addby;
		if ($id_alamat != ''){
			//edit proses
			$res = $this->m_master->updatedata('tb_alamat',$post,array('id_alamat'=>$id_alamat));
			$msg = 'Ubah alamat berhasil';
		}else{
			//add new proses
			$post['id_customer'] = $id_customer;
			$post['date_add'] = $this->datenow;
			$post['add_by'] = $this->addby;
			$res = $this->m_master->insertdata('tb_alamat',$post);
			$msg = 'Tambah alamat baru berhasil';
			
		}
		if ($res){
			$this->session->set_flashdata('success',$msg);
			redirect('bo/'.$this->class.'/form/'.$id_customer);
		}
	}
	function export($to){
		$sql = $this->m_master->ex_customer();
		$filename = 'data_customer-'.short_date($this->datenow);
		$title = 'Data Customer';
		$column_header = array(
				'no'=>'No',
				'id_customer'=>'ID Customer',
				'nama_customer'=>'Nama Supplier',
				'email'=>'Email',
				'phone'=>'Phone',
				'alamat'=>'Alamat',				
				'date_add'=>'Date Add',
	
		);
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);
	}
}
