<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Mpsliting extends CI_Controller{
	/*
	 * bahan yang telah disliting akan menambah stock bahan jadi sesuai kategori,ukuran
	 */
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'SLTN';
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
		$main_page = (empty($priv)) ? 'bo/sliting/v_index_sliting' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['page_title'] = "Sliting";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.ws_number',
				2 => 'A.label_code_bb',
				3 => 'C.nama_product',
				4 => 'D.nama_kategori',
				5 => 'A.lebar_sliting',
				6 => 'A.tinggi_sliting',
				7 => 'A.qty_roll_sliting',
				8 => 'A.qty_pcs_sliting',
				9 => 'E.nama_depan',
				10 => 'A.date_sliting',
				11 => 'F.nama_warehouse',
				12 => 'A.add_by',
				13 => 'A.date_add',
	
		);
		return $column_array;
	}
	function column_produksi(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.ws_number',
				2 => 'A.label_code_bb',
				3 => 'C.nama_product',
				4 => 'D.nama_kategori',
				5 => 'A.qty_produksi',						
	
		);
		return $column_array;
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/sliting/v_crud_sliting';
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
				$data['page_title'] = "Production Worksheet Details";
				$view = 'bo/produksi/v_detail_worksheet';
				$sql = $this->m_master->get_group_produksi(array('id_order_detail'=>$id));
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
				$data['pcsroll'] = $pcsroll;
				$data['karyawan'] = $this->m_master->get_karyawan();//only show operator
				$data['status'] = $this->m_master->get_table('tb_status_produksi');
				$data['status_work'] = $this->m_master->get_table('tb_status_work');
				$data['produksi'] = $this->m_content->table_produksi($row->ws_number);			
			}
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Sliting";				
		}	
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		$data['class'] = $this->class;		
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function get_records_sliting(){
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
		$total = count($this->m_master->get_group_produksi());
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
			$this->m_master->search_like($request['keyword'],$this->column_produksi());
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_group_produksi('',$this->column_produksi()[$request['column']],$request['sorting'],$request['length'],$request['start']);
		
		
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column_produksi());
			$total = count($this->m_master->get_group_produksi());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {			
			$output['data'][]=array($nomor_urut,				
					$row->ws_number,
					$row->label_code_bb,
					$row->nama_product,
					$row->nama_kategori,
					qty_format($row->qty_produksi),								
					'<button type="button" class="btn btn-success btn-circle" title="Pilih Bahan" onclick="ajaxcall(\''.base_url('bo/'.$this->class.'/pilih_bahan').'\',\''.$row->ws_number.'#'.$row->label_code_bb.'\',\'frmsliter\')"><i class="icon-download-alt"></i></button>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	}
	function pilih_bahan(){
		$val = explode('#', $this->input->post('value'));
		$ws_number = $val[0];
		$label_code_bb = $val[1];		
		$post['ws_number'] = $ws_number;
		$post['label_code_bb'] = $label_code_bb;		
		$res = $this->m_master->insertdata('tb_sliting',$post);
		echo $this->m_content->load_form_splitter();
	
	}
	function delete_form(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->deletedata('tb_sliting',array('id_sliting'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus Form sliter berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	
	}
	function proses(){
		$this->db->trans_start();
		$id_sliting = $this->input->post('id');		
		$data = $this->input->post('data');		
		if (empty($data)){
			echo json_encode(array('error'=>1,'msg'=>'Bahan belum dipilih'));
		}else{
			//update 
			foreach ($id_sliting as $id){
				$datax = $data[$id];		
				$ws_number = $datax['ws_number'];
				$label_code_bb = $datax['label_code_bb'];
				$lebar = $datax['lebar_sliting'];
				$tinggi = $datax['tinggi_sliting'];
				$qty_pcs = $datax['qty_pcs_sliting'];
				$qty_roll = $datax['qty_roll_sliting'];
				$id_warehouse = $datax['id_warehouse'];
				$update['date_add'] = $this->datenow;
				$update['add_by'] = $this->addby;
				$update['saving'] = 1;//menandakan data sliting sudah disimpan
				$merge_update = array_merge($update,$datax);
				$res = $this->m_master->updatedata('tb_sliting',$merge_update,array('id_sliting'=>$id));
				
				/*
				 * melakukan update tb_produksi bahwa bahan sudah dilakukan sliting dan tidak dapat tampil kembali
				 */
				$this->db->set('status_sliting',1);
				$this->db->where('ws_number',$ws_number);
				$this->db->where('label_code_bb',$label_code_bb);
				$res = $this->db->update('tb_produksi');
				
				/*
				 * penambahan stock bahan jadi, bahan yang telah disliting setelah produksi
				* stock terdiri dari ukuran yang sama, jenis/kategori bahan yang sama, supplier yang berbeda maupun supplier yang sama
				*/
				$id_kategori = $this->m_master->get_id_kategori($ws_number);			
				$id_status = 2;//status default hasil sliting
				$res = $this->m_master->generate_stock_bj($id_kategori,$lebar,$tinggi,$qty_pcs,$id_warehouse,$id_status);
			}		
			
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				if ($res){
					//show modal summary
					$this->session->set_flashdata('success','Simpan data sliting berhasil');
					echo json_encode(array('error'=>0,'type'=>'save','redirect'=>base_url('bo/mpsliting')));
				}
			}
			
		}
	
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
		$total = count($this->m_master->get_sliting());
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
		$query = $this->m_master->get_sliting('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_sliting());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_sliting());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_sliting());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {			
			$output['data'][]=array($nomor_urut,
					$row->ws_number,
					$row->label_code_bb,
					$row->nama_product,
					$row->nama_kategori,
					$row->lebar_sliting,
					$row->tinggi_sliting,
					qty_format($row->qty_roll_sliting),
					qty_format($row->qty_pcs_sliting),
					$row->nama_depan.' '.$row->nama_belakang,
					$row->date_sliting,
					$row->nama_warehouse,
					$row->add_by,
					$row->date_add,
					
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	
}
