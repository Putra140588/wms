<?php if (!defined('BASEPATH')) exit('No direct access allowed');
class Bo extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->yearnow = date("Y");		
	}
	var $yearnow;
	function index(){
		$this->m_master->get_login();		
		$data['chart1'] = $this->chart1();
		$data['chart2'] = $this->chart2();
		$data['chart3'] = $this->chart3();
		$data['chart4'] = $this->chart4();
		$this->load->view('v_header');
		$this->load->view('dashboard/v_dashboard',$data);
		$this->load->view('v_footer');
	}
	/*
	 * menampilkan grafik produksi vs sliting berdasarkan tahun sekarang
	 * 
	 */
	function chart1(){
		$yearnow = $this->yearnow;		
		$m='';
		$slitting = '';
		$produksi='';
		$rata='';
		/*
		 * melakukan perulangan tahun dan bulan dalam 1 tahun
		 */
		for($i=1; $i <= 12; $i++){
			$yearmonth =  $yearnow.'-'.date("m",strtotime($yearnow."-".$i));		
			$yearmonth_[] = $yearmonth;
			//mendapatkan bulan text dengan index value adalah tahun dan bulan
			$month[$yearmonth] =  date("F Y",strtotime($yearnow."-".$i."-01"));
		}		
		
		$periode = 'ym';//tahun bulan
		//mendapatkan data multiple array perbandingan presentase produksi dan slitting
		$sum = $this->m_master->get_sum_prod_slit($yearmonth_,$periode);	
			
		foreach ($sum['slitting'] as $slit)
			$slitting .= $slit.',';	//menampilkan jumlah presentase slitting dan dibuat string
				
		foreach ($sum['produksi'] as $prod)
			$produksi .= $prod.','; //menampilkan jumlah presentase produksi dan dibuat string
		
		foreach ($sum['rata'] as $rat)
			$rata .= $rat.','; //menampilkan jumlah presentase rata2 dan dibuat string
		
		foreach ($sum['yearmonth'] as $y){
			$m .= "'".$month[$y]."',";  //menampilkan bulan yang sudah diindex dari database produksi dan dibuat string
		}
		
		$periode = 'y';//tahun
		$total = $this->m_master->get_sum_prod_slit($yearmonth_,$periode);
		$total_produksi = number_format($total['produksi'][0],2,'.',',');
		$total_slitting = number_format($total['slitting'][0],2,'.',',');
		
		$data['month'] = substr($m,0,-1);//menghilangkan karater paling akhir		
		$data['produksi'] = substr($produksi,0,-1);
		$data['slitting'] = substr($slitting,0,-1);
		$data['total_produksi'] = $total_produksi;
		$data['total_slitting'] = $total_slitting;
		$data['rata'] = substr($rata,0,-1);
		$data['title'] = 'Grafik Produksi Vs Slitting '.$yearnow;
		return $this->load->view('dashboard/v_chart1',$data,true);		
	}
	function chart2(){
		$yearnow = $this->yearnow;
		/*
		 * melakukan perulangan tahun dan bulan dalam 1 tahun
		*/
		$datax='';
		for($i=1; $i <= 12; $i++){
			$yearmonth[] =  $yearnow.'-'.date("m",strtotime($yearnow."-".$i));		
			
		}
		$so = $this->m_master->get_presentase_so($yearmonth);
		foreach ($so['datax'] as $row)
			$datax.=$row;		
		
		$data['data'] = substr($datax, 0,-1);
		$data['title'] = 'Grafik Sales Order '.$yearnow;
		return $this->load->view('dashboard/v_chart2',$data,true);
	}
	function chart3(){
		$sql = $this->m_master->get_grafik_bb();
		$awal='';
		$terpakai='';
		$tersedia = '';
		$kategori='';
		foreach ($sql as $val){
			$awal .= $val->awal.',';
			$terpakai .= $val->terpakai.',';
			$tersedia .= $val->tersedia.',';
			$kategori .= "'".$val->nama_kategori."',";
		}
		$data['title'] = 'Grafik Stok Bahan Baku ';
		$data['kategori'] = substr($kategori, 0,-1);
		$data['awal'] = substr($awal, 0,-1);
		$data['terpakai'] = substr($terpakai, 0,-1);
		$data['tersedia'] = substr($tersedia, 0,-1);
		return $this->load->view('dashboard/v_chart3',$data,true);
	}
	function chart4(){
		$sql = $this->m_master->get_grafik_bj();
		$awal='';
		$terpakai='';
		$tersedia = '';
		$kategori='';
		foreach ($sql as $val){
			$kategori .= "'".$val->nama_kategori."',";
			if ($val->id_form_order == 'HDW'){				
				//MENKALIKAN DENGAN NILAI 100 UNTUK MENGIMBANGI GRAFIK QTY PCS LABEL
				$awal .= bcmul($val->awal,100).',';
				$terpakai .= bcmul($val->terpakai,100).',';
				$tersedia .= bcmul($val->tersedia,100).',';				
			}else{
				$awal .= $val->awal.',';
				$terpakai .= $val->terpakai.',';
				$tersedia .= $val->tersedia.',';				
			}
			
		}
		$data['title'] = 'Grafik Stok Bahan Jadi ';
		$data['kategori'] = substr($kategori, 0,-1);
		$data['awal'] = substr($awal, 0,-1);
		$data['terpakai'] = substr($terpakai, 0,-1);
		$data['tersedia'] = substr($tersedia, 0,-1);
		return $this->load->view('dashboard/v_chart4',$data,true);
	}
}
