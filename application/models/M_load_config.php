<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class M_load_config extends CI_Model{
	public function __construct(){
		parent::__construct();		
		$_SESSION['date_now'] = date('Y-m-d H:i:s');
		$_SESSION['iso'] = 'IDR';
		$_SESSION['tax'] = 10;
		$_SESSION['iso_code'] = 'Rupiah';
	}
}
