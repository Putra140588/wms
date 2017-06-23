<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('replace_p')){
	function replace_p($data){
		$find = array('<p>','</p>');
		return str_replace($find,'',$data);
	}
}
function replace_freetext($text)
{
	$replace_address = str_ireplace(array("\r","\n",'\r','\n','\\',"<p>","</p>"),'', $text);
	return $replace_address;
}
function replace_desc($text)
{
	$replace_desc = str_ireplace(array("\r","\n",'\r','\n','\\'),'', $text);
	return $replace_desc;
}
function site_title()
{
	$find = array('<p>','</p>');
	return str_replace($find,'',$_SESSION['site_title']);
}
if (!function_exists('format_email')){
	function format_email($subject){
		if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $subject)){
			return false;
		}else{return true;}
	}
}
function hash_password($password)
{
	//buat password_hash
	$options = ['cost' => 10,
				'salt' => mcrypt_create_iv(33, MCRYPT_DEV_URANDOM),];
	$hash = password_hash($password, PASSWORD_BCRYPT, $options);
	return $hash;
}
function email_send($emailparam)
{	
	$ci =& get_instance();
	$ci->load->library('email');
	$subjek      =  $emailparam['subjek'];
	$email_from	 =  $emailparam['email_from'];
	$name_from	 =  $emailparam['name_from'];
	$email_to	 =  $emailparam['email_to'];	
	$email_bcc   =  $emailparam['email_bcc'];
	$content      = $emailparam['content'];
	//konfigurasi pengiriman
	$ci->email->from($email_from,$name_from);
	$ci->email->to($email_to);
	$ci->email->bcc($email_bcc);	
	$ci->email->subject($subjek);		
	$ci->email->message($content);	
	if ($ci->email->send()){		
		return true;
	}else{	
	show_error($ci->email->print_debugger());die;
	return false;}
		
}
function error_page()
{	
	$ci =& get_instance();
	$name = '404 Page not found';
	$data['title']		 = replace_p($name.$_SESSION['site_title']);
	$data['description'] = $name;
	$data['keywords']    = $name;
	$ci->load->view('bm/v_top_panel',$data);		
	$ci->load->view('bm/error/v_404');		
	$ci->load->view('bm/v_footer');
}

function request_server($data,$request)
{
	
	$ci =& get_instance();
	$server_url = $data['request_http'];
	$ci->xmlrpc->server($server_url, 80);
	$ci->xmlrpc->method($data['method']);	
	$ci->xmlrpc->request($request);
	if ( ! $ci->xmlrpc->send_request())
	{
		return $ci->xmlrpc->display_error();
	}
	else
	{			
		$response = $ci->xmlrpc->display_response();
		return $response;
			
	}
}
function xml_generate($data,$sql){
	$xml = new SimpleXMLElement($data['parent']);
	$no=0;
	foreach($sql as $item) {
		$sale = $xml->addChild($data['child'].$no++);
		foreach ($item as $key=>$val)
			$sale->addChild($key,htmlspecialchars($val));
	}
	$xml->asXML($data['path']);
	
}
function short_date($date){
	return date_format(date_create($date),'d/m/Y');
}
function short_date_time($date){
	return date_format(date_create($date),'d/m/Y H:i:s');
}
function long_date($date){
	
	return date_format(date_create($date), 'd M Y');
}
function long_date_time($date){

	return date_format(date_create($date), 'd M Y - H:i:s');
}
function put_short_date($date){
	return date_format(date_create($date), 'Y-m-d');
}
function terbilang($x){
	$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($x < 12)
	return " " . $abil[$x];
	elseif ($x < 20)
	return terbilang($x - 10) . "belas";
	elseif ($x < 100)
	return terbilang($x / 10) . " puluh" . terbilang($x % 10);
	elseif ($x < 200)
	return " seratus" . terbilang($x - 100);
	elseif ($x < 1000)
	return terbilang($x / 100) . " ratus" . terbilang($x % 100);
	elseif ($x < 2000)
	return " seribu" . terbilang($x - 1000);
	elseif ($x < 1000000)
	return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
	elseif ($x < 1000000000)
	return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
}
function meter_to_mm($meter){
	$mm = 1000;
	$hitung = $meter * $mm;
	return $hitung;
}
function qty_format($val){
	return number_format($val,0,',','.');
}
function last_time($last_date)
{
	//60 = 1 menit
	//60 * 60 = 3600 (1 jam)
	$chunks = array(
			array(60 * 60 * 24 * 365, 'tahun'),
			array(60 * 60 * 24 * 30, 'bulan'),
			array(60 * 60 * 24 * 7, 'minggu'),//604800 = 1 minggu
			array(60 * 60 * 24, 'hari'),//86400 = 1hari
			array(60 * 60, 'jam'),//3600 = 1jam
			array(60, 'menit'),//60
			array(1, 'detik'),
	);
	//echo '<pre>';print_r($chunks);die;
	$today = time();

	//menambahkan 1 hari pada tanggal yang diinput di database

	$original = strtotime($last_date);
	//$original = strtotime("+1 day",$originalconvert);

	//waktu sekarang - waktu database
	$since = $today - $original;

	//jika since lebih dari 1 minggu
	//****tidak digunakan***
	if ($since > 604800)
	{
		$print = date("M jS", $original);

		//jika since lebih dari 1 tahun
		if ($since > 31536000)
		{
			$print .= ", " . date("Y", $original);
		}
		//return $print;
	}

	for ($i = 0, $j = count($chunks); $i < $j; $i++)
	{
		//jumlah waktu
		$seconds = $chunks[$i][0];
			
		//nama waktu (menit,jam,hari,dll..)
		$name = $chunks[$i][1];
			
		//floor pembulatan kebawah
		if (($count = floor($since / $seconds)) != 0)
		break;
		//echo $seconds;die;
	}
	
	//jika count == 1 maka dihitung 1 detik yang lalu
	$print = ($count == 1) ? '1 ' . $name : "$count {$name}";
	return $print.' yang lalu';
}