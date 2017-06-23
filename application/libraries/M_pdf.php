<?php if (!defined('BASEPATH')) exit ('No dorect script access allowed');
class m_pdf{
	function m_pdf()
	{
		$CI = & get_instance();
		log_message('Debug', 'mPDF class is loaded.');
	}
	
	function load($param=NULL)
	{
		include_once APPPATH.'/third_party/mpdf/mpdf.php';
		/* 
		if ($params == NULL)
		{
			$param = '"en-GB-x","A4","","",10,10,10,10,6,3';
		}
		*/
		$mode='utf-8';
		$format='A4';//paper format
		$default_font_size=0;
		$default_font='';
		$mgl=5;//margin left
		$mgr=5;//margin right
		$mgt=3;//margin top
		$mgb=10;
		$mgh=6;
		$mgf=3;
		$orientation='P';//portrait & Landscape
		//load function mPdf in librari M_pdf.php
		return new mPDF($mode,$format,$default_font_size,$default_font,$mgl,$mgr,$mgt,$mg,$mgh,$mgf,$orientation);
	}
}
