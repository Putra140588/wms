<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Barcodetest extends CI_Controller{	
	public function __construct(){
		parent::__construct();
		$this->qtyprint = 1;//jumlah setiap barcode yang sama diprint sebanyak 4 kali		
	}
	var $qtyprint;
	function index(){
		echo 'Page not found';
	}
	/*
	 * menjalankan fungsi cetak barcode singgle bahan baku dan bahan jadi
	 */
	function cetak_single($id,$kat){
		$this->m_master->get_login();
		$data['label_code'] = $id;
		$data['page'] = 'single';
		/*
		 * jika print bahan baku maka generate 4 barcode, jika bahan jadi maka generate 1 barcode
		 */
		$data['qtyprint'] = ($kat == 'bb') ? $this->qtyprint : 1;
		$this->load->view('barcode/v_barcode',$data);
	}
	/*
	 * menjalankan fungsi cetak barcode multiple base on database
	 */
	function cetak_multiple($id,$form){
		$this->m_master->get_login();
		if ($form == 'LBL'){
			//jika label maka mengambil data pada stock bahan baku
			$sql = $this->m_master->get_table_column(array('label_code_bb'),'tb_stock_bb',array('id_receive_items_detail'=>$id));
		}else{
			/*
			 * mengambil ukuran dan kategori pada barang yang diterima untuk melakukan cek stock bahan jadi
			 */
			$query = $this->m_master->get_receive_detail(array('id_receive_items_detail'=>$id));
			if (count($query) > 0){
				foreach ($query as $row){
					$where = array('id_kategori'=>$row->id_kategori,'lebar_bj'=>$row->lebar,'tinggi_bj'=>$row->tinggi_m);
					//jika ribbon dan hardware maka mengambil data stock bahan jadi
					$sql = $this->m_master->get_table_column(array('label_code_bj'),'tb_stock_bj',$where);
				}				
			}else{
				echo 'Produk tidak ditemukan!';die;
			}		
		}		
		$data['sql'] = $sql;
		$data['page'] = 'multiple';
		$data['qtyprint'] = ($form == 'LBL') ? $this->qtyprint : 1;
		$data['count'] = count($sql);
		$this->load->view('barcode/v_barcode',$data);
	}
}
