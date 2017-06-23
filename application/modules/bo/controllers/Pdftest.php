<?php if (!defined('BASEPATH')) exit ('No dorect script access allowed');
class Pdftest extends CI_Controller{
	public function __construct(){
		parent::__construct();
		//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library
	}
	function index(){
		$data['title'] = 'Kode bahan baku';
		$html = $this->load->view('barcode/v_barcode',$data,true);		
		//actually, you can pass mPDF parameter on this load() function
		$mpdf = $this->m_pdf->load();		
		
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->list_indent_first_level = 0;
		$mpdf->SetJS('script>alert("tes")</script>');  //JS code with <script></script> tags.
		//generate the PDF!
		$mpdf->WriteHTML($html);
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		$mpdf->Output();
	}
}