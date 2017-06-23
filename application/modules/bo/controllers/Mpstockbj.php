<?php if (!defined('BASEPATH')) exit("No direct script access allowed");
class Mpstockbj extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'STBJ';			
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/stock/v_index_stock_bj' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['page_title'] = "Stock Bahan Jadi";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.id_stock_bj',//default order sort
				1 => 'A.label_code_bj',
				2 => 'B.nama_kategori',
				3 => 'D.nama_form',
				4 => 'A.lebar_bj',
				5 => 'A.tinggi_bj',				
				6 => 'A.qty_pcs_awal',
				7 => 'A.qty_pcs_terpakai',
				8 => 'A.qty_pcs_tersedia',
				9 => 'C.nama_warehouse',				
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
		$total = count($this->m_master->get_stock_bahan_jadi());
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
		$query = $this->m_master->get_stock_bahan_jadi('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_stock_bahan_jadi());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//jis barang adalah hardware maka tidak menampilkan lebar dan tinggi bahan jadi
			$lebar = ($row->id_form_order != 'HDW' ) ? $row->lebar_bj : 'N/A';
			$tinggi = ($row->id_form_order != 'HDW' ) ? $row->tinggi_bj : 'N/A';
			$qty_tersedia = ($row->qty_pcs_tersedia > 0) ? '<span class="badge badge-success">'.qty_format($row->qty_pcs_tersedia).'</span>' : '<span class="badge badge-important">'.qty_format($row->qty_pcs_tersedia).'</span>';
			$output['data'][]=array($nomor_urut,
					$row->label_code_bj,
					$row->nama_kategori,
					$row->nama_form,
					$lebar,
					$tinggi,
					qty_format($row->qty_pcs_awal),
					qty_format($row->qty_pcs_terpakai),
					$qty_tersedia,
					$row->nama_warehouse,
					'<a href="'.base_url('bo/'.$this->class.'/form/'.$row->id_stock_bj).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>
					 <button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->label_code_bj.'\',\'tes\')"><i class="icon-trash"></i></button>
					 <button onclick="openWin(\''.$row->label_code_bj.'\',\'bj\')" title="Cetak Barcode" class="btn btn-success btn-circle"><i class="icon-qrcode"></i></button>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/stock/v_crud_bj';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Stock Bahan Jadi";
			$sql = $this->m_master->get_stock_bahan_jadi(array('id_stock_bj'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}			
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Stock Bahan Jadi";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
	
		$data['class'] = $this->class;
		$this->db->order_by('nama_kategori','ASC');
		$data['kategori'] = $this->m_master->get_table('tb_kategori');		
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
	/*
	 * stock bahan jadi bertambah jika kategori, lebar dan tinggi yg sama
	 * jika ubah stock bahan jadi tidak dapat merubah kategori, lebar dan tinggi, hanya qty pcs yg dapat dirubah, 
	 * karena kategori,lebar,tinggi digunakan untuk kode unik melakukan verivikasi data
	 */
	function proses(){
		$this->db->trans_start();
		$id = $this->input->post('id');
		$expl = explode("#", $this->input->post('kategori'));
		$id_kategori = $expl[0];
		$lebar = $this->input->post('lebar');
		$tinggi = $this->input->post('tinggi');
		$qty_pcs = $this->input->post('qty');
		$id_warehouse = $this->input->post('warehouse');
		$where = array('id_kategori'=>$id_kategori,'lebar_bj'=>$lebar,'tinggi_bj'=>$tinggi);
		$label_code_bj = $this->m_master->cek_label_code_bj($where);			
		$move = $this->input->post('movement');
		
		//jika id kosong maka ke halaman addnew
		if ($id == ""){
			//jika tambah
				if ($move == 1){
					//jika label code bj sudah ada maka akan nambah qty stock bahan jadi
					if ($label_code_bj != false){
						//update qty
						$this->db->set('qty_pcs_awal','qty_pcs_awal+'.$qty_pcs,false);
						$this->db->set('qty_pcs_tersedia','qty_pcs_tersedia+'.$qty_pcs,false);
						$this->db->set('update_by',$this->addby);
						$this->db->where($where);
						$res = $this->db->update('tb_stock_bj');
						$msg = 'Tambah qty bahan jadi berhasil';
					}else{
						//new label code bj
						$label_code_bj = $id_kategori.'/'.$lebar.'/'.$tinggi.'/'.date('is');
						$new['label_code_bj'] = $label_code_bj;
						$new['id_kategori'] = $id_kategori;
						$new['id_warehouse'] = $id_warehouse;
						$new['lebar_bj'] = $lebar;
						$new['tinggi_bj'] = $tinggi;
						$new['qty_pcs_awal'] = $qty_pcs;
						$new['qty_pcs_tersedia'] = $qty_pcs;
						$new['add_by'] = $this->addby;
						$new['update_by'] = $this->addby;
						$res = $this->m_master->insertdata('tb_stock_bj',$new);
						$msg = 'Tambah stock bahan jadi baru berhasil';
					}
				}//jika kurang
				else if ($move == 2){
					//jika label code bj ada maka mengurangi qty stock bahan jadi
					if ($label_code_bj != false){						
						//update qty
						$this->db->set('qty_pcs_awal','qty_pcs_awal-'.$qty_pcs,false);
						$this->db->set('qty_pcs_tersedia','qty_pcs_tersedia-'.$qty_pcs,false);
						$this->db->set('update_by',$this->addby);
						$this->db->where($where);
						$res = $this->db->update('tb_stock_bj');
						$msg = 'Kurangi qty bahan jadi berhasil';
					}else{						
						$msg = 'Stock bahan jadi tidak dapat dikurangkan karena, data stock tidak tersedia';
						$this->session->set_flashdata('danger',$msg);
						redirect('bo/'.$this->class);
						return false;
					}
				}
				/*
				 * insert stock_movement_bj
				*/				
				$id_status =  $this->input->post('movedetail');
				$res = $this->m_master->insert_stock_move_bj($label_code_bj,$qty_pcs,$id_status);
			
			//ke halaman ubah data
			}else{
				//ubah data stock bahan jadi
				$labelcodebj = $this->input->post('label_code_bj');
				$new['id_warehouse'] = $id_warehouse;				
				$new['qty_pcs_awal'] = $qty_pcs;
				$new['qty_pcs_terpakai'] = $this->input->post('qtyterpakai');
				$new['qty_pcs_tersedia'] = $this->input->post('qtytersedia');
				$new['update_by'] = $this->addby;
				$res = $this->m_master->updatedata('tb_stock_bj',$new,array('id_stock_bj'=>$id));
				$msg = 'Ubah data stock bahan jadi berhasil';
				
				/*
				 * insert stock_movement_bj
				*/
				$id_status =  12;//ubah data
				$res = $this->m_master->insert_stock_move_bj($labelcodebj,0,$id_status);
			}		
			
			if ($this->db->trans_status() === false){
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
			$res = $this->m_master->updatedata('tb_stock_bj',array('deleted'=>0,'update_by'=>$this->addby),array('label_code_bj'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus stock bahan jadi berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
	function export($to){
		$sql = $this->m_master->ex_stock_bj();
		$filename = 'stok_bahanjadi-'.short_date($this->datenow);
		$title = 'Stok Bahan Jadi';
		$column_header = array(
				'no'=>'No',
				'label_code_bj'=>'Kode Bahan Jadi',				
				'nama_kategori'=>'Kategori',
				'nama_form'=>'Jenis',
				'lebar_bj'=>'Lebar (mm)',
				'tinggi_bj'=>'Tinggi (mm)',
				'qty_pcs_awal'=>'Qty Pcs Awal',
				'qty_pcs_terpakai'=>'Qty Pcs Terpakai',
				'qty_pcs_tersedia'=>'Qty Pcs Tersedia',				
				'nama_warehouse'=>'Warehouse',
	
		);
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);
	}
}