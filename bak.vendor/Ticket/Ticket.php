<?php 
namespace Ticket;

use BarCodeBakery\BarCodeBakery;
use TCPDF;

class Ticket {

	private $barCodeBakery;
	private $ticketImage;
	private $ticketId;
	private $ticketSize;
	private $ticketPath;
	private $url;
	private $ticketTemplate;
	private $uploadPath;
	private $filename;
	private $PDF;
	
    public function __construct(){
		$this->barCodeBakery = new BarCodeBakery();
	}
   
	public function setTicketImage($ticketImage) {
		$this->ticketImage = $ticketImage;
		return $this;
	}

	public function setTicketTemplate($ticketTemplate) {
		$this->ticketTemplate = $ticketTemplate;
		return $this;
	}
	
	public function setTicketId($ticketId) {
		$this->ticketId = $ticketId;
		return $this;
	}
	public function getTicketId() {
		return $this->ticketId;
	}

	public function setTicketPath($ticketPath) {
		$this->ticketPath = $ticketPath;
		return $this;
	} 
	public function setUploadPath($uploadPath) {
		$this->uploadPath = $uploadPath;
		return $this;
	} 
	public function getTicketPath() {
		return $this->ticketPath;
	}
    private function generateTicket($ticketTemplate, $ticketId, $uploadPath)
	{
		$this->setTicketTemplate($ticketTemplate);
		if (is_file($this->ticketTemplate))
		{
			
			$this->ticketSize = getimagesize($this->ticketTemplate);
			
			switch ($this->ticketSize[2])
			{
				case IMAGETYPE_GIF:
					$dest = imagecreatefromgif($this->ticketTemplate);
					break;
				case IMAGETYPE_PNG:
					$dest = imagecreatefrompng($this->ticketTemplate);
					break;
				case IMAGETYPE_JPEG:
					$dest = imagecreatefromjpeg($this->ticketTemplate);
					// App::dd($dest);
					// exit;
					break;
			}
		} else {
			$dest = imagecreate(510, 280);
			$background = imagecolorallocate($dest, 255, 255, 255);
		}
		
		/**
		 * @ set barcode path
		 * @ set barcode font
		 * @ generate barcode
		 */
		$this->barCodeBakery->setBarCodePath($uploadPath. 'tickets/barcodes/');
		
		$this->barCodeBakery->setBarCodeFont($uploadPath . 'fonts/Arial.ttf');
		$this->barCodeBakery->setBarCodeId($ticketId);
		//$this->barCodeBakery->generateBarCode();
		// Complete barcode generate
		/**
		 * @ set barcode id or rand number
		 */
		
		$this->barCodeBakery->setBarCode($this->barCodeBakery->generateBarCode());
		
		// return an array
		$this->barCodeBakery->setBarCodeSize(getimagesize($this->barCodeBakery->getBarCode()));
		
		// App::dd($this->barCodeBakery->getBarCodeSize());
		// exit;
		switch ($this->barCodeBakery->barCodeSize[2])
		{
			case IMAGETYPE_GIF:
				$src = imagecreatefromgif($this->barCodeBakery->barCode);
				break;
			case IMAGETYPE_PNG:
				$src = imagecreatefrompng($this->barCodeBakery->barCode);
				break;
			case IMAGETYPE_JPEG:
				$src = imagecreatefromjpeg($this->barCodeBakery->barCode);
				break;
		}
		$this->setTicketPath($uploadPath. 'tickets/');
		$filename = $this->ticketPath . 't_' . $this->barCodeBakery->barCodeId . '.png';
		
		
		imagecopymerge($dest, $src, 234, 219, 0, 0, $this->barCodeBakery->barCodeSize[0], $this->barCodeBakery->barCodeSize[1], 100);
		imagepng($dest, $filename, 9);
		imagedestroy($src);
		imagedestroy($dest);
		return $filename;
	}
	
	public function generatePdf($params)
	{
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$pdf->SetFont('dejavusans', '', 8);
		
		$uuid = '';
		
		foreach($params as $v)
		{
			$this->setTicketTemplate($v['ticket_template']);//$this->ticketTemplate
			$this->setTicketId($v['ticket_id']);//$this->ticketId
			$this->setUploadPath($v['PJ_UPLOAD_PATH']);//$this->uploadPath
			
		
			$ticket = $this->generateTicket($this->ticketTemplate, $this->ticketId, $this->uploadPath);
			// App::dd($v['uuid']);
			// exit;
			$pdf->AddPage();
			$pdf->Image($ticket, 10, 10, '', '', 'PNG', '', 'T', false, 300, '', false, false, 0, true, false, true);
			$pdf->Ln(100);
			
			$html = '<p style="color: #000; border:none;">' . preg_replace('/\r\n|\n/', '<br />', $v['ticket_info']) . '</p>';
			$pdf->writeHTMLCell(87, 19, 13, 68, $html, 0);
			
			$uuid = $v['uuid'];
			
		}
		$this->ticketPath = PJ_INSTALL_PATH.$this->ticketPath. 'pdfs/p_'. $uuid .'.pdf';
		$pdf->Output($this->ticketPath, 'F');

		$this->filename = $this->ticketPath;
		return $this->filename;
	}

	public function setPDF($PDF) {
		$this->PDF = $PDF;
		return $this;
	}

	public function getPDF() {
		return $this->PDF;
	}
    
}