<?php
	ob_start();
	require_once '../tcpdf/config/tcpdf_config.php';
	//require_once '../tcpdf/examples/config/tcpdf_config_alt.php';
	//require_once '../tcpdf/tcpdf.php';
	require_once '../tcpdf/my_tcpdf.php';
	
	class PDF
	{
		private $_pdf;
		
		public function PDF()
		{	
			$this->_pdf = new MY_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			// set document information
			$this->_pdf->SetCreator(PDF_CREATOR);
			$this->_pdf->SetAuthor(PDF_AUTHOR);
			$this->_pdf->SetTitle('Lelux');
			$this->_pdf->SetSubject('Lelux');
			//$this->_pdf->SetKeywords('TCPDF, PDF, example, test, guide');
			
			// set default header data
			//$this->_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
			$this->_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');
			
			// set header and footer fonts
			$this->_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$this->_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			
			// set default monospaced font
			$this->_pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			// set margins
			$this->_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$this->_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			// set auto page breaks
			$this->_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			// set image scale factor
			$this->_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			$this->_pdf->SetFont('Times', '', 12);
			
			$this->_pdf->AddPage();
		}
		
		public function show($fileName, $htmlReportInfo)
		{
			$this->_pdf->writeHTML($htmlReportInfo, true, false, false, false, '');
		
			//ob_start();
		
			//Close and output PDF document
			ob_end_clean();
			$this->_pdf->Output("$fileName.pdf", 'I');
		
			//ob_end_flush();
		}
	}
?>