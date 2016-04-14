<?php
	require_once '../tcpdf/tcpdf.php';
	
	class MY_TCPDF extends TCPDF
	{
		//Page header
		public function Header() {
			// Logo
			$image_file = K_PATH_IMAGES.PDF_HEADER_LOGO;
			$this->Image($image_file, 'C', 6, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_LOGO_WIDTH, 'PNG', false, 'N', false, 300, 'C', false, false, 0, false, false, false);
			//$this->Image($image_file, '', '', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_LOGO_WIDTH, 'PNG', false, 'C');
			
			// Set font
			//$this->SetFont('helvetica', 'B', 13);
			// Title
			//$this->Cell(0, 15, PDF_HEADER_TITLE, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		}
	}
?>